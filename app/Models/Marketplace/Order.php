<?php

namespace App\Models\Marketplace;

use App\Models\Garages\GarageBranch;
use App\Models\Globalization\Address;
use App\Models\Globalization\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'buyer_id', 'garage_branch_id', 'seller_id', 'address_id', 'total_amount',
        'currency_id', 'payment_status', 'order_status',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function garageBranch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}
