<?php

namespace App\Models\Marketplace;

use App\Models\Documents\Document;
use App\Models\Globalization\Currency;
use App\Models\Social\SocialInteraction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class ProductListing extends Model
{
    protected $fillable = [
        'seller_id', 'part_id', 'price', 'professional_price', 'sku', 'currency_id',
        'quantity', 'condition', 'availability',
        'delivery_option', 'status',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'part_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(SellerInventory::class, 'listing_id');
    }

    /**
     * Relationship for images and videos (TikTok style demonstration)
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    public function interactions(): MorphMany
    {
        return $this->morphMany(SocialInteraction::class, 'interactable');
    }

    public function likes(): MorphMany
    {
        return $this->interactions()->where('type', 'LIKE');
    }

    public function comments(): MorphMany
    {
        return $this->interactions()->where('type', 'COMMENT');
    }

    public function getIsLikedAttribute(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->likes()->where('user_id', Auth::id())->exists();
    }
}
