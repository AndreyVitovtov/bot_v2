<?php

namespace App\Models\buttons;

use App\Models\buttons\extend\AbstractButtonsViber;
use App\Models\Language;

class RichMediaButtons extends AbstractButtonsViber {

    public function contacts(): array
    {
        return [
            $this->button(6, 1, 'general', '{contacts_general}'),
            $this->button(6, 1, 'access', '{contacts_access}'),
            $this->button(6, 1, 'advertising', '{contacts_advertising}'),
            $this->button(6, 1, 'offers', '{contacts_offers}'),
        ];
    }

    public function languages(): array
    {
        $buttonsLanguages = [];
        $languages = Language::all()->toArray();
        foreach ($languages as $language) {
            $buttonsLanguages[] = $this->button(6, 1, 'selectLanguage__' . $language['id'],
                base64_decode($language['emoji']) . " " . $language['name']);
        }
        return $buttonsLanguages;
    }
}
