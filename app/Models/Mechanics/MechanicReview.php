<?php

namespace App\Models\Mechanics;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicReview extends Model
{
    protected $fillable = ['mechanic_id', 'customer_id', 'rating', 'comment', 'review_date'];

    protected $casts = [
        'review_date' => 'datetime',
    ];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class, 'mechanic_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
