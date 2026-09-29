<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageSubscription;
use App\Models\Garages\GarageSubscriptionPlan;
use App\Models\User;
use App\Services\Payments\NotchPayClient;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function __construct(protected NotchPayClient $notchPay) {}

    public function getActiveSubscription(GarageBranch $branch): ?GarageSubscription
    {
        return GarageSubscription::whereHas('company', function ($q) use ($branch) {
            $q->where('id', $branch->company_id);
        })->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->with('plan')
            ->first();
    }

    public function checkLimit(GarageBranch $branch, string $limitType, int $currentCount): bool
    {
        $subscription = $this->getActiveSubscription($branch);
        if (! $subscription || ! $subscription->plan) {
            return false;
        }

        $plan = $subscription->plan;
        $limit = match ($limitType) {
            'branches' => $plan->max_branches,
            'employees' => $plan->max_employees,
            'vehicles_per_month' => $plan->max_vehicles_per_month,
            'storage_gb' => $plan->max_storage_gb,
            default => null,
        };

        if ($limit === null) {
            return true;
        }

        return $currentCount < $limit;
    }

    public function createSubscription(GarageBranch $branch, GarageSubscriptionPlan $plan, array $data = []): GarageSubscription
    {
        $startsAt = now();
        $endsAt = match ($data['billing_cycle'] ?? 'monthly') {
            'yearly' => $startsAt->copy()->addYear(),
            default => $startsAt->copy()->addMonth(),
        };

        return GarageSubscription::create([
            'company_id' => $branch->company_id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => $data['billing_cycle'] ?? 'monthly',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'trial_ends_at' => $data['trial_ends_at'] ?? null,
        ]);
    }

    public function initializeCheckout(GarageBranch $branch, GarageSubscriptionPlan $plan, User $user, string $billingCycle): string
    {
        $billingCycle = in_array($billingCycle, ['monthly', 'yearly'], true) ? $billingCycle : 'monthly';
        $reference = 'PII-GARAGE-'.strtoupper(Str::random(12));
        $amount = $billingCycle === 'yearly' ? $plan->yearly_price : $plan->monthly_price;
        $response = $this->notchPay->initialize([
            'amount' => (float) $amount,
            'currency' => 'XAF',
            'email' => $user->email,
            'phone' => $user->phone,
            'description' => 'Piiston Garage '.$plan->name.' ('.$billingCycle.')',
            'reference' => $reference,
            'callback' => config('services.notchpay.garage_callback_url') ?: url('/garage/subscriptions/notchpay/callback'),
            'locked_currency' => 'XAF',
            'locked_country' => 'CM',
            'customer_meta' => ['company_id' => $branch->company_id, 'plan_id' => $plan->id],
        ]);
        $checkoutUrl = $response['authorization_url'] ?? null;
        abort_unless(is_string($checkoutUrl) && $checkoutUrl !== '', 502, 'Notch Pay did not return a checkout URL.');

        GarageSubscription::create([
            'company_id' => $branch->company_id,
            'plan_id' => $plan->id,
            'status' => 'pending',
            'billing_cycle' => $billingCycle,
            'starts_at' => now(),
            'ends_at' => now(),
            'payment_method' => 'notchpay',
            'transaction_reference' => $response['transaction']['reference'] ?? $reference,
        ]);

        return $checkoutUrl;
    }
}
