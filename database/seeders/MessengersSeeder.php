<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessengersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messengers')->insert([
            ['id' => 1, 'name' => 'Telegram'],
            ['id' => 2, 'name' => 'Viber'],
            ['id' => 3, 'name' => 'Facebook']
        ]);
    }
}
