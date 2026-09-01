<?php

namespace App\Models\Garages;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyClaim extends Model
{
    protected $fillable = [
        'branch_id', 'repair_order_id', 'customer_id', 'vehicle_id',
        'warranty_provider', 'warranty_reference', 'warranty_expiry_date',
        'claim_description', 'status', 'claim_amount', 'approved_amount',
        'resolution_notes', 'resolved_at',
    ];

    protected $casts = [
        'warranty_expiry_date' => 'date',
        'resolved_at' => 'datetime',
        'claim_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(GarageCustomer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
