<?php


namespace App\Services\Contracts;


interface AdminService {
    function create(array $admin): int;
    function edit(int $id, array $admin): void;
    function delete(int $id): void;
    function editRole(int $id, int $role_id);
    function getRole(int $id);
}
