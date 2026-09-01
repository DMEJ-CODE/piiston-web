<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class AdPlacement extends Model
{
    protected $fillable = ['name', 'location', 'device_target', 'base_price'];
}
