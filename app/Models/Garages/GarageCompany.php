<?php

namespace App\Models\Garages;

use App\Models\Documents\Document;
use App\Models\Globalization\Country;
use App\Models\Promotions\Campaign;
use App\Models\Promotions\Promotion;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GarageCompany extends Model
{
    protected $fillable = [
        'owner_id', 'has_annexes', 'country_id', 'name', 'legal_name', 'registration_number',
        'tax_number', 'email', 'phone', 'website', 'logo',
        'description', 'verification_status', 'status',
    ];

    protected $casts = [
        'has_annexes' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(GarageBranch::class, 'company_id');
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(GarageSupplier::class, 'company_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(GarageReview::class, 'company_id');
    }

    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'owner');
    }

    public function campaigns(): MorphMany
    {
        return $this->morphMany(Campaign::class, 'owner');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    public function getRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
}
