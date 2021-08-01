<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\BotUsers;
use App\Models\Message;
use Illuminate\Http\Request;

class Users extends Controller {
    public function index() {
        $view = view('admin.users.users');
        $view->menuItem = "users";
        $view->users = BotUsers::paginate(15);
        return $view;
    }

    public function profile($id) {
        $user = new BotUsers;

        $profile = $user->find($id);
        if(empty($profile)) {
            return redirect()->to("404");
        }
        $view = view('admin.users.user-profile');
        $view->profile = $profile;
        $view->menuItem = "users";
        return $view;
    }

    public function createUrlSearch(Request $request) {
        $params = $request->input();
        if(empty($params['str'])) {
            return redirect()->to("/admin/users");
        }
        return redirect()->to("/admin/users/search/{$params['str']}");
    }

    public function search($str) {
        $BotUsers = new BotUsers();
        $users = $BotUsers->where('chat', 'LIKE', "%$str%")->orwhere('username', 'LIKE', "%$str%")->paginate(15);
        $view = view('admin.users.users');
        $view->users = $users;
        $view->str = $str;
        $view->menuItem = "users";
        return $view;
    }

    public function access(Request $request) {
        $user = BotUsers::find($request->post('id'));
        $access = $request->post('access');

        if($access == 'on') {
            $user->access = '1';
        }
        else {
            $user->access = '0';
        }
        $user->save();

        $message = new Message();
        if($access == 'on') {
            $message->send($user->messenger, $user->chat, "{full_access_granted}");
        }
        else {
            $message->send($user->messenger, $user->chat, "{full_access_canceled}");
        }

        return redirect()->to("/admin/users/profile/".$request->post('id'));
    }

    public function sendMessage(Request $request) {
        $user = BotUsers::find($request->post('id'));
        $message = new Message();
        $message->send($user->messenger->name, $user->chat, $request->post('message'));

        return redirect()->to(url('admin/users/profile/'.$request->post('id')));
    }

}
