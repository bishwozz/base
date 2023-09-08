<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Database\Seeders\StatusSeeder;

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

        $this->call(CoreTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        // $this->call(MinistrySeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(DateSettingSeeder::class);
        // $this->call(StatusSeeder::class);
    }
}
