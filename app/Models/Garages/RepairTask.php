<?php

namespace App\Models\Garages;

use App\Models\Workflows\LaborRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairTask extends Model
{
    protected $fillable = [
        'repair_order_id', 'assigned_employee_id', 'title',
        'description', 'status', 'estimated_time_minutes', 'actual_time_minutes',
    ];

    public function laborRecords(): HasMany
    {
        return $this->hasMany(LaborRecord::class);
    }

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(GarageEmployee::class, 'assigned_employee_id');
    }
}
