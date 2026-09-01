<?php

namespace App\Models\Workflows;

use App\Models\Garages\RepairOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleDelivery extends Model
{
    protected $fillable = [
        'repair_order_id', 'delivered_by', 'customer_received_at',
        'delivery_date', 'notes', 'mileage_at_delivery',
        'signature_path', 'checklist',
    ];

    protected $casts = [
        'customer_received_at' => 'datetime',
        'delivery_date' => 'datetime',
        'checklist' => 'array',
    ];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function deliverer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
}
