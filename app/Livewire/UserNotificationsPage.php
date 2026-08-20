<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserNotificationsPage extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all, unread, read
    public string $search = '';

    public function markAsRead(int $id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($notif) {
            $notif->update(['is_read' => true]);
            if ($notif->action_url) {
                $targetUrl = parse_url($notif->action_url, PHP_URL_PATH) ?: $notif->action_url;
                return redirect()->to($targetUrl);
            }
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        session()->flash('message', 'All notifications marked as read.');
    }

    public function deleteNotification(int $id)
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        session()->flash('message', 'Notification deleted.');
    }

    public function render()
    {
        $userId = Auth::id();

        $query = Notification::with(['senderUser.client', 'senderUser.staff', 'senderUser.admin'])->where('user_id', $userId)->latest();

        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filter === 'read') {
            $query->where('is_read', true);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('message', 'like', "%{$this->search}%")
                  ->orWhere('sender_name', 'like', "%{$this->search}%")
                  ->orWhere('ticket_number', 'like', "%{$this->search}%");
            });
        }

        $notifications = $query->paginate(15);
        $unreadCount = Notification::where('user_id', $userId)->where('is_read', false)->count();

        // Determine layout dynamically based on role
        $user = Auth::user();
        $layout = match (true) {
            $user?->admin !== null => 'layouts.admin',
            $user?->staff !== null => 'layouts.staff',
            default => 'layouts.client-portal',
        };

        return view('livewire.user-notifications-page', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ])->layout($layout, ['title' => 'Notifications - Aspire Hub']);
    }
}
