<?php

namespace App\Http\Controllers\Api\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebhookController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->webhooks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'event_type' => 'required|string',
        ]);

        $webhook = Auth::user()->webhooks()->create($validated + [
            'secret_token' => bin2hex(random_bytes(16)),
        ]);

        return response()->json($webhook, 201);
    }
}
