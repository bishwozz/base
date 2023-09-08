<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('app_settings')->insert(
            [
                array('id' => 1,'code'=>01,'office_name_lc' => 'मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय', 'office_name_en' => 'मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय',
                'address_name_lc' => 'राप्ती उपत्यका (देउखुरी), नेपाल','address_name_en' =>'राप्ती उपत्यका (देउखुरी), नेपाल','letter_head_title_1'=>'प्रदेश सरकार','letter_head_title_2'=>'लुम्बिनी प्रदेश सरकार',
                'letter_head_title_3' => 'मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय','letter_head_title_4'=>'राप्ती उपत्यका (देउखुरी), नेपाल','fiscal_year_id'=>101,
                'phone'=>'५२३९५७','fax' => '+९७७-०९१-५२५५७२','email'=>'cabinetsecretariat5@gmail.com'),
            ]
        );
        DB::statement("SELECT SETVAL('app_settings_id_seq',1000)");
    }
}
