<?php

namespace App\Jobs\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $subject;

    protected $content;

    public function __construct(User $user, string $subject, string $content)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function handle(): void
    {
        Log::info("Sending Email to: {$this->user->email}");
        // Call actual Email service (SES/SendGrid)
    }
}
