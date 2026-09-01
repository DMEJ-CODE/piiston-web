<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;

class MechanicPerformance extends Model
{
    protected $fillable = [
        'mechanic_id', 'period', 'completed_jobs',
        'average_rating', 'customer_satisfaction', 'average_completion_time_minutes',
    ];
}
