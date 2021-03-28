<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'password', 'name', 'language'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setLocale($locale)
    {
        $this->update([
            'language' => $locale
        ]);

        return $this;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, "roles_id");
    }

    public function hasRole($role)
    {
        return $this
            ->role()
            ->where("name", $role)
            ->exists();
    }

    public function hasPermission($perm)
    {
        return $this->permissions()->where("name", $perm)->exists();
    }

    public function getPermissions(): Collection
    {
        /*** @var Role $role */
        $role = $this->role()->first();
        if ($role == null) return new Collection();
        return $role->permissions()->get();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_has_permissions', 'admin_id', 'permissions_id');
    }
}
