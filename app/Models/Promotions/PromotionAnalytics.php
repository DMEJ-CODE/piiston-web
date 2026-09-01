<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionAnalytics extends Model
{
    protected $table = 'promotion_analytics_summary';

    protected $fillable = ['promotion_id', 'impressions', 'clicks', 'conversions', 'revenue_generated', 'stat_date'];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
