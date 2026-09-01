<?php

namespace App\Models\Fleets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenancePlan extends Model
{
    protected $fillable = ['fleet_id', 'name', 'description', 'interval_type', 'interval_value', 'status'];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class, 'plan_id');
    }
}
