<?php

namespace App\Models\Marketplace;

use App\Models\Garages\RepairOrder;
use App\Models\Mechanics\MechanicProfile;
use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartRecommendation extends Model
{
    protected $fillable = ['mechanic_id', 'vehicle_id', 'part_id', 'repair_order_id', 'notes', 'status'];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }
}
