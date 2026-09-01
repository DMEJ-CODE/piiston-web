<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Garages\SubscriptionService;
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

        return view('garage.settings.index', [
            'branch' => $branch,
            'company' => $company,
            'subscription' => $subscription,
        ]);
    }

    public function update(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manage', $branch->company);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'business_hours' => ['nullable', 'array'],
            'social_links' => ['nullable', 'array'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data = $request->all();
        if (! $request->has('status')) {
            $data['status'] = false;
        }

        $branch->update($data);

        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }
}
