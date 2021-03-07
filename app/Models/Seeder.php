<?php

namespace App\Models;

class Seeder
{
    public static function setMain()
    {
        file_put_contents(public_path('json/seeders/settingsMain.json'), SettingsMain::all()->toJson());
    }

    public static function setPages()
    {
        file_put_contents(public_path('json/seeders/settingsPages.json'), SettingsPages::all()->toJson());
    }

    public static function setButtons()
    {
        file_put_contents(public_path('json/seeders/settingsButtons.json'), SettingsButtons::all()->toJson());
    }

    public static function getMain()
    {
        return json_decode(file_get_contents(public_path('json/seeders/settingsMain.json')), true);
    }

    public static function getPages()
    {
        return json_decode(file_get_contents(public_path('json/seeders/settingsPages.json')), true);
    }

    public static function getButtons()
    {
        return json_decode(file_get_contents(public_path('json/seeders/settingsButtons.json')), true);
    }
}
