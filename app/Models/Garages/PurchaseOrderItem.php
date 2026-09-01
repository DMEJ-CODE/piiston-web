<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'part_id', 'part_name', 'part_number',
        'quantity', 'unit_price', 'total_price', 'received_quantity', 'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(GaragePurchaseOrder::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(RepairPart::class);
    }
}
