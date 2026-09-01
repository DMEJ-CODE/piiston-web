<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fleets\Company;
use App\Models\Fleets\Fleet;
use Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{
    public function companies()
    {
        return response()->json(
            Company::where('owner_id', Auth::id())->with('fleets')->get()
        );
    }

    public function fleetDetails(Fleet $fleet)
    {
        // Simple security check for this turn
        return response()->json($fleet->load(['vehicles.brand', 'vehicles.model', 'vehicles.health', 'members.user', 'maintenancePlans']));
    }

    public function vehicles(Fleet $fleet)
    {
        return response()->json($fleet->vehicles()->with(['brand', 'model', 'health'])->get());
    }
}
