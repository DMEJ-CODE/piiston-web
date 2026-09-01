<?php

namespace App\Models\Finance;

use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'type', 'provider', 'country_id', 'status'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
