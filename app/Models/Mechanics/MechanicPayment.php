<?php

namespace App\Models\Mechanics;

use App\Models\Globalization\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicPayment extends Model
{
    protected $fillable = ['mechanic_id', 'service_description', 'amount', 'currency_id', 'payment_status', 'payment_date'];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class, 'mechanic_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
