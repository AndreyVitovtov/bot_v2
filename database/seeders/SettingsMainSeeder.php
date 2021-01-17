<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsMainSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('settings_main')->insert([
            ["id" => "1","prefix" => "viber_token","name" => "Viber token:","name_us" => "Viber token:","value" => "NULL","type" => "text"],
            ["id" => "2","prefix" => "name_viber_bot","name" => "Название Viber бота:","name_us" => "Viber bot name:","value" => "NULL","type" => "text"],
            ["id" => "3","prefix" => "telegram_token","name" => "Telegram token:","name_us" => "Telegram token:","value" => "NULL","type" => "text"],
            ["id" => "4","prefix" => "name_telegram_bot","name" => "Название Telegram бота:","name_us" => "Telegram bot name:","value" => "NULL","type" => "text"],
            ["id" => "5","prefix" => "default_language","name" => "Язык по умолчанию:","name_us" => "Default language:","value" => "🇷🇺 Русский","type" => "text"],
            ["id" => "6","prefix" => "count_mailing","name" => "По сколько сообщений рассылать за один раз:","name_us" => "How many messages to send at one time:","value" => "200","type" => "number"],
            ["id" => "7","prefix" => "sleep_mailing","name" => "Задержка между рассылками, секунд:","name_us" => "Delay between mailings, seconds:","value" => "2","type" => "number"]
        ]);
    }
}
