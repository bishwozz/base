<?php

namespace App\Models;

use Carbon\Carbon;
// use App\Models\MstMeeting;
use App\Models\Agenda;
use App\Base\BaseModel;
use App\Models\Ministry;
use App\Models\Committee;
use App\Utils\DateHelper;
use App\Models\MstMeeting;
use App\Models\MeetingMinuteDetail;
use Illuminate\Support\Facades\Config;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\MeetingsRequestApprovalHistory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class EcMeetingRequest extends BaseModel
{
    use CrudTrait;
    use RevisionableTrait;

    public function identifiableName()
    {
        return $this->title_name;
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ec_meetings_requests';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['meeting_id','is_submitted','is_approved','level_id','fiscal_year_id','meeting_code','agenda','name_en','name_lc','start_date_ad','start_date_bs','start_time','remarks','is_mailed','meeting_for','committee_id','is_submitted_to_chief_secretary'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function name(){
        return $this->name_lc . "<br>" . $this->name_en;
    }

    public function start_date(){
        return '<span class="class-'.lang().'">'.$this->start_date_bs . '<br>' . $this->start_date_ad.'</span>';
    }
    public function startTime(){
        return '<span class="class-'.lang().'">'.$this->start_time. '</span>';
    }

    public function meeting_attendance(){
        $route = request()->path();
        // dd(request()->path());
        $route = substr($route, 0, -7);
        if(backpack_user()->hasRole('minister')){
            // $parameter = '<b><a data-fancybox data-type="ajax" data-src="'.url('/admin/ec-meeting-request/'.$this->id. '/apply-for-meeting-attend').'" href="javascript:;" title="Apply to attend meeting">' . $this->meeting_code  . '</a></b>';
            $parameter = $this->meeting_code;
        }elseif(backpack_user()->hasRole('ministry')){
            $parameter = $this->meeting_code;
        }else{
            $parameter = '<b><a href="' . url($route . '/' . $this->id . '/edit') . '" title="Click to Edit">' . $this->meeting_code  . '</a></b>';
        }

        return $parameter;
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function meeting_minutes(){
        return $this->hasMany(MeetingMinuteDetail::class,'meeting_request_id','id');
    }
    // public function meeting(){
    //     return $this->belongsTo(MstMeeting::class,'meeting_id','id');
    // }

    public function fiscal_year(){
        return $this->belongsTo(MstFiscalYear::class,'fiscal_year_id','id');
    }

    public function ministry(){
        // return $this->belongsTo(Ministry::class,'ministry_id','id');
        return $this->belongsToMany(Ministry::class,'meeting_request_ministry','meeting_request_id','ministry_id');
    }
    public function committee(){
        return $this->belongsTo(Committee::class,'committee_id','id');
    }

    public function agendas(){
        return $this->hasMany(Agenda::class,'ec_meeting_request_id','id');
    }

    public function meetingRequestApprovalHistory()
    {

        return $this->hasOne(MeetingsRequestApprovalHistory::class,'meetings_request_id','id')->latest();
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

    public function sendSmsEmail(){
        $exists = MeetingMinuteDetail::where('meeting_request_id', $this->id)->exists();
        if($exists) return;
        if($this->is_approved == true && $this->is_mailed == false){
            return '<a data-fancybox data-type="ajax" data-src="'.url('/admin/ec-meeting-request/'.$this->id. '/get-ministry-members').'" href="javascript:;" class="btn btn-sm btn-warning text-dark font-weight-bold" data-toggle="tooltip" title="इमेल पठाउनुहोस">'.trans('common.sendEmailSms').'</a>';
        }elseif($this->is_mailed == true){
            return '<a data-fancybox data-type="ajax" data-src="'.url('/admin/ec-meeting-request/'.$this->id. '/get-ministry-members').'" href="javascript:;" class="btn btn-sm btn-dark font-weight-bold" data-toggle="tooltip" title="पुन इमेल पठाउनुहोस">'.trans('common.sendEmailSmsAgain').'</a>';
        }
    }

    public function printPdf(){

        $meeting_detailss = MeetingsRequestApprovalHistory::where('meetings_request_id',$this->id)->where('role_id',Config::get('roles.id.chief_secretary'))->orderBy('created_at','DESC')->first();
        
        if(isset($meeting_detailss) && $meeting_detailss->status_id == 1 && ($this->pdf_path != null)){
            return '<a target="_blank" href="' . asset('storage/uploads/' . $this->pdf_path) . '" class="btn btn-sm btn-success print-btn" data-toggle="tooltip" title="बैठक आहवान प्रिन्ट गर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
        }else{
            return '<a target="_blank" href="ec-meeting-request-pdf/'.$this->id.'/ministry-meeting-agenda-pdf" class="btn btn-sm btn-primary print-btn" data-toggle="tooltip" title="बैठक आहवान प्रिन्ट गर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
        }
    }

    // Get Status of submitted
    public function getStatusRejection(){
        $user = backpack_user();
        $meetingRequestHistory = MeetingsRequestApprovalHistory::where('meetings_request_id',$this->id)
            ->latest('created_at')
            ->first();

        if(isset($meetingRequestHistory) && $meetingRequestHistory->status_id == 0){
            $role_id = $meetingRequestHistory->role_id;

            if($user->hasRole(Config::get('roles.name.cabinet_creator'))){
               
                if($role_id == Config::get('roles.id.cabinet_approver')) return true;

    
            }elseif($user->hasRole(Config::get('roles.name.cabinet_approver'))){
               
                if($role_id == Config::get('roles.id.chief_secretary')) return true;

            }
            
        }
        return ;
    }

    public function getMeetinRequestStatus(){
        $status = null;
        $user = backpack_user();
        $meetingRequestApprovalHistory = MeetingsRequestApprovalHistory::select('status_id','role_id')->where('meetings_request_id', $this->id)->latest('updated_at')->first();

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
    // Meeting Approval status for notification
    public function getMeetingRequestApprovalStatusNotify(){
        $status = null;
        $user = backpack_user();
        $status = 'बैठक आहवान कोड नं.: '.$this->meeting_code .'स्वीकृत गरिएको छ';
        

        return $status;
    }
    // Meeting Rejection status for notification
    public function getMeetingRequestRejectionStatusNotify(){
        $status = null;
        $user = backpack_user();
        $status = 'बैठक आहवान कोड नं.: '.$this->meeting_code .'फिर्ता गरिएको छ, बैठक आहवान फिर्ता गर्नुको कारण: ';

        return $status;
    }
    
    


    //custom functions
    public function convertToNepaliNumber($input)
    {
        $standard_numsets = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", '-','/');
        $devanagari_numsets = array("०", "१", "२", "३", "४", "५", "६", "७", "८", "९", '-','-');
        return str_replace($standard_numsets, $devanagari_numsets, $input);
    }
    public function convertNumberToNepaliMonth($input)
    {
        $standard_numsets = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11","12");
        $nepali_numsets = array("बैशाख", "जेष्ठ", "आषाढ", "श्रावण", "भाद्र", "आश्विन", "कार्तिक", "मंसिर", "पौष", "माघ", "फाल्गुन","चैत्र");
        return str_replace($standard_numsets, $nepali_numsets,$input);
    }

    public function convert24to12($input){
        $standard_numsets = array("13", "14", "15", "16", "17", "18", "19", "20", "21", "22", '23','24');
        $new_numsets = array("1", "2", "3", "4", "5", "6", "7", "8", "9", '10', '11','12');
        return str_replace($standard_numsets, $new_numsets, $input);
    }
    public function convertToNepaliDay($input)
    {
        $english_day = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        $nepali_day = array("आइतबार", "सोमबार", "मंगलबार", "बुधबार", "बिहिबार", "शुक्रबार", "शनिबार");
        return str_replace( $english_day, $nepali_day, $input);
    }
    public function getReadableDateAttribute()
    {
        $dateAD = Carbon::parse(convert_ad_from_bs($this->start_date_bs));
        $diffForHumanDate = $dateAD->isoFormat('dddd');
        $diff=$this->convertToNepaliDay($diffForHumanDate);
        return $diff;
    }

    public function meetingDateTimeNepali(){
        $date = $this->start_date_bs;
        $date = str_replace('-','/',$date);
        $date_helper = new DateHelper;
        $dayMonthYear = $date_helper->getDayMonthYear($date);
        $nepali_year = $this->convertToNepaliNumber($dayMonthYear['year']);
        if(strlen($dayMonthYear['month']) == 1){
            $nepali_month = $this->convertNumberToNepaliMonth('0'.$dayMonthYear['month']);
        }else{
            $nepali_month = $this->convertNumberToNepaliMonth($dayMonthYear['month']);
        }
        $nepali_day = $this->convertToNepaliNumber($dayMonthYear['day']);

        $time = $this->start_time;
        $match = preg_split('/[: ]/', $time);
        $hour = $match[0];
        $minute = $match[1];
        $samaya = " बिहान ";
        if($hour == 12){
            $hour = $this->convert24to12($hour);
            $samaya = " अपरान्ह ";
        }
        elseif($hour > 12){
            $hour = $this->convert24to12($hour);
            $samaya = " दिनको ";
        }
        $hour = $this->convertToNepaliNumber($hour);
        $minute = $this->convertToNepaliNumber($minute);
        $day = $this->getReadableDateAttribute();
        $time  = $hour.":".$minute;
        return $nepali_year.' साल '.$nepali_month.' '.$nepali_day.' गते '.$day.$samaya.$time.' बजे ';
    }

    public static function boot()
    {
        parent::boot();
    }

     // for revision
     protected $revisionFormattedFields = [
        'name_lc'      => 'string:%s',
        'start_date_bs'      => 'string:%s',
        'meeting_for'     => 'boolean:समिति बैठक|मन्त्रिपरिषद् बैठक',
        'updated_at'   => 'datetime:m/d/Y g:i A',
        'deleted_at' => 'isEmpty:Active|Deleted'
    ];


    protected $revisionFormattedFieldNames = [
        'name_lc'      => 'बैठक आहवानको नाम',
        'start_date_bs'      => 'मिति(वि.सं.)',
        'start_time'      => 'सुरु हुने समय',
        
    ];
}
