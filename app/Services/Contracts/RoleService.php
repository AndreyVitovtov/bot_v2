<?php


namespace App\Services\Contracts;


interface RoleService {
    function create(string $name);
    function edit(int $id, string $name);
    function getPermissions(int $role_id);
}
