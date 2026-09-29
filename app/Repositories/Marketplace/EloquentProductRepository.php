<?php

namespace App\Repositories\Marketplace;

use App\Models\Marketplace\ProductListing;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?ProductListing
    {
        return ProductListing::with(['part.category', 'part.brand', 'seller', 'currency', 'documents.mediaFile'])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->find($id);
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = ProductListing::query()
            ->with(['part.category', 'part.brand', 'seller', 'currency', 'documents.mediaFile'])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->whereIn('status', ['active', 1, 'ACTIVE', 'TRUE', true]);

        if (isset($filters['q'])) {
            $s = $filters['q'];
            $query->whereHas('part', function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                    ->orWhere('description', 'like', "%$s%")
                    ->orWhere('part_number', 'like', "%$s%");
            });
        }

        if (isset($filters['category_id'])) {
            $query->whereHas('part', function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        if (isset($filters['has_video']) && $filters['has_video'] == 1) {
            $query->whereHas('documents.mediaFile', function ($q) {
                $q->where('media_type', 'VIDEO');
            });
        }

        if (isset($filters['vehicle_id'])) {
            // Integration with Vehicle Compatibility logic
            $query->whereHas('part.compatibilities', function ($q) {
                // Simplified: assuming vehicle_id check can be mapped to brand/model in compatibility
            });
        }

        return $query->paginate($perPage);
    }

    public function getStoreProducts(int $sellerId): LengthAwarePaginator
    {
        return ProductListing::where('seller_id', $sellerId)
            ->with(['part'])
            ->paginate(15);
    }

    public function create(array $data): ProductListing
    {
        return ProductListing::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $listing = ProductListing::find($id);
        if (! $listing) {
            return false;
        }

        return $listing->update($data);
    }

    public function delete(int $id): bool
    {
        $listing = ProductListing::find($id);
        if (! $listing) {
            return false;
        }

        return $listing->delete();
    }
}
