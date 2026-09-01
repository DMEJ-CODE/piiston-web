<?php

namespace App\Repositories\Marketplace;

use App\Models\Marketplace\SellerProfile;

class EloquentStoreRepository implements StoreRepositoryInterface
{
    public function findById(int $id): ?SellerProfile
    {
        return SellerProfile::with(['user', 'country', 'address'])->find($id);
    }

    public function findByUserId(int $userId): ?SellerProfile
    {
        return SellerProfile::where('user_id', $userId)->first();
    }

    public function create(array $data): SellerProfile
    {
        return SellerProfile::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $store = SellerProfile::find($id);
        if (! $store) {
            return false;
        }

        return $store->update($data);
    }
}
