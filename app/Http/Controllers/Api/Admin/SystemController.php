<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administration\FeatureFlag;
use App\Models\Administration\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemController extends Controller
{
    public function settings()
    {
        return response()->json(SystemSetting::all());
    }

    public function updateSetting(Request $request, $key)
    {
        $setting = SystemSetting::where('setting_key', $key)->firstOrFail();
        $setting->update([
            'setting_value' => $request->value,
            'updated_by' => Auth::user()->administrator->id,
        ]);

        return response()->json(['message' => "Setting {$key} updated"]);
    }

    public function featureFlags()
    {
        return response()->json(FeatureFlag::all());
    }

    public function toggleFeature(Request $request, $id)
    {
        $flag = FeatureFlag::findOrFail($id);
        $flag->update(['enabled' => $request->enabled]);

        return response()->json(['message' => "Feature {$flag->feature_name} updated"]);
    }
}
