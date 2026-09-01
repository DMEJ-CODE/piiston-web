<?php

use App\Jobs\SystemAgent\ProcessReminderJob;
use App\Models\Reminder;
use App\Models\User;
use App\Notifications\ReminderNotification;
use Illuminate\Support\Facades\Notification;

test('process reminder sends notification to user', function () {
    Notification::fake();

    $user = User::factory()->create();

    $reminder = Reminder::create([
        'title' => 'Test Reminder',
        'body' => 'This is a test reminder body',
        'user_id' => $user->id,
        'enabled' => true,
        'run_at' => now(),
    ]);

    // Run job synchronously
    ProcessReminderJob::dispatchSync($reminder->id);

    Notification::assertSentTo($user, ReminderNotification::class);
});
