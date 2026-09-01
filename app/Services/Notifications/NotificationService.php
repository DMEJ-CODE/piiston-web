<?php

namespace App\Services\Notifications;

use App\Models\Notifications\NotificationPreference;
use App\Models\User;
use App\Repositories\Notifications\NotificationRepositoryInterface;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function send(User $user, string $typeCode, string $title, string $message, array $data = [])
    {
        // 1. Save In-App Notification
        $notification = $this->notificationRepository->create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'priority' => $data['priority'] ?? 'normal',
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'is_read' => false,
        ]);

        // 2. Check Preferences for other channels
        $preferences = NotificationPreference::where('user_id', $user->id)
            ->whereHas('type', function ($q) use ($typeCode) {
                $q->where('name', $typeCode);
            })->first();

        // If no specific preferences, assume default (could be all enabled or all disabled based on policy)

        if (! $preferences || $preferences->push_enabled) {
            // Dispatch Push Job
        }

        if ($preferences && $preferences->email_enabled) {
            // Dispatch Email Job
        }

        if ($preferences && $preferences->sms_enabled) {
            // Dispatch SMS Job
        }

        return $notification;
    }
}
