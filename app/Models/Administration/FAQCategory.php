<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FAQCategory extends Model
{
    protected $table = 'faq_categories';

    protected $fillable = ['name', 'order'];

    public function items(): HasMany
    {
        return $this->hasMany(FAQItem::class, 'category_id')->orderBy('order');
    }
}
