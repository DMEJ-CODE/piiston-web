<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerInventory extends Model
{
    protected $table = 'seller_inventory';

    protected $fillable = [
        'listing_id', 'quantity', 'reserved_quantity',
        'sold_quantity', 'damaged_quantity', 'returned_quantity',
        'minimum_stock', 'warehouse_location',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(ProductListing::class, 'listing_id');
    }
}
