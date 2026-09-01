<?php

namespace App\Repositories\Finance;

use App\Models\Finance\SubscriptionPlan;
use App\Models\Finance\UserSubscription;
use Illuminate\Support\Collection;

class EloquentSubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getActiveSubscription(int $userId): ?UserSubscription
    {
        return UserSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();
    }

    public function getPlans(): Collection
    {
        return SubscriptionPlan::where('status', 'active')->get();
    }

    public function createSubscription(array $data): UserSubscription
    {
        return UserSubscription::create($data);
    }

    public function findPlanById(int $id): ?SubscriptionPlan
    {
        return SubscriptionPlan::find($id);
    }
}
