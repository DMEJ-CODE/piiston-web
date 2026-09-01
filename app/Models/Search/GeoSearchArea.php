<?php

namespace App\Models\Search;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeoSearchArea extends Model
{
    protected $fillable = ['country_id', 'city_id', 'radius_km', 'center_latitude', 'center_longitude'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
