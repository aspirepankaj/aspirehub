<?php

namespace App\Modules\Client\Dashboard\Livewire;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\Support\Models\SupportTicket;
use App\Modules\Support\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.client-portal')]
class ClientSupport extends Component
{
    use WithFileUploads;

    public $activeTab = 'all';
    public $search = '';
    public $selectedTicketId = null;

    // Create Ticket Modal fields
    public $showCreateModal = false;
    public $website_id = '';
    public $subject = '';
    public $category = 'general';
    public $priority = 'medium';
    public $message = '';
    public $createAttachments = [];

    // Reply Message & Per-Ticket Drafts
    public $replyMessage = '';
    public $replyAttachments = [];
    public array $drafts = [];
    public array $draftAttachments = [];

    public function mount($ticket = null)
    {
        if ($ticket) {
            $t = SupportTicket::where('ticket_number', $ticket)->first();
            if ($t) {
                $this->selectedTicketId = $t->id;
            }
        }
    }

    public function updatedReplyMessage($val)
    {
        if ($this->selectedTicketId) {
            if (!empty(trim($val))) {
                $this->drafts[$this->selectedTicketId] = $val;
            } else {
                unset($this->drafts[$this->selectedTicketId]);
            }
        }
    }

    public function updatedReplyAttachments($val)
    {
        if ($this->selectedTicketId) {
            $this->draftAttachments[$this->selectedTicketId] = $this->replyAttachments;
        }
    }

    public function selectTicket($ticketId)
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
            $url = route('client.support.detail', ['ticket' => $t->ticket_number]);
            $this->js("window.history.pushState({}, '', '{$url}')");
        }
    }

    public function closeTicket()
    {
        if ($this->showCreateModal) {
            $this->showCreateModal = false;
            return;
        }
        $this->selectedTicketId = null;
        $url = route('client.support');
        $this->js("window.history.pushState({}, '', '{$url}')");
    }

    public function openCreateModal()
    {
        $this->reset(['website_id', 'subject', 'category', 'priority', 'message', 'createAttachments']);
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['createAttachments']);
    }

    public function removeCreateAttachment($index)
    {
        unset($this->createAttachments[$index]);
        $this->createAttachments = array_values($this->createAttachments);
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

    public function createTicket()
    {
        $client = Client::where('user_id', Auth::id())->first();
        abort_if(!$client, 403, 'Client record not found.');

        // Validate website belongs to client
        $website = Website::where('id', $this->website_id)
            ->where('client_id', $client->id)
            ->first();

        if (!$website) {
            $this->addError('website_id', 'Please select a valid website from your account.');
            return;
        }

        $this->validate();

        // Process attachments if uploaded
        $attachmentData = !empty($this->createAttachments) ? $this->storeUploadedFiles($this->createAttachments) : null;

        // Find assigned staff ID if available
        $assignedStaffId = $client->assignedStaff()->first()?->id;

        $ticket = SupportTicket::create([
            'ticket_number'     => SupportTicket::generateTicketNumber(),
            'client_id'         => $client->id,
            'website_id'        => $this->website_id,
            'subject'           => $this->subject,
            'category'          => $this->category,
            'priority'          => $this->priority,
            'status'            => 'open',
            'assigned_staff_id' => $assignedStaffId,
            'created_by_user_id'=> Auth::id(),
            'last_reply_at'     => now(),
        ]);

        $msg = SupportTicketMessage::create([
            'ticket_id'         => $ticket->id,
            'sender_type'       => 'client',
            'sender_id'         => Auth::id(),
            'message'           => $this->message,
            'attachments'       => $attachmentData,
            'is_read_by_client' => true,
            'is_read_by_staff'  => false,
            'is_read_by_admin'  => false,
        ]);

        \App\Services\NotificationService::notifyNewTicketMessage($ticket, $msg);

        $this->reset(['createAttachments']);
        $this->showCreateModal = false;
        $this->selectedTicketId = $ticket->id;
        $this->ticketNum = $ticket->ticket_number;
        session()->flash('message', "Support Ticket #{$ticket->ticket_number} created successfully.");
    }

    public function sendReply()
    {
        $this->validate([
            'replyMessage' => 'required|string|min:2',
        ]);

        $client = Client::where('user_id', Auth::id())->first();
        abort_if(!$client, 403);

        $ticket = SupportTicket::where('id', $this->selectedTicketId)
            ->where('client_id', $client->id)
            ->firstOrFail();

        $attachmentData = !empty($this->replyAttachments) ? $this->storeUploadedFiles($this->replyAttachments) : null;

        $msg = SupportTicketMessage::create([
            'ticket_id'         => $ticket->id,
            'sender_type'       => 'client',
            'sender_id'         => Auth::id(),
            'message'           => $this->replyMessage,
            'attachments'       => $attachmentData,
            'is_read_by_client' => true,
            'is_read_by_staff'  => false,
            'is_read_by_admin'  => false,
        ]);

        $ticket->update(['last_reply_at' => now()]);

        \App\Services\NotificationService::notifyNewTicketMessage($ticket, $msg);

        if ($this->selectedTicketId) {
            unset($this->drafts[$this->selectedTicketId]);
            unset($this->draftAttachments[$this->selectedTicketId]);
        }

        $this->reset(['replyMessage', 'replyAttachments']);
    }

    public function render()
    {
        $client = Client::where('user_id', Auth::id())->first();

        $websites = $client ? Website::where('client_id', $client->id)->get() : collect();

        $query = SupportTicket::with(['website', 'assignedStaff.user', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('sender_type', '!=', 'client')
                  ->where('is_read_by_client', false);
            }])
            ->where('client_id', $client?->id);

        // activeTab filtering removed to allow client-side filtering via Alpine.js

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('ticket_number', 'like', "%{$this->search}%")
                  ->orWhere('subject', 'like', "%{$this->search}%");
            });
        }

        $tickets = $query->orderBy('updated_at', 'desc')->get();

        // Selected ticket for chat detail view
        $selectedTicket = null;
        if ($this->selectedTicketId) {
            $selectedTicket = SupportTicket::with(['website', 'assignedStaff.user', 'messages.sender.client', 'messages.sender.staff', 'messages.sender.admin'])
                ->where('client_id', $client?->id)
                ->find($this->selectedTicketId);
        }

        // Mark messages as read by client when viewing selected ticket
        if ($selectedTicket) {
            SupportTicketMessage::where('ticket_id', $selectedTicket->id)
                ->where('sender_type', '!=', 'client')
                ->where('is_read_by_client', false)
                ->update(['is_read_by_client' => true]);
        }

        return view('modules.client.dashboard.client-support', [
            'client'         => $client,
            'websites'       => $websites,
            'tickets'        => $tickets,
            'selectedTicket' => $selectedTicket,
        ])->layoutData(['title' => 'Support Center']);
    }
}
