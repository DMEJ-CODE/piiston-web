<?php

namespace App\Models\Mechanics;

use App\Models\Globalization\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MechanicProfile extends Model
{
    protected $fillable = [
        'user_id', 'country_id', 'type_id', 'professional_title',
        'bio', 'years_of_experience', 'availability_status',
        'profile_photo', 'rating', 'total_reviews', 'verification_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MechanicType::class, 'type_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(MechanicSkill::class, 'mechanic_skill_assignments', 'mechanic_id', 'skill_id')
            ->withPivot('experience_level', 'certification', 'years_practiced')
            ->withTimestamps();
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(MechanicCertification::class, 'mechanic_id');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(MechanicExperience::class, 'mechanic_id');
    }

    public function employments(): HasMany
    {
        return $this->hasMany(MechanicEmployment::class, 'mechanic_id');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(MechanicAvailability::class, 'mechanic_id');
    }

    public function location(): HasOne
    {
        return $this->hasOne(MechanicLocation::class, 'mechanic_id');
    }

    public function serviceAreas(): HasMany
    {
        return $this->hasMany(MechanicServiceArea::class, 'mechanic_id');
    }

    public function tools(): HasMany
    {
        return $this->hasMany(MechanicTool::class, 'mechanic_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(MechanicAssignment::class, 'mechanic_id');
    }

    public function performances(): HasMany
    {
        return $this->hasMany(MechanicPerformance::class, 'mechanic_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(MechanicReview::class, 'mechanic_id');
    }

    public function emergencyServices(): HasMany
    {
        return $this->hasMany(MechanicEmergencyService::class, 'mechanic_id');
    }

    public function liveLocation(): MorphOne
    {
        return $this->morphOne(Location::class, 'entity');
    }

    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'owner');
    }

    public function campaigns(): MorphMany
    {
        return $this->morphMany(Campaign::class, 'owner');
    }
}
