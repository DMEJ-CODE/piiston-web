<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Route extends Model
{
    protected $fillable = ['origin_location_id', 'destination_location_id', 'distance_km', 'estimated_duration_minutes', 'traffic_level', 'status'];

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_location_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    public function points(): HasMany
    {
        return $this->hasMany(RoutePoint::class)->orderBy('sequence');
    }

    public function estimate(): HasOne
    {
        return $this->hasOne(TravelEstimate::class);
    }
}
