<?php

use App\Mail\ContactSubmissionMail;
use App\Mail\NewsletterSubscriptionMail;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

test('contact form is accessible on home page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('contact');
});

test('contact form submission succeeds and stores record', function () {
    $response = $this->post(route('contact.submit'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'owner',
        'message' => 'Hello, this is a test message.',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseHas('contact_submissions', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);
});

test('contact form rejects invalid email', function () {
    $response = $this->post(route('contact.submit'), [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'message' => 'Hello',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('newsletter subscription succeeds and stores record', function () {
    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'subscriber@example.com',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseHas('newsletter_subscriptions', [
        'email' => 'subscriber@example.com',
    ]);
});

test('newsletter subscription rejects duplicate email', function () {
    NewsletterSubscription::create([
        'email' => 'subscriber@example.com',
        'subscribed_at' => now(),
    ]);

    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'subscriber@example.com',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('contact submission triggers admin mail', function () {
    Mail::fake();

    $this->post(route('contact.submit'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'garage',
        'message' => 'Need support with fleet integration.',
    ]);

    Mail::assertSent(ContactSubmissionMail::class, function ($mail) {
        return $mail->submission->email === 'test@example.com';
    });
});

test('newsletter subscription triggers admin mail', function () {
    Mail::fake();

    $this->post(route('newsletter.subscribe'), [
        'email' => 'subscriber@example.com',
    ]);

    Mail::assertSent(NewsletterSubscriptionMail::class, function ($mail) {
        return $mail->subscription->email === 'subscriber@example.com';
    });
});
