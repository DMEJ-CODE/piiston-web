<?php

namespace App\Models\Marketplace;

use App\Models\Globalization\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartQuote extends Model
{
    protected $fillable = [
        'request_id', 'seller_id', 'part_id', 'price',
        'currency_id', 'availability', 'notes', 'valid_until', 'status',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(PartRequest::class, 'request_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'part_id');
    }
}
