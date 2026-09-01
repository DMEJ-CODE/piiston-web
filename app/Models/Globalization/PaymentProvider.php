<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentProvider extends Model
{
    protected $fillable = ['country_id', 'name', 'type', 'api_configuration', 'status'];

    protected $casts = [
        'api_configuration' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
