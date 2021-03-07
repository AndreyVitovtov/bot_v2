<?php


namespace App\Models\buttons;


class ButtonsFacebook {
    public static function main_menu(): array
    {
        return [
            [
                'type' => 'postback',
                'title' => '{add_subscription_2}',
                'payload' => 'add_subscription'
            ], [
                'type' => 'postback',
                'title' => '{add_subscription_2}',
                'payload' => 'add_subscription'
            ], [
                'type' => 'postback',
                'title' => '{add_subscription_2}',
                'payload' => 'add_subscription'
            ]
        ];
    }

    public function back(): array
    {
        return [
            [
                'type' => 'postback',
                'title' => '{back}',
                'payload' => 'back'
            ]
        ];
    }

    public static function contacts(): array
    {
        return [
            [
                'content_type' => 'text',
                'title' => '{contacts_general}',
                'payload' => 'general'
            ], [
                'content_type' => 'text',
                'title' => '{contacts_access}',
                'payload' => 'access'
            ], [
                'content_type' => 'text',
                'title' => '{contacts_advertising}',
                'payload' => 'advertising'
            ], [
                'content_type' => 'text',
                'title' => '{contacts_offers}',
                'payload' => 'offers'
            ]
        ];
    }

    private static function pagesButtons($res, $methodPages, $method, $name = 'name', $page): array
    {
        $count = 400;
        $buttons = [];

        foreach($res as $o) {
            $buttons[] = [
                'content_type' => 'text',
                'title' => $o->$name,
                'payload' => $method.'__'.$o->id
            ];
        }

        $countPage = ceil($count / 8);

        $nextPage = (int) $page+1;
        $prewPage = (int) $page-1;

        if($countPage > 1) {
            if($page == 1) {
                $buttons[] = [
                    'content_type' => 'text',
                    'title' => '{next_buttons}',
                    'payload' => $methodPages.'__'.$nextPage
                ];
            }
            elseif($page == $countPage) {
                array_unshift($buttons, [
                    'content_type' => 'text',
                    'title' => '{prew_buttons}',
                    'payload' => $methodPages.'__'.$prewPage
                ]);
            }
            else {
                array_unshift($buttons, [
                    'content_type' => 'text',
                    'title' => '{prew_buttons}',
                    'payload' => $methodPages.'__'.$prewPage
                ]);
                $buttons[] = [
                    'content_type' => 'text',
                    'title' => '{next_buttons}',
                    'payload' => $methodPages.'__'.$nextPage
                ];
            }
        }

        return $buttons;
    }
}
