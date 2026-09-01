<?php

namespace App\Search\Providers;

use App\Models\Search\SearchIndex;
use App\Search\Contracts\SearchProviderInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LocalSearchProvider implements SearchProviderInterface
{
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $searchQuery = SearchIndex::query()->where('status', true);

        if (! empty($query)) {
            $searchQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('keywords', 'like', "%{$query}%");
            });
        }

        if (isset($filters['category_id'])) {
            $searchQuery->where('category_id', $filters['category_id']);
        }

        if (isset($filters['country_id'])) {
            $searchQuery->where('country_id', $filters['country_id']);
        }

        // Standard local ranking logic
        $searchQuery->orderBy(DB::raw('(popularity_score * 0.4) + (rating_score * 0.4) + (search_score * 0.2)'), 'desc');

        return $searchQuery->paginate($perPage);
    }

    public function index(string $entityType, int $entityId, array $data): bool
    {
        SearchIndex::updateOrCreate(
            ['entity_type' => $entityType, 'entity_id' => $entityId],
            $data
        );

        return true;
    }

    public function delete(string $entityType, int $entityId): bool
    {
        return SearchIndex::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->delete();
    }
}
