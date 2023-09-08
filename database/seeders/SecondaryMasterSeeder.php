<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecondaryMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clean_tables();

        $this->ministry_member_type();
        $this->political_party();
        $this->mst_steps();
        $this->mst_posts();
        $this->ec_ministry();
        $this->ec_mp();
        // $this->ec_ministry_members();
        $this->fileType();
        $this->mst_agenda_types();
        $this->ec_committees();
    }

    private function clean_tables(){

        DB::table('mst_ministry_member_type')->delete();
        DB::table('ec_political_parties')->delete();
        DB::table('mst_steps')->delete();
        DB::table('mst_posts')->delete();
        DB::table('ec_ministry')->delete();
        DB::table('ec_mp')->delete();
        DB::table('ec_ministry_members')->delete();
        DB::table('mst_agenda_types')->delete();
        DB::table('ec_committees')->delete();
    }

    private function ec_committees()
    {
        DB::table('ec_committees')->insert(
            [
                array('id' => 1,  'name_en' => 'म.प. राजनीति समिति', 'name_lc' => 'म.प. राजनीति समिति','display_order' => 1 ),
                array('id' => 2, 'name_en' => 'सामाजिक तथा पुर्बधार समिति', 'name_lc' => 'सामाजिक तथा पुर्बधार समिति','display_order' => 2),
                array('id' => 3,  'name_en' => 'प्रशासन तथा विधयेक समिति', 'name_lc' => 'प्रशासन तथा विधयेक समिति','display_order' => 3),
            ]
        );
        DB::statement("SELECT SETVAL('ec_ministry_id_seq',1000)");
    }

    private function mst_agenda_types()
    {
        DB::table('mst_agenda_types')->insert(
            [
                array('id' => 1,  'name_en' => 'Bill', 'name_lc' => 'विधयेक','display_order' => 1 ),
                array('id' => 2, 'name_en' => 'Rules', 'name_lc' => 'नियमावली','display_order' => 2),
                array('id' => 3,  'name_en' => 'Directory', 'name_lc' => 'निदेशिका','display_order' => 3),
                array('id' => 4,  'name_en' => 'Procedure', 'name_lc' => 'कार्यविधि','display_order' => 4),
                array('id' => 5,  'name_en' => 'Policy', 'name_lc' => 'नीति','display_order' => 5),
                array('id' => 6,  'name_en' => 'Formation order', 'name_lc' => 'गठन आदेश','display_order' => 6),
                array('id' => 7,  'name_en' => 'Appointment', 'name_lc' => 'नियुक्ति','display_order' => 7),
                array('id' => 8,  'name_en' => 'Sipharis', 'name_lc' => 'सिफारिस','display_order' => 8),
                array('id' => 9,  'name_en' => 'Policies and Programs', 'name_lc' => 'नीति तथा कार्यक्रम','display_order' => 9),
                array('id' => 10,  'name_en' => 'Budget', 'name_lc' => 'बजेट','display_order' => 10),
            ]
        );
        DB::statement("SELECT SETVAL('mst_agenda_types_id_seq',1000)");
    }

    private function ministry_member_type()
    {
        DB::table('mst_ministry_member_type')->insert(
            [
                array('id' => 1,  'name_en' => 'Chairman', 'name_lc' => 'सभापति','display_order' => 1 ),
                array('id' => 2, 'name_en' => 'Member', 'name_lc' => 'सदस्य','display_order' => 2),
                array('id' => 3,  'name_en' => 'Secretary', 'name_lc' => 'सचिव','display_order' => 3),
                array('id' => 4,  'name_en' => 'Employee', 'name_lc' => 'कर्मचारी','display_order' => 4),
            ]
        );
        DB::statement("SELECT SETVAL('mst_ministry_member_type_id_seq',1000)");
    }

    private function political_party()
    {
        DB::table('ec_political_parties')->insert(
            [
                array('id' => 1,  'name_en' => 'Nepal Communist Party (UML)', 'name_lc' => 'नेपाल कम्युनिष्ट पार्टी (एमाले)','display_order' => 1 ),
                array('id' => 2, 'name_en' => 'Nepal Communist Party (Maoist Centre)', 'name_lc' => 'नेपाल कम्युनिष्ट पार्टी (माओवादी केन्द्र)','display_order' => 2),
                array('id' => 3,  'name_en' => 'Nepali Congress', 'name_lc' => 'नेपाली काँग्रेस','display_order' => 3),
                array('id' => 4,  'name_en' => 'Janata Samajwadi Party, Nepal', 'name_lc' => 'जनता समाजवादी पार्टी, नेपाल','display_order' => 4),
                array('id' => 5,  'name_en' => 'Rastriya Janamorcha', 'name_lc' => 'राष्ट्रिय जनमोर्चा','display_order' => 5),
                array('id' => 6,  'name_en' => 'Nepal Communist Party (Unified Socialist)', 'name_lc' => 'नेपाल कम्युनिष्ट पार्टी (एकीकृत समाजवादी)','display_order' => 6),
                array('id' => 7,  'name_en' => 'Free', 'name_lc' => 'स्वतन्त्र','display_order' => 7),
            ]
        );
        DB::statement("SELECT SETVAL('ec_political_parties_id_seq',1000)");
    }

    private function mst_steps()
    {
        DB::table('mst_steps')->insert(
            [
                array('id' => 1,  'name_en' => 'In Process', 'name_lc' => 'प्रक्रियामा','display_order' => 1 ),
                array('id' => 2, 'name_en' => 'Under Construction', 'name_lc' => 'निर्माणाधीन','display_order' => 2),
                array('id' => 3,  'name_en' => 'Finalized', 'name_lc' => 'फाइनल गरिएको','display_order' => 3),
                array('id' => 4,  'name_en' => 'Rejected', 'name_lc' => 'अस्वीकार गरिएको','display_order' => 4),
            ]
        );
        DB::statement("SELECT SETVAL('mst_steps_id_seq',1000)");
    }

    private function mst_posts()
    {
        DB::table('mst_posts')->insert(
            [
                array('id' => 1,  'name_en' => 'Chief Minister', 'name_lc' => 'मुख्यमन्त्री','display_order' => 1 ),
                array('id' => 2,  'name_en' => 'State Minister', 'name_lc' => 'राज्यमन्त्री','display_order' => 3),
                array('id' => 3, 'name_en' => 'Minister', 'name_lc' => 'मन्त्री','display_order' => 2),
                array('id' => 4,  'name_en' => 'Chief Secretary', 'name_lc' => 'प्रमुख सचिव','display_order' => 4),
                array('id' => 5,  'name_en' => 'Deputy Chief Secretary', 'name_lc' => 'निमित्त प्रमुख सचिव','display_order' => 5),
                array('id' => 6,  'name_en' => 'Secretary', 'name_lc' => 'सचिव','display_order' => 6),
                array('id' => 7,  'name_en' => 'Deputy Secretary', 'name_lc' => 'निमित्त सचिव','display_order' => 7),
            ]
        );
        DB::statement("SELECT SETVAL('mst_posts_id_seq',1000)");
    }


    private function ec_ministry()
    {
        DB::table('ec_ministry')->insert(
            [
                array('id' => 1,  'name_en' => 'Office of Chief Minister and Council of Minister (OCMCM)', 'name_lc' => 'मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय','display_order' => 1 ),
                array('id' => 2,  'name_en' => 'Ministyr of Tourism, Rural and Urban Development (MORUD)', 'name_lc' => 'पर्यटन, ग्रामिण तथा शहरी विकास मन्त्रालय','display_order' => 2 ),
                array('id' => 3,  'name_en' => 'Ministyr of Finance and Cooperatives (MOEAP)', 'name_lc' => 'अर्थ तथा सहकारी मन्त्रालय','display_order' => 3 ),
                array('id' => 4,  'name_en' => 'Ministry of Physical Infrastructure Development (MOPID)', 'name_lc' => 'भौतिक पूर्वाधार विकास मन्त्रालय','display_order' => 4 ),
                array('id' => 5,  'name_en' => 'Ministry of Internal Affairs and Law (MOIAL)', 'name_lc' => 'आन्तरिक मामिला,कानुन तथा संचार मन्त्रालय','display_order' => 5 ),
                array('id' => 6,  'name_en' => 'Ministry of Health (MOHP)', 'name_lc' => 'स्वास्थ्य मन्त्रालय','display_order' => 6 ),
                array('id' => 7,  'name_en' => 'Ministry of Energy, Water Resources and Irrigation (MOEWRI)', 'name_lc' => 'उर्जा, जलश्रोत तथा सिंचाई मन्त्रालय','display_order' => 7 ),
                array('id' => 8,  'name_en' => 'Ministry of Industry, Tourism, Forest and Environment (MOITFE)', 'name_lc' => 'वन तथा वातावरण मन्त्रालय','display_order' => 8 ),
                array('id' => 9,  'name_en' => 'Ministry of Industry, Commerce and Supplies (MOICS)', 'name_lc' => 'उद्योग, वाणिज्य तथा आपूर्ति मन्त्रालय','display_order' => 9 ),
                array('id' => 10,  'name_en' => 'Ministry of Women, Children and Senior Citizens (MOWCSC)', 'name_lc' => 'महिला, बालबालिका तथा जेष्ठ नागरिक मन्त्रालय','display_order' => 10 ),
                array('id' => 11,  'name_en' => 'Ministry of Education and Sports (MOSD)', 'name_lc' => 'शिक्षा तथा खेलकुद मन्त्रालय','display_order' => 11 ),
                array('id' => 12,  'name_en' => 'Ministry of Agriculture and Land Management (MOLMAC)', 'name_lc' => 'कृषि तथा भूमि ब्यवस्था मन्त्रालय','display_order' => 12 ),
                array('id' => 13,  'name_en' => 'Ministry of Labour and Transport (MOLETM)', 'name_lc' => 'श्रम तथा यातायात व्यवस्था मन्त्रालय','display_order' => 13 ),
            ]
        );
        DB::statement("SELECT SETVAL('ec_ministry_id_seq',1000)");
    }

    private function ec_mp()
    {
        DB::table('ec_mp')->insert(
            [
                array('id' => 1, 'name_en' => 'Mr. Kul Prashad K.C', 'name_lc' => 'श्री कुल प्रसाद के.सी.','gender_id' => 1,'post_id'=> 1,'display_order' => 1 ),
                array('id' => 2, 'name_en' => 'Mr. Dillibahadur Chaudhary', 'name_lc' => 'श्री डिल्लीबहादुर चौधरी','gender_id' => 1,'post_id'=> 2,'display_order' => 2 ),
                array('id' => 3, 'name_en' => 'Mr. Krishandhwaj Khadka', 'name_lc' => 'श्री कृष्णध्वज खड्का','gender_id' => 1,'post_id'=> 2,'display_order' => 3 ),
                array('id' => 4, 'name_en' => 'Mr. Sahasram Yadhav', 'name_lc' => 'श्री सहसराम यादव','gender_id' => 1,'post_id'=> 2,'display_order' =>  4),
                array('id' => 5, 'name_en' => 'Mr. Tilakram Sharma', 'name_lc' => 'श्री तिलकराम शर्मा','gender_id' => 1,'post_id'=> 2,'display_order' =>  5),
                array('id' => 6, 'name_en' => 'Mr. Indra Jit Tharu', 'name_lc' => 'श्री इन्द्र जीत थारु','gender_id' => 1,'post_id'=> 2,'display_order' =>  6),
                array('id' => 7, 'name_en' => 'Mr. Bir Bahadur Rana', 'name_lc' => 'श्री विर बहादुर राना','gender_id' => 1,'post_id'=> 2,'display_order' =>  7),
                array('id' => 8, 'name_en' => 'Mr. Surendra Bahadur Hamal', 'name_lc' => 'श्री सुरेन्द्र बहादुर हमाल','gender_id' => 1,'post_id'=> 2,'display_order' =>  8),
                array('id' => 9, 'name_en' => 'Mr. Arya Sahi', 'name_lc' => 'श्री अजय शाही','gender_id' => 1,'post_id'=> 2,'display_order' =>  9),
                array('id' => 10, 'name_en' => 'Mr. Ram Gharti', 'name_lc' => 'श्री रमा घर्ती','gender_id' => 1,'post_id'=> 2,'display_order' =>  10),
                array('id' => 11, 'name_en' => 'Mr. Basiuddin Khan', 'name_lc' => 'श्री बसीउद्दीन खाँ','gender_id' => 1,'post_id'=> 2,'display_order' =>  11),
                array('id' => 12, 'name_en' => 'Mr. Suman Sharma Raimajhi', 'name_lc' => 'श्री सुमन शर्मा रायमाझी','gender_id' => 1,'post_id'=> 2,'display_order' =>  12),
                array('id' => 13, 'name_en' => 'Mr. Purmati Denga', 'name_lc' => 'श्री पुर्मती ढेंगा','gender_id' => 2,'post_id'=> 2,'display_order' =>  13),
                array('id' => 14, 'name_en' => 'Mr. Arjun Kumar Shrestha', 'name_lc' => 'श्री अर्जुन कुमार श्रेष्ठ','gender_id' => 1,'post_id'=> 3,'display_order' =>  14),
                array('id' => 15, 'name_en' => 'Mr. Sushma Yadhav', 'name_lc' => 'श्री सुष्मा यादव','gender_id' => 1,'post_id'=> 3,'display_order' =>  15),
                array('id' => 16, 'name_en' => 'Mr. Reena Nepal B.K', 'name_lc' => 'श्री रिना नेपाल वि.क.','gender_id' => 2,'post_id'=> 3,'display_order' =>  16),
            ]
        );
        DB::statement("SELECT SETVAL('ec_mp_id_seq',1000)");
    }

    private function ec_ministry_members()
    {
    //     DB::table('ec_ministry_members')->insert(
    //         [
    //             array('id' => 1,  'mp_id' => '1', 'ministry_id' => '1','display_order' => 1),
    //             array('id' => 2,  'mp_id' => '2', 'ministry_id' => '2','display_order' => 1),
    //             array('id' => 3,  'mp_id' => '3', 'ministry_id' => '3','display_order' => 1),
    //             array('id' => 4,  'mp_id' => '4', 'ministry_id' => '4','display_order' => 1),
    //             array('id' => 5,  'mp_id' => '5', 'ministry_id' => '5','display_order' => 1),
    //             array('id' => 6,  'mp_id' => '6', 'ministry_id' => '6','display_order' => 1),
    //             array('id' => 7,  'mp_id' => '7', 'ministry_id' => '7','display_order' => 1),
    //             array('id' => 8,  'mp_id' => '8', 'ministry_id' => '8','display_order' => 1),
    //             array('id' => 9,  'mp_id' => '9', 'ministry_id' => '9','display_order' => 1),
    //             array('id' => 10,  'mp_id' => '10', 'ministry_id' => '10','display_order' => 1),
    //             array('id' => 11,  'mp_id' => '11', 'ministry_id' => '11','display_order' => 1),
    //             array('id' => 12,  'mp_id' => '12', 'ministry_id' => '12','display_order' => 1),
    //             array('id' => 13,  'mp_id' => '13', 'ministry_id' => '13','display_order' => 1),
    //             array('id' => 14,  'mp_id' => '14', 'ministry_id' => '7','display_order' => 1),
    //             array('id' => 15,  'mp_id' => '15', 'ministry_id' => '7','display_order' => 1),
    //             array('id' => 16,  'mp_id' => '16', 'ministry_id' => '2','display_order' => 1),
    //         ]
    //     );
    //     DB::statement("SELECT SETVAL('ec_ministry_members_id_seq',1000)");
    }
    private function fileType()
    {
        DB::table('agenda_file_type')->insert(
            [
                array('id' => 1,  'code' => 'टिप्पणी1', 'name' => 'टिप्पणी1','display_order' => 1),
                array('id' => 2,  'code' => 'टिप्पणी2', 'name' => 'टिप्पणी2', 'display_order' => 2),
                array('id' => 3,  'code' => 'टिप्पणी3', 'name' => 'टिप्पणी3', 'display_order' => 3),
                array('id' => 4,  'code' => 'टिप्पणी4', 'name' => 'टिप्पणी4', 'display_order' => 4),
                array('id' => 5,  'code' => 'टिप्पणी5', 'name' => 'टिप्पणी5', 'display_order' => 5),
            ]
        );
        DB::statement("SELECT SETVAL('agenda_file_type_id_seq',5)");
    }

}
