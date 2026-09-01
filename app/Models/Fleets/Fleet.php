<?php

namespace App\Models\Fleets;

use App\Models\Maps\Geofence;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    protected $fillable = ['company_id', 'name', 'description', 'manager_id', 'type', 'status'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function fleetVehicles(): HasMany
    {
        return $this->hasMany(FleetVehicle::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(FleetMember::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(FleetExpense::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(FleetAlert::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(FleetReport::class);
    }

    public function geofences(): HasMany
    {
        return $this->hasMany(Geofence::class, 'owner_id')->where('owner_type', 'Fleet');
    }
}
