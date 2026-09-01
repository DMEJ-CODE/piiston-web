<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Fleets\Insurance;
use App\Models\Vehicles\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsuranceController extends Controller
{
    public function index(): JsonResponse
    {
        $insurances = Insurance::whereHas('vehicle', function ($query) {
            $query->where('owner_id', Auth::id());
        })
            ->with(['vehicle.brand', 'vehicle.model'])
            ->orderBy('expiry_date', 'asc')
            ->get();

        return response()->json($insurances);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'provider_name' => 'required|string|max:255',
            'policy_number' => 'required|string|max:255',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after:start_date',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $insurance = Insurance::create($validated + ['status' => 'ACTIVE']);

        return response()->json($insurance, 201);
    }
}
