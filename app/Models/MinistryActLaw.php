<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MinistryActLaw extends BaseModel
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ministry_act_laws';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    public static $type = [
        0=>'नयाँ',
        1=>'संशोधन',
    ];
    public static $status = [
        0=>'Pending',
        1=>'In Progress',
        2=>'Completed',
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
    public function setFileUploadAttribute($value){

        if(!$this->preventAttrSet){
            $attribute_name = "file_upload";
            $disk = "uploads";

            $ministry_detail= request()->ministry_id;
            // $user_id = (isset(request()->id) ? request()->id : 0);

            $path  = 'MinistryActLaw/###MINISTRY_ID###/Files';
            $destination_path = str_replace("###MINISTRY_ID###", $ministry_detail, $path);
            // $destination_path = str_replace("###USER_ID###", $user_id, $destination_path);

            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        }else{
            $this->attributes['file_upload'] = json_encode($value);
        }
    }
    public static function boot()
    {
        parent::boot();
        static::deleted(function ($obj) {
            \Storage::disk('uploads')->delete($obj->photo_path);
        });
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
