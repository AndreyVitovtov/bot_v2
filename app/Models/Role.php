<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    protected $table = "roles";
    public $timestamps = false;
    public $fillable = [
        'name'
    ];

    public function admins() {
        return $this->hasMany(Admin::class, 'roles_id');
    }

    public function permissions() {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permissions_id'
        );
    }


}
