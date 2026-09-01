<?php

namespace App\Models\Workflows;

use App\Models\Garages\RepairOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairProgress extends Model
{
    protected $table = 'repair_progress';

    protected $fillable = ['repair_order_id', 'status', 'message'];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }
}
