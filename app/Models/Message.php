<?php


namespace App\Models;


use App\Models\API\FacebookMessenger;
use App\Models\API\Telegram;
use App\Models\API\Viber;
use App\Models\buttons\Menu;

class Message {
    private $tgm;
    private $viber;

    public function __construct() {
        if(defined('TELEGRAM_TOKEN')) {
            $this->tgm = new Telegram(TELEGRAM_TOKEN);
        }

        if(defined('VIBER_TOKEN')) {
            $this->viber = new Viber(VIBER_TOKEN);
        }

        if(defined('FACEBOOK_TOKEN')) {
            $this->facebook = new FacebookMessenger(FACEBOOK_TOKEN);
        }
    }

    public function send($messenger, $chat, $message, $n = []) {
        $message = $this->valueSubstitution($message, 'pages', $n);
        if($messenger == "Telegram") {
            $mainMenu = $this->valueSubstitutionArray(Menu::main(['messenger' => 'Telegram']));
            return $this->tgm->sendMessage($chat, $message, [
                'buttons' => $mainMenu
            ]);
        }
        elseif($messenger == "Viber") {
            $mainMenu = $this->valueSubstitutionArray(Menu::main(array('messenger' => 'Viber')));
            return $this->viber->sendMessage($chat, $message, [
                'buttons' => $mainMenu
            ]);
        }
    }

    private function valueSubstitution($str, $type, $n = []) {
        if(preg_match_all('/{([^}]*)}/', $str, $matches)) {
            $textName = file_get_contents(public_path("json/{$type}.json"));
            $textName = json_decode($textName, true);

            foreach($matches[1] as $word) {
                if(!empty($textName[$word])) {
                    $text = $textName[$word];
                    $str = str_replace("{".$word."}", stripcslashes($text), $str);
                }
            }
        }
        if(preg_match_all('/{{([^}]*)}}/', $str, $matches)) {
            foreach($matches[1] as $word) {
                if(isset($n[$word])) {
                    $str = str_replace("{{".$word."}}", $n[$word], $str);
                }
            }
        }
        return $str;
    }

    private function valueSubstitutionArray($array, $n = []) {
        $new_array = [];
        foreach($array as $key => $item) {
            if(is_array($item)) {
                $new_array[$key] = $this->valueSubstitutionArray($item, $n);
            }
            else {
                $new_array[$key] = $this->valueSubstitution($item, "buttons", $n);
            }
        }

        return $new_array;
    }
}
