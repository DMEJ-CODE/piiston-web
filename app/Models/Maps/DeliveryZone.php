<?php

namespace App\Models\Maps;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use App\Models\Marketplace\SellerProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryZone extends Model
{
    protected $fillable = ['seller_id', 'country_id', 'city_id', 'radius_km', 'delivery_fee', 'status'];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function coverages(): HasMany
    {
        return $this->hasMany(DeliveryCoverage::class);
    }
}
