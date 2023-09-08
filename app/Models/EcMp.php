<?php

namespace App\Models;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class EcMp extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'ec_mp';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable =  ['name_en','name_lc','gender_id','district_id','post_id','photo_path','signature_path','mobile_number','email','display_order','is_active','remarks'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function gender()
    {

        return $this->belongsTo('App\Models\CoreMaster\MstGender', 'gender_id', 'id');
    }
    public function district()
    {
        return $this->belongsTo('App\Models\CoreMaster\MstFedDistrict', 'district_id', 'id');
    }
    public function post()
    {
        return $this->belongsTo('App\Models\MstPost','post_id','id');
    }
    public function commitee(){
        return $this->belongsTo('App\Models\MinistryMember','ministry_id','id');
    }
    public function ministry(){
        return $this->hasOne('App\Models\MinistryMember','mp_id','id');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
    public function setPhotoPathAttribute($value){
        $attribute_name = "photo_path";
        $disk = "uploads";

        $mp_id = (isset(request()->id) ? request()->id : 0);
        $path  = 'MpPhoto/###Mp_ID###/';
        $destination_path = str_replace("###Mp_ID###", $mp_id, $path);

        // dd($destination_path);


        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }


        if (\Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            // 3. Save the public path to the database
        // but first, remove "public/" from the path, since we're pointing to it from the root folder
        // that way, what gets saved in the database is the user-accesible URL
            // $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $destination_path.$filename;
        }
        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    } 
    public function setSignaturePathAttribute($value){

        $attribute_name = "signature_path";
        $disk = "uploads";

        $mp_id = (isset(request()->id) ? request()->id : 0);
        $path  = 'MpSignaturePhoto/###Mp_ID###/';
        $destination_path = str_replace("###Mp_ID###", $mp_id, $path);

        // dd($destination_path);


        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }


        if (\Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            // 3. Save the public path to the database
        // but first, remove "public/" from the path, since we're pointing to it from the root folder
        // that way, what gets saved in the database is the user-accesible URL
            // $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $destination_path.$filename;
        }
        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
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
