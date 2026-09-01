<?php

namespace App\Models\Garages;

use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VehicleCheckIn extends Model
{
    protected $fillable = [
        'appointment_id', 'branch_id', 'vehicle_id', 'customer_id', 'received_by',
        'arrival_date', 'mileage', 'fuel_level',
        'vehicle_condition', 'checklist', 'notes', 'signature_path', 'status',
    ];

    protected $casts = [
        'arrival_date' => 'datetime',
        'checklist' => 'array',
    ];

    public function media()
    {
        return $this->morphMany(GarageMedia::class, 'mediable');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(GarageAppointment::class);
    }

    public function repairOrder(): HasOne
    {
        return $this->hasOne(RepairOrder::class, 'check_in_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(GarageCustomer::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
