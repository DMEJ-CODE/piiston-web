<?php

namespace App\Models\Vehicles;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleHistory extends Model
{
    protected $fillable = ['vehicle_id', 'event_type', 'description', 'date', 'created_by'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
