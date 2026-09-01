<?php

namespace App\Models\Garages;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GarageCustomer extends Model
{
    protected $fillable = ['branch_id', 'user_id', 'customer_type', 'notes'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repairOrders(): HasMany
    {
        return $this->hasMany(RepairOrder::class, 'customer_id');
    }
}
