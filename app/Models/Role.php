<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(int $id)
 */
class Role extends Model
{
    protected $table = "roles";
    public $timestamps = false;
    public $fillable = [
        'name'
    ];

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class, 'roles_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permissions_id'
        );
    }


}
