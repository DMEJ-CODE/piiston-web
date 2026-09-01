<?php

namespace App\Http\Controllers\Api\v1\Social;

use App\Http\Controllers\Controller;
use App\Models\Social\SocialInteraction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedInteractionController extends Controller
{
    public function toggleLike(Request $request): JsonResponse
    {
        $request->validate([
            'interactable_id' => 'required',
            'interactable_type' => 'required|string',
        ]);

        $userId = Auth::id();
        $type = 'LIKE';

        $existing = SocialInteraction::where('user_id', $userId)
            ->where('interactable_id', $request->interactable_id)
            ->where('interactable_type', $request->interactable_type)
            ->where('type', $type)
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json(['liked' => false, 'message' => 'Like removed']);
        }

        SocialInteraction::create([
            'user_id' => $userId,
            'interactable_id' => $request->interactable_id,
            'interactable_type' => $request->interactable_type,
            'type' => $type,
        ]);

        return response()->json(['liked' => true, 'message' => 'Liked successfully']);
    }

    public function comment(Request $request): JsonResponse
    {
        $request->validate([
            'interactable_id' => 'required',
            'interactable_type' => 'required|string',
            'content' => 'required|string|max:1000',
        ]);

        $comment = SocialInteraction::create([
            'user_id' => Auth::id(),
            'interactable_id' => $request->interactable_id,
            'interactable_type' => $request->interactable_type,
            'type' => 'COMMENT',
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment posted',
            'comment' => $comment->load('user:id,first_name,last_name,avatar_url'),
        ], 201);
    }

    public function getComments(Request $request): JsonResponse
    {
        $request->validate([
            'interactable_id' => 'required',
            'interactable_type' => 'required|string',
        ]);

        $comments = SocialInteraction::where('interactable_id', $request->interactable_id)
            ->where('interactable_type', $request->interactable_type)
            ->where('type', 'COMMENT')
            ->with('user:id,first_name,last_name,avatar_url')
            ->latest()
            ->paginate(20);

        return response()->json($comments);
    }
}
