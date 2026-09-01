<?php

namespace App\Models\Garages;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GarageEmployee extends Model
{
    protected $fillable = [
        'branch_id', 'user_id', 'department_id', 'employee_number',
        'position', 'employment_type', 'hire_date', 'salary', 'status',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(GarageDepartment::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(EmployeeSchedule::class, 'employee_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(RepairTask::class, 'assigned_employee_id');
    }
}
