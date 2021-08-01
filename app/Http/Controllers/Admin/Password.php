<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Password extends Controller
{
    public function forgot()
    {
        return view('admin.password.forgot');
    }

    public function send(Request $request)
    {
        return 'In developing';
        $user = User::where('login', $request->input('login'))->first();
        if($user->email != null) {
            $password = Str::random(10);
            $user->password = Hash::make($password);
            $user->save();
        } else {
            return redirect('404');
        }
        return $password;
    }
}
