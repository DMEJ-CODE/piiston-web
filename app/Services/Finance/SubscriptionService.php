<?php

namespace App\Services\Finance;

use App\Models\Finance\UserSubscription;
use App\Models\User;
use App\Repositories\Finance\SubscriptionRepositoryInterface;
use App\Services\Payments\NotchPayClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function __construct(
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected NotchPayClient $notchPay,
    ) {}

    /** @return array{subscription: UserSubscription, checkout_url: string} */
    public function subscribe(User $user, int $planId): array
    {
        $plan = $this->subscriptionRepository->findPlanById($planId);
        abort_unless($plan && $plan->status, 404, 'Subscription plan not found.');

        $reference = 'PII-SUB-'.strtoupper(Str::random(12));
        $callback = config('services.notchpay.callback_url') ?: route('subscriptions.notchpay.callback');
        try {
            $response = $this->notchPay->initialize([
                'amount' => (float) $plan->price,
                'currency' => $plan->currency?->code ?? 'XAF',
                'email' => $user->email,
                'phone' => $user->phone,
                'description' => 'Piiston '.$plan->name.' subscription',
                'reference' => $reference,
                'callback' => $callback,
                'locked_currency' => $plan->currency?->code ?? 'XAF',
                'locked_country' => 'CM',
                'customer_meta' => ['user_id' => $user->id, 'plan_id' => $plan->id],
            ]);
        } catch (ConnectionException|RequestException $exception) {
            report($exception);
            abort(503, 'Le service de paiement est momentanément indisponible.');
        }

        $transaction = $response['transaction'] ?? [];
        $checkoutUrl = $response['authorization_url'] ?? null;
        abort_unless(is_string($checkoutUrl) && $checkoutUrl !== '', 502, 'Notch Pay did not return a checkout URL.');

        $subscription = $this->subscriptionRepository->createSubscription([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now(),
            'status' => 'pending',
            'auto_renew' => true,
            'notchpay_reference' => $transaction['reference'] ?? $reference,
            'checkout_url' => $checkoutUrl,
        ]);

        return ['subscription' => $subscription, 'checkout_url' => $checkoutUrl];
    }

    public function finalize(UserSubscription $subscription, string $status): void
    {
        $status = strtolower(trim($status));

        if (in_array($status, ['complete', 'completed', 'success', 'successful', 'paid'], true)) {
            $duration = strtolower((string) $subscription->plan->duration);
            $endDate = $duration === 'yearly' ? now()->addYear() : now()->addMonth();
            $subscription->update(['status' => 'active', 'start_date' => now(), 'end_date' => $endDate]);
        } elseif (in_array($status, ['failed', 'canceled', 'expired'], true)) {
            $subscription->update(['status' => 'canceled']);
        }
    }
}
