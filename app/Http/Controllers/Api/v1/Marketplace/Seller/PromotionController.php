<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Promotions\Promotion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $promotions = Promotion::where('owner_type', 'SellerProfile')
            ->where('owner_id', $seller->id)
            ->get();

        return response()->json($promotions);
    }

    public function store(Request $request): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'promotion_type_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data['owner_type'] = 'SellerProfile';
        $data['owner_id'] = $seller->id;
        $data['status'] = 'active';

        $promotion = Promotion::create($data);

        return response()->json($promotion, 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $promotion = Promotion::where('owner_type', 'SellerProfile')
            ->where('owner_id', $seller->id)
            ->findOrFail($id);

        $promotion->delete();

        return response()->json(['message' => 'Promotion deleted']);
    }
}
