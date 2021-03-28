<?php

namespace App\Http\Controllers\Bot;

use App\Models\Answer;
use App\Models\API\FacebookMessenger;
use App\Models\API\Telegram;
use App\Models\API\Viber;
use App\Models\BotUsers;
use App\Models\buttons\Menu;
use App\Models\Interaction;
use App\Models\Language;
use App\Models\Messenger;
use App\Models\RefSystem;
use App\Models\Text;
use App\Models\Visit;
use Exception;


/**
 * @method getMessenger()
 * @method methodFromGroupAndChat()
 * @method performAnActionRef(mixed $id)
 * @method start()
 */
class BaseRequestHandler
{
    private $bot;
    private $chat;
    private $userId = null;
    private $type;
    private $messenger;
    private $user = null;

    public function __construct()
    {
        $this->messenger = $this->getMessenger();

        if ($this->messenger == "Viber") {
            $this->bot = new Viber(defined('VIBER_TOKEN') ? VIBER_TOKEN : null);
        } elseif ($this->messenger == "Facebook") {
            $this->bot = new FacebookMessenger(defined('FACEBOOK_TOKEN') ? FACEBOOK_TOKEN : null);
        } elseif ($this->messenger == "Telegram") {
            $this->bot = new Telegram(defined('TELEGRAM_TOKEN') ? TELEGRAM_TOKEN : null);
        }

        $this->chat = $this->bot->getId();

        if (!$this->chat) {
            die(response('OK', '200')->header('Content-Type', 'text/plain'));
        }

        $this->setType();

        $visit = new Visit();
        $visit->add(date("Y-m-d"), $this->getUserId());
    }

    public function setUserId(): void
    {
        if ($this->messenger == "Telegram") {
            //We do not add a user.
            //Message from a channel or chat
            if (substr($this->chat, 0, 1) == '-') {
                $this->userId = 0;
                return;
            }
        }
        $botUsers = new BotUsers();
        $res = $botUsers->where('chat', $this->chat)->first();
        $this->user = $res;
        if (empty($res)) {
            $messenger = Messenger::where('name', $this->messenger)->first();

            $name = ($this->messenger == "Facebook") ? $this->bot->getName($this->chat) : $this->bot->getName();
            $botUsers->chat = $this->chat;
            $botUsers->first_name = $name['first_name'] ?? "No name";
            $botUsers->last_name = $name['last_name'] ?? "No name";
            $botUsers->username = $name['username'] ?? "No name";
            $botUsers->country = ($this->messenger == "Viber") ? $this->bot->getCountry() : '';
            $botUsers->messengers_id = $messenger->id;
            $botUsers->date = date("Y-m-d");
            $botUsers->time = date("H:i:s");
            $botUsers->save();
            $this->userId = $botUsers->id;
        } else {
            $this->userId = $res['id'];
        }
    }

    private function setType()
    {
        if ($this->messenger == "Viber") {
            $this->type = $this->getTypeReq();
        } else {
            $request = json_decode($this->getRequest());

            if(($request->my_chat_member->new_chat_member->status ?? '') === 'kicked') {
                $this->type = 'unsubscribed';
            } else {
                $arrProperties = $this->getProperties($request);
                $this->type = $this->getTypeReq($arrProperties);
            }
        }
    }

    public function getTypeChat(): ?string
    {
        $request = json_decode($this->getRequest());
        return $request->message->chat->type ?? $request->channel_post->chat->type ?? null;
    }

    private function getTypeReq($arrProperties = null): ?string
    {
        if ($this->messenger == "Viber") {
            $request = json_decode($this->getRequest());
            return ($request->message->type) ??
                    (($request->event == "conversation_started") ? "started" :
                    (($request->event == "unsubscribed") ? "unsubscribed" : null));
        } elseif ($this->messenger == "Facebook") {
            $rules = [
                'postback' => 'postback',
                'quick_reply' => 'quick_reply',
                'file' => 'url',
                'message' => 'message'
            ];
            foreach ($rules as $type => $rule) {
                if (array_key_exists($rule, $arrProperties)) return $type;
            }
            return 'other';
        } elseif ($this->messenger == "Telegram") {
            $rules = [
                'callback_query' => 'callback_query',
                'channel_post' => 'channel_post',
                'location' => 'location',
                'contact' => 'contact',
                'reply_to_message' => 'reply_to_message',
                'edited_message' => 'edited_message',
                'text' => 'text',
                'document' => 'document',
                'photo' => 'photo',
                'video' => 'video',
                'bot_command' => 'entities',
                'new_chat_participant' => 'new_chat_participant',
                'left_chat_participant' => 'left_chat_participant'
            ];
            foreach ($rules as $type => $rule) {
                if (array_key_exists($rule, $arrProperties)) return $type;
            }
            return 'other';
        }
        return 'other';
    }

    private function getProperties($obj, $names = []): array
    {
        if (is_object($obj) || is_array($obj)) foreach ($obj as $name => $el) {
            $names[$name] = $name;
            if (is_object($el) || is_array($el)) {
                $names = $this->getProperties($el, $names);
            }
        }
        return $names;
    }

    public function getDataByType(): ?array
    {
        $request = json_decode($this->getRequest());
        if ($this->messenger == "Viber") {
            if (empty($request)) return null;
            if ($this->type == "text") {
                return [
                    'message_id' => $request->message_token,
                    'text' => $request->message->text
                ];
            } elseif ($this->type == "picture") {
                return [
                    'message_id' => $request->message_token,
                    'picture' => [
                        [
                            'media' => $request->message->media,
                            'thumbnail' => $request->message->thumbnail,
                            'file_name' => $request->message->file_name
                        ]
                    ]
                ];
            } elseif ($this->type == "file") {
                return [
                    'message_id' => $request->message_token,
                    'data' => [
                        'type' => $request->message->type,
                        'media' => $request->message->media,
                        'file_name' => $request->message->file_name,
                        'size' => $request->message->size
                    ]
                ];
            } elseif ($this->type == "location") {
                return [
                    'lat' => $request->message->location->lat,
                    'lon' => $request->message->location->lon,
                    'address' => $request->message->location->address
                ];
            } elseif ($this->type == "contact") {
                return [
                    'phone' => $request->message->contact->phone_number
                ];
            } else {
                return [
                    'message_id' => $request->message_token ?? null,
                    'data' => null
                ];
            }
        } elseif ($this->messenger == "Facebook") {
            if ($this->type == "quick_reply") {
                return [
                    'message_id' => $request->entry[0]->id,
                    'text' => $request->entry[0]->messaging[0]->message->quick_reply->payload
                ];
            } elseif ($this->type == "payload") {
                return [
                    'message_id' => $request->entry[0]->id,
                    'text' => $request->entry[0]->messaging[0]->message->text
                ];
            } elseif ($this->type == "message") {
                return [
                    'message_id' => $request->entry[0]->id,
                    'text' => $request->entry[0]->messaging[0]->message->text
                ];
            } elseif ($this->type == "postback") {
                return [
                    'message_id' => $request->entry[0]->id,
                    'text' => $request->entry[0]->messaging[0]->postback->payload
                ];
            } elseif ($this->type == "file") {
                return [
                    'message_id' => $request->entry[0]->id,
                    'type' => $request->entry[0]->messaging[0]->message->attachments[0]->type,
                    'url' => $request->entry[0]->messaging[0]->message->attachments[0]->payload->url
                ];
            } else {
                return [
                    'message_id' => $request->entry[0]->id,
                    'data' => null
                ];
            }
        } elseif ($this->messenger == "Telegram") {
            if (empty($request)) return null;
            if ($this->type == "text") {
                return [
                    'message_id' => $request->message->message_id,
                    'text' => $request->message->text
                ];
            } elseif ($this->type == "document") {
                $data = [
                    'message_id' => $request->message->message_id,
                    'file_name' => $request->message->document->file_name,
                    'mime_type' => $request->message->document->mime_type,
                    'file_id' => $request->message->document->file_id,
                    'file_unique_id' => $request->message->document->file_unique_id,
                    'file_size' => $request->message->document->file_size
                ];
                if (isset($request->message->document->thumb)) {
                    $data['thumb'] = [
                        'file_id' => $request->message->document->thumb->file_id,
                        'file_unique_id' => $request->message->document->thumb->file_unique_id,
                        'file_size' => $request->message->document->thumb->file_size,
                        'width' => $request->message->document->thumb->width,
                        'height' => $request->message->document->thumb->height
                    ];
                }
                return $data;
            } elseif ($this->type == "photo") {
                return [
                    'message_id' => $request->message->message_id,
                    'photo' => [
                        [
                            'file_id' => $request->message->photo[0]->file_id,
                            'file_unique_id' => $request->message->photo[0]->file_unique_id,
                            'file_size' => $request->message->photo[0]->file_size,
                            'width' => $request->message->photo[0]->width,
                            'height' => $request->message->photo[0]->height
                        ],
                        [
                            'file_id' => $request->message->photo[1]->file_id ?? null,
                            'file_unique_id' => $request->message->photo[1]->file_unique_id ?? null,
                            'file_size' => $request->message->photo[1]->file_size ?? null,
                            'width' => $request->message->photo[1]->width ?? null,
                            'height' => $request->message->photo[1]->height ?? null
                        ]
                    ]
                ];
            } elseif ($this->type == "video") {
                return [
                    'message_id' => $request->message->message_id ?? null,
                    'video' => [
                        'duration' => $request->message->video->duration ?? null,
                        'width' => $request->message->video->width ?? null,
                        'height' => $request->message->video->height ?? null,
                        'file_name' => $request->message->video->file_name ?? null,
                        'mime_type' => $request->message->video->mime_type ?? null,
                        'file_id' => $request->message->video->file_id ?? null,
                        'file_unique_id' => $request->message->video->file_unique_id ?? null,
                        'file_size' => $request->message->video->file_size ?? null
                    ],
                    'thumb' => [
                        'file_id' => $request->message->video->thumb->file_id ?? null,
                        'file_unique_id' => $request->message->video->thumb->file_unique_id ?? null,
                        'file_size' => $request->message->video->thumb->file_size ?? null,
                        'width' => $request->message->video->thumb->width ?? null,
                        'height' => $request->message->video->thumb->height ?? null
                    ]
                ];
            } elseif ($this->type == "callback_query") {
                return [
                    'message_id' => $request->callback_query->message->message_id,
                    'data' => $request->callback_query->data,
                    'from' => [
                        'id' => $request->callback_query->from->id,
                        'first_name' => $request->callback_query->from->first_name ?? null,
                        'last_name' => $request->callback_query->from->last_name ?? null,
                        'username' => $request->callback_query->from->username ?? null
                    ],
                    'chat' => [
                        'id' => $request->callback_query->message->chat->id,
                        'title' => $request->callback_query->message->chat->title ?? null,
                        'type' => $request->callback_query->message->chat->type
                    ]
                ];
            } elseif ($this->type == "bot_command") {
                return [
                    'message_id' => $request->callback_query->message->message_id,
                    'text' => $request->callback_query->message->text
                ];
            } elseif ($this->type == "channel_post") {
                return [
                    'message_id' => $request->channel_post->message_id,
                    'text' => $request->channel_post->text
                ];
            } elseif ($this->type == "location") {
                return [
                    'message_id' => $request->message->message_id,
                    'lat' => $request->message->location->latitude,
                    'lon' => $request->message->location->longitude
                ];
            } elseif ($this->type == "contact") {
                return [
                    'phone' => $request->message->contact->phone_number
                ];
            } elseif ($this->type == "new_chat_participant") {
                return [
                    'message_id' => $request->message->message_id ?? null,
                    'from' => [
                        'id' => $request->message->from->id ?? null,
                        'first_name' => $request->message->from->first_name ?? null,
                        'last_name' => $request->message->from->last_name ?? null,
                        'username' => $request->message->from->username ?? null,
                    ],
                    'whom' => [
                        'id' => $request->message->new_chat_participant->id,
                        'first_name' => $request->message->new_chat_participant->first_name ?? null,
                        'last_name' => $request->message->new_chat_participant->last_name ?? null,
                        'username' => $request->message->new_chat_participant->username ?? null,
                    ],
                    'chat' => [
                        'id' => $request->message->chat->id ?? null,
                        'title' => $request->message->chat->title ?? null,
                        'type' => $request->message->chat->type ?? null,
                    ],
                    'date' => $request->message->date ?? null
                ];
            } elseif ($this->type == 'left_chat_participant') {
                return [
                    'message_id' => $request->message->message_id ?? null,
                    'from' => [
                        'id' => $request->message->from->id ?? null,
                        'first_name' => $request->message->from->first_name ?? null,
                        'last_name' => $request->message->from->last_name ?? null,
                        'username' => $request->message->from->username ?? null,
                    ],
                    'whom' => [
                        'id' => $request->message->left_chat_participant->id,
                        'first_name' => $request->message->left_chat_participant->first_name ?? null,
                        'last_name' => $request->message->left_chat_participant->last_name ?? null,
                        'username' => $request->message->left_chat_participant->username ?? null,
                    ],
                    'chat' => [
                        'id' => $request->message->chat->id ?? null,
                        'title' => $request->message->chat->title ?? null,
                        'type' => $request->message->chat->type ?? null,
                    ],
                    'date' => $request->message->date ?? null
                ];
            } elseif ($this->type == 'reply_to_message') {
                return [
                    'message_id' => $request->message->message_id ?? null,
                    'from' => [
                        'id' => $request->message->from->id ?? null,
                        'first_name' => $request->message->from->first_name ?? null,
                        'last_name' => $request->message->from->last_name ?? null,
                        'username' => $request->message->from->username ?? null
                    ],
                    'chat' => [
                        'id' => $request->message->chat->id ?? null,
                        'first_name' => $request->message->chat->first_name ?? null,
                        'last_name' => $request->message->chat->last_name ?? null,
                        'username' => $request->message->chat->username ?? null
                    ],
                    'reply_to_message' => [
                        'message_id' => $request->message->reply_to_message->message_id ?? null,
                        'from' => [
                            'id' => $request->message->reply_to_message->from->id ?? null,
                            'first_name' => $request->message->reply_to_message->from->first_name ?? null,
                            'last_name' => $request->message->reply_to_message->from->last_name ?? null,
                            'username' => $request->message->reply_to_message->from->username ?? null
                        ],
                        'chat' => [
                            'id' => $request->message->reply_to_message->chat->id ?? null,
                            'first_name' => $request->message->reply_to_message->chat->first_name ?? null,
                            'last_name' => $request->message->reply_to_message->chat->last_name ?? null,
                            'username' => $request->message->reply_to_message->chat->username ?? null,
                        ],
                        'forward_from' => [
                            'id' => $request->message->reply_to_message->forward_from->id ?? null,
                            'first_name' => $request->message->reply_to_message->forward_from->first_name ?? null,
                            'last_name' => $request->message->reply_to_message->forward_from->last_name ?? null,
                            'username' => $request->message->reply_to_message->forward_from->username ?? null,
                        ],
                        'text' => $request->message->reply_to_message->text ?? null,
                    ],
                    'text' => $request->message->text
                ];
            } elseif($this->type == 'edited_message') {
                return [
                    'message_id' => $request->edited_message->message_id,
                    'from' => [
                        'id' => $request->edited_message->from->id,
                        'first_name' => $request->edited_message->from->first_name ?? null,
                        'last_name' => $request->edited_message->from->last_name ?? null,
                        'username' => $request->edited_message->from->username ?? null
                    ],
                    'chat' => [
                        'id' => $request->edited_message->chat->id ?? null,
                        'first_name' => $request->edited_message->chat->first_name ?? null,
                        'last_name' => $request->edited_message->chat->last_name ?? null,
                        'username' => $request->edited_message->chat->username ?? null,
                    ],
                    'text' => $request->edited_message->text
                ];
            } else {
                return [
                    'message_id' => $request->message->message_id ?? null,
                    'data' => null
                ];
            }
        }
        return [];
    }

    public function saveFile($path = null, $name = null, $folderName = null): ?string
    {
        $filePath = $this->getFilePath();
        if ($this->messenger == "Telegram") {
            $ext = explode(".", $filePath);
        } else {
            $ext = explode("?", $filePath);
            $ext = explode(".", $ext[0]);
        }
        if ($name == null) {
            $name = time() . "." . end($ext);
        }
        if ($folderName == null) {
            $folderName = 'photo';
        }
        if ($path == null) {
            if (copy($filePath, public_path() . "/" . $folderName . "/" . $name)) return $name;
        } else {
            if (copy($filePath, $path . $name)) return $name;
        }
        return null;
    }

    public function getMethodName(): ?string
    {
        $data = $this->getDataByType();
        if ($this->messenger == "Viber") {
            if ($this->type == "text") {
                return trim($data['text']);
            } elseif ($this->type == "picture") {
                return "photo_sent";
            } elseif ($this->type == "file") {
                return "file";
            } elseif ($this->type == "location") {
                return "location";
            } elseif ($this->type == "contact") {
                return "contact";
            } elseif ($this->type == "unsubscribed") {
                return "unsubscribed";
            } else {
                return null;
            }
        } elseif ($this->messenger == "Facebook") {
            $name = "";
            if ($this->type == "message" || $this->type == "postback" || $this->type == "quick_reply" || $this->type == "payload") {
                $name = trim(trim($data['text'], "/"));
            } elseif ($this->type == "file") {
                return "file_send";
            }
            if ($name == "Начать" || $name == "начать") {
                $name = "start";
            }
            if (isset($name)) {
                $commandFromMessage = $this->getCommandFromMessage($name);
                return $commandFromMessage['command'] ?? $name ?? null;
            } else {
                return null;
            }
        } elseif ($this->messenger == "Telegram") {
            if ($this->type == "text" || $this->type == "bot_command") {
                if (strpos($data['text'], "@")) {
                    return trim(explode('@', $data['text'])[0], "/");
                }
                return trim($data['text'], "/");
            } elseif ($this->type == "callback_query") {
                return trim($data['data'], "/");
            } elseif ($this->type == "channel_post") {
                return trim($data['text'], "/");
            } elseif ($this->type == "photo") {
                return "photo_sent";
            } elseif ($this->type == "document") {
                return "document_sent";
            } elseif ($this->type == "location") {
                return "location";
            } elseif ($this->type == "contact") {
                return "contact";
            } elseif ($this->type == "unsubscribed") {
                return "unsubscribed";
            } else {
                return null;
            }
        }
        return null;
    }

    public function getLocation(): ?array
    {
        if ($this->type == "location") {
            return $this->getDataByType();
        } else {
            return null;
        }
    }

    public function getParams($assoc = false)
    {
        return json_decode($this->getInteraction()['params'], $assoc);
    }

    public function getFilePath($thumb = false)
    {
        $data = $this->getDataByType();
        if ($this->messenger == "Telegram") {
            if (isset($data['photo'][0]['file_id'])) {
                return $this->getBot()->getFilePath($data['photo'][0]['file_id']);
            } else {
                if ($thumb) {
                    if (isset($data['thumb']['file_id'])) {
                        return $this->getBot()->getFilePath($data['thumb']['file_id']);
                    }
                } else {
                    if (isset($data['video']['file_id'])) {
                        return $this->getBot()->getFilePath($data['video']['file_id']);
                    }
                }
                return $this->getBot()->getFilePath($data['file_id']);
            }
        } elseif ($this->messenger == "Viber") {
            if (isset($data['picture'][0]['media'])) {
                return $data['picture'][0]['media'];
            } else {
                return $data['data']['media'];
            }
        } elseif ($this->messenger == "Facebook") {
            return $data['url'];
        }
        return [];
    }

    public function unknownTeam()
    {
        if (substr($this->getBot()->getMessage(), 0, 4) == "http") return;
        if ($this->messenger == "Facebook") {
            $this->send("{unknown_team}");
        } else {
            $this->send("{unknown_team}", Menu::main());
        }
    }

    public function getType(): string
    {
        if ((MESSENGER ?? null) == 'Telegram' && $this->type == 'document') {
            if (preg_match('/("mime_type":"video)/m', $this->getRequest())) {
                return 'video';
            } else {
                return $this->type;
            }
        }
        return $this->type;
    }

    public function getRequest(): ?string
    {
        return $this->bot->getRequest();
    }

    public function getBot()
    {
        return $this->bot;
    }

    public function getChat(): string
    {
        return $this->chat ?? '-';
    }

    public function getUserId(): ?int
    {
        if ($this->userId) {
            return $this->userId;
        } else {
            $this->setUserId();
            return $this->userId;
        }
    }

    public function getUser(): BotUsers
    {
        if ($this->user == null) {
            return BotUsers::find($this->getUserId());
        }
        return $this->user;
    }

    public function callMethodIfExists(): void
    {
        if (substr($this->getChat(), 0, 1) == '-') {
            $this->methodFromGroupAndChat();
            return;
        }
        $nameCommand = $this->getMethodName();
        if (substr($nameCommand, 0, 4) == "http") return;
        if (method_exists($this, $nameCommand)) {
            try {
                $this->$nameCommand();
            } catch (Exception $e) {
                file_put_contents(
                    public_path("error.txt"),
                    $e->getFile() . "\n " . $e->getLine() . "\n " . $e->getMessage() . "\n\n", FILE_APPEND
                );
            }
        } else {
            //Start referrals
            if (substr($nameCommand, 0, 5) == "start" && $nameCommand != "start") {
                $r = explode(" ", $nameCommand);
                if (!empty($r[1])) {
                    $this->startRef($r[1]);
                    return;
                }
            }

            if (strpos($nameCommand, "__")) {
                $arr = explode("__", $nameCommand);
                $nameCommand = $arr[0];
                $params = $arr[1];
                if (strpos($params, "_")) {
                    $params = explode("_", $params);
                }
            }
            if (method_exists($this, $nameCommand)) {
                try {
                    $this->$nameCommand($params ?? null);
                } catch (Exception $e) {
                    file_put_contents(
                        public_path("error.txt"),
                        $e->getFile() . "\n " . $e->getLine() . "\n " . $e->getMessage() . "\n\n", FILE_APPEND
                    );
                }
            } else {
                $command = $this->getCommandFromMessage($nameCommand);
                if ($command['command']) {
                    $nameCommand = $command['command'];
                    $params = $command['params'];
                    if (method_exists($this, $nameCommand)) {
                        try {
                            $this->$nameCommand($params);
                        } catch (Exception $e) {
                            file_put_contents(
                                public_path("error.txt"),
                                $e->getFile() . "\n " . $e->getLine() . "\n " . $e->getMessage() . "\n\n",
                                FILE_APPEND
                            );
                        }
                    } else {
                        //Unknown
                        $this->unknownTeam();
                    }
                } else {
                    //Interaction
                    $interaction = $this->getInteraction();
                    if ($interaction) {
                        if (!empty($interaction['params'])) {
                            $params = json_decode($interaction['params'], true);
                        }
                        if (!empty($interaction['command'])) {
                            $method = $interaction['command'];
                            if (method_exists($this, $method)) {
                                try {
                                    $this->$method($params ?? null);
                                } catch (Exception $e) {
                                    file_put_contents(
                                        public_path("error.txt"),
                                        $e->getFile() . "\n " . $e->getLine() . "\n " . $e->getMessage() . "\n\n", FILE_APPEND);
                                }
                            }
                        } else {
                            //Unknown
                            $this->unknownTeam();
                        }
                    } else {
                        //Answers
                        $answer = Answer::toAnswerIfExistQuestion($nameCommand);
                        if ($answer) {
                            if (method_exists($this, $answer->method)) {
                                $method = $answer->method;
                                $this->$method();
                            }
                            if ($answer->menu) {
                                $menuName = $answer->menu;
                                $menu = Menu::$menuName();
                            } else {
                                $menu = [];
                            }
                            $this->send($answer->answer, $menu);
                            return;
                        }

                        //Unknown
                        $this->unknownTeam();
                    }
                }
            }
        }
    }

    private function getCommandFromMessage(string $message): array
    {
        $user = $this->getUser();
        $language = Language::where('id', $user->languages_id)->first();
        if ($language === null) {
            $language = 'ru';
        } else {
            $language = $language->code;
        }
        $pathButtons = public_path("/json/buttons.json");
        if ($user->language != '0') {
            if (file_exists(public_path("/json/buttons_" . $language . ".json"))) {
                $pathButtons = public_path("/json/buttons_" . $language . ".json");
            }
        }
        $textsButtons = json_decode(file_get_contents($pathButtons), true);
        $command = array_search($message, $textsButtons);
        $params = [];
        if (strpos($command, "__")) {
            $commandAndParams = explode("__", $command);
            $command = $commandAndParams[0];
            $params = $commandAndParams[1];
            if (strpos($params, "_")) {
                $params = explode("_", $params);
            }
        }
        return [
            'command' => $command,
            'params' => $params
        ];
    }

    public function send(string $message, array $buttons = [], bool $inline = false, array $params = [
        'input' => 'hidden'
    ], array $n = []): string
    {
        return $this->sendTo($this->chat, $message, $buttons, $inline, $params, $n);
    }

    public function sendTo(string $chat, string $message, array $buttons = [], bool $inline = false, array $params = [
        'input' => 'hidden'
    ], array $n = []): string
    {
        $message = Text::valueSubstitution($this->getUser(), $message, "page", $n);
        $buttons = Text::valueSubstitutionArray($this->getUser(), $buttons, $n);
        if ($inline) {
            $params['inlineButtons'] = $buttons;
        } else {
            $params['buttons'] = $buttons;
        }
        if (MESSENGER == 'Facebook') {
            $params = [
                'keyboard' => $buttons
            ];
        }
        return $this->bot->sendMessage($chat, $message, $params);
    }

    public function sendCarousel(array $richMedia, array $params = [
        'columns' => 6,
        'rows' => 7
    ], array $buttons = [], array $n = []): string
    {
        $buttons = Text::valueSubstitutionArray($this->getUser(), $buttons, $n);
        $richMedia = Text::valueSubstitutionArray($this->getUser(), $richMedia, $n);
        $richMedia = [
            'Type' => 'rich_media',
            'ButtonsGroupColumns' => $params['columns'] ?? 6,
            'ButtonsGroupRows' => $params['rows'] ?? 7,
            'BgColor' => '#FFFFFF',
            'Buttons' => $richMedia
        ];
        return $this->bot->sendCarousel($this->chat, $richMedia, $buttons ?? []);
    }

    public function sendImage($img, $message = null, $params = [], $n = [])
    {
        $message = Text::valueSubstitution($this->getUser(), $message, "pages", $n);
        $params = Text::valueSubstitutionArray($this->getUser(), $params, $n);
        return $this->bot->sendImage($this->chat, $img, $message, $params);
    }

    public function deleteMessage($messageId, $chat = null)
    {
        return $this->bot->deleteMessage($chat ?? $this->chat, $messageId);
    }

    public function getIdSendMessage($res)
    {
        return json_decode($res)->result->message_id ?? null;
    }

    public function setIdSendMessage($res, $command = '', $params = [])
    {
        $this->setInteraction($command, array_merge($params, [
            'messageId' => $this->getIdSendMessage($res)
        ]));
    }

    public function delInteraction(): void
    {
        $interaction = new Interaction();
        $interaction->where('users_id', $this->getUserId())->delete();
    }

    public function setInteraction(string $command, array $params = []): void
    {
        $this->delInteraction();
        $interaction = new Interaction();
        $interaction->users_id = $this->getUserId();
        $interaction->command = $command;
        $interaction->params = json_encode($params);
        $interaction->save();
    }

    public function getInteraction(): ?array
    {
        $interaction = new Interaction();
        $res = $interaction->where('users_id', $this->getUserId())->get()->toArray();
        return $res[0] ?? null;
    }

    public function getCallbackQueryId()
    {
        return json_decode($this->getRequest())->callback_query->id ?? null;
    }

    public function answerCallbackQuery($text, $n = [])
    {
        $text = Text::valueSubstitution($this->getUser(), $text, "pages", $n);
        return $this->bot->answerCallbackQuery($this->getCallbackQueryId(), $text);
    }

    public function editMessage($messageId, $message, $inlineKeyboard = [], $n = [])
    {
        $message = Text::valueSubstitution($this->getUser(), $message, "pages", $n);
        $inlineKeyboard = Text::valueSubstitutionArray($this->getUser(), $inlineKeyboard, $n);
        return $this->bot->editMessageText($this->getChat(), $messageId, $message, $inlineKeyboard);
    }

    public function editMessageReplyMarkup($chat, $messageId, $inlineButtons = [], $n = [])
    {
        $inlineButtons = Text::valueSubstitutionArray((new BotUsers)->where('chat', $chat), $inlineButtons, $n);
        $reply_markup = [
            'inline_keyboard' => $inlineButtons
        ];
        return $this->bot->editMessageReplyMarkup($chat, $messageId, $reply_markup);
    }

    public function startRef($chat)
    {
        try {
            $referral = $this->getUserId();
            $referrer = (new BotUsers)->where('chat', $chat)->first();

            if ($referrer->id != $referral) {
                RefSystem::insert([
                    'referrer' => $referrer->id,
                    'referral' => $referral,
                    'date' => date("Y-m-d"),
                    'time' => date("H:i:s")
                ]);
            }
        } catch (Exception $e) {
            /** @var BotUsers $referrer */
            /** @var int $referral */
            file_put_contents(
                public_path("/refError.txt"),
                "referral " . $referral . "\nreferrer " . $referrer->id . "\n" . $e->getMessage() . "\n\n",
                FILE_APPEND
            );
            echo $e->getMessage();
        }
        $this->performAnActionRef($referrer->id);
        if (MESSENGER == "Telegram") {
            $this->start();
        }
    }

    public function setUserStart()
    {
        $botUsers = BotUsers::find($this->getUserId());
        $botUsers->update([
            'start' => 1,
            'unsubscribed' => 0
        ]);
    }

    public function delMessage()
    {
        $params = json_decode($this->getInteraction()['params'] ?? '{}');
        if (isset($params->messageId)) {
            $this->deleteMessage($params->messageId, $this->getChat());
        }
        $this->delInteraction();
    }

    public function typing_on()
    {
        return $this->bot->senderAction($this->chat, 'typing_on');
    }

    public function typing_off()
    {
        return $this->bot->senderAction($this->chat, 'typing_off');
    }

    public function mark_seen()
    {
        return $this->bot->senderAction($this->chat, 'mark_seen');
    }

    public function sendButton($message, $buttons, $n = [])
    {
        $message = Text::valueSubstitution($this->getUser(), $message, "pages", $n);
        $buttons = Text::valueSubstitutionArray($this->getUser(), $buttons, $n);
        return $this->bot->sendButton($this->chat, $message, $buttons);
    }

    public function getMessage(): ?string
    {
        return $this->bot->getMessage();
    }

    public function getChatMember($idUser, $chat, $status = false): ?bool
    {
        $result = json_decode($this->getBot()->getChatMember($idUser, $chat))->result->status ?? null;
        if ($status) {
            return $result;
        }
        if ($result == 'creator' || $result == 'administrator' || $result == 'member' || $result == 'restricted') {
            return true;
        }
        return false;
    }

    public function getMessageId()
    {
        return $this->getDataByType()['message_id'] ?? null;
    }

    public function forwardMessage($whomChat, $fromChat = null, $messageId = null)
    {
        if ((MESSENGER ?? null) == 'Telegram') {
            return $this->getBot()->forwardMessage(
                $whomChat,
                $fromChat ?? $this->getChat(),
                $messageId ?? $this->getMessageId()
            );
        } else {
            return 'In developing';
        }
    }
}
