<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;

class SearchKeyword extends Model
{
    protected $fillable = ['keyword', 'language_code', 'normalized_keyword', 'frequency'];
}
