<?php


namespace App\Services\Implement;


use App\models\BotUsers;
use App\Services\Contracts\UserService;

class UserServiceImpl implements UserService {

    function create(array $u): int {
        $user = new BotUsers();
        $user->chat = $u['chat'];
        $user->username = $u['username'];
        $user->firstname = $u['firstname'];
        $user->lastname = $u['lastname'];
        $user->date = date("Y-m-d");
        $user->time = date("H:i:s");
        $user->country = $u['country'];
        $user->messenger = $u['messenger'];
        $user->save();

        return $user->id;
    }
}
