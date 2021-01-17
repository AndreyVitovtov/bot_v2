<?php


namespace App\Services\Contracts;


interface ReferralSystemService {
    function create(array $ref): void;
    function count($referrer): int;
}
