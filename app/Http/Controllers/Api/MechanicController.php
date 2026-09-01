<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mechanics\MechanicSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->mechanicProfile()->with(['type', 'skills', 'certifications', 'experiences', 'location'])->first();

        return response()->json($profile);
    }

    public function assignments()
    {
        $profile = Auth::user()->mechanicProfile;
        if (! $profile) {
            return response()->json([], 404);
        }

        return response()->json(
            $profile->assignments()->with(['repairOrder.vehicle', 'repairOrder.customer.user'])->get()
        );
    }

    public function skills()
    {
        return response()->json(MechanicSkill::all());
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $profile = Auth::user()->mechanicProfile;
        if (! $profile) {
            return response()->json(['message' => 'Mechanic profile not found'], 404);
        }

        $profile->location()->updateOrCreate(
            ['mechanic_id' => $profile->id],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'last_updated' => now(),
            ]
        );

        return response()->json(['message' => 'Location updated']);
    }
}
