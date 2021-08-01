<?php


namespace App\Models\buttons;


use App\Models\Language;

class Pages {
    public static function get($messenger, $data, $page, $method, $count = [
        'viber' => 38,
        'telegram' => 8
    ], $params = [
        'id' => 'id',
        'name' => 'name'
    ]): array
    {
        if (is_string($data)) {
            $data = $data::all()->toArray();
        }

        if ($messenger == 'Viber') {
            $data = array_chunk($data, $count['viber']);
            $countPages = count($data);
            $viber = new ButtonsViber;
            $buttons = [];
            foreach ($data[$page - 1] as $d) {
                $buttons[] = $viber->button(6, 1, $method . "__" . $d[$params['id']], $d[$params['name']]);
            }

            return $buttons;
        } else {
            $data = array_chunk($data, $count['telegram']);
            $countPages = count($data);
            return [];
        }
    }
}

Pages::get('Viber', Language::class, 1, 'lang');
