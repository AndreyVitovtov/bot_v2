<?php


namespace App\Services\Contracts;


interface ContactService {
    function create(array $contact): int;
    function delete(int $id): void;
    function deleteSeveral(array $ids);
    function getByType(int $type_id);
    function get(int $id);
}
