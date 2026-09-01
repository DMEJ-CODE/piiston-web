<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneCountryCode extends Model
{
    protected $fillable = ['country_id', 'code', 'format'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
