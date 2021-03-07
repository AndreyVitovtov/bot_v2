<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Text extends Model {
    public static function valueSubstitution($user, $str, $type, $n = []) {
        $language = Language::find($user->languages_id ?? 1);
        if(file_exists(public_path()."/json/".$type."_".($language->code ?? 'ru').".json")) {
            $type = $type."_".($language->code ?? 'ru');
        }
        if(preg_match_all('/{([^}]*)}/', $str, $matches)) {
            $textName = file_get_contents(public_path("json/{$type}.json"));
            $textName = json_decode($textName, true);

            foreach($matches[1] as $word) {
                if(!empty($textName[$word])) {
                    $text = $textName[$word];
                    $str = str_replace("{".$word."}", stripcslashes($text), $str);
                }
            }
        }
        if(preg_match_all('/{{([^}]*)}}/', $str, $matches)) {
            foreach($matches[1] as $word) {
                if(isset($n[$word])) {
                    $str = str_replace("{{".$word."}}", $n[$word], $str);
                }
            }
        }
        return $str;
    }

    public static function valueSubstitutionArray($user, $array, $n = []): array
    {
        $new_array = [];
        foreach($array as $key => $item) {
            if(is_array($item)) {
                $new_array[$key] = self::valueSubstitutionArray($user, $item, $n);
            }
            else {
                $new_array[$key] = self::valueSubstitution($user, $item, "buttons", $n);
            }
        }

        return $new_array;
    }
}
