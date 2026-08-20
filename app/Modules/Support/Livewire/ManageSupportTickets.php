<?php

namespace App\Modules\Support\Livewire;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Staff\Models\Staff;
use App\Modules\Support\Models\SupportTicket;
use App\Modules\Support\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class ManageSupportTickets extends Component
{
    use WithFileUploads;

    public string $activeTab = 'all';
    public string $search = '';
    public string $clientFilter = '';
    public string $staffFilter = '';
    public ?int $selectedTicketId = null;

    public string $replyMessage = '';
    public $replyAttachments = [];
    public array $drafts = [];
    public array $draftAttachments = [];

    protected array $rules = [
        'replyAttachments.*' => 'nullable|file|max:51200',
    ];

    public function mount($ticket = null): void
    {
        if ($ticket) {
            $t = SupportTicket::where('ticket_number', $ticket)->first();
            if ($t) {
                $this->selectedTicketId = $t->id;
            }
        }
    }

    public function updatedReplyMessage($val): void
    {
        if ($this->selectedTicketId) {
            if (!empty(trim($val))) {
                $this->drafts[$this->selectedTicketId] = $val;
            } else {
                unset($this->drafts[$this->selectedTicketId]);
            }
        }
    }

    public function updatedReplyAttachments($val): void
    {
        if ($this->selectedTicketId) {
            $this->draftAttachments[$this->selectedTicketId] = $this->replyAttachments;
        }
    }

    public function selectTicket(int $ticketId): void
    {
        // Save text & attachment drafts of currently open ticket
        if ($this->selectedTicketId) {
            if (!empty(trim($this->replyMessage))) {
                $this->drafts[$this->selectedTicketId] = $this->replyMessage;
            } else {
                unset($this->drafts[$this->selectedTicketId]);
            }

            if (!empty($this->replyAttachments)) {
                $this->draftAttachments[$this->selectedTicketId] = $this->replyAttachments;
            } else {
                unset($this->draftAttachments[$this->selectedTicketId]);
            }
        }

        $this->selectedTicketId = $ticketId;

        // Restore text & attachment drafts of newly selected ticket
        $this->replyMessage = $this->drafts[$ticketId] ?? '';
        $this->replyAttachments = $this->draftAttachments[$ticketId] ?? [];

        $t = SupportTicket::find($ticketId);
        if ($t) {
            $url = route('admin.support.detail', ['ticket' => $t->ticket_number]);
            $this->js("window.history.pushState({}, '', '{$url}')");
        }
    }

    public function closeTicket(): void
    {
        $this->selectedTicketId = null;
        $url = route('admin.support');
        $this->js("window.history.pushState({}, '', '{$url}')");
    }

    public function removeReplyAttachment($index)
    {
        unset($this->replyAttachments[$index]);
        $this->replyAttachments = array_values($this->replyAttachments);
        if ($this->selectedTicketId) {
            if (!empty($this->replyAttachments)) {
                $this->draftAttachments[$this->selectedTicketId] = $this->replyAttachments;
            } else {
                unset($this->draftAttachments[$this->selectedTicketId]);
            }
        }
    }

    protected function storeUploadedFiles($files): array
    {
        $attachments = [];
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if ($file) {
                $origName = $file->getClientOriginalName();
                $path = $file->store('ticket_attachments', 'public');
                $mime = $file->getMimeType() ?: $file->getClientMimeType();
                $size = $file->getSize();

                $attachments[] = [
                    'name' => $origName,
                    'path' => $path,
                    'url'  => asset('storage/' . $path),
                    'size' => $size,
                    'type' => $mime,
                ];
            }
        }
        return $attachments;
    }

    public function sendReply(): void
    {
        $this->validate([
            'replyMessage' => 'required|string|min:2',
        ]);

        $ticket = SupportTicket::findOrFail($this->selectedTicketId);

        $attachmentData = !empty($this->replyAttachments) ? $this->storeUploadedFiles($this->replyAttachments) : null;

        $msg = SupportTicketMessage::create([
            'ticket_id'         => $ticket->id,
            'sender_type'       => 'admin',
            'sender_id'         => Auth::id(),
            'message'           => $this->replyMessage,
            'attachments'       => $attachmentData,
            'is_read_by_client' => false,
            'is_read_by_staff'  => false,
            'is_read_by_admin'  => true,
        ]);

        $ticket->update(['last_reply_at' => now()]);

        \App\Services\NotificationService::notifyNewTicketMessage($ticket, $msg);

        if ($this->selectedTicketId) {
            unset($this->drafts[$this->selectedTicketId]);
            unset($this->draftAttachments[$this->selectedTicketId]);
        }

        $this->reset(['replyMessage', 'replyAttachments']);
    }

    public function updateStatus(string $newStatus): void
    {
        if (!in_array($newStatus, ['open', 'in_progress', 'resolved', 'closed'])) {
            return;
        }

        $ticket = SupportTicket::findOrFail($this->selectedTicketId);
        $ticket->update(['status' => $newStatus]);
        session()->flash('message', "Ticket #{$ticket->ticket_number} status updated to " . str_replace('_', ' ', $newStatus) . ".");
    }

    public function assignStaff($staffId): void
    {
        $ticket = SupportTicket::findOrFail($this->selectedTicketId);
        $ticket->update(['assigned_staff_id' => $staffId ?: null]);
        session()->flash('message', "Ticket #{$ticket->ticket_number} staff assignment updated.");
    }

    public function render()
    {
        $query = SupportTicket::with(['client.user', 'website', 'assignedStaff.user', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('sender_type', '!=', 'admin')
                  ->where('is_read_by_admin', false);
            }]);

        if ($this->activeTab !== 'all') {
            $query->where('status', $this->activeTab);
        }

        if ($this->clientFilter) {
            $query->where('client_id', $this->clientFilter);
        }

        if ($this->staffFilter) {
            $query->where('assigned_staff_id', $this->staffFilter);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('ticket_number', 'like', "%{$this->search}%")
                  ->orWhere('subject', 'like', "%{$this->search}%")
                  ->orWhereHas('client', function ($cq) {
                      $cq->where('company_name', 'like', "%{$this->search}%")
                         ->orWhereHas('user', function ($uq) {
                             $uq->where('name', 'like', "%{$this->search}%");
                         });
                  });
            });
        }

        $tickets = $query->orderBy('updated_at', 'desc')->get();

        $selectedTicket = null;
        if ($this->selectedTicketId) {
            $selectedTicket = SupportTicket::with(['client.user', 'website', 'assignedStaff.user', 'messages.sender.client', 'messages.sender.staff', 'messages.sender.admin'])
                ->find($this->selectedTicketId);
        }

        // Mark messages as read by admin when viewing selected ticket
        if ($selectedTicket) {
            SupportTicketMessage::where('ticket_id', $selectedTicket->id)
                ->where('sender_type', '!=', 'admin')
                ->where('is_read_by_admin', false)
                ->update(['is_read_by_admin' => true]);
        }

        $allClients = Client::whereHas('supportTickets')->with('user')->orderBy('company_name')->get();
        $allStaff = Staff::with('user')->get();

        return view('modules.support.manage-support-tickets', [
            'tickets'        => $tickets,
            'selectedTicket' => $selectedTicket,
            'allClients'     => $allClients,
            'allStaff'       => $allStaff,
        ])->layoutData(['title' => 'Support Center - Admin']);
    }
}
