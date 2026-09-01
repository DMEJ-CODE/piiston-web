<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQItem extends Model
{
    protected $table = 'faq_items';

    protected $fillable = ['category_id', 'question', 'answer', 'order', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FAQCategory::class, 'category_id');
    }
}
