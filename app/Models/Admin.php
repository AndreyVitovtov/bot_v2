<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {
    protected $table = "admin";
    public $timestamps = false;
    public $fillable = [
        'login',
        'password',
        'name',
        'language',
        'role_id'
    ];

    public function role() {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function permissions() {
        return $this->belongsToMany(
            Permission::class,
            'admin_has_permissions',
            'admin_id',
            'permissions_id'
        );
    }

    public function hasPermissionById($id) {
        return $this->permissions()->where('id', $id)->exists();
    }

    public function hasPermission($permission) {
        if(is_array($permission)) {
            foreach($permission as $p) {
                if($this->permissions()->where('name', $p)->exists()) return true;
                return false;
            }
        }
        return $this->permissions()->where('name', $permission)->exists();
    }
}
