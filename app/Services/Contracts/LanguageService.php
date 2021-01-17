<?php


namespace App\Services\Contracts;


interface LanguageService {
    function create(array $language): int;
    function delete(int $id);
}
