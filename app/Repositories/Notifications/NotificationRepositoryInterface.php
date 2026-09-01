<?php

namespace App\Repositories\Notifications;

use App\Models\Notifications\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function findById(int $id): ?Notification;

    public function getUserNotifications(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function getUnreadCount(int $userId): int;

    public function markAsRead(int $id): bool;

    public function markAllAsRead(int $userId): int;

    public function delete(int $id): bool;

    public function create(array $data): Notification;
}
