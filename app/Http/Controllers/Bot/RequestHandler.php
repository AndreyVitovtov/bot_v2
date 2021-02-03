<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Bot\Traits\RequestHandlerTrait;
use App\Models\buttons\Menu;
use App\Models\buttons\RichMedia;

class RequestHandler extends BaseRequestHandler {

    use RequestHandlerTrait;

    public function start() {
        echo $this->send("dads", Menu::test());
    }
    // TODO: bot commands
}
