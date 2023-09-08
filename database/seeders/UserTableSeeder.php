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
            // super user
            array('id' => 1, 'ministry_id'=> null,  'committee_id'=> null, 'name' => 'System Admin', 'email' => 'super_admin@gmail.com','password' => \Hash::make('Admin@1234'),'display_order'=>1,'created_at'=>$now),
            
            // admin user
            array('id' => 2, 'ministry_id'=> null, 'committee_id'=> null,'name' => 'Admin', 'email' => 'admin@gmail.com','password' => \Hash::make('123456'),'display_order'=>1,'created_at'=>$now),

            // ministry users
            // array('id' => 3, 'ministry_id'=> 1, 'committee_id'=> null, 'name' => 'Office of Chief Minister and Council of Minister (OCMCM)', 'email' => 'ocmcm@gmail.com','password' => \Hash::make('123456'),'display_order'=>2,'created_at'=>$now),
            // array('id' => 4, 'ministry_id'=> 2, 'committee_id'=> null, 'name' => 'Ministyr of Tourism, Rural and Urban Development (MORUD)', 'email' => 'morud@gmail.com','password' => \Hash::make('123456'),'display_order'=>3,'created_at'=>$now),
            // array('id' => 5, 'ministry_id'=> 3, 'committee_id'=> null, 'name' => 'Ministyr of Finance and Cooperatives (MOEAP)', 'email' => 'moeap@gmail.com','password' => \Hash::make('123456'),'display_order'=>4,'created_at'=>$now),
            // array('id' => 6, 'ministry_id'=> 4, 'committee_id'=> null, 'name' => 'Ministyr of Physical Infrastructure Development (MOPID)', 'email' => 'mopid@gmail.com','password' => \Hash::make('123456'),'display_order'=>5,'created_at'=>$now),
            // array('id' => 7, 'ministry_id'=> 5, 'committee_id'=> null, 'name' => 'Ministyr of Internal Affairs and Law (MOIAL)', 'email' => 'moial@gmail.com','password' => \Hash::make('123456'),'display_order'=>6,'created_at'=>$now),
            // array('id' => 8, 'ministry_id'=> 6, 'committee_id'=> null, 'name' => 'Ministyr of Health (MOHP)', 'email' => 'mphp@gmail.com','password' => \Hash::make('123456'),'display_order'=>7,'created_at'=>$now),
            // array('id' => 9, 'ministry_id'=> 7, 'committee_id'=> null, 'name' => 'Ministyr of Energy, Water Resources and Irrigation (MOEWRI)', 'email' => 'moewri@gmail.com','password' => \Hash::make('123456'),'display_order'=>8,'created_at'=>$now),
            // array('id' => 10, 'ministry_id'=> 8, 'committee_id'=> null, 'name' => 'Ministyr of Industry, Tourism, Forest and Environment (MOITFE)', 'email' => 'moitfe@gmail.com','password' => \Hash::make('123456'),'display_order'=>9,'created_at'=>$now),
            // array('id' => 11, 'ministry_id'=> 9, 'committee_id'=> null, 'name' => 'Ministry of Industry, Commerce and Supplies (MOICS)', 'email' => 'moics@gmail.com','password' => \Hash::make('123456'),'display_order'=>10,'created_at'=>$now),
            // array('id' => 12, 'ministry_id'=> 10, 'committee_id'=> null, 'name' => 'Ministry of Women, Children and Senior Citizens (MOWCSC)', 'email' => 'mowcsc@gmail.com','password' => \Hash::make('123456'),'display_order'=>11,'created_at'=>$now),
            // array('id' => 13, 'ministry_id'=> 11, 'committee_id'=> null, 'name' => 'Ministry of Education and Sports (MOSD)', 'email' => 'mosd@gmail.com','password' => \Hash::make('123456'),'display_order'=>12,'created_at'=>$now),
            // array('id' => 14, 'ministry_id'=> 12, 'committee_id'=> null, 'name' => 'Ministry of Agriculture and Land Management (MOLMAC)', 'email' => 'molmac@gmail.com','password' => \Hash::make('123456'),'display_order'=>13,'created_at'=>$now),
            // array('id' => 15, 'ministry_id'=> 13, 'committee_id'=> null, 'name' => 'Ministry of Labour and Transport (MOLETM)', 'email' => 'moletm@gmail.com','password' => \Hash::make('123456'),'display_order'=>14,'created_at'=>$now),
           
            // committee users
            // array('id' => 16, 'ministry_id'=> null, 'committee_id'=> 1, 'name' => 'म.प. राजनीति समिति', 'email' => 'committee_one@gmail.com','password' => \Hash::make('123456'),'display_order'=>15,'created_at'=>$now),
            // array('id' => 17, 'ministry_id'=> null, 'committee_id'=> 2, 'name' => 'सामाजिक तथा पुर्बधार समिति', 'email' => 'committee_two@gmail.com','password' => \Hash::make('123456'),'display_order'=>16,'created_at'=>$now),
            // array('id' => 18, 'ministry_id'=> null, 'committee_id'=> 3, 'name' => 'प्रशासन तथा विधयेक समिति', 'email' => 'committee_three@gmail.com','password' => \Hash::make('123456'),'display_order'=>17,'created_at'=>$now),

            // array('id' => 19, 'ministry_id'=> null, 'committee_id'=> null, 'name' => 'Chief Secretary', 'email' => 'chiefsecretary@gmail.com','password' => \Hash::make('123456'),'display_order'=>17,'created_at'=>$now),
            // array('id' => 20, 'ministry_id'=> null, 'committee_id'=> null, 'name' => 'Secretary', 'email' => 'secretary@gmail.com','password' => \Hash::make('123456'),'display_order'=>18,'created_at'=>$now),

            // array('id' => 10, 'ministry_id'=> null, 'mp_id'=> 1, 'name' => 'श्री त्रिलोचन भट्ट', 'email' => 'trilochan.bhatta@gmail.com','password' => \Hash::make('123456'),'display_order'=>9,'created_at'=>$now),
            // array('id' => 11, 'ministry_id'=> null, 'mp_id'=> 2, 'name' => 'श्री दीर्घ बहादुर सोडारी', 'email' => 'dirga.sodari@gmail.com','password' => \Hash::make('123456'),'display_order'=>10,'created_at'=>$now),
            // array('id' => 12, 'ministry_id'=> null, 'mp_id'=> 3, 'name' => 'श्री डा.रण बहादुर रावल', 'email' => 'dr.rawal@gmail.com','password' => \Hash::make('123456'),'display_order'=>11,'created_at'=>$now),
            // array('id' => 13, 'ministry_id'=> null, 'mp_id'=> 4, 'name' => 'श्री मान बहादुर धामी', 'email' => 'manbahadur.dhami@gmail.com','password' => \Hash::make('123456'),'display_order'=>12,'created_at'=>$now),
            // array('id' => 14, 'ministry_id'=> null, 'mp_id'=> 5, 'name' => 'श्री विनिता देवी चौधरी', 'email' => 'binita.chaudhary@gmail.com','password' => \Hash::make('123456'),'display_order'=>13,'created_at'=>$now),
            // array('id' => 15, 'ministry_id'=> null, 'mp_id'=> 6, 'name' => 'श्री प्रकाश रावल', 'email' => 'prakash.rawal@gmail.com','password' => \Hash::make('123456'),'display_order'=>14,'created_at'=>$now),
            // array('id' => 16, 'ministry_id'=> null, 'mp_id'=> 7, 'name' => 'श्री गोविन्द राज बोहरा', 'email' => 'govinda.bohara@gmail.com','password' => \Hash::make('123456'),'display_order'=>15,'created_at'=>$now),
        ]);

        DB::statement("SELECT SETVAL('users_id_seq',10)");


        //call artisan commands
        Artisan::call('generate:permissions');
        Artisan::call('disable:backpack_pro');

        $permissions = Permission::all();
        $super_admin_role = Role::find(1);
        $admin_role =Role::find(2);

        $minister_role = Role::find(3);
        $chief_secretary = Role::find(4);
        $cabinet_approver = Role::find(5);
        $cabinet_creator = Role::find(6);
        $ministry_secretary = Role::find(7);
        $ministry_reviewer = Role::find(8);
        $ministry_creator = Role::find(9);
      

        $super_admin_role->givePermissionTo($permissions);
        $admin_role->givePermissionTo($permissions);
       
        $minister_role->givePermissionTo([
            'list agenda', 'update agenda',
            'list ecmeetingrequest',
            'update ecmeetingrequest',
            'list meetingminutedetail', 
            'update meetingminutedetail',
        ]);

          
        $ministry_creator->givePermissionTo([
            'list agenda', 'create agenda', 'update agenda','delete agenda',
        ]);
        $ministry_reviewer->givePermissionTo([
            'list agenda', 'update agenda',
        ]);
        $ministry_secretary->givePermissionTo([
            'list agenda', 'update agenda',
            'list ecmeetingrequest',
            'update ecmeetingrequest',
            'list meetingminutedetail',  
            'update meetingminutedetail'
        ]);
        $cabinet_creator->givePermissionTo([
            'list agenda', 'create agenda', 'update agenda','delete agenda',
            'list ecmeetingrequest','create ecmeetingrequest', 'update ecmeetingrequest','delete ecmeetingrequest',
            'list meetingminutedetail','create meetingminutedetail', 'update meetingminutedetail','delete meetingminutedetail',
        ]);
        $cabinet_approver->givePermissionTo([
            'list agenda', 'update agenda',
            'list ecmeetingrequest', 'update ecmeetingrequest',
            'list meetingminutedetail', 'update meetingminutedetail'
        ]);
        $chief_secretary->givePermissionTo([
            'list agenda', 'update agenda',
            'list ecmeetingrequest', 'update ecmeetingrequest',
            'list meetingminutedetail', 'update meetingminutedetail'
        ]);


        //assign role for superadmin
        $user = User::findOrFail(1);
        $user_admin = User::findOrFail(2);

        $user->assignRoleCustom("superadmin", $user->id);
        $user->assignRoleCustom("admin", $user_admin->id);
    }
}
