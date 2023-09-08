<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();

        DB::table('users')->insert([
            array('id' => 1, 'name' => 'System Admin', 'email' => 'super@gmail.com','password' => \Hash::make('1'),'created_at'=>$now),
            array('id' => 2, 'name' => 'Admin', 'email' => 'admin@gmail.com','password' => \Hash::make('123456'),'created_at'=>$now),
            array('id' => 3, 'name' => 'User', 'email' => 'user@gmail.com','password' => \Hash::make('123456'),'created_at'=>$now),
        ]);

        DB::statement("SELECT SETVAL('users_id_seq',100)");


        //call artisan commands
        Artisan::call('generate:permissions');
        Artisan::call('disable:backpack_pro');

        $permissions = Permission::all();
        $super_admin_role = Role::find(1);
        $admin_role =Role::find(2);
        $user_role =Role::find(3);


        $super_admin_role->givePermissionTo($permissions);
        $admin_role->givePermissionTo($permissions);
        $user_role->givePermissionTo($permissions);

        //assign role for superadmin
        $user = User::findOrFail(1);
        $user_admin = User::findOrFail(2);
        $main_users = User::findOrFail(3);

        $user->assignRoleCustom("superadmin", $user->id);
        $user->assignRoleCustom("admin", $user_admin->id);
        $user->assignRoleCustom("user", $main_users->id);
   

        DB::table('app_settings')->insert([
            array('id' => 1, 'fiscal_year_id' => 3, 'office_name_lc' => 'superadmin','created_at'=>$now),
        ]);

        DB::statement("SELECT SETVAL('app_settings_id_seq',10)");
    }
}
