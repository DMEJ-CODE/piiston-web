<?php

namespace App\Models\Search;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SearchIndex extends Model
{
    protected $table = 'search_indexes';

    protected $fillable = [
        'entity_type', 'entity_id', 'category_id', 'title', 'subtitle',
        'description', 'keywords', 'country_id', 'city_id',
        'latitude', 'longitude', 'popularity_score',
        'rating_score', 'search_score', 'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SearchCategory::class, 'category_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(SearchDocument::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(SearchResultClick::class);
    }
}
