<?php

namespace Database\Seeders;

use App\Models\SettingsPages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsPagesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $data = [
            ["id" => "1","name" => "greeting","text" => "0J3QsNC20LzQuNGC0LUgU3RhcnQg8J+agCDQtNC70Y8g0L/RgNC+0LTQvtC70LbQtdC90LjRjw==","description" => "Приветствие","description_us" => "Greeting"],
            ["id" => "2","name" => "welcome","text" => "0J/RgNC40LLQtdGC0YHRgtCy0LjQtSDQsdC+0YLQsA==","description" => "Приветствие","description_us" => "Greeting"],
            ["id" => "3","name" => "main_menu","text" => "0JPQu9Cw0LLQvdC+0LUg0LzQtdC90Y4=","description" => "Главное меню","description_us" => "Main menu"],
            ["id" => "4","name" => "unknown_team","text" => "0J3QtdC40LfQstC10YHRgtC90LDRjyDQutC+0LzQsNC90LTQsCDwn5qn","description" => "Неизвестная команда","description_us" => "Unknown team"],
            ["id" => "5","name" => "error","text" => "0KfRgtC+INGC0L4g0L/QvtGI0LvQviDQvdC1INGC0LDQuigoINCf0L7Qv9GA0L7QsdGD0LnRgtC1INC/0L7Qt9C20LVcblxue3tlcnJvcn19","description" => "Ошибка","description_us" => "Error"],
            ["id" => "6","name" => "send_support_message","text" => "0J7RgtC/0YDQsNCy0LjRgtGMINGB0L7QvtCx0YnQtdC90LjQtSDQsiDQv9C+0LTQtNC10YDQttC60YM=","description" => "Отправить сообщение в поддержку","description_us" => "Send a message to support"],
            ["id" => "7","name" => "select_topic","text" => "0JLRi9Cx0LXRgNC40YLQtSDRgtC10LzRgzo=","description" => "Выберите тему","description_us" => "Choose a topic"],
            ["id" => "8","name" => "send_message","text" => "0J7RgtC/0YDQsNCy0YzRgtC1INGB0L7QvtCx0YnQtdC90LjQtQ==","description" => "Отправьте сообщение","description_us" => "Send a message"],
            ["id" => "9","name" => "message_sending","text" => "0KHQvtC+0LHRidC10L3QuNC1INC+0YLQv9GA0LDQstC70LXQvdC+","description" => "Сообщение отправлено","description_us" => "Message sent"],
            ["id" => "10","name" => "choose_language","text" => "0JLRi9Cx0LXRgNC40YLQtSDRj9C30YvQug==","description" => "Выберите язык","description_us" => "Choose language"],
            ["id" => "11","name" => "language_saved","text" => "0K/Qt9GL0Log0YHQvtGF0YDQsNC90LXQvQ==","description" => "Язык сохранен","description_us" => "Language saved"],
            ["id" => "12","name" => "payment_header","text" => "0J7Qv9C70LDRgtCw","description" => "Шапка страницы Оплата","description_us" => "Payment page header"],
            ["id" => "13","name" => "payment_title","text" => "0JLRi9Cx0LXRgNC40YLQtSDRgdC/0L7RgdC+0LEg0L7Qv9C70LDRgtGLOg==","description" => "Заголовок страницы Оплата","description_us" => "Payment page title"],
            ["id" => "14","name" => "payment_sum","text" => "0KHRg9C80LzQsDo=","description" => "Сумма, страница Оплата","description_us" => "Amount, page Payment"],
            ["id" => "15","name" => "payment_next","text" => "0JTQsNC70LXQtQ==","description" => "Далее, страница Оплата","description_us" => "Next, the Payment page"],
            ["id" => "16","name" => "payment_details","text" => "0JTQsNC90L3Ri9C1INC00LvRjyDQvtC/0LvQsNGC0Ys6","description" => "Данные для оплаты, страница Оплата","description_us" => "Payment data, page Payment"],
            ["id" => "17","name" => "payment_method","text" => "0KHQv9C+0YHQvtCxINC+0L/Qu9Cw0YLRizo=","description" => "Способ оплаты, страница Оплата","description_us" => "Payment method, page Payment"],
            ["id" => "18","name" => "payment_purpose","text" => "0J3QsNC30L3QsNGH0LXQvdC40LU6","description" => "Назначение, страница Оплата","description_us" => "Purpose, page Payment"],
            ["id" => "19","name" => "payment_pay","text" => "0J7Qv9C70LDRgtC40YLRjA==","description" => "Оплатить, страница Оплата","description_us" => "Pay, page Payment"],
            ["id" => "20","name" => "payment_currency","text" => "UlVC","description" => "Валюта, страница Оплата","description_us" => "Currency, page Payment"]
        ];

        $dataFile = \App\Models\Seeder::getPages();

        $seeder = (count($dataFile) > count($data)) ? $dataFile : $data;

        DB::table('settings_pages_ru')->insert($seeder);

        $settingsPages = SettingsPages::all();
        foreach($settingsPages as $sp) {
            $res[$sp->name] = base64_decode($sp->text);
        }
        file_put_contents(public_path("json/pages.json"), json_encode($res));
    }
}
