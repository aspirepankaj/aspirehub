<?php

namespace App\Modules\Core\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'adspv_roles';

    protected $fillable = ['name', 'slug', 'description'];

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class, 'role_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'adspv_role_permissions', 'role_id', 'permission_id');
    }
}
