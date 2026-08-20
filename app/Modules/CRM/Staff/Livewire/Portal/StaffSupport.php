<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\Support\Models\SupportTicket;
use App\Modules\Support\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

#[Layout('layouts.staff')]
class StaffSupport extends Component
{
    use WithFileUploads;

    public string $activeTab = 'all';
    public string $search = '';
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
            $url = route('support.detail', ['ticket' => $t->ticket_number]);
            $this->js("window.history.pushState({}, '', '{$url}')");
        }
    }

    public function closeTicket(): void
    {
        $this->selectedTicketId = null;
        $url = route('support');
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
            'sender_type'       => 'staff',
            'sender_id'         => Auth::id(),
            'message'           => $this->replyMessage,
            'attachments'       => $attachmentData,
            'is_read_by_client' => false,
            'is_read_by_staff'  => true,
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

    public function render()
    {
        $staffId = Auth::user()->staff->id ?? 0;

        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        $query = SupportTicket::with(['client.user', 'website', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('sender_type', '!=', 'staff')
                  ->where('is_read_by_staff', false);
            }])
            ->where(function ($q) use ($assignedClientIds, $staffId) {
                $q->whereIn('client_id', $assignedClientIds)
                  ->orWhere('assigned_staff_id', $staffId);
            });

        if ($this->activeTab !== 'all') {
            $query->where('status', $this->activeTab);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('ticket_number', 'like', "%{$this->search}%")
                  ->orWhere('subject', 'like', "%{$this->search}%")
                  ->orWhereHas('client', function ($cq) {
                      $cq->where('company_name', 'like', "%{$this->search}%");
                  });
            });
        }

        $tickets = $query->orderBy('updated_at', 'desc')->get();

        $selectedTicket = null;
        if ($this->selectedTicketId) {
            $selectedTicket = SupportTicket::with(['client.user', 'website', 'assignedStaff.user', 'messages.sender.client', 'messages.sender.staff', 'messages.sender.admin'])
                ->find($this->selectedTicketId);
        }

        // Mark messages as read by staff when viewing selected ticket
        if ($selectedTicket) {
            SupportTicketMessage::where('ticket_id', $selectedTicket->id)
                ->where('sender_type', '!=', 'staff')
                ->where('is_read_by_staff', false)
                ->update(['is_read_by_staff' => true]);
        }

        return view('modules.crm.staff.portal.staff-support', [
            'tickets'        => $tickets,
            'selectedTicket' => $selectedTicket,
        ])->layoutData(['title' => 'Support Center - Staff Portal']);
    }
}
