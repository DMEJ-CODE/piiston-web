<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Geofence extends Model
{
    protected $fillable = ['owner_type', 'owner_id', 'name', 'center_latitude', 'center_longitude', 'radius_meters', 'status'];

    public function events(): HasMany
    {
        return $this->hasMany(GeofenceEvent::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
