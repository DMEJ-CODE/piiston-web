<?php

namespace App\Services\Finance;

use App\Models\User;
use App\Repositories\Finance\SubscriptionRepositoryInterface;

class SubscriptionService
{
    protected $subscriptionRepository;

    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function subscribe(User $user, int $planId): void
    {
        $plan = $this->subscriptionRepository->findPlanById($planId);

        $this->subscriptionRepository->createSubscription([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration),
            'status' => 'active',
            'auto_renew' => true,
        ]);
    }
}
