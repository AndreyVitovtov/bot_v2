<?php


namespace App\Models;

use App\Models\buttons\Menu;
use Illuminate\Support\Facades\DB;

class Mailing
{

    public $pathTask;
    public $countUsers;
    public $chatPathTask;

    public function __construct()
    {
        $this->pathTask = public_path('/json/mailing_task.json');
        $this->chatPathTask = public_path('/json/mailing_task_chat.json');
        $this->countUsers = 1800;
    }

    public function send(): string
    {
        if (!file_exists($this->pathTask)) return json_encode([
            'status' => 'fail',
            'message' => 'No task'
        ]);

        $taskJson = file_get_contents($this->pathTask);
        $task = json_decode($taskJson);

        if ($task->performed == "true") return json_encode([
            'status' => 'fail',
            'message' => 'Mailing performed'
        ]);

        $db = DB::table('users')
            ->join('messengers', 'users.messengers_id', '=', 'messengers.id')
            ->where('messengers.name', 'LIKE', $task->messenger);

        if ($task->country !== 'all') {
            $db = $db->where('country', $task->country);
        }

        $db = MailingParameters::apply($db, $task);

        $users = $db->limit($this->countUsers)
            ->offset($task->start)
            ->get(['id', 'chat', 'messengers.name AS messenger'])
            ->toArray();

        $task->performed = "true";
        if ($task->count <= $this->countUsers) {
            $task->start = $task->count;
        } else {
            $task->start += $this->countUsers;
        }
        file_put_contents($this->pathTask, json_encode($task));

        if (empty($users)) {
            unlink($this->pathTask);
            if (isset($task->img)) {
                $imgArr = explode("/", $task->img);
                $imgName = end($imgArr);
                if(file_exists(public_path('/img/mailing/' . $imgName))) {
                    unlink(public_path('/img/mailing/' . $imgName));
                }
            }
            return json_encode([
                'status' => 'fail',
                'message' => 'No users for mailing'
            ]);
        }

        $usersChunk = array_chunk($users, (defined('COUNT_MAILING') ? COUNT_MAILING : 200));

        $handle = fopen(public_path() . "/txt/log.txt", "a");

        foreach ($usersChunk as $uc) {
            $data = [];
            foreach ($uc as $user) {
                if ($task->type == "text") {
                    if ($user->messenger == "Telegram") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://api.telegram.org/bot" .
                                (defined('TELEGRAM_TOKEN') ? TELEGRAM_TOKEN : null) . "/sendMessage",
                            'params' => [
                                'text' => $task->text,
                                'chat_id' => $user->chat,
                                'parse_mode' => 'HTML',
                                'disable_web_page_preview' => true,
                                'reply_markup' => [
                                    'keyboard' => Text::valueSubstitutionArray($user, Menu::main(
                                        ['messenger' => 'Telegram']
                                    )),
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => false,
                                    'parse_mode' => 'HTML',
                                    'selective' => true
                                ]
                            ]
                        ];
                    } elseif ($user->messenger == "Viber") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://chatapi.viber.com/pa/send_message",
                            'params' => [
                                'receiver' => $user->chat,
                                'min_api_version' => 7,
                                'type' => 'text',
                                'text' => $task->text,
                                'keyboard' => [
                                    'Type' => 'keyboard',
                                    'InputFieldState' => 'hidden',
                                    'DefaultHeight' => 'false',
                                    'Buttons' => Text::valueSubstitutionArray($user, Menu::main(
                                        ['messenger' => 'Viber']
                                    ))
                                ]
                            ]
                        ];
                    } elseif ($user->messenger == "Facebook") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://graph.facebook.com/v3.2/me/messages?access_token=" .
                                (defined('FACEBOOK_TOKEN') ? FACEBOOK_TOKEN : null),
                            'params' => [
                                'recipient' => [
                                    'id' => $user->chat
                                ],
                                'message' => [
                                    'text' => $task->text
                                ]
                            ]
                        ];
                    }
                } elseif ($task->type == "img") {
                    if ($user->messenger == "Telegram") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => 'https://api.telegram.org/bot' .
                                (defined('TELEGRAM_TOKEN') ? TELEGRAM_TOKEN : null) . '/sendPhoto',
                            'params' => [
                                'chat_id' => $user->chat,
                                'photo' => $task->img,
                                'caption' => $task->text,
                                'parse_mode' => 'Markdown',
                                'reply_markup' => [
                                    'keyboard' => Text::valueSubstitutionArray($user, Menu::main(
                                        ['messenger'=> 'Telegram']
                                    )),
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => false,
                                    'parse_mode' => 'HTML',
                                    'selective' => true
                                ]
                            ]
                        ];
                    } elseif ($user->messenger == "Viber") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://chatapi.viber.com/pa/send_message",
                            'params' => [
                                'receiver' => $user->chat,
                                'min_api_version' => 7,
                                'type' => 'picture',
                                'text' => $task->text,
                                'media' => $task->img,
                                'keyboard' => [
                                    'Type' => 'keyboard',
                                    'InputFieldState' => 'hidden',
                                    'DefaultHeight' => 'false',
                                    'Buttons' => Text::valueSubstitutionArray($user, Menu::main(
                                        ['messenger' => 'Viber']
                                    ))
                                ]
                            ]
                        ];
                    }
                }
            }

            $res = $this->multiCurl($data);

            if (!is_array($res['response'])) {
                json_encode([
                    'status' => 'error',
                    'message' => 'No response',
                    'response' => json_encode($res)
                ]);
            }

            foreach ($res['response'] as $key => $response) {
                fwrite($handle, $key . "=>" . $response . "\n");
            }
            unset($data);
            sleep((defined('SLEEP_MAILING') ? SLEEP_MAILING : 2));
        }

        fclose($handle);


        $task->performed = "false";
        file_put_contents($this->pathTask, json_encode($task));

        return json_encode([
            'status' => 'success',
            'message' => 'Mailing finished',
            'response' => json_encode($res ?? [])
        ]);
    }

    private function multiCurl($data): array
    {
        $mh = curl_multi_init();
        $connectionArray = [];

        foreach ($data as $item) {
            $key = $item['key'];
            $data_string = json_encode($item['params']);

            $ch = curl_init($item['url']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($data_string),
            ];

            if ($item['messenger'] == "Viber") {
                $headers[] = 'X-Viber-Auth-Token: ' . (defined('VIBER_TOKEN') ? VIBER_TOKEN : null);
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_multi_add_handle($mh, $ch);
            $connectionArray[$key] = $ch;
        }

        $running = null;

        do {
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $responseEmpty = [];
        $content = [];
        $httpCode = [];
        $url = [];

        foreach ($connectionArray as $key => $ch) {
            $content[$key] = curl_multi_getcontent($ch);

            if (empty(curl_multi_getcontent($ch))) {
                $responseEmpty[] = $key;
            }

            $getInfo = curl_getinfo($ch);
            $httpCode[$key] = $getInfo['http_code'];
            $url[$key] = $getInfo['url'];
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);

        $result = [
            "status" => !empty($content) ? "success" : "error",
            "httpCode" => $httpCode,
            "url" => $url,
            "response" => $content
        ];

        if (!empty($responseEmpty)) {
            $result['responseEmpty'] = $responseEmpty;
        }

        return $result;
    }
}
