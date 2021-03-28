<?php


namespace App\Services\Implement;


use App\Models\RefSystem;
use App\Services\Contracts\ReferralSystemService;

class ReferralSystemImpl implements ReferralSystemService
{

    function create(array $r): void
    {
        $ref = new RefSystem();
        $ref->referrer = $r['referrer'];
        $ref->referral = $r['referral'];
        $ref->datetime = date("Y-m-d H:i:s");
        $ref->save();
    }

    function count($referrer): int
    {
        return RefSystem::where('referrer', $referrer)->count();
    }
}
