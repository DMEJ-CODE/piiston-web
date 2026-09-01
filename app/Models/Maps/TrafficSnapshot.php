<?php

namespace App\Models\Maps;

use App\Models\Globalization\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrafficSnapshot extends Model
{
    public $timestamps = false;

    protected $fillable = ['region_id', 'provider_id', 'traffic_level', 'captured_at'];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(MapProvider::class, 'provider_id');
    }
}
