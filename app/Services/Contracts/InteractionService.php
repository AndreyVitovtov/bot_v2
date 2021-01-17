<?php


namespace App\Services\Contracts;


interface InteractionService {
    function set(int $user_id, string $command, array $params): void;
    function get(int $user_id);
    function delete(int $user_id);
}
