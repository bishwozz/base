<?php

namespace App\Models\CoreMaster;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoreMaster\MstFiscalYear;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MstMinistry extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'mst_ministries';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
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
    public function fiscalYear(){
        return $this->belongsTo(MstFiscalYear::class,'fiscal_year_id','id');
    }

    public function appSetting(){
        return $this->hasOne(AppSetting::class,'ministry_id','id');
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

    public function setOrganogramAttribute($value){
        if(!$this->preventAttrSet){
            $attribute_name = "organogram";
            $disk = "uploads";

            $ministry_id= request()->ministry_id;
            $client_id = (isset(request()->id) ? request()->id : 0);

            $path  = 'Organogram/Ministry/###CLIENT_ID###/Files/';
            $destination_path = str_replace("###CLIENT_ID###", $client_id, $path);

            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        }else{
            $this->attributes['organogram'] = json_encode($value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
