<?php


namespace App\models;


use App\models\API\Telegram;
use App\models\API\Viber;

class Webhook {
    public function set($params = []) {
        $uri = "https://".$_SERVER['HTTP_HOST']."/bot/index";

        if(isset($params['viber'])) {
            if(defined("VIBER_TOKEN")) {
                $viber = new Viber(VIBER_TOKEN);
                $viber->setWebhook($uri);
            }
        }

        if(isset($params['telegram'])) {
            if(defined("TELEGRAM_TOKEN")) {
                $telegram = new Telegram(TELEGRAM_TOKEN);
                $telegram->setWebhook($uri);
            }
        }
    }
}
