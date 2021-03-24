<?php

namespace App\Models\API;

class Viber
{
    protected $token;
    private $request;

    public function __construct(string $token = null)
    {
        $this->request = json_decode(file_get_contents('php://input'));
        if ($token === null) {
            $this->token = (defined('VIBER_TOKEN') ? VIBER_TOKEN : null);
        } else {
            $this->token = $token;
        }
    }

    public function getContext(): ?string
    {
        return $this->request->context ?? null;
    }

    public function getId(): ?string
    {
        return (
            $this->request->sender->id ??
            $this->request->user->id ??
            $this->request->user_id ??
            null
        );
    }

    public function getName(): ?array
    {
        if (isset($this->request->sender->name)) {
            return [
                'first_name' => $this->request->sender->name,
                'last_name' => $this->request->sender->name,
                'username' => $this->request->sender->name
            ];
        } elseif (isset($this->request->user->name)) {
            return [
                'first_name' => $this->request->user->name,
                'last_name' => $this->request->user->name,
                'username' => $this->request->user->name
            ];
        } else {
            return null;
        }
    }

    public function getCountry(): ?string
    {
        return (
            $this->request->user->country ??
            $this->request->sender->country ??
            null
        );
    }

    public function getRequest(): ?string
    {
        return json_encode($this->request);
    }

    public function getMessage(): ?string
    {
        return $this->request->message->text ?? null;
    }

    public function getTypeMessage(): ?string
    {
        return $this->request->message->type ?? null;
    }

    public function getAvatar(): ?string
    {
        return (
            $this->request->sender->avatar ??
            $this->request->user->avatar ??
            null
        );
    }

    public function sendMessage(?string $chat, string $message, array $params = []): string
    {
        if (!empty($params['buttons'])) {
            $buttons = $params['buttons'];

            $data = [
                'receiver' => $chat,
                'min_api_version' => 7,
                'type' => 'text',
                'text' => $message,
                'keyboard' => [
                    'Type' => 'keyboard',
                    'InputFieldState' => $params['input'] ?? 'hidden',
                    'DefaultHeight' => 'false',
                    'Buttons' => $buttons
                ]
            ];
        } else {
            $data = [
                'receiver' => $chat,
                'min_api_version' => 7,
                'type' => 'text',
                'text' => $message
            ];
        }

        return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
    }

    public function sendContact(?string $chat, string $name, string $phone, array $params = [])
    {
        if (!empty($params['buttons'])) {
            $buttons = $params['buttons'];

            if (empty($params['input'])) {
                $InputFieldState = "hidden";
            } else {
                $InputFieldState = $params['input'];
            }

            $data = [
                'receiver' => $chat,
                'min_api_version' => 1,
                'type' => 'contact',
                'contact' => [
                    'name' => $name,
                    'phone_number' => $phone
                ],
                'keyboard' => [
                    'Type' => 'keyboard',
                    'InputFieldState' => $InputFieldState,
                    'DefaultHeight' => 'false',
                    'Buttons' => $buttons
                ]
            ];
        } else {
            $data = [
                'receiver' => $chat,
                'min_api_version' => 1,
                'type' => 'contact',
                'contact' => [
                    'name' => $name,
                    'phone_number' => $phone
                ]
            ];
        }

        return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
    }

    public function sendImage(?string $chat, string $image, ?string $text = null, array $params = [])
    {
        if (!empty($params['buttons'])) {
            $buttons = $params['buttons'];

            $data = [
                'receiver' => $chat,
                'min_api_version' => 7,
                'type' => 'picture',
                'text' => $text,
                'media' => $image,
                'keyboard' => [
                    'Type' => 'keyboard',
                    'InputFieldState' => $params['input'] ?? 'hidden',
                    'DefaultHeight' => 'false',
                    'Buttons' => $buttons
                ]
            ];
        } else {
            $data = [
                'receiver' => $chat,
                'min_api_version' => 7,
                'type' => 'picture',
                'text' => $text,
                'media' => $image
            ];
        }
        return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
    }

    public function sendFile(?string $chat, string $media, $file_name, $size, array $params = []): string
    {
        if (!empty($params['buttons'])) {
            $buttons = $params['buttons'];

            $data = [
                'receiver' => $chat,
                'min_api_version' => 1,
                'tracking_data' => 'tracking data',
                'type' => 'file',
                'media' => $media,
                'file_name' => $file_name,
                'size' => $size,
                'keyboard' => [
                    'Type' => 'keyboard',
                    'InputFieldState' => $params['input'] ?? 'hidden',
                    'DefaultHeight' => 'false',
                    'Buttons' => $buttons
                ]
            ];
        } else {
            $data = [
                'receiver' => $chat,
                'min_api_version' => 1,
                'tracking_data' => 'tracking data',
                'type' => 'file',
                'media' => $media,
                'file_name' => $file_name,
                'size' => $size
            ];
        }
        return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
    }

    public function sendCarousel($chat, $rich_media, $buttons): string
    {
        $data = [
            'receiver' => $chat,
            'type' => 'rich_media',
            'min_api_version' => 7,
            'rich_media' => $rich_media
        ];

        if (!empty($buttons)) {
            $data['keyboard'] = [
                "Type" => "keyboard",
                'InputFieldState' => 'hidden',
                "DefaultHeight" => 'false',
                "Buttons" => $buttons
            ];
        }
        return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
    }

    public function sendMessageBroadcast(array $arrId, string $message, array $params = [])
    {
        if (!empty($params['buttons'])) {
            $buttons = $params['buttons'];

            if (empty($params['input'])) {
                $InputFieldState = "hidden";
            } else {
                $InputFieldState = $params['input'];
            }

            $data = [
                'min_api_version' => 7,
                'type' => 'text',
                'text' => $message,
                'broadcast_list' => [
                    implode('","', $arrId)
                ],
                'keyboard' => [
                    'Type' => 'keyboard',
                    'InputFieldState' => $InputFieldState,
                    'DefaultHeight' => 'false',
                    'Buttons' => $buttons
                ]
            ];
        } else {
            $data = [
                'min_api_version' => 7,
                'type' => 'text',
                'text' => $message,
                'broadcast_list' => [
                    implode('","', $arrId)
                ]
            ];
        }
        return $this->makeRequest("https://chatapi.viber.com/pa/broadcast_message", $data);
    }

    public function getUserInfo($chat): string
    {
        return $this->makeRequest("https://chatapi.viber.com/pa/get_user_details", [
            'id' => $chat
        ]);
    }

    public function setWebhook($url): string
    {
        return $this->makeRequest("https://chatapi.viber.com/pa/set_webhook", [
            "url" => $url,
            "event_types" => [
                //"delivered",
                //"seen",
                "failed",
                "subscribed",
                "unsubscribed",
                "conversation_started"
            ],
            "send_name" => 'true',
            "send_photo" => 'true'
        ]);
    }

    public function deleteWebhook()
    {
        return $this->makeRequest("https://chatapi.viber.com/pa/set_webhook", [
            "url" => ""
        ]);
    }

    public function getWebhook(): string
    {
        return '';
    }

    private function makeRequest($url, $data)
    {
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Cache-Control: no-cache',
                'Content-Type: application/JSON',
                'X-Viber-Auth-Token: ' . $this->token
            ]
        );
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
