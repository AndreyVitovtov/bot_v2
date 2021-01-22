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
}
