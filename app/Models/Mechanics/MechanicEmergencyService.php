<?php

namespace App\Models\Mechanics;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicEmergencyService extends Model
{
    protected $fillable = ['mechanic_id', 'location_description', 'latitude', 'longitude', 'vehicle_id', 'problem_description', 'status'];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class, 'mechanic_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
