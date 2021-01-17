<?php


namespace App\models\buttons;


use App\models\BotUsers;

class ButtonsTelegram {

    public static function main_menu($userId) {
//        $user = BotUsers::find($userId);

        return [
            ["{search_ads}", "{create_ad}"],
            ["{my_ads}", "{contacts}"],
            ["{edit_country}"]
        ];
    }

    public static function start()
    {
        return [
            ["start"]
        ];
    }

    public function back() {
        return [
            ["{back}"]
        ];
    }

    public function getPhone() {
        return [
            [[
                'text' => '{send_phone}',
                'request_contact' => true
            ], "{back}"]
        ];
    }

    public function getLocation() {
        return [
            [[
                'text' => '{send_location}',
                'request_location' => true
            ], "{back}"]
        ];
    }
}
