<?php

namespace App\Models\Fleets;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accident extends Model
{
    protected $table = 'vehicle_accidents';

    protected $fillable = [
        'vehicle_id', 'driver_id', 'location', 'accident_date',
        'description', 'severity', 'repair_cost_estimated', 'status',
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
