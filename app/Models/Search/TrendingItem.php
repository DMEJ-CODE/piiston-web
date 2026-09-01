<?php

namespace App\Models\Search;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrendingItem extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'country_id', 'city_id', 'score', 'period'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
