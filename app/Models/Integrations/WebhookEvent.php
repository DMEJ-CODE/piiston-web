<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['event_name', 'payload', 'created_at'];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];
}
