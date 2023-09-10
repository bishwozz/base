<?php
use App\Utils\DateHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Base\Helpers\GetNepaliServerDate;

/**
 * Convert BS date to AD
 *
 * @param string|null $date_ad
 * @return string
 */
function convert_bs_from_ad(string $date_ad = null) {
    if(empty($date_ad)) {
        $date_ad = Carbon::now()->todateString();
    }

    $dateHelper = new DateHelper();
    return $dateHelper->convertBsFromAd($date_ad);
}

function get_current_fiscal_year(){
    $date_ad = Carbon::now()->todateString();
    $dateHelper = new DateHelper();
    $date_bs =  $dateHelper->convertBsFromAd($date_ad);
    return $dateHelper->fiscalYear($date_bs);
}
/**
 * Convert date from AD to BS
 *
 * @param string|null $date_bs
 * @return string
 */
function convert_ad_from_bs(string $date_bs = null) {
    if(empty($date_bs)) {
        $date_bs = Carbon::now()->todateString();
        return $date_bs;
    }

    $dateHelper = new DateHelper();
    return $dateHelper->convertAdFromBs($date_bs);
}

function current_nepali_date_formatted(){
    $nepali_Date = new GetNepaliServerDate();      
    return $nepali_Date->getNepaliDate();
}


//get all models
function modelCollection()
{
    // $output = [];
    $base_path = \base_path().'/app/Models';
    
    $entities = \scandir($base_path);

    fetchAllEntities($base_path,$entities);

    $final_output=Session::get('temp_output');

    Session::forget('temp_output');

    return $final_output;
}

//fetch all entities/models in output dir

function fetchAllEntities($base_path,$entities)
{
    foreach($entities as $entity)
    {
        if(\str_starts_with($entity,'.')) continue;  //ignore '.' and '..' files

        if(is_dir($base_path.'/'.$entity)){
            fetchAllEntities($base_path,\scandir($base_path.'/'.$entity));
        }else{
            Session::put('temp_output.'.\strtolower(\substr($entity,0,-4)),\substr($entity,0,-4));

        }
    }
}

function dateToString($date)
{
    return Carbon::parse($date)->toDateString();
}

function generate_date_with_extra_days(string $date = null,$days) {
    if(empty($date)) {
        $date = Carbon::now();
        $date->subDays($days);
        return $date;
    }
    $date=Carbon::parse($date);
    $date->subDays($days);

    return Carbon::parse($date)->toDateString();

}

function dateToday(){
    return Carbon::now()->toDateString();
}

function lang()
{
    $lang = App::getLocale();

    $lang = $lang == 'np'? 'lc' : $lang;
    return $lang;
}


