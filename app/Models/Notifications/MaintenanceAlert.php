<?php

namespace App\Models\Notifications;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceAlert extends Model
{
    protected $fillable = [
        'vehicle_id', 'maintenance_type', 'current_mileage',
        'recommended_mileage', 'due_date', 'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
