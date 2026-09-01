<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelEstimate extends Model
{
    public $timestamps = false;

    protected $fillable = ['route_id', 'distance_km', 'estimated_time_minutes', 'fuel_estimation_liters', 'generated_at'];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
