<?php

namespace Database\Seeders;

use App\Models\SettingsButtons;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsButtonsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            ["id" => "1","name" => "start","text" => "U3RhcnQg8J+agA==","menu" => "Старт","menu_us" => "Start"],
            ["id" => "2","name" => "back","text" => "8J+UmSDQndCw0LfQsNC0","menu" => "back","menu_us" => "back"],
            ["id" => "3","name" => "contacts","text" => "4pyJINCf0L7QtNC00LXRgNC20LrQsA==","menu" => "Главное меню","menu_us" => "Main menu"],
            ["id" => "4","name" => "contacts_general","text" => "0J7QsdGJ0LjQtSDQstC+0L/RgNC+0YHRiw==","menu" => "Поддержка","menu_us" => "Support"],
            ["id" => "5","name" => "contacts_access","text" => "0JTQvtGB0YLRg9C/INC4INC+0L/Qu9Cw0YLQsA==","menu" => "Поддержка","menu_us" => "Support"],
            ["id" => "6","name" => "contacts_advertising","text" => "0KDQtdC60LvQsNC80LA=","menu" => "Поддержка","menu_us" => "Support"],
            ["id" => "7","name" => "contacts_offers","text" => "0J/RgNC10LTQu9C+0LbQtdC90LjRjw==","menu" => "Поддержка","menu_us" => "Support"],
            ["id" => "8","name" => "languages","text" => "8J+RhSDQr9C30YvQutC4","menu" => "Главное меню","menu_us" => "Main menu"],
            ["id" => "9","name" => "payment","text" => "8J+SuCDQntC/0LvQsNGC0LA=","menu" => "Оплата","menu_us" => "Payment"]
        ];

        $dataFile = \App\Models\Seeder::getButtons();

        $seeder = (count($dataFile) > count($data)) ? $dataFile : $data;

        DB::table('settings_buttons_ru')->insert($seeder);

        $settingsButtons = SettingsButtons::all();
        foreach($settingsButtons as $sb) {
            $res[$sb->name] = base64_decode($sb->text);
        }
        file_put_contents(public_path("json/buttons.json"), json_encode($res));
    }
}
