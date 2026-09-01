<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Maps\TrackingSession;
use App\Models\Workflows\EmergencyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SOSTrackingController extends Controller
{
    public function show(int $emergencyId): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($emergencyId);
        $user = Auth::user();

        // If Owner is calling, return Mechanic's location
        // If Mechanic is calling, return Owner's location
        $entityType = '';
        $entityId = null;

        if ($emergency->user_id === $user->id) {
            $entityType = 'MECHANIC';
            $entityId = $emergency->assigned_mechanic_id;
        } elseif ($emergency->assigned_mechanic_id === $user->id || ($emergency->branch && $emergency->branch->manager_id === $user->id)) {
            $entityType = 'USER';
            $entityId = $emergency->user_id;
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (! $entityId) {
            return response()->json(['message' => 'Tracking entity not assigned yet'], 404);
        }

        // Find associated tracking session
        $session = TrackingSession::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $session) {
            return response()->json(['message' => 'Tracking not available yet'], 404);
        }

        return response()->json([
            'session_id' => $session->id,
            'entity_type' => $entityType,
            'current_location' => [
                'lat' => $session->liveLocation?->latitude,
                'lng' => $session->liveLocation?->longitude,
            ],
            'last_updated' => $session->liveLocation?->captured_at ?? $session->updated_at,
        ]);
    }

    public function store(Request $request, int $emergencyId): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($emergencyId);
        $user = Auth::user();

        // Determine if current user is Owner or Mechanic for this SOS
        $entityType = '';
        if ($emergency->user_id === $user->id) {
            $entityType = 'USER';
        } elseif ($emergency->assigned_mechanic_id === $user->id) {
            $entityType = 'MECHANIC';
        } else {
            return response()->json(['message' => 'Unauthorized to update tracking for this SOS'], 403);
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        // Find or create active session for this entity
        $session = TrackingSession::firstOrCreate(
            [
                'entity_type' => $entityType,
                'entity_id' => $user->id,
                'status' => 'active',
            ],
            [
                'started_by' => $user->id,
                'started_at' => now(),
            ]
        );

        // Update live location
        $session->liveLocation()->updateOrCreate(
            ['tracking_session_id' => $session->id],
            [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'speed' => $validated['speed'] ?? 0,
                'heading' => $validated['heading'] ?? 0,
                'captured_at' => now(),
            ]
        );

        // Record history
        $session->history()->create([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'speed' => $validated['speed'] ?? 0,
            'heading' => $validated['heading'] ?? 0,
            'recorded_at' => now(),
        ]);

        return response()->json(['message' => 'SOS Tracking updated']);
    }
}
