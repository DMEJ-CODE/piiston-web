<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertisement extends Model
{
    protected $fillable = ['campaign_id', 'title', 'content', 'media_type', 'media_url', 'landing_type', 'landing_id', 'status'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function creatives(): HasMany
    {
        return $this->hasMany(AdCreative::class);
    }
}
