<?php

namespace App\Repositories\Marketplace;

use App\Models\Marketplace\SellerProfile;

interface StoreRepositoryInterface
{
    public function findById(int $id): ?SellerProfile;

    public function findByUserId(int $userId): ?SellerProfile;

    public function create(array $data): SellerProfile;

    public function update(int $id, array $data): bool;
}
