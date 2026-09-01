<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notifications\MaintenanceAlert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceAlertController extends Controller
{
    public function index(): JsonResponse
    {
        $alerts = MaintenanceAlert::whereHas('vehicle', function ($query) {
            $query->where('owner_id', Auth::id());
        })
            ->with(['vehicle.brand', 'vehicle.model'])
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json($alerts);
    }

    public function show(int $id): JsonResponse
    {
        $alert = MaintenanceAlert::with('vehicle')->findOrFail($id);

        if ($alert->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($alert);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|string|in:PENDING,IN_PROGRESS,COMPLETED,SNOOZED']);

        $alert = MaintenanceAlert::findOrFail($id);

        if ($alert->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $alert->update(['status' => $request->status]);

        return response()->json(['message' => 'Alert status updated successfully']);
    }
}
