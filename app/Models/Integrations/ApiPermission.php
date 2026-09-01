<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;

class ApiPermission extends Model
{
    protected $fillable = ['name', 'scope', 'description'];
}
