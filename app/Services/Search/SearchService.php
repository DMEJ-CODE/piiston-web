<?php

namespace App\Services\Search;

use App\Search\Contracts\SearchProviderInterface;
use App\Search\Providers\LocalSearchProvider;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    /**
     * Get the active search provider (abstraction layer)
     */
    public function getProvider(): SearchProviderInterface
    {
        // Future: could return Meilisearch or Elasticsearch provider based on config
        return new LocalSearchProvider;
    }

    public function globalSearch(string $query, array $filters = []): LengthAwarePaginator
    {
        return $this->getProvider()->search($query, $filters);
    }
}
