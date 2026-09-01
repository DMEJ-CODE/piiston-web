<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    protected $table = 'bi_alert_rules';

    protected $fillable = ['owner_type', 'owner_id', 'metric_name', 'operator', 'threshold', 'notification_channel', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
