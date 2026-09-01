<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GarageDepartment extends Model
{
    protected $fillable = ['branch_id', 'name', 'description', 'status'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class, 'branch_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(GarageEmployee::class, 'department_id');
    }
}
