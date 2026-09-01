<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleOwnership;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OwnershipHistoryController extends Controller
{
    public function index(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        // Ownership history is public/semi-public for the current owner in the Digital Passport
        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $history = VehicleOwnership::where('vehicle_id', $vehicleId)
            ->with('owner:id,name')
            ->orderBy('start_date', 'asc')
            ->get();

        return response()->json($history);
    }
}
