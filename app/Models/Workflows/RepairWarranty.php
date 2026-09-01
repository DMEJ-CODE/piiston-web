<?php

namespace App\Models\Workflows;

use App\Models\Garages\RepairOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairWarranty extends Model
{
    protected $fillable = ['repair_order_id', 'duration', 'start_date', 'end_date', 'conditions', 'status'];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }
}
