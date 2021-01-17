<?php


namespace App\Services\Contracts;


interface PermissionService {
    function create(string $name): int;
    function delete(int $id): void;
}
