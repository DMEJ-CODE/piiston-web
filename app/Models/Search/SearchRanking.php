<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;

class SearchRanking extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'ranking_score', 'algorithm_version'];
}
