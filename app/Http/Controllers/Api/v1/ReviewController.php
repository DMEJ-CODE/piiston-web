<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:garage_companies,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = GarageReview::create([
            'company_id' => $validated['company_id'],
            'customer_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'review_date' => now(),
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $reviews = GarageReview::where('customer_id', Auth::id())
            ->with('company')
            ->orderBy('review_date', 'desc')
            ->get();

        return response()->json($reviews);
    }
}
