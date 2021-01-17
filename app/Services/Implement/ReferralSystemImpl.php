<?php


namespace App\Services\Implement;


use App\models\RefSystem;
use App\Services\Contracts\ReferralSystemService;

class ReferralSystemImpl implements ReferralSystemService {

    function create(array $r): void {
        $ref = new RefSystem();
        $ref->referrer = $r['referrer'];
        $ref->referral = $r['referral'];
        $ref->date = date("Y-m-d");
        $ref->time = date("H:i:s");
        $ref->save();
    }

    function count($referrer): int {
        return RefSystem::where('referrer', $referrer)->count();
    }
}
