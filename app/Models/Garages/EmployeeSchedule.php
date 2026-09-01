<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSchedule extends Model
{
    protected $fillable = ['employee_id', 'day', 'start_time', 'end_time', 'status'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(GarageEmployee::class);
    }
}
