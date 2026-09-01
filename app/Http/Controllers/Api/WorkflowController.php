<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garages\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    public function myServiceRequests()
    {
        return response()->json(
            Auth::user()->serviceRequests()->with(['vehicle.brand', 'vehicle.model', 'appointment'])->get()
        );
    }

    public function storeServiceRequest(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'request_type' => 'required|string',
            'description' => 'required|string',
            'priority' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $serviceRequest = Auth::user()->serviceRequests()->create($validated);

        return response()->json($serviceRequest, 201);
    }

    public function repairProgress(RepairOrder $repairOrder)
    {
        // Check if user is owner of the vehicle or the assigned mechanic
        if (Auth::id() !== $repairOrder->customer_id && Auth::id() !== $repairOrder->assigned_mechanic_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($repairOrder->progress()->orderBy('created_at', 'desc')->get());
    }

    public function diagnosis(RepairOrder $repairOrder)
    {
        return response()->json($repairOrder->diagnosis()->with(['measurements', 'faultCodes'])->first());
    }
}
