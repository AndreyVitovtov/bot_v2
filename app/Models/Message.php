<?php


namespace App\Models;


use App\Models\API\FacebookMessenger;
use App\Models\API\Telegram;
use App\Models\API\Viber;
use App\Models\buttons\Menu;


class Message
{
    private $tgm;
    private $viber;
    private $facebook;

    public function __construct()
    {
        if (defined('TELEGRAM_TOKEN')) {
            $this->tgm = new Telegram(TELEGRAM_TOKEN);
        }

        if (defined('VIBER_TOKEN')) {
            $this->viber = new Viber(VIBER_TOKEN);
        }

        if (defined('FACEBOOK_TOKEN')) {
            $this->facebook = new FacebookMessenger(FACEBOOK_TOKEN);
        }
    }

    public function send($messenger, $chat, $message, $n = []): ?string
    {
        $texts = new Text();
        $user = (new BotUsers)->where('chat', $chat)->get();
        $message = $texts->valueSubstitution($user, $message, 'pages', $n);
        if ($messenger == "Telegram") {
            $mainMenu = $texts->valueSubstitutionArray($user, Menu::main(['messenger' => 'Telegram']));
            return $this->tgm->sendMessage($chat, $message, [
                'buttons' => $mainMenu
            ]);
        } elseif ($messenger == "Viber") {
            $mainMenu = $texts->valueSubstitutionArray($user, Menu::main(array('messenger' => 'Viber')));
            return $this->viber->sendMessage($chat, $message, [
                'buttons' => $mainMenu
            ]);
        }
        return null;
    }
}
