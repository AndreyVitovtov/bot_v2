<?php

namespace App\Models\buttons\extend;

class AbstractButtonsTelegram {
    public function start(): array
    {
        return [
            ["start"]
        ];
    }

    public function back(): array
    {
        return [
            ["{back}"]
        ];
    }

    public function getPhone(): array
    {
        return [
            [
                [
                    'text' => '{send_phone}',
                    'request_contact' => true
                ],
                "{back}"
            ]
        ];
    }

    public function getLocation(): array
    {
        return [
            [
                [
                    'text' => '{send_location}',
                    'request_location' => true
                ],
                "{back}"
            ]
        ];
    }
}
