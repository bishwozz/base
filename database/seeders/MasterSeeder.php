<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clean_tables();
        $this->userSeeder();
        $this->sliders();
        $this->games();

    }
    
    public function clean_tables(){
        DB::table('users')->delete();
        DB::table('sliders')->delete();
        DB::table('games')->delete();
    }
    public function userSeeder(){
        DB::table('users')->insert([
            ['id' => 1,'name' => 'admin', 'email' => 'admin@gmail.com','password'=> \Hash::make('1')],
        ]); 
    }

    private function mst_social_media(){
        DB::table('mst_social_media')->insert([
            array('id' => '1','code' => 'fb', 'name' => 'FaceBook', 'display_order' => 1, 'is_active' => true),
            array('id' => '2','code' => 'tw', 'name' => 'Twitter', 'display_order' => 2, 'is_active' => true),            
            array('id' => '3','code' => 'lin', 'name' => 'Linkedin', 'display_order' => 3, 'is_active' => true),            
            array('id' => '4','code' => 'yu', 'name' => 'Youtube', 'display_order' => 4, 'is_active' => true),            
            array('id' => '5','code' => 'ig', 'name' => 'Instagram', 'display_order' => 5, 'is_active' => true),            
        ]);
    }
   
    public function sliders(){
        DB::table('sliders')->insert([
            // mainmenus
            ['id' => 1,'display_order'=>2,'title' => 'START YOUR COREER WITH US','file_upload'=>'Sliders/slider1.jpg','description'=>'CAMPUS IN SYDNEY','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'display_order'=>1,'title' => 'START YOUR COREER WITH US','file_upload'=>'Sliders/slider2.jpg','description'=>'CAMPUS IN SYDNEY','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]); 
        // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    }
    public function games(){
        DB::table('games')->insert([
            // mainmenus
            ['id' => 1,'display_order'=>1,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo1.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'display_order'=>2,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo2.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 3,'display_order'=>3,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo3.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 4,'display_order'=>4,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo4.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 5,'display_order'=>5,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo5.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 6,'display_order'=>6,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo6.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 7,'display_order'=>7,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo7.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 8,'display_order'=>8,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo8.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 9,'display_order'=>9,'title' => 'START YOUR COREER WITH US','game_img'=>'Games/game_logo9.jpg', 'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]); 
        // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    }
   


    // public function about_us(){
    //     DB::table('about_us')->insert([
    //         ['id' => 1,'title' => 'WHY CHOOSE LINCOLN COLLEGE','file_upload'=>'AboutUs/about_us.jpg','details'=>'Medicus College is dedicatedly providing study options that prepare our students to become leaders and innovators in their careers. We can help you get started on your career path, opening a world of opportunity and gaining the skills to change yourself and the world.
    //         We deliver quality education and training to international students according to the Australian Vocational Education and Training sector. We offer nationally recognised degrees that help our students develop skills that meet current industry needs. Our qualifications give students the knowledge and experience they need to be fully prepared for their chosen career path.','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
    //     ]); 
    //     // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    // }

    // public function gallery(){
    //     DB::table('galleries')->insert([
    //         // mainmenus
    //         ['id' => 1,'title' => 'gallery One','category_id'=>1,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '1','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
    //         ['id' => 2,'title' => 'gallery Two','category_id'=>2,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '2','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
    //         ['id' => 3,'title' => 'gallery Three','category_id'=>2,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '3','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
    //         ['id' => 4,'title' => 'gallery Four','category_id'=>1,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
    //         ['id' => 5,'title' => 'gallery Five','category_id'=>3,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '5','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
    //         ['id' => 6,'title' => 'gallery Six','category_id'=>1,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '6','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
    //     ]); 
    //     // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    // }


}



