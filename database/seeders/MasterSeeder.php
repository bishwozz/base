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
        $this->menuSeeder();
        $this->category();
        $this->mst_social_media();
        $this->sliders();
        $this->about_us();
        $this->gallery();
        $this->blogs();
    }
    
    public function clean_tables(){
        DB::table('users')->delete();
        DB::table('blogs')->delete();
        DB::table('categories')->delete();
        DB::table('mst_social_media')->delete();
        DB::table('sliders')->delete();
        DB::table('about_us')->delete();
        DB::table('galleries')->delete();
        DB::table('menus')->delete();
        DB::table('app_settings')->delete();
    }
    public function userSeeder(){
        DB::table('users')->insert([
            ['id' => 1,'name' => 'admin', 'email' => 'admin@gmail.com','password'=> \Hash::make('123456')],
        ]); 
    }
    public function category(){
        DB::table('categories')->insert([
            // gallery
            ['id' => 1,'name' => 'First cat gal','menu_id'=>'5','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'name' => 'Second cat gal','menu_id'=>'5','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 3,'name' => 'Third cat gal','menu_id'=>'5','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 4,'name' => 'Fourth cat gal','menu_id'=>'5','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            
            // news
            ['id' => 5,'name' => 'First cat news','menu_id'=>'4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 6,'name' => 'Second cat news','menu_id'=>'4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 7,'name' => 'Third cat news','menu_id'=>'4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 8,'name' => 'Fourth cat news','menu_id'=>'4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 9,'name' => 'Fifth cat news','menu_id'=>'4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 10,'name' => 'Sixth cat news','menu_id'=>'4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
          
            //events
            ['id' => 11,'name' => 'First cat event','menu_id'=>'11','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 12,'name' => 'Second cat event','menu_id'=>'11','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            
            //blogs
            ['id' => 13,'name' => 'First cat blog','menu_id'=>'12','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 14,'name' => 'Second cat blog','menu_id'=>'12','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]);
        // DB::statement("SELECT SETVAL('menus_id_seq',1000)");

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
    public function menuSeeder(){
        DB::table('menus')->insert([
            // mainmenus
            ['id' => 1,'title' => 'Home', 'display_order' => '1','parent_id' => null,'type_id' => 0,'link' => '/home','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'title' => 'Courses', 'display_order' => '2','parent_id' => null,'type_id' => 0,'link' => '/courses','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 3,'title' => 'About Us', 'display_order' => '3','parent_id' => null,'type_id' => 0,'link' => '/about_us','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 4,'title' => 'News Notices', 'display_order' => '4','parent_id' => null,'type_id' => 0,'link' => '/news_notices','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 5,'title' => 'Gallery', 'display_order' => '5','parent_id' => null,'type_id' => 0,'link' => '/gallery','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 6,'title' => 'Contact Us', 'display_order' => '6','parent_id' => null,'type_id' => 0,'link' => '/contact_us','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            
            // submenus
            ['id' => 7,'title' => 'Fee Stucture', 'display_order' => '1','parent_id' => 3,'type_id' => 1,'link' => '/about_us/fee_type','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 8,'title' => 'Our Team', 'display_order' => '2','parent_id' => 3,'type_id' => 1,'link' => '/about_us/our_team','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 9,'title' => 'Scholarships', 'display_order' => '6','parent_id' => 3,'type_id' => 1,'link' => '/about_us/scholarships','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 10,'title' => 'Our Agents', 'display_order' => '7','parent_id' => 3,'type_id' => 1,'link' => '/about_us/agents','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
          
            ['id' => 11,'title' => 'Events', 'display_order' => '1','parent_id' => 4,'type_id' => 1,'link' => '/news_notices/events','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 12,'title' => 'Blogs', 'display_order' => '2','parent_id' => 4,'type_id' => 1,'link' => '/news_notices/blogs','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]);
        // DB::statement("SELECT SETVAL('menus_id_seq',1000)");

    }

   
    public function sliders(){
        DB::table('sliders')->insert([
            // mainmenus
            ['id' => 1,'display_order'=>2,'title' => 'START YOUR COREER WITH US','file_upload'=>'Sliders/slider1.jpg','description'=>'CAMPUS IN SYDNEY','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'display_order'=>1,'title' => 'START YOUR COREER WITH US','file_upload'=>'Sliders/slider2.jpg','description'=>'CAMPUS IN SYDNEY','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]); 
        // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    }
   


    public function about_us(){
        DB::table('about_us')->insert([
            ['id' => 1,'title' => 'WHY CHOOSE LINCOLN COLLEGE','file_upload'=>'AboutUs/about_us.jpg','details'=>'Medicus College is dedicatedly providing study options that prepare our students to become leaders and innovators in their careers. We can help you get started on your career path, opening a world of opportunity and gaining the skills to change yourself and the world.
            We deliver quality education and training to international students according to the Australian Vocational Education and Training sector. We offer nationally recognised degrees that help our students develop skills that meet current industry needs. Our qualifications give students the knowledge and experience they need to be fully prepared for their chosen career path.','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]); 
        // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    }

    public function human_resources(){
        DB::table('app_settings')->insert([
            ['id' => 1,'code'=>'001','type'=>1,'name' => 'Sandip Silwal','email'=>'sandip.silwal.ss@gmail.com','phone'=>'9843180434','designation'=>'','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/teacher1.jpg','display_order' => '1','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'code'=>'002','type'=>2,'name' => 'Sunil Pathak','email'=>'sunil.pathak@gmail.com','phone'=>'9843180767','designation'=>'','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/team1.jpg','display_order' => '2','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 3,'code'=>'003','type'=>0,'name' => 'Yogesh Acharya','email'=>'yogesh.acharya@gmail.com','phone'=>'9843180567','designation'=>'','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/team4.jpg','display_order' => '3','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 4,'code'=>'004','type'=>1,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/teacher2.jpg','display_order' => '4','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 5,'code'=>'005','type'=>2,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/noimg.jpg','display_order' => '5','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 6,'code'=>'006','type'=>1,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'CEO','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/teacher3.jpg','display_order' => '6','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 7,'code'=>'007','type'=>3,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Hr','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/team1.jpg','display_order' => '7','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 8,'code'=>'008','type'=>1,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Hr','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/teacher4.jpg','display_order' => '8','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 9,'code'=>'009','type'=>3,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Hr','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/team2.jpg','display_order' => '9','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 10,'code'=>'010','type'=>1,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Hr','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/noimg.jpg','display_order' => '10','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 11,'code'=>'011','type'=>3,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'HOD','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/team3.jpg','display_order' => '11','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 12,'code'=>'012','type'=>1,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Manager','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/noimg.jpg','display_order' => '12','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 13,'code'=>'013','type'=>3,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Manager','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/noimg.jpg','display_order' => '13','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 14,'code'=>'014','type'=>1,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Manager','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/team3.jpg','display_order' => '14','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 15,'code'=>'015','type'=>2,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Manager','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/team2.jpg','display_order' => '15','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 16,'code'=>'016','type'=>2,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Manager','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/noimg.jpg','display_order' => '16','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 17,'code'=>'017','type'=>2,'name' => 'Ram Thapa','email'=>'ram.thapa@gmail.com','phone'=>'9843180778','designation'=>'Manager','address'=>'Banasthali, Kathmandu','degree'=>'Bachelor','institute'=>'Acme Engineering College','file_upload'=>'HumanResource/noimg.jpg','display_order' => '17','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]); 
        // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    }

    public function gallery(){
        DB::table('galleries')->insert([
            // mainmenus
            ['id' => 1,'title' => 'gallery One','category_id'=>1,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '1','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'title' => 'gallery Two','category_id'=>2,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '2','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 3,'title' => 'gallery Three','category_id'=>2,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '3','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 4,'title' => 'gallery Four','category_id'=>1,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '4','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 5,'title' => 'gallery Five','category_id'=>3,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '5','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 6,'title' => 'gallery Six','category_id'=>1,'gallery_images'=>'Gallery/noimg.jpg','description'=>'Donald Palmer is a Specialist Real Estate Agent with 8 years of Experience in Real Estate field. He achive success with his honesty,determination, hardwork and commetment. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, totam?','display_order' => '6','created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]); 
        // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    }


    public function blogs(){
        DB::table('blogs')->insert([
            // mainmenus
            ['id' => 1,'category_id'=>14,'title' => 'Blog One','image'=>'Blog/blog1.jpg','description'=>'Dear All,

                As we start to wrap up another eventful year, we wanted to take a moment to wish you and your family a Merry Christmas and happy 2022!

                At the end of last year, we were looking forward to a new year with no more interruptions from Covid. Thank you for your continued trust in Medicus in 2021. We’ve taken the challenges in our stride, continuing to improve our students support with standards while also introducing innovative new plans and services.

                We’d also like to remind you that our students support team will still be available over the phone to assist with any immediate concerns (info@medicus.edu.au).

                Students Classes will resume from 31st January 2022 (this may vary, depending on the individual student’s timetable)

                LMS Support: Available on weekdays except for public holidays and weekends (lmssupport@medicus.edu.au)

                Admin/student Support Not available -23rd December till the 9th January 2022.','display_order' => '1','visit_counts'=>400,'date'=>Carbon::now()->toDateString(),'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 2,'category_id'=>13,'title' => 'Blog Two','image'=>'Blog/blog2.jpg','description'=>'Dear All,

                As we start to wrap up another eventful year, we wanted to take a moment to wish you and your family a Merry Christmas and happy 2022!

                At the end of last year, we were looking forward to a new year with no more interruptions from Covid. Thank you for your continued trust in Medicus in 2021. We’ve taken the challenges in our stride, continuing to improve our students support with standards while also introducing innovative new plans and services.

                We’d also like to remind you that our students support team will still be available over the phone to assist with any immediate concerns (info@medicus.edu.au).

                Students Classes will resume from 31st January 2022 (this may vary, depending on the individual student’s timetable)

                LMS Support: Available on weekdays except for public holidays and weekends (lmssupport@medicus.edu.au)

                Admin/student Support Not available -23rd December till the 9th January 2022.','display_order' => '2','visit_counts'=>500,'date'=>Carbon::now()->toDateString(),'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 3,'category_id'=>13,'title' => 'Blog Three','image'=>'Blog/blog3.jpg','description'=>'Dear All,

                As we start to wrap up another eventful year, we wanted to take a moment to wish you and your family a Merry Christmas and happy 2022!

                At the end of last year, we were looking forward to a new year with no more interruptions from Covid. Thank you for your continued trust in Medicus in 2021. We’ve taken the challenges in our stride, continuing to improve our students support with standards while also introducing innovative new plans and services.

                We’d also like to remind you that our students support team will still be available over the phone to assist with any immediate concerns (info@medicus.edu.au).

                Students Classes will resume from 31st January 2022 (this may vary, depending on the individual student’s timetable)

                LMS Support: Available on weekdays except for public holidays and weekends (lmssupport@medicus.edu.au)

                Admin/student Support Not available -23rd December till the 9th January 2022.','display_order' => '3','visit_counts'=>600,'date'=>Carbon::now()->toDateString(),'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 4,'category_id'=>14,'title' => 'Blog Four','image'=>'Blog/blog4.jpg','description'=>'Dear All,

                As we start to wrap up another eventful year, we wanted to take a moment to wish you and your family a Merry Christmas and happy 2022!

                At the end of last year, we were looking forward to a new year with no more interruptions from Covid. Thank you for your continued trust in Medicus in 2021. We’ve taken the challenges in our stride, continuing to improve our students support with standards while also introducing innovative new plans and services.

                We’d also like to remind you that our students support team will still be available over the phone to assist with any immediate concerns (info@medicus.edu.au).

                Students Classes will resume from 31st January 2022 (this may vary, depending on the individual student’s timetable)

                LMS Support: Available on weekdays except for public holidays and weekends (lmssupport@medicus.edu.au)

                Admin/student Support Not available -23rd December till the 9th January 2022.','display_order' => '4','visit_counts'=>500,'date'=>Carbon::now()->toDateString(),'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 5,'category_id'=>13,'title' => 'Blog Five','image'=>'Blog/blog5.jpg','description'=>'Dear All,

                As we start to wrap up another eventful year, we wanted to take a moment to wish you and your family a Merry Christmas and happy 2022!

                At the end of last year, we were looking forward to a new year with no more interruptions from Covid. Thank you for your continued trust in Medicus in 2021. We’ve taken the challenges in our stride, continuing to improve our students support with standards while also introducing innovative new plans and services.

                We’d also like to remind you that our students support team will still be available over the phone to assist with any immediate concerns (info@medicus.edu.au).

                Students Classes will resume from 31st January 2022 (this may vary, depending on the individual student’s timetable)

                LMS Support: Available on weekdays except for public holidays and weekends (lmssupport@medicus.edu.au)

                Admin/student Support Not available -23rd December till the 9th January 2022.','display_order' => '5','visit_counts'=>300,'date'=>Carbon::now()->toDateString(),'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
            ['id' => 6,'category_id'=>14,'title' => 'Blog Six','image'=>'Event/blog5.jpg','description'=>'Dear All,

                As we start to wrap up another eventful year, we wanted to take a moment to wish you and your family a Merry Christmas and happy 2022!

                At the end of last year, we were looking forward to a new year with no more interruptions from Covid. Thank you for your continued trust in Medicus in 2021. We’ve taken the challenges in our stride, continuing to improve our students support with standards while also introducing innovative new plans and services.

                We’d also like to remind you that our students support team will still be available over the phone to assist with any immediate concerns (info@medicus.edu.au).

                Students Classes will resume from 31st January 2022 (this may vary, depending on the individual student’s timetable)

                LMS Support: Available on weekdays except for public holidays and weekends (lmssupport@medicus.edu.au)

                Admin/student Support Not available -23rd December till the 9th January 2022.','display_order' => '6','visit_counts'=>500,'date'=>Carbon::now()->toDateString(),'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],
        ]); 
        // DB::statement("SELECT SETVAL('mst_fee_types_id_seq',1000)");
    }

    public function team()
    {
        DB::table('teams')->insert([
            ['id' => 1, 'full_name' => 'Bishow Sapkota', 'designation' => 'Developer', 'display_order' => 9, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $this->current_date_time, 'updated_at' => $this->current_date_time],
            ['id' => 2, 'full_name' => 'Yogesh Acharya', 'designation' => 'Senior Developer(PM)', 'display_order' => 4, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $this->current_date_time, 'updated_at' => $this->current_date_time],
            ['id' => 3, 'full_name' => 'Sandip Silwal', 'designation' => 'Developer', 'display_order' => 11, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $this->current_date_time, 'updated_at' => $this->current_date_time],

        ]);
    }

    public function product()
    {
        DB::table('product')->insert([
                ['id' => 1, 'title' => 'iPayroll', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 2, 'title' => 'Shifaris', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 3, 'title' => 'Easy Industry', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 4, 'title' => 'Information Bank', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 5, 'title' => 'WPPMS', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 6, 'title' => 'E-legislation', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 7, 'title' => 'Digitization for Building Permit', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 8, 'title' => 'EDMIS', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 9, 'title' => 'CMIS Application', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 10, 'title' => 'Office Automation', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 11, 'title' => 'CRO Website', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 12, 'title' => 'TMPP Website', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 13, 'title' => 'PradeshSabha Website', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 14, 'title' => 'Outdoor Tourist Digital Signage', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 15, 'title' => 'Website of Communication Registers Office', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 16, 'title' => 'Pradesh Sabha Profile', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 17, 'title' => 'Tourist Information System', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 18, 'title' => 'GCMS', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                ['id' => 19, 'title' => 'HMIS', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                // ['id' => 6, 'title' => 'Integrated Municipal Information Framework', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                // ['id' => 7, 'title' => 'Cloud Space Leasing', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                // ['id' => 11, 'title' => 'LULC and Resource Map', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                // ['id' => 15, 'title' => 'Training to Parliament Staffs', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
            ]);
            DB::statement("SELECT SETVAL('teams_id_seq',1000)");
    }
}



