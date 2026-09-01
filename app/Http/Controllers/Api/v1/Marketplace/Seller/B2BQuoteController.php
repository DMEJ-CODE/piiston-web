<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PartQuote;
use App\Models\Marketplace\PartRequest;
use App\Services\Marketplace\QuoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class B2BQuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function requests(): JsonResponse
    {
        // View open requests from garages/owners
        // In a real app, we might filter by seller's categories
        $requests = PartRequest::with(['user', 'vehicle'])->where('status', 'OPEN')->latest()->get();

        return response()->json($requests);
    }

    public function store(Request $request, int $requestId): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'availability' => 'nullable|string',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
            'part_id' => 'nullable|exists:spare_parts,id',
        ]);

        $quote = $this->quoteService->submitQuote($requestId, $seller->id, $data);

        return response()->json($quote, 201);
    }

    public function myQuotes(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $quotes = PartQuote::with(['request.user', 'part'])
            ->where('seller_id', $seller->id)
            ->latest()
            ->get();

        return response()->json($quotes);
    }
}
