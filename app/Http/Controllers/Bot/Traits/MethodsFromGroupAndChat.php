<?php


namespace App\Http\Controllers\Bot\Traits;


trait MethodsFromGroupAndChat {

    public function methodFromGroupAndChat() {
        $type = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $this->getType()))));
        if (method_exists($this, $type)) {
            $this->$type();
        } else {
            $this->groupAndChatUnknownTeam();
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

    public function groupAndChatUnknownTeam() {
    }
}
