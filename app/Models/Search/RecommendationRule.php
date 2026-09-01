<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;

class RecommendationRule extends Model
{
    protected $fillable = ['name', 'rule_type', 'configuration', 'priority', 'status'];

    protected $casts = [
        'configuration' => 'array',
        'status' => 'boolean',
    ];
}
