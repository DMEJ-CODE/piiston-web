<?php

namespace App\Models\Workflows;

use App\Models\Garages\RepairOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualityCheck extends Model
{
    protected $fillable = ['repair_order_id', 'checked_by', 'checklist', 'result', 'notes', 'check_date'];

    protected $casts = [
        'checklist' => 'array',
    ];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
