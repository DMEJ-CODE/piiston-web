<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairPartUsage extends Model
{
    protected $table = 'repair_parts_usage';

    protected $fillable = [
        'repair_order_id', 'part_id', 'part_name',
        'quantity', 'unit_price', 'total_price',
    ];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }
}
