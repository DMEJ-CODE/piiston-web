<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageInventory extends Model
{
    protected $table = 'garage_inventory';

    protected $fillable = [
        'branch_id', 'part_id', 'internal_part_number',
        'name', 'quantity', 'minimum_stock',
        'maximum_stock', 'storage_location', 'status',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }
}
