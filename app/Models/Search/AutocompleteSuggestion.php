<?php

namespace App\Models\Search;

use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutocompleteSuggestion extends Model
{
    protected $fillable = ['keyword', 'frequency', 'language_code', 'country_id'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
