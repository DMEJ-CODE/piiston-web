<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Models\Search\TrendingItem;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function personalized()
    {
        return response()->json(
            Auth::user()->recommendations()->orderBy('score', 'desc')->limit(20)->get()
        );
    }

    public function trending()
    {
        return response()->json(
            TrendingItem::orderBy('score', 'desc')->limit(10)->get()
        );
    }
}
