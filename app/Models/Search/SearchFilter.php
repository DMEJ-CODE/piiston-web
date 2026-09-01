<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchFilter extends Model
{
    protected $fillable = ['category_id', 'name', 'field', 'data_type', 'operator'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SearchCategory::class, 'category_id');
    }
}
