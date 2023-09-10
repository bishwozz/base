<?php
namespace App\Base\Traits;

use App\Models\AppSettings;
use Illuminate\Support\Facades\DB;


trait HeaderFooterData
{
    private function getApplicationSettingsData(){
        
        $college_details = AppSettings::first();
                     
        return $college_details;
    }
}