<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $fillable = ['owner_type', 'owner_id', 'promotion_type_id', 'title', 'description', 'start_date', 'end_date', 'status'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(PromotionType::class, 'promotion_type_id');
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(PromotionAnalytics::class);
    }
}
