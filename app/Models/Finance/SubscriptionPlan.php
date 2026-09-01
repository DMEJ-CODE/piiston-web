<?php

namespace App\Models\Finance;

use App\Models\Globalization\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'price', 'currency_id', 'duration', 'description', 'status'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
