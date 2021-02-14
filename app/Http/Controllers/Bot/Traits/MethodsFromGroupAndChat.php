<?php


namespace App\Http\Controllers\Bot\Traits;


trait MethodsFromGroupAndChat {
    public function methodFromGroupAndChat() {
        $type = $this->getType();
        $type = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $type))));
        if(method_exists($this, $type)) {
            $this->$type();
        }
    }

    public function newChatParticipant() {
        dd($this->getDataByType());
    }

    public function leftChatParticipant() {
        dd($this->getDataByType());
    }

    public function callbackQuery() {
        dd($this->getDataByType());
    }
}
