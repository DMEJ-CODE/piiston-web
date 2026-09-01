<?php

namespace App\Models\Search;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchAnalytics extends Model
{
    protected $table = 'search_analytics_summary';

    protected $fillable = ['keyword', 'country_id', 'city_id', 'search_count', 'result_count', 'click_count'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
