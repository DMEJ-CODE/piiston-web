<?php

namespace App\Models\BI;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsSnapshot extends Model
{
    public $timestamps = false;

    protected $fillable = ['snapshot_type', 'country_id', 'city_id', 'data', 'generated_at'];

    protected $casts = [
        'data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
