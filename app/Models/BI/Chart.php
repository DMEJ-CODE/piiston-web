<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chart extends Model
{
    protected $fillable = ['dashboard_widget_id', 'chart_type', 'configuration'];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function widget(): BelongsTo
    {
        return $this->belongsTo(DashboardWidget::class, 'dashboard_widget_id');
    }
}
