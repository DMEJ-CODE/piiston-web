<?php

namespace App\Models\Fleets;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageLog extends Model
{
    protected $table = 'vehicle_usage_logs';

    protected $fillable = [
        'vehicle_id', 'driver_id', 'start_location', 'end_location',
        'distance_km', 'start_time', 'end_time', 'purpose',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
