<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairPart extends Model
{
    protected $fillable = [
        'branch_id', 'part_number', 'name', 'category', 'description',
        'manufacturer', 'compatible_vehicles', 'cost_price', 'selling_price',
        'stock_quantity', 'minimum_stock', 'maximum_stock', 'storage_location', 'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }
}
