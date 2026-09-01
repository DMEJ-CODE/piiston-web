<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = ['name', 'channel', 'title_template', 'body_template', 'variables'];

    protected $casts = [
        'variables' => 'array',
    ];
}
