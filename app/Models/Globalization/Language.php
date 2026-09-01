<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Language extends Model
{
    protected $fillable = ['name', 'code', 'status'];

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_languages')
            ->withPivot('is_default')
            ->withTimestamps();
    }
}
