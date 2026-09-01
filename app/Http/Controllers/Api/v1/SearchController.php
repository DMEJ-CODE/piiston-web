<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Search\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function globalSearch(Request $request): JsonResponse
    {
        $query = $request->query('q', '');
        $filters = $request->only(['category_id', 'country_id']);

        $results = $this->searchService->globalSearch($query, $filters);

        // Record history
        if (Auth::check() && ! empty($query)) {
            Auth::user()->searchHistory()->create([
                'keyword' => $query,
                'category_id' => $request->category_id,
                'country_id' => Auth::user()->country_id,
            ]);
        }

        return response()->json($results);
    }
}
