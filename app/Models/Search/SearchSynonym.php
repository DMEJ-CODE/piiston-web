<?php

namespace App\Models\Search;

use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchSynonym extends Model
{
    protected $fillable = ['language_code', 'keyword', 'synonym', 'country_id', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
