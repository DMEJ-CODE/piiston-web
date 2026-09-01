<?php

namespace App\Models\Maps;

use App\Models\Globalization\Address;
use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    protected $fillable = [
        'entity_type', 'entity_id', 'country_id', 'city_id', 'address_id',
        'latitude', 'longitude', 'altitude', 'accuracy', 'source', 'status',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
