<?php


namespace App\Models\buttons\Traits;


trait ButtonsFacebookMessenger {

    public static function main_menu() {
        return [
            [
                'type' => 'postback',
                'title' => '{main_menu}',
                'payload' => 'main_menu'
            ]
        ];
    }
}
