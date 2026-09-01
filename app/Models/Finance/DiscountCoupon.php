<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    protected $fillable = ['code', 'discount_type', 'value', 'start_date', 'end_date', 'usage_limit', 'status'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
