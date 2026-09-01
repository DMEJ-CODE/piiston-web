<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timezone extends Model
{
    protected $fillable = ['name', 'utc_offset'];

    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }
}
