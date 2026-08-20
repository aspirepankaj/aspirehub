<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HeaderNotifications extends Component
{
    public bool $open = false;

    public function markAsRead(int $notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
            if ($notification->action_url) {
                $targetUrl = parse_url($notification->action_url, PHP_URL_PATH) ?: $notification->action_url;
                return redirect()->to($targetUrl);
            }
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getViewAllUrl(): string
    {
        $user = Auth::user();
        if (!$user) {
            return '#';
        }
        if ($user->admin !== null) {
            return route('admin.notifications');
        } elseif ($user->staff !== null) {
            return route('staff.notifications');
        }
        return route('client.notifications');
    }

    public function render()
    {
        $userId = Auth::id();

        $unreadCount = $userId ? Notification::where('user_id', $userId)->where('is_read', false)->count() : 0;

        $notifications = $userId ? Notification::with(['senderUser.client', 'senderUser.staff', 'senderUser.admin'])->where('user_id', $userId)->latest()->take(10)->get() : collect();

        return view('livewire.header-notifications', [
            'unreadCount' => $unreadCount,
            'notifications' => $notifications,
            'viewAllUrl' => $this->getViewAllUrl(),
        ]);
    }
}
