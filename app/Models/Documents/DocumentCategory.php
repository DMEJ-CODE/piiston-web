<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    protected $table = 'dms_categories';

    protected $fillable = ['name', 'icon', 'description'];

    public function types(): HasMany
    {
        return $this->hasMany(DocumentType::class, 'category_id');
    }
}
