<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property mixed id
 * @property mixed login
 * @property mixed name
 * @property mixed|string password
 * @property int|mixed roles_id
 * @method static find(int $id)
 * @method static where(string $string, mixed $id, mixed $param)
 */
class Admin extends Model
{
    protected $table = "admin";
    public $timestamps = false;
    public $fillable = [
        'login',
        'password',
        'name',
        'language',
        'role_id'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'admin_has_permissions',
            'admin_id',
            'permissions_id'
        );
    }

    public function hasPermissionById($id): bool
    {
        return $this->permissions()->where('id', $id)->exists();
    }

    public function hasPermission($permission): bool
    {
        if (is_array($permission)) {
            foreach ($permission as $p) {
                if ($this->permissions()->where('name', $p)->exists()) return true;
                return false;
            }
        }
        return $this->permissions()->where('name', $permission)->exists();
    }
}
