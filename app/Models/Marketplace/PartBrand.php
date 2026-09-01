<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartBrand extends Model
{
    protected $fillable = ['name', 'country_origin', 'logo', 'status'];

    public function parts(): HasMany
    {
        return $this->hasMany(SparePart::class, 'brand_id');
    }
}
