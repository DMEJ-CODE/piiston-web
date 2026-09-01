<?php

namespace App\Services\Marketplace;

use App\Models\Marketplace\ProductListing;
use App\Models\Marketplace\SellerProfile;
use App\Repositories\Marketplace\ProductRepositoryInterface;

class MarketplaceService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProductListing(SellerProfile $store, array $data): ProductListing
    {
        return $this->productRepository->create($data + [
            'seller_id' => $store->id,
            'status' => 'active',
        ]);
    }

    public function updateStock(ProductListing $listing, int $quantity, string $type = 'adjustment'): void
    {
        $newQuantity = $type === 'sale' ? $listing->quantity - $quantity : $listing->quantity + $quantity;
        $listing->update(['quantity' => max(0, $newQuantity)]);

        // Log movement
        $listing->inventory()->updateOrCreate(['listing_id' => $listing->id], [
            'current_quantity' => $listing->quantity,
        ]);
    }
}
