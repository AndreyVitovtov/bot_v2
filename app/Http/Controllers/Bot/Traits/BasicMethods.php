<?php


namespace App\Http\Controllers\Bot\Traits;

use App\Models\Bot;
use App\Models\BotUsers;
use App\Models\buttons\ButtonsFacebook;
use App\Models\buttons\ButtonsTelegram;
use App\Models\buttons\ButtonsViber;
use App\Models\buttons\InlineButtons;
use App\Models\buttons\Menu;
use App\Models\buttons\RichMedia;
use App\Models\buttons\RichMediaButtons;
use App\Models\ContactsModel;
use App\Models\ContactsType;
use App\Models\Language;
use App\Models\RefSystem;
use App\Services\Contracts\BotService;
use Throwable;

trait BasicMethods
{
    private $messenger;
    private $botService;
    private $richMedia;
    protected $botModel = null;

    public function __construct(BotService $botService)
    {
        $this->botService = $botService;

        $headers = getallheaders();
        if (isset($_SERVER['HTTP_X_VIBER_CONTENT_SIGNATURE']) || isset($headers['Viber'])) {
            $this->messenger = "Viber";
        } elseif (isset($headers['Facebook-Api-Version'])) {
            $this->messenger = "Facebook";
        } else {
            $this->messenger = "Telegram";
        }

        define("MESSENGER", $this->messenger);

        if ($this->messenger == "Facebook") {
            $this->mark_seen();
            $this->typing_on();
            sleep(rand(1, 2));
        }
    }

    public function getMessenger(): string
    {
        return $this->messenger;
    }

    public function buttons()
    {
        if ($this->messenger == "Viber") {
            return new ButtonsViber();
        } elseif ($this->messenger == "Telegram") {
            return new ButtonsTelegram();
        } else {
            return new ButtonsFacebook();
        }
    }

    public function getBotModel()
    {
        return $this->botModel;
    }

    public function index($id)
    {
        try {
            $bot = Bot::find($id);
            define(((MESSENGER == 'Telegram') ? 'TELEGRAM_TOKEN' : ((MESSENGER == 'Viber') ? 'VIBER_TOKEN' : 'FACEBOOK_TOKEN')),
                $bot->token ?? '0');
            define('BOT', $bot->toArray());
            $this->botModel = $bot;
        } catch (Throwable $e) {
            echo $e->getMessage();
        }

        parent::__construct();

        file_put_contents(public_path("json/request.json"), $this->getRequest());

        if ($this->getType() == "started") {
            $this->setUserId();

            $context = $this->getBot()->getContext();
            if ($context) {
                $context = str_replace(" ", "+", $context);
                if ($this->messenger == "Viber" && substr($context, -2) != "==") {
                    $context .= "==";
                }

                $this->startRef($context);
            }

            $this->send("{greeting}", Menu::start());
        } else {
            $this->callMethodIfExists();
        }

        return response('OK', '200')->header('Content-Type', 'text/plain');


//TODO: ДОБАВИТЬ WEBHOOK FACEBOOK MESSENGER
//        $verify_token = "31ad48b8b8b266e8f653de34252e44a0"; //Маркер подтверждения
//        if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {
//            echo $_REQUEST['hub_challenge'];
//        }
    }

    public function start()
    {
        $this->delInteraction();
        $this->setUserStart();

        //Facebook referrals
        if (MESSENGER == "Facebook") {
            $chat = $this->getBot()->getRef();
            if ($chat != null) {
                $this->startRef($chat);
            }
        }

        //TODO: execute start method
        $languages = $this->getLanguagesBot();
        if(MESSENGER == 'Telegram') {
            $this->send("{welcome}");
            $this->send("{select_language}", InlineButtons::languages($languages), true);
        } else {
            $this->send('{welcome}');
            $this->send('{select_language}');
            $this->sendCarousel((new RichMediaButtons)->languages($languages), [
                'columns' => 6,
                'rows' => count($languages)
            ]);
        }

    }

    public function unsubscribed()
    {
        (new BotUsers)->where('chat', $this->getChat())->update([
            'start' => 0,
            'unsubscribed' => 1
        ]);
        return response('OK', '200')->header('Content-Type', 'text/plain');
    }

    public function selectLanguage($id) {
        $this->delMessage();
        $user = $this->getUser();
        $user->languages_id = $id;
        $user->save();
        $language = Language::find($id);
        $this->send('{language_selected}', Menu::main($this->getUser()), false, [], [
            'lang' => mb_strtolower($language->name)
        ]);
        $this->send('{main_menu}', Menu::main($this->getUser()));
    }

    public function changeLanguage() {
        $languages = $this->getLanguagesBot();
        if (MESSENGER == 'Telegram') {
            $res = $this->send('{select_language}', InlineButtons::languages($languages), true);
            $this->setIdSendMessage($res);
        } else {
            $this->send('{select_language}', Menu::back());
            $this->sendCarousel(
                (new RichMediaButtons)->languages($languages), ['rows' => count($languages)], Menu::back()
            );
        }
    }

    public function contacts()
    {
        $this->setInteraction('contacts_select_topic');
        $res = $this->send("{send_support_message}", Menu::back());

        if (MESSENGER == "Facebook") {
            $this->send("{select_topic}", ButtonsFacebook::contacts());
        } elseif (MESSENGER == "Telegram") {
            $this->send("{select_topic}", InlineButtons::contacts(), true);
        } else {
            $this->send("{select_topic}", Menu::back());
            $this->sendCarousel(
                RichMedia::contacts(), [
                    'rows' => 3
                ], Menu::back()
            );
        }
    }

    public function contacts_select_topic()
    {
        $topic = $this->getBot()->getMessage();
        if ($topic == "lookingbook" ||
            $topic == "access" ||
            $topic == "advertising" ||
            $topic == "offers") {
            $this->send("{send_message}", Menu::back(), false, [
                'input' => 'regular'
            ]);
            $this->delInteraction();
            $this->setInteraction('contacts_send_message', [
                'topic' => $topic
            ]);
        } else {
            $this->contacts();
        }
    }

    public function contacts_send_message($params)
    {
        $contactsType = ContactsType::where('type', $params['topic'])->first();
        $contacts = new ContactsModel();
        $contacts->contacts_type_id = $contactsType->id;
        $contacts->users_id = $this->getUserId();
        $contacts->text = $this->getBot()->getMessage();
        $contacts->date = date("Y-m-d");
        $contacts->time = date("H:i:s");
        $contacts->save();

        $this->send("{message_sending}", Menu::main($this->getUser()));
        $this->delInteraction();
    }

    public function main()
    {
        $this->delInteraction();
        $this->send("{main_menu}", Menu::main($this->getUser()));
    }

    public function back()
    {
        $this->delInteraction();
        $this->send("{main_menu}", Menu::main($this->getUser()));
        exit;
    }

    public function performAnActionRef($referrerId)
    {
        $this->userAccess($referrerId);
//        $this->send("REF SYSTEM");
    }

    public function userAccess($id)
    {
        $count = RefSystem::where('referrer', $id)->count();

        if ($count == (defined('COUNT_INVITES_ACCESS') ? COUNT_INVITES_ACCESS : 0)) {
            $user = BotUsers::find($id);
            $user->access = '1';
            $user->access_free = '1';
            $user->save();

            $this->sendTo($user->chat, "{got_free_access}", Menu::main($this->getUser()), false, [], [
                'count' => (defined('COUNT_INVITES_ACCESS') ? COUNT_INVITES_ACCESS : 0)
            ]);
        }
    }
}
