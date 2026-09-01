<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DashboardWidget extends Model
{
    protected $fillable = ['dashboard_id', 'widget_type', 'title', 'configuration', 'position_x', 'position_y', 'width', 'height'];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(Dashboard::class);
    }

    public function chart(): HasOne
    {
        return $this->hasOne(Chart::class);
    }
}
