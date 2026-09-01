<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchDocument extends Model
{
    protected $fillable = ['search_index_id', 'language_code', 'content', 'metadata', 'version'];

    protected $casts = [
        'content' => 'array',
        'metadata' => 'array',
    ];

    public function index(): BelongsTo
    {
        return $this->belongsTo(SearchIndex::class, 'search_index_id');
    }
}
