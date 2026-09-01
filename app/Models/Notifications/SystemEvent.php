<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;

class SystemEvent extends Model
{
    protected $fillable = ['event_name', 'source_type', 'source_id', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];
}
