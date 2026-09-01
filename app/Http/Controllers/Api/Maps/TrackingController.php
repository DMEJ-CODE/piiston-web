<?php

namespace App\Http\Controllers\Api\Maps;

use App\Http\Controllers\Controller;
use App\Models\Maps\TrackingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function sessions()
    {
        return response()->json(
            TrackingSession::with('liveLocation')->where('status', 'active')->get()
        );
    }

    public function start(Request $request)
    {
        $session = TrackingSession::create([
            'entity_type' => $request->entity_type,
            'entity_id' => $request->entity_id,
            'started_by' => Auth::id(),
            'status' => 'active',
        ]);

        return response()->json($session, 201);
    }

    public function updateLiveLocation(Request $request, TrackingSession $session)
    {
        $session->liveLocation()->updateOrCreate(
            ['tracking_session_id' => $session->id],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'speed' => $request->speed,
                'heading' => $request->heading,
                'captured_at' => now(),
            ]
        );

        // Record history
        $session->history()->create($request->only(['latitude', 'longitude', 'speed', 'heading']) + ['recorded_at' => now()]);

        return response()->json(['message' => 'Live location updated']);
    }
}
