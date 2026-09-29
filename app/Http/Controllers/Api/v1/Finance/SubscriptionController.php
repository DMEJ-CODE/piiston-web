<?php

namespace App\Http\Controllers\Api\v1\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Finance\SubscriptionResource;
use App\Models\Finance\UserSubscription;
use App\Repositories\Finance\SubscriptionRepositoryInterface;
use App\Services\Finance\SubscriptionService;
use App\Services\Payments\NotchPayClient;
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

        $result = $this->subscriptionService->subscribe(Auth::user(), $request->integer('plan_id'));

        return response()->json([
            'message' => 'Payment initialized',
            'checkout_url' => $result['checkout_url'],
            'subscription' => new SubscriptionResource($result['subscription']->load('plan')),
        ], 201);
    }

    public function callback(Request $request, NotchPayClient $notchPay): JsonResponse
    {
        $reference = $request->string('reference')->toString();
        abort_unless($reference !== '', 422, 'Payment reference is required.');

        $payment = $notchPay->retrieve($reference)['transaction'] ?? [];
        $subscription = UserSubscription::where('notchpay_reference', $reference)->firstOrFail();

        $this->subscriptionService->finalize($subscription, (string) ($payment['status'] ?? 'pending'));

        return response()->json(new SubscriptionResource($subscription->fresh('plan')));
    }

    public function webhook(Request $request, NotchPayClient $notchPay): JsonResponse
    {
        $secret = config('services.notchpay.webhook_secret');
        if (is_string($secret) && $secret !== '') {
            $signature = (string) $request->header('x-notchpay-signature');
            $expected = hash_hmac('sha256', $request->getContent(), $secret);
            abort_unless($signature !== '' && hash_equals($expected, $signature), 401);
        }

        $reference = (string) $request->input('data.reference', $request->input('reference'));
        if ($reference === '') {
            return response()->json(['message' => 'Ignored']);
        }

        $subscription = UserSubscription::where('notchpay_reference', $reference)->first();
        if ($subscription) {
            $payment = $notchPay->retrieve($reference)['transaction'] ?? [];
            $this->subscriptionService->finalize($subscription, (string) ($payment['status'] ?? $request->input('data.status', 'pending')));
        }

        return response()->json(['received' => true]);
    }
}
