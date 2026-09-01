<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;

class GarageInvitation extends Model
{
    protected $fillable = [
        'branch_id',
        'email',
        'role',
        'token',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(GarageBranch::class);
    }
}
