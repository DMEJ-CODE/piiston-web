<?php

namespace App\Models\Finance;

use App\Models\Globalization\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessExpense extends Model
{
    protected $fillable = ['business_type', 'business_id', 'category', 'amount', 'currency_id', 'description', 'date'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
