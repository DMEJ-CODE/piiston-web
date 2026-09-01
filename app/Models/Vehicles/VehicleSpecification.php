<?php

namespace App\Models\Vehicles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleSpecification extends Model
{
    protected $fillable = [
        'vehicle_id', 'engine_id', 'dimensions',
        'weight', 'drive_type', 'doors', 'seats',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function engine(): BelongsTo
    {
        return $this->belongsTo(VehicleEngine::class);
    }
}
