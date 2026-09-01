<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActionController extends Controller
{
    public function favorites()
    {
        return response()->json(Auth::user()->favorites()->orderBy('created_at', 'desc')->get());
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
        ]);

        $favorite = Auth::user()->favorites()->where([
            'entity_type' => $request->entity_type,
            'entity_id' => $request->entity_id,
        ])->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json(['message' => 'Removed from favorites', 'is_favorite' => false]);
        }

        Auth::user()->favorites()->create($request->all());

        return response()->json(['message' => 'Added to favorites', 'is_favorite' => true], 201);
    }

    public function history()
    {
        return response()->json(Auth::user()->searchHistory()->orderBy('searched_at', 'desc')->limit(20)->get());
    }
}
