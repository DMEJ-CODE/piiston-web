<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;

class ExternalService extends Model
{
    protected $fillable = ['name', 'service_type', 'endpoint', 'status'];
}
