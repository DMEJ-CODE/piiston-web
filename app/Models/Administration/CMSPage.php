<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

class CMSPage extends Model
{
    protected $table = 'cms_pages';

    protected $fillable = ['title', 'slug', 'content', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
