<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgendaDecisionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->agenda_decision_type();

    }
    private function agenda_decision_type(){
        DB::table('agenda_decision_type')->insert(
            [
                array(

                    'id' => 1,
                    'agenda_decision_code'=>'"प्रस्तावमा लेखिएबमोजिम गर्ने।"',
                    'agenda_decision_content' => '"प्रस्तावमा लेखिएबमोजिम गर्ने।"',
                    'display_order' => 1,
                ),
                array(

                    'id' => 2,
                    'agenda_decision_code'=>'"प्रस्ताव फिर्ता गर्ने।"',
                    'agenda_decision_content' => '"प्रस्ताव फिर्ता गर्ने।"',
                    'display_order' => 2,
                ),
                array(

                    'id' => 3,
                    'agenda_decision_code'=>'"प्रस्तावको प्रकरण ४ “निर्णय हुनुपर्ने व्यहोस" अन्तर्गत प्रस्तावित ब्यहोराको सा देहायको व्यहोरा राखे"',
                    'agenda_decision_content' => '"प्रस्तावको प्रकरण ४ “निर्णय हुनुपर्ने व्यहोस" अन्तर्गत प्रस्तावित ब्यहोराको सा देहायको व्यहोरा राखे"',
                    'display_order' => 3,
                ),
                array(

                    'id' => 4,
                    'agenda_decision_code'=>'"प्रस्तावको प्रकरण ४ "निर्णय हुनुपर्ने ब्यहोरा" अन्तर्गत प्रस्तावित ................... व्यहोरा हटाई अरू प्रस्तावमा लेखावमोजिम गर्ने ।"',
                    'agenda_decision_content' => '"प्रस्तावको प्रकरण ४ "निर्णय हुनुपर्ने ब्यहोरा" अन्तर्गत प्रस्तावित ................... व्यहोरा हटाई अरू प्रस्तावमा लेखावमोजिम गर्ने ।"',
                    'display_order' => 4,
                ),
                array(

                    'id' => 5,
                    'agenda_decision_code'=>'"मन्त्रिपरिषद्, ................... " समितिमा छलफल गरी समितिको निर्णवबमोजिम गर्ने ।" वा मन्तिपरिषद्‌, ................... समितिमा छलफल गरी पेश गर्ने ।"  प्रस्ताव २ बा २ भन्दा बढी पेज भएमा सबैभन्दा पछाडिको पेजमा रातोले प्रिण्ट गर्ने ।"',
                    'agenda_decision_content' => '"मन्त्रिपरिषद्, ................... " समितिमा छलफल गरी समितिको निर्णवबमोजिम गर्ने ।" वा मन्तिपरिषद्‌, ................... समितिमा छलफल गरी पेश गर्ने ।"  प्रस्ताव २ बा २ भन्दा बढी पेज भएमा सबैभन्दा पछाडिको पेजमा रातोले प्रिण्ट गर्ने ।"',
                    'display_order' => 5,
                ),
            ]
        );
        DB::statement("SELECT SETVAL('agenda_decision_type_id_seq',10)");
    }

  
}
