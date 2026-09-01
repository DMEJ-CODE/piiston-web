<?php

namespace App\Http\Controllers\Api\v1\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PartQuote;
use App\Services\Marketplace\QuoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PartQuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function accept(int $id): JsonResponse
    {
        $quote = PartQuote::with('request')->findOrFail($id);

        // Ensure the requester is the one accepting
        if ($quote->request->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order = $this->quoteService->acceptQuote($id);

        return response()->json([
            'message' => 'Quote accepted and order created',
            'order' => $order,
        ]);
    }
}
