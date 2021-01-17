<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactsTypeSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('contacts_type')->insert([
            ["id" => "1","type" => "general"],
            ["id" => "2","type" => "access"],
            ["id" => "3","type" => "adversting"],
            ["id" => "4","type" => "offers"]
        ]);
    }
}
