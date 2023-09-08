<?php

namespace App\Models;

use App\Models\User;
use App\Base\BaseModel;
use App\Models\Ministry;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Models\EcMeetingRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\MeetingMinuteApprovalHistory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MeetingMinuteDetail extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ec_meeting_minute_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['meeting_request_id','is_submitted','is_approved','level_id','fiscal_year_id','meeting_id','meeting_content','is_verified','verified_date_ad','verified_date_bs','file_upload','is_mailed','committee_id','committee_attendance_detail'];
    // protected $hidden = [];
    // protected $dates = [];

    public function fiscal_year(){
        return $this->belongsTo('App\Models\CoreMaster\MstFiscalYear','fiscal_year_id','id');
    }

    // public function meeting_entity(){
    //     return $this->belongsTo('App\Models\MstMeeting','meeting_id','id');
    // }

    // public function ministry_entity(){
    //     // return $this->belongsTo('App\Models\Ministry','ministry_id','id');
    //     return $this->belongsToMany(Ministry::class,'meeting_request_ministry','meeting_request_id','ministry_id');

    // }

    public function meeting_request(){
        return $this->belongsTo(EcMeetingRequest::class,'meeting_request_id','id');
    }

    public function meetingMinuteApprovalHistory()
    {

        return $this->hasOne(MeetingMinuteApprovalHistory::class,'meeting_minute_id','id')->latest();
    }
    
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

        $meeting_minute_detail_id = (isset(request()->id) ? request()->id : 0);
        $path  = 'MeetingMinuteDetail/###MEETING_MINUTE_DETAIL###/Signature/';
        $destination_path = str_replace("###MEETING_MINUTE_DETAIL###", $meeting_minute_detail_id, $path);

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

    public function setFileUploadAttribute($value){
        $attribute_name = "file_upload";
        $disk = "uploads";

        $meeting_minute_detail_id = (isset(request()->id) ? request()->id : 0);
        $path  = 'MeetingMinuteDetail/###MEETING_MINUTE_DETAIL###/Files';
        $destination_path = str_replace("###MEETING_MINUTE_DETAIL###", $meeting_minute_detail_id, $path);
       
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
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
    public function sendEmail(){
     
        if($this->is_mailed == true ){
            return '<a data-fancybox data-type="ajax" data-src="'.url('/admin/meeting-minute-detail/'.$this->id. '/meeting-minute-details').'" href="javascript:;" class="btn btn-sm btn-warning" data-toggle="tooltip" title="पुन इमेल पठाउनुहोस्">'.trans('common.reSendEmail').'</a>';
        }elseif($this->file_upload && $this->is_approved == true && $this->is_mailed == false){
            return '<a data-fancybox data-type="ajax" data-src="'.url('/admin/meeting-minute-detail/'.$this->id. '/meeting-minute-details').'" href="javascript:;" class="btn btn-sm btn-warning" data-toggle="tooltip" title="इमेल पठाउनुहोस्">'.trans('common.sendEmail').'</a>';

        }
    }

    // status of meeting minute
    public function getMeetinMinuteStatus(){
        $status = null;
        $user = backpack_user();
        $meetingRequestApprovalHistory = MeetingMinuteApprovalHistory::select('status_id','role_id')->where('meeting_minute_id', $this->id)->latest('updated_at')->first();

        if($user->hasRole(Config::get('roles.name.chief_secretary'))){
            $status_msg = '<p style="color:blue">स्वीकृत गर्नुहोस।</p>';
        }else{

            $status_msg = '<p style="color:blue">पेश गर्नुहोस।</p>';
        }



        if(isset($meetingRequestApprovalHistory->status_id) && $meetingRequestApprovalHistory->status_id == 1 && $meetingRequestApprovalHistory->role_id == Config::get('roles.id.chief_secretary')){
            if($user->hasRole(Config::get('roles.name.chief_secretary'))){
                $status = 'स्वीकृत गरिएको छ';
            }else{
                $status = 'प्रमुख सचिवद्वारा स्वीकृत गरिएको छ';
            }
        }else{
            switch($this->level_id){
                case 1:
                    $status = '<p>पेश गर्नुहोस।</p>';
                break;
                case 2:
                    if($user->hasRole(Config::get('roles.name.cabinet_approver'))){
                        $status = $status_msg;
                    }else{

                        $status = '<p >मुख्य मन्त्रि कार्यालय रिभ्युअरको मा छ।</p>';
                    }
                break;
                case 3:
                    if($user->hasRole(Config::get('roles.name.chief_secretary'))){
                        $status = $status_msg;
                    }else{
                        $status = '<p > प्रमुख सचिवको मा छ।</p>';
                    }
                break;

                default:
                    $status = '<p >मुख्य मन्त्रि कार्यालय रिभ्युअरको मा छ।</p>';
                break;
            }

        }

        return $status;
    }

    // get Cabinet Approver
    public function getMinistryApproverName(){
        $chief_secretary = MeetingMinuteApprovalHistory::where('meeting_minute_id',$this->id)->where('role_id',Config::get('roles.id.chief_secretary'))->where('status_id', 1)->orderBy('created_at','DESC')->first();
        $name_chief_secretary = null;
        if($chief_secretary){
            $name_chief_secretary = User::find($chief_secretary->created_by)->name;
        }
        return $name_chief_secretary;
    }
    // get Cabinet Approver
    public function getMinistryApproverDesignation(){
        $chief_secretary = MeetingMinuteApprovalHistory::where('meeting_minute_id',$this->id)->where('role_id',Config::get('roles.id.chief_secretary'))->where('status_id', 1)->orderBy('created_at','DESC')->first();
        $post_name = null;
        if($chief_secretary){
            $user = User::find($chief_secretary->created_by);
            if($user){
                if($user->MinistryEmployee){

                    $post_name = $user->MinistryEmployee->post->name_lc;
                }
            }
        }
        return $post_name;
    }

    public function meetingMinutePdf(){
        if($this->committee_id == null){
                // return '<a target="_blank" href="meeting-minute-detail/'.$this->id.'/meeting-minute-details-pdf" class="btn btn-sm btn-primary print-btn" data-toggle="tooltip" title="बैठक माइनुट प्रिन्ट गर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
            if($this->file_upload){
                return '<a target="_blank" href="' . asset('storage/uploads/' . $this->file_upload) . '" class="btn btn-sm btn-success print-btn" data-toggle="tooltip" title="बैठक माइनुट प्रिन्ट गर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
            }else if(backpack_user()->hasRole('minister')){

                return '<a data-fancybox data-type="ajax" data-src="meeting-minute-detail/'.$this->id.'/meeting-minute-for-minister-pdf" href="javascript:;" style="padding: 1px 4px;" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>';
            }else{ 
                return '<a target="_blank" href="meeting-minute-detail/'.$this->id.'/meeting-minute-details-pdf" class="btn btn-sm btn-primary print-btn" data-toggle="tooltip" title="बैठक माइनुट प्रिन्ट गर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
            }
        }else{
            return '<a target="_blank" href="meeting-minute-detail/'.$this->id.'/committee-meeting-minute-details-pdf" class="btn btn-sm btn-primary print-btn" data-toggle="tooltip" title="बैठक माइनुट प्रिन्ट गर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
        }
    }
    public function meetingAgendaPdf(){
            return '<a target="_blank" href="meeting-agenda-detail/'.$this->id.'/meeting-agenda-details-pdf" class="btn btn-sm btn-primary print-btn" data-toggle="tooltip" title="बैठक माइनुट प्रिन्ट गर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
    }
    public function verifiedDateBS(){
        return '<span class="class-'.lang().'">'.$this->verified_date_bs. '</span>';

    }
    public function verifiedDateAD(){
        return '<span class="class-'.lang().'">'.$this->verified_date_ad. '</span>';
    }


    // Get Status of submitted
    public function getStatusRejection(){
        $user = backpack_user();
        $minuteHistory = MeetingMinuteApprovalHistory::where('meeting_minute_id',$this->id)
            ->latest('created_at')
            ->first();

        if(isset($minuteHistory) && $minuteHistory->status_id == 0){
            $role_id = $minuteHistory->role_id;

            if($user->hasRole(Config::get('roles.name.cabinet_creator'))){
               
                if($role_id == Config::get('roles.id.cabinet_approver')) return true;

    
            }elseif($user->hasRole(Config::get('roles.name.cabinet_approver'))){
               
                if($role_id == Config::get('roles.id.chief_secretary')) return true;
            }
            
        }
        return ;
    }


     // Meeting Minute Approval status for notification
     public function getMeetingMinuteApprovalStatusNotify(){
        $status = null;
        $user = backpack_user();
        $status = 'बैठक आहवान कोड नं.: '.$this->meeting_request->meeting_code. 'को बैठक माइनुट स्वीकृत गरिएको छ।';
        return $status;
    }
    // Meeting Minute Rejection status for notification
    public function getMeetingMinuteRejectionStatusNotify(){
        $status = null;
        $user = backpack_user();
        $status = 'बैठक आहवान कोड नं.:  '.$this->meeting_request->meeting_code. ' को बैठक माइनुट फिर्ता गरिएको छ।  फिर्ता गर्नुको कारण: ';

        return $status;
    }

}
