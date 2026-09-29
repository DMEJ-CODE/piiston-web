<?php

namespace App\Http\Middleware;

use App\Models\Garages\GarageSubscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();
        if (! $user || $user->hasRole('ADMIN') || $user->administrator()->exists()) {
            return $next($request);
        }

        $features = [];
        if ($request->attributes->get('garageBranch')) {
            $subscription = GarageSubscription::where('company_id', $request->attributes->get('garageBranch')->company_id)
                ->where('status', 'active')
                ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))
                ->with('plan')
                ->latest('ends_at')
                ->first();
            $features = $subscription?->plan?->features ?? [];
        } else {
            $subscription = $user->subscription()->with('plan')->where('status', 'active')->latest('end_date')->first();
            $features = $subscription?->plan?->features ?? [];
        }

        // Existing plans without a feature list remain backward-compatible.
        if ($features !== [] && ! in_array($feature, $features, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'This feature is not included in your subscription.'], 403);
            }

            return response()->view('errors.subscription-feature', ['feature' => $feature], 403);
        }

        return $next($request);
    }
}
