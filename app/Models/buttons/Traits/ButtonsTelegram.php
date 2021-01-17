<?php

namespace App\Models\buttons\Traits;

trait ButtonsTelegram {

    public static function main_menu() {
        return [
            ['{main_menu}']
        ];
    }

    public static function start() {
        return [
            ["start"]
        ];
    }

    public static function back() {
        return [
            ["{back}"]
        ];
    }
}
