<?php

namespace App\Services\Notifications;

use App\Events\Notifications\NotificationSent;
use App\Jobs\Notifications\SendEmailNotificationJob;
use App\Jobs\Notifications\SendPushNotificationJob;
use App\Jobs\Notifications\SendSmsNotificationJob;
use App\Models\Notifications\NotificationPreference;
use App\Models\Notifications\NotificationType;
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
        $type = NotificationType::firstOrCreate(
            ['name' => $typeCode],
            ['category' => $data['category'] ?? 'General', 'status' => true]
        );

        // 1. Save In-App Notification
        $notification = $this->notificationRepository->create([
            'user_id' => $user->id,
            'type_id' => $type->id,
            'title' => $title,
            'message' => $message,
            'priority' => $data['priority'] ?? 'medium',
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'is_read' => false,
        ]);

        // Broadcast for Web/Real-time
        event(new NotificationSent($notification));

        // 2. Check Preferences for other channels
        $preferences = NotificationPreference::where('user_id', $user->id)
            ->whereHas('type', function ($q) use ($typeCode) {
                $q->where('name', $typeCode);
            })->first();

        // If no specific preferences, assume default (could be all enabled or all disabled based on policy)

        if (! $preferences || $preferences->push_enabled) {
            SendPushNotificationJob::dispatch($user, $title, $message, $data);
        }

        if ($preferences && $preferences->email_enabled) {
            SendEmailNotificationJob::dispatch($user, $title, $message, $data);
        }

        if ($preferences && $preferences->sms_enabled) {
            SendSmsNotificationJob::dispatch($user, $title, $message, $data);
        }

        return $notification;
    }
}
