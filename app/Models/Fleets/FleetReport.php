<?php

namespace App\Models\Fleets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetReport extends Model
{
    protected $fillable = [
        'fleet_id', 'period_name', 'total_distance_km', 'total_cost',
        'fuel_consumption_total', 'maintenance_count', 'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }
}
