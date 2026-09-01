<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administration\PlatformStatistic;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Fetch latest cached statistics
        return response()->json(
            PlatformStatistic::orderBy('stat_date', 'desc')->limit(20)->get()
        );
    }
}
