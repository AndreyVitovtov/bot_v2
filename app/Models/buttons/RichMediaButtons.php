<?php

namespace App\Models\buttons;

use App\Models\buttons\extend\AbstractButtonsViber;
use App\Models\Language;

class RichMediaButtons extends AbstractButtonsViber {

    public function contacts() {
        return [
            $this->button(6, 1, 'general', '{contacts_general}'),
            $this->button(6, 1, 'access', '{contacts_access}'),
            $this->button(6, 1, 'advertising', '{contacts_advertising}'),
            $this->button(6, 1, 'offers', '{contacts_offers}'),
        ];
    }

    public function languages() {
        $languages = $this->button(6, 1, 'lang__0', DEFAULT_LANGUAGE);
        $lang = Language::all()->toArray();
        foreach ($lang as $l) {
            $languages[] = $this->button(6, 1, 'lang__' . $l['code'], $l['name']);
        }
        return $languages;
    }

    public function get($messenger, $data, $page, $method, $count = [
        'viber' => 38,
        'telegram' => 8
    ], $params = [
        'id' => 'id',
        'name' => 'name'
    ]) {
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
