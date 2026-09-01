<?php

namespace App\Repositories\Finance;

use App\Models\Finance\SubscriptionPlan;
use App\Models\Finance\UserSubscription;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function getActiveSubscription(int $userId): ?UserSubscription;

    public function getPlans(): Collection;

    public function createSubscription(array $data): UserSubscription;

    public function findPlanById(int $id): ?SubscriptionPlan;
}
