<?php

namespace App\Services\Identity;

use App\Models\Identity\UserActivity;
use App\Models\Notifications\NotificationType;
use App\Models\User;
use App\Repositories\Notifications\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ActivityService
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function log(User $user, string $type, string $title, ?string $description = null, ?Model $subject = null, array $data = []): UserActivity
    {
        $icon = $this->getIconForType($type);

        $activity = UserActivity::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'subject_id' => $subject?->id,
            'subject_type' => $subject ? get_class($subject) : null,
            'data' => $data,
        ]);

        $this->createNotification($user, $type, $title, $description, $subject);

        return $activity;
    }

    protected function createNotification(User $user, string $type, string $title, ?string $description, ?Model $subject): void
    {
        $notifType = NotificationType::firstOrCreate(
            ['name' => $type],
            ['category' => 'System', 'status' => true]
        );

        $this->notificationRepository->create([
            'user_id' => $user->id,
            'type_id' => $notifType->id,
            'title' => $title,
            'message' => $description ?? $title,
            'reference_type' => $subject ? get_class($subject) : null,
            'reference_id' => $subject?->id,
            'priority' => $this->getPriorityForType($type),
        ]);
    }

    protected function getIconForType(string $type): string
    {
        return match ($type) {
            'VEHICLE_ADDED' => 'directions_car',
            'APPOINTMENT_BOOKED' => 'event',
            'SOS_REQUESTED' => 'bolt',
            'SOS_CANCELLED' => 'cancel',
            'ORDER_PLACED' => 'shopping_bag',
            default => 'notifications',
        };
    }

    protected function getPriorityForType(string $type): string
    {
        return match ($type) {
            'SOS_REQUESTED' => 'high',
            'SOS_CANCELLED' => 'medium',
            'APPOINTMENT_BOOKED', 'ORDER_PLACED' => 'medium',
            default => 'low',
        };
    }
}
