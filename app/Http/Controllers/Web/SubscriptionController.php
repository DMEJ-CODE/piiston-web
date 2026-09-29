<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finance\SubscriptionPlan;
use App\Models\Finance\UserSubscription;
use App\Services\Finance\SubscriptionService;
use App\Services\Payments\NotchPayClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptionService) {}

    public function publicIndex()
    {
        return view('pricing.index', [
            'plans' => SubscriptionPlan::where('status', true)->with('currency')->orderBy('price')->get(),
        ]);
    }

    public function publicShow(SubscriptionPlan $plan)
    {
        abort_if(! $plan->status, 404);

        return view('pricing.show', [
            'plan' => $plan->load('currency'),
        ]);
    }

    public function publicCheckout(SubscriptionPlan $plan)
    {
        abort_if(! $plan->status, 404);

        try {
            $result = $this->subscriptionService->subscribe(request()->user(), $plan->id);
        } catch (ConnectionException|RequestException) {
            return redirect()->route('payment.failed')->with('error', 'Le service de paiement est temporairement indisponible.');
        }

        return redirect()->away($result['checkout_url']);
    }

    public function index(Request $request)
    {
        abort_if($request->user()->hasRole('ADMIN'), 403);

        return view('subscriptions.index', [
            'plans' => SubscriptionPlan::where('status', true)->with('currency')->orderBy('price')->get(),
            'subscription' => $request->user()->subscription()->with('plan')->latest()->first(),
        ]);
    }

    public function checkout(Request $request)
    {
        abort_if($request->user()->hasRole('ADMIN'), 403);
        $data = $request->validate(['plan_id' => ['required', 'exists:subscription_plans,id']]);
        $result = $this->subscriptionService->subscribe($request->user(), (int) $data['plan_id']);

        return redirect()->away($result['checkout_url']);
    }

    public function callback(Request $request, NotchPayClient $notchPay)
    {
        $reference = $request->string('reference')->toString();
        abort_unless($reference !== '', 422, 'Payment reference is required.');

        $subscription = $request->user()?->subscription()->where('notchpay_reference', $reference)->first()
            ?: UserSubscription::where('notchpay_reference', $reference)->firstOrFail();
        $payment = $notchPay->retrieve($reference)['transaction'] ?? [];
        $this->subscriptionService->finalize($subscription, (string) ($payment['status'] ?? 'pending'));

        return redirect()->route(($subscription->fresh()->status === 'active' ? 'payment.success' : 'payment.failed'));
    }
}
