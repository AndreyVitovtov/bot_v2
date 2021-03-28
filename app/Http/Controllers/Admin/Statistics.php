<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Statistics extends Controller
{

    public function index()
    {


        $view = view('admin.statistics.statistics');
        $view->menuItem = "statistics";

        $visitsJson = file_get_contents(public_path("json/visits.json"));
        $visitsArray = json_decode($visitsJson, true);

        $statistics = new Statistic();

        //Статистика по визитам
        $date = [];
        for ($i = 9; $i >= 0; $i--) {
            $date[] = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - $i, date('Y')));
        }
        $visits = [];
        foreach ($date as $d) {
            if (isset($visitsArray[$d])) {
                $count = count($visitsArray[$d]);
            } else {
                $count = 0;
            }
            $visits[] = [
                $d, $count
            ];
        }

        //Статистика по странам
        $country = DB::select("SELECT country, COUNT(*) AS count FROM users WHERE country <> ''
           AND start = 1
           GROUP BY country");
        if (App::getLocale() == "ru") {
            $ISO = json_decode(file_get_contents(public_path("json/ISO_3166-1_alpha-2.json")), true);
        } else {
            $ISO = json_decode(file_get_contents(public_path("json/ISO_3166-1_alpha-2_us.json")), true);
        }
        $countries = [];
        foreach ($country as $c) {
            $countries[] = [
                $ISO[$c->country], $c->count
            ];
        }
        if (empty($countries)) {
            $countries[] = [
                'no users from countries', 1
            ];
        }

        //Статистика по мессенджерам
        $messenger = DB::select("SELECT messengers.name, COUNT(*) as count
            FROM users JOIN messengers ON users.messengers_id = messengers.id
            AND start = 1 GROUP BY messengers.name");
        $messengers = [];
        foreach ($messenger as $m) {
            $messengers[$m->messenger] = $m->count;
        }
        if (empty($messengers)) {
            $messengers = [
                'Viber' => 0,
                'Telegram' => 0
            ];
        } else {
            $messengers['Viber'] = $messengers['Viber'] ?? 0;
            $messengers['Telegram'] = $messengers['Telegram'] ?? 0;
        }

        $messenger = DB::select("SELECT messengers.name, COUNT(*) as count
            FROM users
            JOIN messengers ON users.messengers_id = messengers.id
            WHERE unsubscribed = 1 GROUP BY messengers.name");
        foreach ($messenger as $m) {
            $messengers[$m->messenger . '_U'] = $m->count;
        }
        if (empty($messengers)) {
            $messengers = [
                'Viber_U' => 0,
                'Telegram_U' => 0
            ];
        } else {
            $messengers['Viber_U'] = $messengers['Viber_U'] ?? 0;
            $messengers['Telegram_U'] = $messengers['Telegram_U'] ?? 0;
        }

        $messenger = DB::select("SELECT COUNT(*) as count
            FROM users WHERE start = 0 AND unsubscribed = 0");

        $messengers['not_start'] = $messenger[0]->count ?? 0;

        //Статистика по доступу
        $accessNo = DB::select("SELECT COUNT(*) AS count FROM users WHERE access = '0'");
        $accessPaid = DB::select("SELECT COUNT(*) AS count FROM users WHERE access = '1' AND access_free = '0'");
        $accessFree = DB::select("SELECT COUNT(*) AS count FROM users WHERE access = '1' AND access_free = '1'");
        $access = [
            'no' => $accessNo[0]->count,
            'paid' => $accessPaid[0]->count,
            'free' => $accessFree[0]->count
        ];


        $statistics->countries = $countries;
        $statistics->messengers = $messengers;
        $statistics->visits = $visits;
        $statistics->access = $access;

        $view->statistics = $statistics;

        return $view;
    }
}
