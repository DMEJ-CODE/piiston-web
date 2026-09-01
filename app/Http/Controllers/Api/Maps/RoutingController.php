<?php

namespace App\Http\Controllers\Api\Maps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoutingController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
        ]);

        // In a real app, this calls OSM or Google Directions API
        return response()->json([
            'distance_km' => 15.5,
            'duration_minutes' => 25,
            'points' => [
                ['lat' => $request->origin_lat, 'lng' => $request->origin_lng],
                ['lat' => $request->dest_lat, 'lng' => $request->dest_lng],
            ],
        ]);
    }
}
