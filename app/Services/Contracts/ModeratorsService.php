<?php


namespace App\Services\Contracts;


use App\models\Admin;

interface ModeratorsService
{
    function create(array $moderator): int;
    function delete(int $id): void;
    function edit(array $moderator): void;
    function all();
    function get(int $id): Admin;
}
