<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;

class MechanicVerification extends Model
{
    protected $fillable = ['mechanic_id', 'document_type', 'document_number', 'document_file', 'status', 'verified_at'];
}
