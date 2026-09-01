<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    protected $fillable = ['name', 'entity_type', 'condition_logic', 'action', 'priority', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
