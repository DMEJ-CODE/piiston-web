<?php

namespace App\Models\Marketplace;

use App\Models\Documents\Document;
use App\Models\Promotions\SponsoredListing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SparePart extends Model
{
    protected $fillable = [
        'category_id', 'brand_id', 'name', 'part_number', 'oem_reference',
        'description', 'condition', 'quality_grade',
        'warranty_period', 'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PartCategory::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PartBrand::class);
    }

    public function compatibilities(): HasMany
    {
        return $this->hasMany(PartVehicleCompatibility::class, 'part_id');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(ProductListing::class, 'part_id');
    }

    public function sponsoredListing(): MorphOne
    {
        return $this->morphOne(SponsoredListing::class, 'entity');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }
}
