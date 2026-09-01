<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\SellerReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $reviews = SellerReview::with('buyer')
            ->where('seller_id', $seller->id)
            ->latest()
            ->paginate(15);

        return response()->json($reviews);
    }

    public function respond(Request $request, int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $review = SellerReview::where('seller_id', $seller->id)->findOrFail($id);

        $data = $request->validate([
            'response' => 'required|string',
        ]);

        $review->update(['seller_response' => $data['response']]);

        return response()->json($review);
    }
}
