<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartCategory extends Model
{
    protected $fillable = ['parent_id', 'name', 'description', 'image', 'status'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PartCategory::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(PartCategory::class, 'parent_id');
    }

    public function parts(): HasMany
    {
        return $this->hasMany(SparePart::class, 'category_id');
    }
}
