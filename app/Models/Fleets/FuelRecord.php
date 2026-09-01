<?php

namespace App\Models\Fleets;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelRecord extends Model
{
    protected $fillable = [
        'vehicle_id', 'driver_id', 'quantity_liters', 'price_per_liter',
        'total_price', 'gas_station', 'date', 'mileage_at_fill',
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
