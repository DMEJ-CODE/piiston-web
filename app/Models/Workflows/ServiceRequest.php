<?php

namespace App\Models\Workflows;

use App\Models\Garages\GarageAppointment;
use App\Models\Garages\RepairOrder;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ServiceRequest extends Model
{
    protected $fillable = ['user_id', 'vehicle_id', 'request_type', 'description', 'priority', 'location', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(GarageAppointment::class, 'request_id');
    }

    public function repairOrder(): HasOneThrough
    {
        return $this->hasOneThrough(
            RepairOrder::class,
            GarageAppointment::class,
            'request_id',
            'appointment_id',
            'id',
            'id'
        );
    }
}
