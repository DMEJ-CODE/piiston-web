<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchResultClick extends Model
{
    public $timestamps = false;

    protected $fillable = ['session_id', 'search_index_id', 'position', 'clicked_at'];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(SearchSession::class, 'session_id');
    }

    public function index(): BelongsTo
    {
        return $this->belongsTo(SearchIndex::class, 'search_index_id');
    }
}
