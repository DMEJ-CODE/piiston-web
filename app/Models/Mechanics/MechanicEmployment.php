<?php

namespace App\Models\Mechanics;

use App\Models\Garages\GarageBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicEmployment extends Model
{
    protected $fillable = [
        'mechanic_id', 'branch_id', 'position', 'start_date',
        'end_date', 'employment_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class, 'mechanic_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class, 'branch_id');
    }
}
