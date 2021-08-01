<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
        $user = User::where('login', $request->input('login'))->first();
        if ($user->chat_id != null) {
            $password = Str::random(10);
            $user->password = Hash::make($password);
            $user->save();

            if (substr($user->chat_id, -2) == '==') {
                $messenger = 'Viber';
            } else {
                $messenger = 'Telegram';
            }

            if (App::getLocale() == 'ru') {
                $text = "Ваш новый пароль: " . $password .
                    "\nИзменить пароль вы можете в меню настройки администратора";
            } else {
                $text = "Your new password: " . $password .
                    "\nYou can change the password in the administrator settings menu";
            }

            $message = new Message;
            $message->send($messenger, $user->chat_id, $text);

            return redirect()->to(route('login'));
        } else {
            return redirect('404');
        }
    }
}
