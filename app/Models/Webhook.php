<?php


namespace App\Models;


use App\Models\API\Telegram;
use App\Models\API\Viber;

class Webhook
{
    public function set($params = [], $token = null, $botId = null): ?string
    {
        $uri = $uri ?? "https://" . $_SERVER['HTTP_HOST'] . "/bot/index".($botId === null ? '' : $botId);

        if (isset($params['viber']) || isset($params['Viber'])) {
            if (defined("VIBER_TOKEN") || $token != null) {
                $viber = new Viber(VIBER_TOKEN ?? $token);
                return $viber->setWebhook($uri);
            }
        }

        if (isset($params['telegram']) || isset($params['Telegram'])) {
            if (defined("TELEGRAM_TOKEN") || $token != null) {
                $telegram = new Telegram(TELEGRAM_TOKEN ?? $token);
                return $telegram->setWebhook($uri);
            }
        }

        return null;
    }
}
