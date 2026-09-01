<?php

namespace App\Http\Controllers\Api\BI;

use App\Http\Controllers\Controller;
use App\Models\BI\Dashboard;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->dashboards()->with('widgets.chart')->get()
        );
    }

    public function show(Dashboard $dashboard)
    {
        if ($dashboard->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($dashboard->load('widgets.chart'));
    }
}
