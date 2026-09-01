<?php

namespace App\Models\Mechanics;

use App\Models\Garages\RepairOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicAssignment extends Model
{
    protected $fillable = ['mechanic_id', 'repair_order_id', 'assigned_by', 'assigned_date', 'status'];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class, 'mechanic_id');
    }

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
