<?php


namespace App\Services\Implement;


use App\models\Interaction;
use App\Services\Contracts\InteractionService;

class InteractionServiceImpl implements InteractionService {

    function set(int $user_id, string $command, array $params): void {
        $interaction = new Interaction();
        $interaction->users_id = $user_id;
        $interaction->command = $command;
        $interaction->params = json_encode($params);
        $interaction->save();
    }

    function get(int $user_id) {
        $interaction = Interaction::where('users_id', $user_id)->get();
        return [
            'command' => $interaction->command,
            'params' => json_decode($interaction->params)
        ];
    }

    function delete(int $user_id) {
        Interaction::where('users_id', $user_id)->delete();
    }
}
