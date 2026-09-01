<?php

namespace App\Mail;

use App\Models\NewsletterSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public NewsletterSubscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Newsletter Subscription - Automotive Insights Africa',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-subscription',
            with: ['subscription' => $this->subscription],
        );
    }
}
