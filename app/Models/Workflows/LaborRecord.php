<?php

namespace App\Models\Workflows;

use App\Models\Garages\GarageEmployee;
use App\Models\Garages\RepairTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaborRecord extends Model
{
    protected $fillable = ['repair_task_id', 'employee_id', 'start_time', 'end_time', 'hours', 'cost'];

    public function repairTask(): BelongsTo
    {
        return $this->belongsTo(RepairTask::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(GarageEmployee::class);
    }
}
