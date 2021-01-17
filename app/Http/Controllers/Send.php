<?php

namespace App\Http\Controllers;

use App\Models\Mailing;

class Send extends Controller {

    public function mailing() {
       $mailing = new Mailing();
       return $mailing->send();
    }
}
