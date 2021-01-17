<?php

namespace App\Http\Controllers;

use App\models\Mailing;

class Send extends Controller {

    public function mailing() {
       $mailing = new Mailing();
       return $mailing->send();
    }
}
