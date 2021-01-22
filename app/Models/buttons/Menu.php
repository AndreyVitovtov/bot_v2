<?php

namespace App\Models\buttons;

/**
 * @method static main(string $param)
 */
class Menu {
    public static function __callStatic(string $name, array $arguments) {
        $className = 'App\Models\buttons\Buttons' . (defined('MESSENGER') ? MESSENGER : $arguments['messenger']);
        if (method_exists($className, $name)) {
            $buttons = new $className;
            return $buttons->$name($arguments);
        } else {
            $menu = [];
            if (file_exists($path = public_path('json/menu/' . $name . '.json'))) {
                $messenger = (defined('MESSENGER') ? MESSENGER : $arguments['messenger']);
                $menu = json_decode(file_get_contents($path));
                if ($messenger == 'Telegram') {
                    array_walk($menu, function (&$item) {
                        if (is_array($item)) {
                            array_walk($item, function (&$i) {
                                $i = "{" . $i . "}";
                            });
                        } else {
                            $item = "{" . $item . "}";
                        }
                    });
                } elseif ($messenger == 'Viber') {
                    $viber = new ButtonsViber();
                    foreach($menu as $m) {
                        if(is_array($m)) {
                            $columns = 6 / count($m);
                            foreach($m as $mi) {
                                $menuViber[] = $viber->button($columns, 1, $mi, "{" . $mi . "}");
                            }
                        }
                        else {
                            $menuViber[] = $viber->button(6, 1, $m, "{" . $m . "}");
                        }
                    }
                }
            }
            return $menuViber ?? $menu;
        }
    }
}
