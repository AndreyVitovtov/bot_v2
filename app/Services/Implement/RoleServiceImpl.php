<?php


namespace App\Services\Implement;


use App\Models\Role;
use App\Services\Contracts\RoleService;

class RoleServiceImpl implements RoleService
{

    function create(string $name)
    {
        $role = new Role();
    }

    function edit(int $id, string $name)
    {
        $role = Role::find($id);
        $role->name = $name;
        $role->save();
    }

    function getPermissions(int $role_id)
    {
        $role = Role::find($role_id);
        return $role->permissions;
    }
}
