<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Info;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function index()
    {
        return view('developer.info.index', [
            'info' => Info::first(),
            'menuItem' => 'info'
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $info = Info::first();
        if(!$info) {
            $info = new Info();
        }
        $info->db_address = $request['db_address'];
        $info->db_login = $request['db_login'];
        $info->db_password = $request['db_password'];
        $info->db_name = $request['db_name'];
        $info->ftp_type = $request['ftp_type'];
        $info->ftp_login = $request['ftp_login'];
        $info->ftp_address = $request['ftp_address'];
        $info->ftp_password = $request['ftp_password'];
        $info->save();

        return redirect()->to(route('info'));
    }
}
