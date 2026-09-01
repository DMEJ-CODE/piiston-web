<?php

namespace App\Models\Workflows;

use App\Models\Garages\RepairEstimate;
use App\Models\Garages\RepairOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairApproval extends Model
{
    protected $fillable = ['repair_order_id', 'customer_id', 'estimate_id', 'approval_status', 'approved_at'];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(RepairEstimate::class);
    }
}
