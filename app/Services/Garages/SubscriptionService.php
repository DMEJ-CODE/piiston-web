<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageSubscription;
use App\Models\Garages\GarageSubscriptionPlan;

class SubscriptionService
{
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
}
