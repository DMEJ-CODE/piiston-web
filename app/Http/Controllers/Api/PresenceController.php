<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    public function updateStatus(Request $request)
    {
        $request->validate(['status' => 'required|string']);

        Auth::user()->presence()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => $request->status, 'last_seen' => now()]
        );

        return response()->json(['message' => 'Status updated']);
    }

    public function updateTyping(Request $request, $conversationId)
    {
        // Simple indicator logic
        return response()->json(['message' => 'Typing status sent']);
    }
}
