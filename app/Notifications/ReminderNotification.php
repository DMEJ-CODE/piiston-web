<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification
{
    use Queueable;

    protected $reminder;

    protected $message;

    public function __construct($reminder, string $message)
    {
        $this->reminder = $reminder;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->reminder->title,
            'body' => $this->message,
            'reminder_id' => $this->reminder->id,
            'metadata' => $this->reminder->metadata,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->reminder->title)
            ->line($this->message);
    }
}
