<?php

namespace App\Models\Finance;

use App\Models\Globalization\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'price', 'currency_id', 'duration', 'description', 'features', 'status'];

    protected $casts = ['features' => 'array', 'status' => 'boolean'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
