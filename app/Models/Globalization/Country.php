<?php

namespace App\Models\Globalization;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    protected $fillable = [
        'name', 'iso_code', 'phone_code', 'currency_id',
        'default_language_id', 'timezone_id', 'flag', 'status',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function defaultLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'default_language_id');
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'country_languages')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'country_currencies')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function configurations(): HasMany
    {
        return $this->hasMany(CountryConfiguration::class);
    }

    public function phoneCode(): HasOne
    {
        return $this->hasOne(PhoneCountryCode::class);
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(TaxConfiguration::class);
    }

    public function paymentProviders(): HasMany
    {
        return $this->hasMany(PaymentProvider::class);
    }

    public function documentTypes(): HasMany
    {
        return $this->hasMany(DocumentType::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
