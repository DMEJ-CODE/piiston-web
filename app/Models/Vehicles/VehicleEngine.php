<?php

namespace App\Models\Vehicles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleEngine extends Model
{
    protected $fillable = [
        'generation_id', 'engine_code', 'fuel_type',
        'capacity', 'horsepower', 'cylinder', 'turbo',
    ];

    public function generation(): BelongsTo
    {
        return $this->belongsTo(VehicleGeneration::class);
    }
}
