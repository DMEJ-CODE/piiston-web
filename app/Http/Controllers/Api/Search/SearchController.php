<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Models\Search\AutocompleteSuggestion;
use App\Models\Search\SearchCategory;
use App\Services\Search\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function globalSearch(Request $request)
    {
        $query = $request->input('q', '');
        $filters = $request->only(['category_id', 'country_id']);

        $results = $this->searchService->search($query, $filters);

        // Record history if logged in
        if (Auth::check() && ! empty($query)) {
            Auth::user()->searchHistory()->create([
                'keyword' => $query,
                'category_id' => $request->category_id,
                'country_id' => Auth::user()->country_id,
            ]);
        }

        return response()->json($results);
    }

    public function categories()
    {
        return response()->json(SearchCategory::where('status', true)->get());
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');

        return response()->json(
            AutocompleteSuggestion::where('keyword', 'like', "{$query}%")
                ->orderBy('frequency', 'desc')
                ->limit(10)
                ->get()
        );
    }
}
