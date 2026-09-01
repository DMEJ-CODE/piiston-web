<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;

class Benchmark extends Model
{
    public $timestamps = false;

    protected $fillable = ['benchmark_type', 'entity_type', 'entity_id', 'reference_period', 'comparison_period', 'result_percentage'];
}
