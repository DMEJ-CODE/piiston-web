<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'device_token' => 'required|string|unique:user_devices,device_token',
            'device_type' => 'required|string', // ANDROID, IOS, WEB
            'platform' => 'nullable|string',
        ]);

        $device = Auth::user()->notificationDevices()->updateOrCreate(
            ['device_token' => $validated['device_token']],
            [
                'device_type' => $validated['device_type'],
                'platform' => $validated['platform'],
                'last_active' => now(),
                'status' => true,
            ]
        );

        return response()->json($device, 201);
    }
}
