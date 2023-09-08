<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Base\BaseModel;
use App\Models\MstPost;
use App\Models\Ministry;
use Illuminate\Support\Str;
use App\Models\AgendaHistory;
use App\Models\Notifications;
use App\Models\AgendaFileType;
use App\Models\EcMeetingRequest;
use App\Models\TransferedAgenda;
use Illuminate\Support\Facades\DB;
use App\Models\AgendaApprovalHistory;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Agenda extends BaseModel
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

    protected $table = 'agendas';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $dontKeepRevisionOf = ['agenda_code'];


    protected $guarded = ['id'];
    protected $fillable =
        [

            // Ministry 1st level fields
            'ec_meeting_request_id',
            'ministry_id',
            'step_id',
            'agenda_code',
            'fiscal_year_id',
            'agenda_year',
            'agenda_type_id',
            'agenda_title',
            'agenda_description',
            'paramarsha_and_others',
            'agenda_reason_and_ministry_sipharis',
            'decision_reason',
            'file_upload',
            'is_submitted',
            'submitted_date_time',
            'is_direct_agenda',
            'minister_approval_date_bs',
            'year',

        ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    // Get Status of submitted
    public function getStatusRejection(){
        $user = backpack_user();
        $agendaHistory = AgendaApprovalHistory::where('agenda_id',$this->id)
            ->latest('created_at')
            ->first();

        if(isset($agendaHistory) && $agendaHistory->status_id == 0){
            $role_id = $agendaHistory->role_id;

            if($user->hasRole(Config::get('roles.name.ministry_creator'))){

                if($role_id == Config::get('roles.id.ministry_reviewer')) return true;

            }elseif($user->hasRole(Config::get('roles.name.ministry_reviewer'))){

                if($role_id == Config::get('roles.id.ministry_secretary')) return true;


            }elseif($user->hasRole(Config::get('roles.name.ministry_secretary'))){

                if($role_id == Config::get('roles.id.cabinet_creator')) return true;


            }elseif($user->hasRole(Config::get('roles.name.cabinet_creator'))){

                if($role_id == Config::get('roles.id.cabinet_approver')) return true;


            }elseif($user->hasRole(Config::get('roles.name.cabinet_approver'))){

                if($role_id == Config::get('roles.id.chief_secretary')) return true;

            }

        }
        return ;
    }

    // Latest date of ministry 3rd level user approved
    public function getLatestDateBs(){
        $agendaHistory = AgendaApprovalHistory::where('agenda_id',$this->id)
            ->where('role_id', Config::get('roles.id.ministry_secretary'))->where('status_id', 1)
            ->orderBy('created_at','DESC')->first();
            // dd($agendaHistory->date_bs);
        if($agendaHistory){
            $agenda_approve_date = $agendaHistory->date_bs;

        }else{

            $agenda_approve_date = null;
        }
        return $agenda_approve_date;
    }

    public function getAgendaStatus(){
        $status = null;
        $user = backpack_user();
        $agenda_history = AgendaHistory::select('decision_of_cabinet','decision_of_committee')->whereAgendaId($this->id)->latest('updated_at')->first();

        if($user->hasRole(Config::get('roles.name.ministry_secretary')) || $user->hasRole(Config::get('roles.name.chief_secretary'))){
            $status_msg = '<p style="color:blue">स्वीकृत गर्नुहोस।</p>';
        }else{

            $status_msg = '<p style="color:blue">पेश गर्नुहोस।</p>';
        }



        if(isset($agenda_history->decision_of_cabinet)){

            $status = 'निर्णय भएको';
        }else{
            switch($this->level_id){
                case 1:
                    $status = '<p>पेश गर्नुहोस।</p>';
                break;
                case 2:
                    if($user->hasRole(Config::get('roles.name.ministry_reviewer'))){
                        $status = $status_msg;
                    }else{

                        $status = '<p >मन्त्रालय रिभ्युअरको मा छ।</p>';
                    }
                break;
                case 3:
                    if($user->hasRole(Config::get('roles.name.ministry_secretary'))){
                        $status = $status_msg;
                    }else{

                        $status = '<p >मन्त्रालय सचिवको मा छ।</p>';
                    }
                break;
                case 4:
                    if($user->hasRole(Config::get('roles.name.cabinet_creator'))){
                        $status = $status_msg;
                    }else{

                        $status = '<p >मुख्य मन्त्रि कार्यालय अपरेटरको मा छ।</p>';
                    }
                break;
                case 5:
                    if($user->hasRole(Config::get('roles.name.cabinet_approver'))){
                        $status = $status_msg;
                    }else{

                        $status = '<p >मुख्य मन्त्रि कार्यालय रिभ्युअरको मा छ।</p>';
                    }
                break;
                case 6:
                    if($user->hasRole(Config::get('roles.name.chief_secretary'))){
                        $status = $status_msg;
                    }else{

                        $status = '<p >प्रमुख सचिवको मा छ।</p>';
                    }
                break;
                case 7:
                    $status = '<p >यो प्रस्ताव प्रमुख सचिवद्वारा स्वीकृत गरिएको छ।</p>';
                break;

                default:
                    $status = '<p >मन्त्रालय रिभ्युअरको मा छ।</p>';
                break;
            }

        }

        return $status;
    }
    public function getAgendaApprovalStatusNotify(){
        $status = null;
        $user = backpack_user();
        $status = 'प्रस्ताव: '.mb_substr($this->agenda_title, 0, 50, 'UTF-8') . 'स्वीकृत गरिएको छ।';
        

        return $status;
    }
    public function getAgendaRejectionStatusNotify(){
        $status = null;
        $user = backpack_user();
        $status = 'प्रस्ताव: '.mb_substr($this->agenda_title, 0, 50, 'UTF-8'). 'फिर्ता गरिएको छ। प्रस्ताब फिर्ता गर्नुको कारण: ';

        return $status;
    }

    // public function decision(){

    //     $submitted_date_time = $this->submitted_date_time;
    //     $agenda_history = AgendaHistory::select('decision_of_cabinet','decision_of_committee')->whereAgendaId($this->id)->latest('updated_at')->first();
    //     $agenda_details = AgendaApprovalHistory::where('agenda_id',$this->id)->orderBy('created_at','DESC')->first();
    //     if($this->is_submitted == false){
    //         if(isset($agenda_details) && $agenda_details->status_id === 0){
    //             return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-danger btn-sm">'.trans("common.notSubmitted").'</a>';
    //         }else{
    //             return '<a  href="javascript:;" class="btn btn-danger btn-sm">'.trans("common.notSubmitted").'</a>';
    //         }

    //     }else if(isset($agenda_history) && (isset($agenda_history->decision_of_cabinet) || isset($agenda_history->decision_of_committee))){
    //         if(isset($agenda_details) && $agenda_details->status_id === 0){
    //             return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-success btn-sm">'.trans("common.decisionTaken").'</a>';
    //         }else{
    //             return '<a  target="_blank" href="/admin/print-agenda/' . urlencode($this->id) . '/report" class="btn btn-success btn-sm">'.trans("common.decisionTaken").'</a>';
    //         }
    //     }else{
    //         if(isset($agenda_details) && $agenda_details->status_id === 0){
    //             return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-danger btn-sm">'.trans("common.decisionNotTaken").'</a>';
    //         }else{
    //             return '<a  href="javascript:;" class="btn btn-secondary style=btn-sm">'.trans("common.decisionNotTaken").'</a>';
    //         }
    //     }

    // }

    // Agenda Print Button
    public function agendaPrintButton(){
        $agenda_detailss = AgendaApprovalHistory::where('agenda_id',$this->id)->where('role_id',Config::get('roles.id.chief_secretary'))->orderBy('created_at','DESC')->first();
        
        if(isset($agenda_detailss) && $agenda_detailss->status_id == 1 && $this->file_upload !== null){
            return '<a target="_blank" href="' . asset('storage/uploads/' . $this->file_upload) . '" class="btn btn-sm btn-success print-btn" data-toggle="tooltip" title="प्रस्ताव हेर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';

        }else{
            
            return '<a target="_blank" href="/admin/agenda/' . urlencode($this->id) . '/view" class="btn btn-sm btn-success print-btn" data-toggle="tooltip" title="प्रस्ताव हेर्नुहोस"><i class="la la-file-pdf-o font-weight-bold"></i></a>';
        }

    }
    // Agenda Subject
    public function agendaTitle(){
        return wordwrap($this->agenda_title, 100, "</br>",true);
    }

    // get Ministry Approver
    public function getMinistryApproverName(){
        $ministry_secretary = AgendaApprovalHistory::where('agenda_id',$this->id)->where('role_id',Config::get('roles.id.ministry_secretary'))->where('status_id', 1)->orderBy('created_at','DESC')->first();
        $name_ministry_secretary = null;
        if($ministry_secretary){
            $name_ministry_secretary = User::find($ministry_secretary->created_by)->name;
        }
        return $name_ministry_secretary;
    }
    // get Ministry Approver
    public function getMinistryApproverDesignation(){
        $ministry_secretary = AgendaApprovalHistory::where('agenda_id',$this->id)->where('role_id',Config::get('roles.id.ministry_secretary'))->where('status_id', 1)->orderBy('created_at','DESC')->first();
        $post_name = null;
        if($ministry_secretary){
            $user = User::find($ministry_secretary->created_by);
            if($user){
                if(isset($user->MinistryEmployee->post)){
                    $post_name = $user->MinistryEmployee->post->name_lc;
                }
            }
        }
        return $post_name;
    }

    
   

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function agendaApprovalHistory()
    {

        return $this->hasOne(AgendaApprovalHistory::class,'agenda_id','id')->latest();
    }
    public function agendaApprovalNotification()
    {

        return $this->hasOne(Notifications::class,'agenda_id','id')->latest();
    }

    public function ministry_entity(){
        return $this->belongsTo('App\Models\Ministry','ministry_id','id');
    }
    public function agendaHistories(){
        return $this->hasMany('App\Models\AgendaHistory','agenda_id','id');
    }
    public function step(){
        return $this->belongsTo('App\Models\MstStep','step_id','id');
    }
    public function fiscal_year(){
        return $this->belongsTo('App\Models\CoreMaster\MstFiscalYear','fiscal_year_id','id');
    }
    public function agenda_type(){
        return $this->belongsTo('App\Models\MstAgendaType','agenda_type_id','id');
    }
    public function agenda_file_type(){
        return $this->belongsTo('App\Models\AgendaFileType','agenda_file_type_id','id');
    }
    public function ecMeetingRequest()
    {
        return $this->belongsTo(EcMeetingRequest::class,'ec_meeting_request_id','id');
    }
    public function transfered_agendas()
    {
        return $this->hasMany(TransferedAgenda::class,'agenda_id','id');
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


    public static function boot()
    {
        parent::boot();
        static::deleted(function ($obj) {
            \Storage::disk('uploads')->delete($obj->file_upload);
        });
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    // Get Year from current Date
    // public function getYear(){
    //     return getCurrentNepaliYear();
    // }


    public function holdAgendas(){
        if($this->ec_meeting_request_id == null && $this->is_approved == true && $this->is_rejected == false && $this->is_hold == false){
            return '<a onclick="ECABINET.confirmation('.$this->id.',this)" id="hold-agenda" href="javascript:;" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Hold Agenda">'.trans('common.holdAgenda').'</a>';
        }
    }
    public function unholdAgendas(){
        if($this->ec_meeting_request_id == null && $this->is_approved == true && $this->is_rejected == false && $this->is_hold == true){
            return '<a onclick="ECABINET.confirmation('.$this->id.',this)" id="unhold-agenda" href="javascript:;" class="btn btn-sm btn-success" data-toggle="tooltip" title="Unhold Agenda">'.trans('common.unholdAgenda').'</a>';
        }
    }
    public function holdTransferedAgendas(){
        $transfered_agenda = $this->transfered_agendas()->where('committee_id',backpack_user()->committee_id)->first();
        if(!$transfered_agenda->is_hold){
            return '<a onclick="ECABINET.confirmation('.$transfered_agenda->id.',this)" id="hold-transfered-agenda" href="javascript:;" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Hold Agenda">'.trans('common.holdAgenda').'</a>';
        }
    }
    public function unholdTransferedAgendas(){
        $transfered_agenda = $this->transfered_agendas()->where('committee_id',backpack_user()->committee_id)->first();
        if($transfered_agenda->is_hold){
            return '<a onclick="ECABINET.confirmation('.$transfered_agenda->id.',this)" id="unhold-transfered-agenda" href="javascript:;" class="btn btn-sm btn-success" data-toggle="tooltip" title="Unhold Agenda">'.trans('common.unholdAgenda').'</a>';
        }
    }



    // for revision
    protected $revisionFormattedFields = [
        'agenda_title'      => 'string:%s',
        // 'public'     => 'boolean:No|Yes',
        'updated_at'   => 'datetime:m/d/Y g:i A',
        'deleted_at' => 'isEmpty:Active|Deleted'
    ];


    protected $revisionFormattedFieldNames = [
        'agenda_title'      => 'प्रस्तावको विषय',
        'minister_approval_date_bs'      => 'विभागीय मन्त्रीबाट स्वीकृत प्राप्त मिति',
        'agenda_description'      => 'विषयको संक्षिप्त व्यहोरा',
        'paramarsha_and_others'      => 'प्राप्त परामर्श तथा अन्य प्रासंगिक कुरा',
        'agenda_reason_and_ministry_sipharis'      => 'प्रस्ताव पेश गर्नु पर्नाको कारण र मन्त्रालयको सिफारिस',
        'decision_reason'      => 'निर्णय हुनुपर्ने व्यहोरा',
        'agenda_type_id'      => 'प्रस्तावको प्रकार',
    ];

}
