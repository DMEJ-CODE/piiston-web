<?php

namespace App\Models\Workflows;

use App\Models\Garages\GarageBranch;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyRequest extends Model
{
    protected $fillable = [
        'user_id', 'vehicle_id', 'branch_id', 'location',
        'latitude', 'longitude', 'radius_km',
        'problem_description', 'assigned_mechanic_id', 'status',
        'service_sheet', 'estimated_cost', 'final_cost', 'paid_at',
    ];

    protected $casts = [
        'service_sheet' => 'array',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class, 'branch_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_mechanic_id');
    }
}
