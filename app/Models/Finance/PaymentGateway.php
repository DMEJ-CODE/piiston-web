<?php

namespace App\Models\Finance;

use App\Models\Globalization\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGateway extends Model
{
    protected $fillable = ['name', 'provider', 'country_id', 'api_status', 'is_active'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
