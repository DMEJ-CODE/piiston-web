<?php

namespace App\Models\BI;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Metric extends Model
{
    public $timestamps = false;

    protected $fillable = ['kpi_id', 'value', 'period', 'country_id', 'city_id', 'calculated_at'];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    public function kpi(): BelongsTo
    {
        return $this->belongsTo(KPI::class, 'kpi_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
