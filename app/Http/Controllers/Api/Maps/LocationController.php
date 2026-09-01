<?php

namespace App\Http\Controllers\Api\Maps;

use App\Http\Controllers\Controller;
use App\Models\Maps\GeoPoint;
use App\Models\Maps\Location;
use App\Models\Maps\NearbySearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'nullable|string',
            'radius_meters' => 'nullable|integer',
        ]);

        $radius = $request->input('radius_meters', 10000);
        $type = $request->input('type');

        // Record search
        if (Auth::check()) {
            NearbySearch::create([
                'user_id' => Auth::id(),
                'search_type' => $type ?? 'ALL',
                'radius_meters' => $radius,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        }

        // Logic would use Haversine query here
        return response()->json(
            Location::where('status', 'active')->limit(50)->get()
        );
    }

    public function updateCurrent(Request $request)
    {
        $user = Auth::user();
        $user->location()->updateOrCreate(
            ['entity_id' => $user->id, 'entity_type' => 'User'],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'accuracy' => $request->accuracy,
                'source' => $request->source ?? 'gps',
            ]
        );

        return response()->json(['message' => 'Location updated']);
    }

    public function geoPoints()
    {
        return response()->json(GeoPoint::all());
    }
}
