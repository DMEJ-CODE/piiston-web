<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SearchCategory extends Model
{
    protected $fillable = ['name', 'icon', 'description', 'status'];

    public function indexes(): HasMany
    {
        return $this->hasMany(SearchIndex::class, 'category_id');
    }
}
