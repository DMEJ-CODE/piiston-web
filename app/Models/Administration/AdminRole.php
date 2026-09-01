<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminRole extends Model
{
    protected $fillable = ['name', 'description', 'level', 'status'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    public function administrators(): BelongsToMany
    {
        return $this->belongsToMany(Administrator::class, 'administrator_roles', 'role_id', 'administrator_id')
            ->withTimestamps();
    }
}
