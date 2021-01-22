<?php

namespace App\Models\buttons;

use App\Models\Language;

class InlineButtons {

    public static function contacts() {
        return [
            [
                [
                    "text" => "{contacts_general}",
                    "callback_data" => "general"
                ], [
                    "text" => "{contacts_access}",
                    "callback_data" => "access"
                ]
            ], [
                [
                    "text" => "{contacts_advertising}",
                    "callback_data" => "advertising"
                ], [
                    "text" => "{contacts_offers}",
                    "callback_data" => "offers"
                ]
            ]
        ];
    }

    public static function languages() {
        $languages = [
            'text' => DEFAULT_LANGUAGE,
            'callback_data' => 'lang__0'
        ];
        $lang = Language::all()->toArray();
        foreach ($lang as $l) {
            $languages[] = [
                'text' => $l['name'],
                'callback_data' => 'lang__' . $l['code']
            ];
        }
        return [$languages];
    }


}
