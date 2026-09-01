<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;

class SearchIndexQueue extends Model
{
    protected $table = 'search_index_queue';

    protected $fillable = ['entity_type', 'entity_id', 'operation', 'priority', 'status', 'processed_at'];

    protected $casts = [
        'processed_at' => 'datetime',
    ];
}
