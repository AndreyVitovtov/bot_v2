<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class MailingParameters {
    public static function apply(DB $db, object $task): DB {
        //TODO: Mailing parameters

        return $db->where('start', 1);
    }
}
