<?php

namespace App\Models\buttons;

/**
 * @method static languages()
 * @method static contacts()
 */
class RichMedia {
    public static function __callStatic($name, $arguments): array
    {
        $richMediaButtons = new RichMediaButtons();
        if (method_exists($richMediaButtons, $name)) {
            return $richMediaButtons->$name($arguments);
        } else {
            return [];
        }
    }
}
