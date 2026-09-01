<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxConfiguration extends Model
{
    protected $fillable = ['country_id', 'name', 'percentage', 'status'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
