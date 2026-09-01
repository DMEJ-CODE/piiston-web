<?php

namespace App\Http\Controllers\Api\v1\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Finance\SubscriptionResource;
use App\Repositories\Finance\SubscriptionRepositoryInterface;
use App\Services\Finance\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    protected $subscriptionRepository;

    public function __construct(SubscriptionService $subscriptionService, SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionService = $subscriptionService;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function plans(): JsonResponse
    {
        return response()->json($this->subscriptionRepository->getPlans());
    }

    public function current(): JsonResponse
    {
        $sub = $this->subscriptionRepository->getActiveSubscription(Auth::id());
        if (! $sub) {
            return response()->json(['message' => 'No active subscription'], 404);
        }

        return response()->json(new SubscriptionResource($sub->load('plan')));
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate(['plan_id' => 'required|exists:subscription_plans,id']);

        $this->subscriptionService->subscribe(Auth::user(), $request->plan_id);

        return response()->json(['message' => 'Subscription activated successfully']);
    }
}
