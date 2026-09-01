<?php

namespace App\Models\Marketplace;

use App\Models\Globalization\Address;
use App\Models\Globalization\Country;
use App\Models\Maps\DeliveryZone;
use App\Models\Maps\Location;
use App\Models\Promotions\Campaign;
use App\Models\Promotions\Promotion;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SellerProfile extends Model
{
    protected $fillable = [
        'user_id', 'country_id', 'business_name', 'business_type',
        'registration_number', 'tax_number', 'description', 'logo',
        'address_id', 'verification_status', 'rating', 'status', 'is_onboarded',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(SellerBranch::class, 'seller_id');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(ProductListing::class, 'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'entity');
    }

    public function deliveryZones(): HasMany
    {
        return $this->hasMany(DeliveryZone::class, 'seller_id');
    }

    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'owner');
    }

    public function campaigns(): MorphMany
    {
        return $this->morphMany(Campaign::class, 'owner');
    }
}
