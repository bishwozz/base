<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeDetail extends BaseModel
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'office_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    public static $is_information_updated = [
        0=>'नभएको',
        1=>'भएको',
    ];
    public static $internal_control_system = [
        0=>'निर्माण नभएको',
        1=>'निर्माणको क्रममा रहेको',
        2=>'निर्माण भई कार्यान्वयनमा आएको',
    ];
    public static $toilet_status = [
        0=>'नभएको',
        1=>'भएको',
        2=>'निर्माणाधीन',
    ];
    public static $public_procurement = [
        0=>'मौजुदा जनशक्तिबाट मात्र गर्ने गरिएको',
        1=>'बाहिरि परामर्शदाता मार्फत मात्र गर्ने गरिएको',
        2=>'मौजुदा जनशक्ति र बाहिरि परामर्शदाता मार्फत गर्ने गरिएको',
    ];
    public static $inspection_monitoring_period = [
        0=>'माषिक रुपमा गर्ने गरिएको',
        1=>'चौमासिक रुपमा गर्ने गरिएको',
        2=>'हाल सम्म नगरेको',
    ];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function ministry()
    {
        return $this->belongsTo('App\Models\CoreMaster\MstMinistry', 'ministry_id', 'id');
    }
    public function fiscal_year()
    {
        return $this->belongsTo('App\Models\CoreMaster\MstFiscalYear', 'fiscal_year_id', 'id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
