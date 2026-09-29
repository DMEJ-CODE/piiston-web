<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationErrorLog extends Model
{
    protected $fillable = ['user_id', 'exception_class', 'message', 'file', 'line', 'method', 'url', 'ip_address', 'trace'];
}
