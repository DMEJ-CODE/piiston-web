<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Messaging\Conversation;
use App\Models\Messaging\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        if (! $conversation->members()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'message_type' => 'required|string',
            'content' => 'nullable|string',
            'reply_message_id' => 'nullable|exists:messages,id',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'message_type' => $validated['message_type'],
            'content' => $validated['content'],
            'reply_message_id' => $validated['reply_message_id'] ?? null,
            'sent_at' => now(),
        ]);

        $conversation->update(['last_message_id' => $message->id]);

        return response()->json($message->load('sender'), 201);
    }

    public function markAsRead(Message $message)
    {
        $message->statuses()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'READ', 'timestamp' => now()]
        );

        return response()->json(['message' => 'Marked as read']);
    }

    public function react(Request $request, Message $message)
    {
        $request->validate(['reaction' => 'required|string']);

        $message->reactions()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['reaction' => $request->reaction]
        );

        return response()->json(['message' => 'Reaction added']);
    }
}
