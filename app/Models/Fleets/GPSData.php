<?php

namespace App\Models\Fleets;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GPSData extends Model
{
    protected $table = 'vehicle_tracking';

    protected $fillable = ['vehicle_id', 'latitude', 'longitude', 'speed', 'direction', 'tracked_at'];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
