<?php


namespace App\Services\Implement;


use App\Models\Permission;
use App\Services\Contracts\PermissionService;

class PermissionServiceImpl implements PermissionService
{

    function create(string $name): int
    {
        $permission = new Permission();
        $permission->name = $name;
        $permission->save();

        return $permission->id;
    }

    function delete(int $id): void
    {
        Permission::where('id', $id)->delete();
    }
}
