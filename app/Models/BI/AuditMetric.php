<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;

class AuditMetric extends Model
{
    public $timestamps = false;

    protected $fillable = ['metric_name', 'old_value', 'new_value', 'changed_at'];

    protected $casts = [
        'changed_at' => 'datetime',
    ];
}
