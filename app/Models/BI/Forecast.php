<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $table = 'platform_forecasts';

    protected $fillable = ['forecast_type', 'entity_type', 'entity_id', 'predicted_value', 'confidence_score', 'forecast_date'];

    protected $casts = [
        'forecast_date' => 'date',
    ];
}
