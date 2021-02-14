<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Bot\Traits\MethodsFromGroupAndChat;
use App\Http\Controllers\Bot\Traits\RequestHandlerTrait;
use App\Models\buttons\Menu;
use App\Models\buttons\RichMedia;

class RequestHandler extends BaseRequestHandler {

    use RequestHandlerTrait;
    use MethodsFromGroupAndChat;

    // TODO: bot commands
}
