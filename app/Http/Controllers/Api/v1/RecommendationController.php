<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Search\TrendingItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function index(): JsonResponse
    {
        $recommendations = Auth::user()->recommendations()
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get();

        return response()->json($recommendations);
    }

    public function trending(): JsonResponse
    {
        $trending = TrendingItem::orderBy('score', 'desc')
            ->limit(10)
            ->get();

        return response()->json($trending);
    }
}
