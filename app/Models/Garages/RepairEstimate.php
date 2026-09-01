<?php

namespace App\Models\Garages;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairEstimate extends Model
{
    protected $fillable = [
        'repair_order_id', 'branch_id', 'created_by', 'subtotal',
        'labor_cost', 'parts_cost', 'tax', 'discount', 'total_amount',
        'status', 'public_token', 'token_expires_at', 'valid_until', 'notes',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'valid_until' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
