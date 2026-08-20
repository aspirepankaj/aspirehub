<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Modules\Support\Models\SupportTicket;
use App\Modules\Support\Models\SupportTicketMessage;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Dispatch notification to appropriate parties whenever a message is posted on a ticket.
     */
    public static function notifyNewTicketMessage(SupportTicket $ticket, SupportTicketMessage $message): void
    {
        $senderName = $message->sender?->name ?? ucfirst($message->sender_type);
        $snippet = Str::limit(strip_tags($message->message), 90);
        $ticketNum = $ticket->ticket_number;
        $title = "New reply on ticket #{$ticketNum}";

        $recipientUserIds = collect();

        // 1. If sender is client -> Notify assigned staff + all admin users
        if ($message->sender_type === 'client') {
            if ($ticket->assignedStaff?->user_id) {
                $recipientUserIds->push($ticket->assignedStaff->user_id);
            }
            $adminUserIds = User::whereHas('admin')->pluck('id');
            $recipientUserIds = $recipientUserIds->merge($adminUserIds);
        }
        // 2. If sender is staff -> Notify client user + all admin users
        elseif ($message->sender_type === 'staff') {
            if ($ticket->client?->user_id) {
                $recipientUserIds->push($ticket->client->user_id);
            }
            $adminUserIds = User::whereHas('admin')->pluck('id');
            $recipientUserIds = $recipientUserIds->merge($adminUserIds);
        }
        // 3. If sender is admin -> Notify client user + assigned staff user
        elseif ($message->sender_type === 'admin') {
            if ($ticket->client?->user_id) {
                $recipientUserIds->push($ticket->client->user_id);
            }
            if ($ticket->assignedStaff?->user_id) {
                $recipientUserIds->push($ticket->assignedStaff->user_id);
            }
        }

        // Exclude sender from receiving their own notification
        $recipientUserIds = $recipientUserIds->reject(fn($id) => $id == $message->sender_id)->unique();

        foreach ($recipientUserIds as $userId) {
            $user = User::find($userId);
            if (!$user) continue;

            $fullUrl = match (true) {
                $user->admin !== null => route('admin.support.detail', ['ticket' => $ticketNum]),
                $user->staff !== null => route('staff.support.detail', ['ticket' => $ticketNum]),
                default => route('client.support.detail', ['ticket' => $ticketNum]),
            };
            $actionUrl = parse_url($fullUrl, PHP_URL_PATH) ?: $fullUrl;

            Notification::create([
                'user_id' => $user->id,
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticketNum,
                'title' => $title,
                'message' => "{$senderName}: \"{$snippet}\"",
                'sender_name' => $senderName,
                'sender_type' => $message->sender_type,
                'sender_user_id' => $message->sender_id,
                'action_url' => $actionUrl,
                'is_read' => false,
            ]);
        }
    }
}
