<?php

namespace App\Jobs\SystemAgent;

use App\Jobs\Job;
use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use App\Services\SystemAgent\SystemAgentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class ProcessReminderJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public int $reminderId) {}

    public function handle(SystemAgentService $agent)
    {
        $reminder = Reminder::find($this->reminderId);
        if (! $reminder || ! $reminder->enabled) {
            return;
        }

        $aiResponse = $agent->evaluateReminder($reminder);
        $message = is_array($aiResponse) && isset($aiResponse['content']) ? $aiResponse['content'] : (string) ($aiResponse['message'] ?? $reminder->body ?? $reminder->title);

        // Notify the user if present
        if ($reminder->user) {
            Notification::send($reminder->user, new ReminderNotification($reminder, $message));
        } else {
            // system-wide or unassigned: create a system announcement entry if Notification model exists
            try {
                if (class_exists(\App\Models\Notifications\Notification::class)) {
                    \App\Models\Notifications\Notification::create([
                        'user_id' => null,
                        'title' => $reminder->title,
                        'body' => $message,
                        'type' => 'system.reminder',
                    ]);
                }
            } catch (\Throwable $e) {
                // swallow to avoid failing the job
            }
        }
    }
}
