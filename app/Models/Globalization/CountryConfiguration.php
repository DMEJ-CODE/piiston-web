<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryConfiguration extends Model
{
    protected $fillable = ['country_id', 'key', 'value', 'type'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
