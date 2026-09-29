<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageSubscription;
use App\Models\Garages\GarageSubscriptionPlan;
use App\Services\Garages\SubscriptionService;
use App\Services\Payments\NotchPayClient;
use Illuminate\Http\Request;

class GarageSettingsController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptionService) {}

    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $company = $branch->company;
        $subscription = $this->subscriptionService->getActiveSubscription($branch);
        $plans = GarageSubscriptionPlan::where('is_active', true)->orderBy('monthly_price')->get();

        return view('garage.settings.index', [
            'branch' => $branch,
            'company' => $company,
            'subscription' => $subscription,
            'plans' => $plans,
        ]);
    }

    public function checkout(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manage', $branch->company);
        $data = $request->validate([
            'plan_id' => ['required', 'exists:garage_subscription_plans,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
        ]);
        $plan = GarageSubscriptionPlan::whereKey($data['plan_id'])->where('is_active', true)->firstOrFail();

        return redirect()->away($this->subscriptionService->initializeCheckout($branch, $plan, $request->user(), $data['billing_cycle']));
    }

    public function callback(Request $request, NotchPayClient $notchPay)
    {
        $reference = $request->string('reference')->toString();
        abort_unless($reference !== '', 422);
        $payment = $notchPay->retrieve($reference)['transaction'] ?? [];
        $subscription = GarageSubscription::where('transaction_reference', $reference)->firstOrFail();

        if (($payment['status'] ?? null) === 'complete') {
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => $subscription->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
            ]);

            return redirect()->route('payment.success')->with('success', 'Abonnement activé avec succès.');
        }

        $subscription->update(['status' => 'past_due']);

        return redirect()->route(in_array($payment['status'] ?? null, ['canceled', 'expired'], true) ? 'payment.canceled' : 'payment.failed')->with('error', 'Le paiement Notch Pay n’a pas été confirmé.');
    }

    public function update(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'business_hours' => ['nullable', 'array'],
            'social_links' => ['nullable', 'array'],
            'status' => ['nullable', 'boolean'],
        ]);

        if (! $request->has('status')) {
            $validated['status'] = false;
        }

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('garage_logos', 'public');
        }

        $branch->update($validated);

        if ($branch->company) {
            $branch->company->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? $branch->company->phone,
                'email' => $validated['email'] ?? $branch->company->email,
            ]);
        }

        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }

    public function updateLocation(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        $branch->update([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'] ?? $branch->address,
            'city' => $validated['city'] ?? $branch->city,
        ]);

        return back()->with('success', 'Géolocalisation du garage enregistrée avec succès !');
    }
}
