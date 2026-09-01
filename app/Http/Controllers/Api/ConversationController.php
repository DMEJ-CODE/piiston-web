<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Messaging\Conversation;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->conversations()
                ->with(['lastMessage', 'members'])
                ->orderBy('updated_at', 'desc')
                ->get()
        );
    }

    public function show(Conversation $conversation)
    {
        if (! $conversation->members()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($conversation->load(['members', 'contexts', 'groupMetadata']));
    }

    public function getMessages(Conversation $conversation)
    {
        if (! $conversation->members()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            $conversation->messages()
                ->with(['sender', 'media', 'voice', 'location', 'replyTo'])
                ->orderBy('created_at', 'asc')
                ->paginate(50)
        );
    }
}
