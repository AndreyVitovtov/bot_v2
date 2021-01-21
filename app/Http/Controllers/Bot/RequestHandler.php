<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Bot\Traits\RequestHandlerTrait;
use App\Models\buttons\Menu;

class RequestHandler extends BaseRequestHandler {

    use RequestHandlerTrait;

    public function get_id() {
        dd(Menu::test());
    }
    // TODO: bot commands
}
