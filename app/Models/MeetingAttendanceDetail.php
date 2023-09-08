<?php

namespace App\Models;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MeetingAttendanceDetail extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'meeting_attendance_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['meeting_request_id','meeting_id','agenda','mp_id','ministry_id','apply_for_meeting_attendance','requested_date_bs','requested_date_ad','remarks','is_scheduled','is_present','present_time','signature','display_order'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function requested_date(){
        return $this->requested_date_bs . '<br/>' . $this->requested_date_ad;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function meeting_request(){
        return $this->belongsTo(EcMeetingRequest::class,'meeting_request_id','id');
    }

    // public function meeting(){
    //     return $this->belongsTo(MstMeeting::class,'meeting_id','id');
    // }

    public function mp(){
        return $this->belongsTo(EcMp::class,'mp_id','id');
    }

    public function step(){
        return $this->belongsTo(MstStep::class,'step_id','id');
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
    public function setSignatureAttribute($value){
        $attribute_name = "signature";
        $disk = "uploads";

        $meeting_attendance_detail_id = (isset(request()->id) ? request()->id : 0);
        $path  = 'MeetingAttendanceDetailSignature/###MEETING_ATTENDANCE_DETAIL###/';
        $destination_path = str_replace("###MEETING_ATTENDANCE_DETAIL###", $meeting_attendance_detail_id, $path);

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
