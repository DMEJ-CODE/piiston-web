<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionMail;
use App\Mail\NewsletterSubscriptionMail;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function cookiePolicy(): View
    {
        return view('cookie-policy');
    }

    public function setConsent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'consent' => ['required', 'in:accepted,declined'],
        ]);

        $response = response()->json([
            'success' => true,
            'consent' => $validated['consent'],
        ]);

        $response->cookie(
            'piiston_cookie_consent',
            $validated['consent'],
            365 * 24 * 60,
            '/',
            null,
            config('session.secure', false),
            config('session.http_only', true),
            false,
            'lax'
        );

        return $response;
    }

    public function submitContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Mail::to(config('services.piiston_admin_email', env('PIISTON_ADMIN_EMAIL', 'support@piiston.com')))
            ->send(new ContactSubmissionMail($submission));

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent. Our team will get back to you shortly.',
        ]);
    }

    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletter_subscriptions,email'],
        ]);

        $subscription = NewsletterSubscription::create([
            'email' => $validated['email'],
            'ip_address' => $request->ip(),
            'subscribed_at' => now(),
        ]);

        Mail::to(config('services.piiston_admin_email', env('PIISTON_ADMIN_EMAIL', 'support@piiston.com')))
            ->send(new NewsletterSubscriptionMail($subscription));

        return response()->json([
            'success' => true,
            'message' => 'Welcome to Automotive Insights Africa! Check your inbox for a confirmation.',
        ]);
    }
}
