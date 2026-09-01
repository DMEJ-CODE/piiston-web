<?php

namespace App\Models\Fleets;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetVehicle extends Model
{
    protected $fillable = ['fleet_id', 'vehicle_id', 'assigned_date', 'status'];

    protected $casts = [
        'assigned_date' => 'datetime',
    ];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
