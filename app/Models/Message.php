<?php


namespace App\Models;


use App\Models\API\FacebookMessenger;
use App\Models\API\Telegram;
use App\Models\API\Viber;
use App\Models\buttons\Menu;


class Message
{
    private $messenger;

    public function __construct()
    {
        if (defined('TELEGRAM_TOKEN')) {
            $this->messenger = new Telegram(TELEGRAM_TOKEN);
        }
        if (defined('VIBER_TOKEN')) {
            $this->messenger = new Viber(VIBER_TOKEN);
        }
        if (defined('FACEBOOK_TOKEN')) {
            $this->messenger = new FacebookMessenger(FACEBOOK_TOKEN);
        }
    }

    public function send($messenger, $chat, $message, $n = []): ?string
    {
        $texts = new Text();
        $user = (new BotUsers)->where('chat', $chat)->get();
        $message = $texts->valueSubstitution($user, $message, 'pages', $n);
        $mainMenu = $texts->valueSubstitutionArray($user, Menu::main(['messenger' => $messenger]));
        if (!$this->messenger) {
            $this->messenger = new $messenger($user->bot->token);
        }
        return $this->messenger->sendMessage($chat, $message, [
            'buttons' => $mainMenu
        ]);
    }
}
