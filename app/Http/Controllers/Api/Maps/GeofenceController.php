<?php

namespace App\Http\Controllers\Api\Maps;

use App\Http\Controllers\Controller;
use App\Models\Maps\Geofence;
use Illuminate\Http\Request;

class GeofenceController extends Controller
{
    public function index()
    {
        return response()->json(Geofence::where('status', true)->get());
    }

    public function store(Request $request)
    {
        $geofence = Geofence::create($request->all());

        return response()->json($geofence, 201);
    }

    public function logEvent(Request $request, Geofence $geofence)
    {
        $event = $geofence->events()->create($request->all() + ['occurred_at' => now()]);

        return response()->json($event, 201);
    }
}
