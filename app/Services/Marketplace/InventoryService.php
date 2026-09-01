<?php

namespace App\Services\Marketplace;

use App\Models\Marketplace\ProductListing;
use App\Models\Marketplace\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Records a stock movement and updates the inventory level.
     */
    public function recordMovement(int $listingId, string $type, int $quantity, ?string $reason = null): StockMovement
    {
        return DB::transaction(function () use ($listingId, $type, $quantity, $reason) {
            $listing = ProductListing::findOrFail($listingId);
            $inventory = $listing->inventory()->firstOrCreate(
                ['listing_id' => $listingId],
                ['quantity' => 0, 'minimum_stock' => 0]
            );

            // Update quantity based on type
            if (in_array($type, ['PURCHASE', 'RETURN', 'ADJUSTMENT_IN'])) {
                $inventory->increment('quantity', $quantity);
            } else {
                $inventory->decrement('quantity', $quantity);
            }

            // Sync listing total quantity
            $listing->update(['quantity' => $inventory->quantity]);

            return StockMovement::create([
                'inventory_id' => $inventory->id,
                'listing_id' => $listingId, // Legacy mapping if needed
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $reason,
                'movement_date' => now(),
            ]);
        });
    }

    /**
     * Adjusts stock manually.
     */
    public function adjustStock(int $listingId, int $newQuantity, string $reason): StockMovement
    {
        $listing = ProductListing::findOrFail($listingId);
        $inventory = $listing->inventory;
        $currentQuantity = $inventory ? $inventory->quantity : 0;

        $diff = $newQuantity - $currentQuantity;
        $type = $diff >= 0 ? 'ADJUSTMENT_IN' : 'ADJUSTMENT_OUT';

        return $this->recordMovement($listingId, $type, abs($diff), $reason);
    }
}
