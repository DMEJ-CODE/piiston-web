<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicAvailability extends Model
{
    protected $table = 'mechanic_availabilities';

    protected $fillable = ['mechanic_id', 'date', 'start_time', 'end_time', 'status'];

    protected $casts = [
        'date' => 'date',
    ];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class, 'mechanic_id');
    }
}
