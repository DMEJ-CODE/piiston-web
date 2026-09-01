<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = ['name', 'code', 'symbol', 'decimal_places', 'status'];

    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }
}
