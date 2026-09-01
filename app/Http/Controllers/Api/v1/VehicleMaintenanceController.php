<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleMaintenance;
use App\Models\Workflows\EmergencyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleMaintenanceController extends Controller
{
    public function index(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        // Manual check for now if no Policy
        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $maintenances = $vehicle->maintenances()->orderBy('date', 'desc')->get();

        return response()->json($maintenances);
    }

    public function store(Request $request, int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $user = Auth::user();

        // Check authorization: Owner OR assigned mechanic in an active SOS
        $isOwner = $vehicle->owner_id === $user->id;
        $isAssignedMechanic = EmergencyRequest::where('vehicle_id', $vehicleId)
            ->where('assigned_mechanic_id', $user->id)
            ->whereIn('status', ['ACCEPTED', 'IN_PROGRESS', 'ARRIVED'])
            ->exists();

        if (! $isOwner && ! $isAssignedMechanic) {
            return response()->json(['message' => 'Unauthorized. Only the owner or an assigned mechanic can add maintenance records.'], 403);
        }

        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'date' => 'required|date',
            'mileage' => 'required|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'garage_id' => 'nullable|exists:garages,id',
            'status' => 'nullable|string|in:COMPLETED,SCHEDULED',
        ]);

        $maintenance = $vehicle->maintenances()->create($validated);

        return response()->json([
            'message' => 'Maintenance record added successfully',
            'maintenance' => $maintenance,
        ], 201);
    }

    public function show(int $vehicleId, int $id): JsonResponse
    {
        $maintenance = VehicleMaintenance::where('vehicle_id', $vehicleId)->findOrFail($id);

        if ($maintenance->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($maintenance);
    }

    public function destroy(int $vehicleId, int $id): JsonResponse
    {
        $maintenance = VehicleMaintenance::where('vehicle_id', $vehicleId)->findOrFail($id);

        if ($maintenance->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $maintenance->delete();

        return response()->json(['message' => 'Maintenance record deleted']);
    }
}
