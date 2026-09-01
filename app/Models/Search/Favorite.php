<?php

namespace App\Models\Search;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'entity_type', 'entity_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
