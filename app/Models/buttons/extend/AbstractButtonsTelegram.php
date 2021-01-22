<?php

namespace App\Models\buttons\extend;

class AbstractButtonsTelegram {
    public function start() {
        return [
            ["start"]
        ];
    }

    public function back() {
        return [
            ["{back}"]
        ];
    }

    public function getPhone() {
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

    public function getLocation() {
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
