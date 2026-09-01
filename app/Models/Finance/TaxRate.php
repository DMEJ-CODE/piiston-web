<?php

namespace App\Models\Finance;

use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Model
{
    protected $fillable = ['country_id', 'name', 'rate', 'status'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
