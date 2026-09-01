<?php

namespace App\Models\Search;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    protected $table = 'platform_recommendations';

    protected $fillable = ['user_id', 'recommendation_type', 'entity_type', 'entity_id', 'score', 'reason'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
