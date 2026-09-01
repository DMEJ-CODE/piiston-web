<?php

namespace App\Http\Controllers\Api\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeveloperController extends Controller
{
    public function myApplications()
    {
        return response()->json(
            Auth::user()->developerApplications()->with('subscription')->get()
        );
    }

    public function createApplication(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|unique:developer_applications,app_name',
            'description' => 'nullable|string',
        ]);

        $app = Auth::user()->developerApplications()->create($validated);

        $app->subscription()->create([
            'plan_name' => 'FREE',
            'daily_limit' => 1000,
        ]);

        return response()->json($app, 201);
    }
}
