<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportTemplate extends Model
{
    protected $fillable = ['name', 'report_type', 'configuration', 'status'];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(ScheduledReport::class);
    }

    public function filters(): HasMany
    {
        return $this->hasMany(ReportFilter::class);
    }
}
