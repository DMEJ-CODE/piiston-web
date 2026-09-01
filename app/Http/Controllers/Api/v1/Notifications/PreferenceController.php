<?php

namespace App\Http\Controllers\Api\v1\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\UpdatePreferenceRequest;
use App\Models\Notifications\NotificationPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function index(): JsonResponse
    {
        $preferences = NotificationPreference::where('user_id', Auth::id())
            ->with('type')
            ->get();

        return response()->json($preferences);
    }

    public function update(UpdatePreferenceRequest $request): JsonResponse
    {
        $preference = NotificationPreference::updateOrCreate(
            ['user_id' => Auth::id(), 'type_id' => $request->type_id],
            $request->validated()
        );

        return response()->json(['message' => 'Preferences updated', 'preference' => $preference]);
    }
}
