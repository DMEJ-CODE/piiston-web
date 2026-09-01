<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapLayer extends Model
{
    protected $fillable = ['name', 'layer_type', 'provider_id', 'visibility', 'status'];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(MapProvider::class, 'provider_id');
    }
}
