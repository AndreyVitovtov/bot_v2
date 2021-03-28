<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TodoStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('todo_status')->insert([
            ['id' => 1, 'name' => 'make'],
            ['id' => 2, 'name' => 'work'],
            ['id' => 3, 'name' => 'performed']
        ]);
    }
}
