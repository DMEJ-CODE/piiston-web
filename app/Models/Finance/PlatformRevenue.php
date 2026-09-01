<?php

namespace App\Models\Finance;

use App\Models\Globalization\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformRevenue extends Model
{
    protected $table = 'platform_revenue';

    protected $fillable = ['source_type', 'reference_id', 'amount', 'currency_id'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
