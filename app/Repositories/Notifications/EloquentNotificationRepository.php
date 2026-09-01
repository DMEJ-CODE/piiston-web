<?php

namespace App\Repositories\Notifications;

use App\Models\Notifications\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    public function findById(int $id): ?Notification
    {
        return Notification::find($id);
    }

    public function getUserNotifications(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead(int $id): bool
    {
        $notification = $this->findById($id);
        if (! $notification) {
            return false;
        }

        return $notification->update(['is_read' => true, 'read_at' => now()]);
    }

    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function delete(int $id): bool
    {
        $notification = $this->findById($id);
        if (! $notification) {
            return false;
        }

        return $notification->delete();
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }
}
