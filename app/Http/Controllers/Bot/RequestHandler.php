<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Bot\Traits\HelperMethods;
use App\Http\Controllers\Bot\Traits\MethodsFromGroupAndChat;
use App\Http\Controllers\Bot\Traits\BasicMethods;
use App\Models\buttons\Menu;
use App\Models\buttons\RichMedia;

class RequestHandler extends BaseRequestHandler
{

    use BasicMethods;
    use MethodsFromGroupAndChat;
    use HelperMethods;

    // TODO: bot commands
}
