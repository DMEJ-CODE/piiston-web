<?php

namespace App\Models\Garages;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageReview extends Model
{
    protected $fillable = ['company_id', 'customer_id', 'rating', 'comment', 'review_date'];

    protected $casts = [
        'review_date' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(GarageCompany::class);
    }

    /**
     * Note: GarageCustomer might be a pivot or a wrapper for User.
     * Based on previous implementations, customer_id usually links to users.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
