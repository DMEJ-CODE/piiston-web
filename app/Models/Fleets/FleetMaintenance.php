<?php

namespace App\Models\Fleets;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetMaintenance extends Model
{
    protected $table = 'fleet_maintenance_schedules';

    protected $fillable = ['vehicle_id', 'plan_id', 'next_due_date', 'next_due_mileage', 'status'];

    protected $casts = [
        'next_due_date' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
