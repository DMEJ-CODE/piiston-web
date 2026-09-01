<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledReport extends Model
{
    protected $fillable = ['report_template_id', 'owner_type', 'owner_id', 'frequency', 'delivery_method', 'next_execution_at', 'status'];

    protected $casts = [
        'next_execution_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }
}
