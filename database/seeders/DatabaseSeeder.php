<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(ContactsTypeSeeder::class);
        $this->call(SettingsButtonsSeeder::class);
        $this->call(SettingsPagesSeeder::class);
        $this->call(SettingsMainSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(TodoStatusSeeder::class);
        $this->call(MessengersSeeder::class);
    }
}
