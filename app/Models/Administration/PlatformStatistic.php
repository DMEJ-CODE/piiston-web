<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

class PlatformStatistic extends Model
{
    protected $fillable = ['metric_name', 'metric_value', 'period', 'stat_date'];
}
