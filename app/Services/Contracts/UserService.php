<?php


namespace App\Services\Contracts;


interface UserService
{
    function create(array $user): int;
}
