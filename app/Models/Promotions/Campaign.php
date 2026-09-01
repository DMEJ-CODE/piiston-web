<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Campaign extends Model
{
    protected $fillable = ['owner_type', 'owner_id', 'name', 'objective', 'budget', 'start_date', 'end_date', 'status'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function owner()
    {
        return $this->morphTo();
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function targets(): HasMany
    {
        return $this->hasMany(CampaignTarget::class);
    }

    public function budgetDetails(): HasOne
    {
        return $this->hasOne(PromotionBudget::class);
    }
}
