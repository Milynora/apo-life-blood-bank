<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class NotificationService
{
    public function send(User $user, Notification $notification): void
    {
        $user->notify($notification);
    }

    public function sendToRole(string $role, Notification $notification): void
    {
        User::where('role', $role)
            ->where('status', 'approved')
            ->each(fn(User $user) => $user->notify($notification));
    }

    public function getUnread(User $user): Collection
    {
        return $user->unreadNotifications;
    }

    public function markAllRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    public function unreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }
}
