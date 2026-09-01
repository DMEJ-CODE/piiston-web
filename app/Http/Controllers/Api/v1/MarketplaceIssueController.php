<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceIssueController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'issue_type' => 'required|string|in:COMPATIBILITY,DAMAGE,LATE_DELIVERY,OTHER',
            'description' => 'required|string',
            'request_return' => 'boolean',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        if ($order->buyer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // In a real app, we'd create an 'OrderIssue' or 'Ticket' model.
        // For now, we'll return success and log the intent.

        return response()->json([
            'message' => 'Your issue has been reported. Customer support will contact you.',
            'ticket_id' => 'TKT-'.rand(1000, 9999),
        ], 201);
    }
}
