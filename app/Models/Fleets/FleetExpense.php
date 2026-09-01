<?php

namespace App\Models\Fleets;

use App\Models\Globalization\Currency;
use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetExpense extends Model
{
    protected $fillable = [
        'fleet_id', 'vehicle_id', 'expense_type', 'amount',
        'currency_id', 'expense_date', 'description',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
