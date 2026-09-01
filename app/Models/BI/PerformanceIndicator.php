<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;

class PerformanceIndicator extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'indicator_name', 'value', 'period'];
}
