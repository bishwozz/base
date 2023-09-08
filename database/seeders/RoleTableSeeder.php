<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1,'name' => 'superadmin', 'field_name' => 'Super Admin', 'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'name' => 'admin', 'field_name' => 'व्यवस्थापक', 'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 3,'name' => 'minister', 'field_name' => 'मन्त्री', 'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 4,'name' => 'chief_secretary', 'field_name' => 'प्रमुख सचिव', 'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 5,'name' => 'cabinet_approver', 'field_name' => 'मुख्य मन्त्रि कार्यालय रिभ्युअर', 'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 6,'name' => 'cabinet_creator','field_name' => 'मुख्य मन्त्रि कार्यालय अपरेटर',  'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 7,'name' => 'ministry_secretary','field_name' => 'मन्त्रालय सचिव',  'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 8,'name' => 'ministry_reviewer','field_name' => ' मन्त्रालय रिभ्युअर',  'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 9,'name' => 'ministry_creator','field_name' => ' मन्त्रालय अपरेटर',  'guard_name' => 'backpack','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]);

        DB::statement("SELECT SETVAL('roles_id_seq',10)");

    }
}
