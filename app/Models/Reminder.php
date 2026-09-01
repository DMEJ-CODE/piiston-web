<?php

namespace App\Models;

use App\Models\AI\AiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $table = 'reminders';

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'cron_expression',
        'run_at',
        'channel',
        'enabled',
        'metadata',
        'ai_model_id',
    ];

    protected $casts = [
        'run_at' => 'datetime',
        'enabled' => 'boolean',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiModel()
    {
        return $this->belongsTo(AiModel::class, 'ai_model_id');
    }
}
