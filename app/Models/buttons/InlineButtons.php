<?php

namespace App\Models\buttons;

use App\Models\Language;

class InlineButtons {

    public static function contacts(): array
    {
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

    public static function languages(): array
    {
        $buttonsLanguages = [];
        $languages = Language::all()->toArray();
        foreach ($languages as $language) {
            $buttonsLanguages[] = [[
                'text' => base64_decode($language['emoji']) . " " . $language['name'],
                'callback_data' => 'selectLanguage__' . $language['id']
            ]];
        }
        return $buttonsLanguages;
    }
}
