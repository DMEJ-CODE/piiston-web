<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Fleets\Accident;
use App\Models\Vehicles\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index(): JsonResponse
    {
        $incidents = Accident::whereHas('vehicle', function ($query) {
            $query->where('owner_id', Auth::id());
        })
            ->with(['vehicle.brand', 'vehicle.model'])
            ->orderBy('accident_date', 'desc')
            ->get();

        return response()->json($incidents);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'location' => 'required|string',
            'accident_date' => 'required|date',
            'description' => 'required|string',
            'severity' => 'required|string|in:MINOR,MEDIUM,SEVERE',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $incident = Accident::create($validated + [
            'status' => 'REPORTED',
        ]);

        return response()->json($incident, 201);
    }
}
