<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
        $now = Carbon::now()->toDateTimeString();
        $this->time = $now;

        $this->call(RoleTableSeeder::class);
        $this->call(CoreTableSeeder::class);
        $this->call(SecondaryMasterSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(DateSettingSeeder::class);
        $this->call(AppSettingSeeder::class);
        $this->call(AgendaDecisionTypeSeeder::class);
    }
}
