<?php

namespace App\Search\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface SearchProviderInterface
{
    /**
     * Search the index with a query and filters.
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Index a single document.
     */
    public function index(string $entityType, int $entityId, array $data): bool;

    /**
     * Remove a document from the index.
     */
    public function delete(string $entityType, int $entityId): bool;
}
