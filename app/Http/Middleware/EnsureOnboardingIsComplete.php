<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && ! $request->routeIs('onboarding')) {
            $isComplete = $user->phone_verified_at && $user->roles()->exists();
            $isAdmin = $user->hasRole('ADMIN') || $user->administrator()->exists();

            if (! $isComplete && ! $isAdmin) {
                return redirect()->route('onboarding');
            }
        }

        return $next($request);
    }
}
