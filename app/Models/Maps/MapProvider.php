<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MapProvider extends Model
{
    protected $fillable = ['name', 'provider_code', 'api_key_reference', 'status'];

    public function layers(): HasMany
    {
        return $this->hasMany(MapLayer::class, 'provider_id');
    }
}
