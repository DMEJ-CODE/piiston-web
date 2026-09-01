<?php

namespace App\Models\Garages;

use App\Models\Vehicles\Vehicle;
use App\Models\Workflows\ServiceRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GarageAppointment extends Model
{
    const STATUS_REQUESTED = 'REQUESTED'; // Owner proposed

    const STATUS_PROPOSED = 'PROPOSED';   // Garage proposed back

    const STATUS_CONFIRMED = 'CONFIRMED'; // Agreed

    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'request_id', 'branch_id', 'customer_id', 'vehicle_id',
        'service_id', 'scheduled_date', 'duration_minutes', 'status',
        'notes', 'proposed_by_id',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function checkIn(): HasOne
    {
        return $this->hasOne(VehicleCheckIn::class, 'appointment_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(GarageCustomer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(GarageService::class);
    }
}
