<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;

class BotsController extends Controller
{
    public function list()
    {
        return view('admin.bots.list', [
            'bots' => Bot::all(),
            'menuItem' => 'botslist'
        ]);
    }

    public function add()
    {
        return view('admin.bots.add', [
            'menuItem' => 'botsadd'
        ]);
    }
}
