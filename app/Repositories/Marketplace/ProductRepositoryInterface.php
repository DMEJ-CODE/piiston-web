<?php

namespace App\Repositories\Marketplace;

use App\Models\Marketplace\ProductListing;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?ProductListing;

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getStoreProducts(int $sellerId): LengthAwarePaginator;

    public function create(array $data): ProductListing;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
