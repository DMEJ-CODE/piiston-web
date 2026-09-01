<?php

namespace App\Http\Middleware;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageEmployee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ResolveGarageBranch
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Only Garage Owners, Mechanics and Admins should access these routes
        if (! ($user->hasRole('GARAGE_OWNER') || $user->hasRole('MECHANIC') || $user->hasRole('ADMIN'))) {
            return redirect()->route('dashboard')->with('error', 'Accès réservé aux professionnels du garage.');
        }

        $branch = null;

        // Try to get branch from session first (for multi-branch support)
        if (session()->has('active_garage_branch_id')) {
            $branch = GarageBranch::find(session('active_garage_branch_id'));

            // Security check: Verify user still has access to this branch
            if ($branch && ! $this->userHasAccess($user, $branch)) {
                $branch = null;
                session()->forget('active_garage_branch_id');
            }
        }

        if (! $branch) {
            // Fallback: Get first accessible branch
            $employee = GarageEmployee::where('user_id', $user->id)->first();
            if ($employee && $employee->branch) {
                $branch = $employee->branch;
            } else {
                $company = GarageCompany::where('owner_id', $user->id)->first();
                if ($company) {
                    $branch = $company->branches()->first();
                }
            }

            if ($branch) {
                session(['active_garage_branch_id' => $branch->id]);
            }
        }

        // Redirect to setup if no company exists for a GARAGE_OWNER
        if (! $branch && $user->hasRole('GARAGE_OWNER')) {
            $hasCompany = GarageCompany::where('owner_id', $user->id)->exists();
            if (! $hasCompany && ! $request->routeIs('garage.setup*')) {
                return redirect()->route('garage.setup');
            }
        }

        // Final check: If still no branch, and not on setup page
        if (! $branch && ! $request->routeIs('garage.setup*')) {
            if ($user->hasRole('ADMIN')) {
                $branch = GarageBranch::first();
            }

            if (! $branch && $user->hasRole('GARAGE_OWNER')) {
                return redirect()->route('garage.setup');
            }

            if (! $branch) {
                return redirect()->route('dashboard')->with('error', 'Accès réservé aux professionnels du garage.');
            }
        }

        if ($branch) {
            $request->attributes->set('garageBranch', $branch);
            View::share('activeBranch', $branch);
        }

        return $next($request);
    }

    protected function userHasAccess($user, $branch): bool
    {
        if ($user->hasRole('ADMIN')) {
            return true;
        }
        if ($user->id === $branch->company->owner_id) {
            return true;
        }
        if ($user->id === $branch->manager_id) {
            return true;
        }

        return $branch->employees()->where('user_id', $user->id)->exists();
    }
}
