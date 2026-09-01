<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageService extends Model
{
    protected $fillable = ['branch_id', 'name', 'description', 'duration_minutes', 'price', 'status'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
