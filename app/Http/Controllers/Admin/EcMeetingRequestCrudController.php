<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\EcMp;
use App\Models\User;
use App\Models\Agenda;
// use App\Models\MstMeeting;
use App\Models\MstStep;
use App\Utils\PdfPrint;
use App\Models\Ministry;
use App\Models\Committee;
use App\Helpers\SmsHelper;
use App\Models\MstMeeting;
use Illuminate\Http\Request;
use App\Mail\MeetingSchedule;
use App\Models\AgendaHistory;
use App\Models\MstAgendaType;
use App\Models\Notifications;
use App\Models\MinistryMember;
use App\Base\BaseCrudController;
use App\Models\EcMeetingRequest;
use App\Models\TransferedAgenda;
use App\Models\AgendaDecisionType;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\MeetingMinuteDetail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Models\CoreMaster\AppSetting;
use App\Events\AgendaApprovedRejected;
use Illuminate\Support\Facades\Config;
use App\Models\MeetingAttendanceDetail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\CoreMaster\MstFiscalYear;
use App\Base\Helpers\GetNepaliServerDate;
use App\Jobs\SendEmailToEcMeetingRequest;
use Backpack\ReviseOperation\ReviseOperation;
use App\Http\Requests\EcMeetingRequestRequest;
use App\Models\MeetingsRequestApprovalHistory;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class EcMeetingRequestCrudController extends BaseCrudController
{
    private $user;
    private $action_method;
    use ReviseOperation;
    public function setup()
    {
        $this->user=backpack_user();
        $this->action_method = $this->crud->getActionMethod();
        $this->user=backpack_user();
        $this->user_role = backpack_user()->getRoleNames()[0];

        CRUD::setModel(EcMeetingRequest::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ec-meeting-request');
        CRUD::setEntityNameStrings(trans('menu.ecMeetingRequest'), trans('menu.ecMeetingRequest'));
        $this->crud->denyAccess(['edit','update','delete']);

        $this->addFilters();


        $this->checkPermission([
            'getMinistryMembers' => 'getMinistryMembers',
            'sendEmail' => 'sendEmail',
            'getAgendTable' => 'getAgendTable',
            'getToggleData' => 'getToggleData',
            'applyForMeetingAttend' => 'applyForMeetingAttend',
            'applyForMeetingAttendConfirmation' => 'applyForMeetingAttendConfirmation',
            'ministryMeetingAgendaPdf' => 'ministryMeetingAgendaPdf',
            'committeeMeetingAgendaPdf' => 'committeeMeetingAgendaPdf',
            'getAgendaDetails' => 'getAgendaDetails',
            'submitToChiefSecretary' => 'submitToChiefSecretary',
            'approve' => 'approve',
            'submitMeetingRequest' => 'submitMeetingRequest',
            'meetingRequestApproval' => 'meetingRequestApproval',
            'meetingRequestRejectView' => 'meetingRequestRejectView',
            'meetingRequestRejection' => 'meetingRequestRejection',
            'listRevisions' => 'listRevisions',
            'restoreRevision' => 'restoreRevision',
            'directAgendaView' => 'directAgendaView',
            'meetingRequestDetailDirectAgenda' => 'meetingRequestDetailDirectAgenda',
            'meetingRequestPdf' => 'meetingRequestPdf',
        ]);

        $this->crud->denyAccess('show');

        // Meeting Request Submit Button
        if($this->user->hasRole(Config::get('roles.name.cabinet_creator'))){
            $this->crud->allowAccess(['edit','update','delete']);
            $this->crud->addButtonFromView('line', 'submit_ec_meeting_request_btn', 'submit_ec_meeting_request_btn', 'beginning');

            // send mail Button
            // If meeting minute is created from this id
            // DB::table('ec_meeting')
            $this->crud->addButtonFromModelFunction('line', 'sendSmsEmail', 'sendSmsEmail', 'end');
        }
        // Meeting Request Approve and Reject Button
        if($this->user->hasRole(Config::get('roles.name.cabinet_approver'))){
            $this->crud->addButtonFromView('line', 'meetingRequestApproveReject', 'meetingrequestapprovereject', 'end');
            $this->crud->addClause('whereIn','level_id', [2,3,4]);

        }
        if($this->user->hasRole(Config::get('roles.name.chief_secretary'))){
            $this->crud->addButtonFromView('line', 'meetingRequestApproveReject', 'meetingrequestapprovereject', 'end');
            $this->crud->addClause('whereIn','level_id', [3,4]);
            $this->crud->allowAccess(['edit','update','delete']);

        }

        // pdf print
        $this->crud->addButtonFromModelFunction('line', 'printPdf', 'printPdf', 'beginning');



        if($this->user->hasRole('committee')){
            $this->crud->addClause('where', 'committee_id', $this->user->committee_id);
        }else{
            if(in_array($this->action_method,['index','list','search']))
            {
                $this->setCustomTabLinks();
            }

        }

        if($this->user->hasRole(Config::get('roles.name.chief_secretary'))){

            // $this->crud->addButtonFromModelFunction('line', 'sendSmsEmails', 'sendSmsEmails', 'beginning');
            // $this->crud->addButtonFromModelFunction('line', 'sendSmsEmailsAgain', 'sendSmsEmailsAgain', 'beginning');
        }

        $this->crud->orderBy('created_at','desc');
        if($this->action_method=='edit'|| $this->action_method=='create'){
            $this->data['script_js'] = $this->getScriptJs($this->action_method);
        }

        // Read Notifications
        $currentUri = Request()->getRequestUri();
        if($currentUri == '/admin/ec-meeting-request'){
            if(getRoleId() == Config::get('roles.id.ministry_creator') || getRoleId() == Config::get('roles.id.ministry_reviewer') || getRoleId() == Config::get('roles.id.ministry_secretary')){
                $notifications = DB::table('notifications')->where('roles_id', getRoleId())->where('ministry_id',backpack_user()->ministry_id)->where('type','MeetingRequest')->where('read_at', null)->get();

            }else{
                $notifications = DB::table('notifications')->where('roles_id', getRoleId())->where('type','MeetingRequest')->where('read_at', null)->get();

            }
            if($notifications){
                foreach($notifications as $notification){
                    Notifications::where('id', $notification->id)->update(['read_at' => now()]);
                }
            }
        }
    }

    protected function setCustomTabLinks(){
        $this->data['ministry_tab'] = "";
        $this->data['committee_tab'] = "";
        $this->data['list_tab_header_view'] = 'admin.tab.meeting_request_tab';

        $tab = $this->request->meeting;
        switch($tab){
            case "ministry":
                $this->data['ministry_tab'] = "disabled active";
                $this->crud->query->whereNull('committee_id');
            break;
            // case "committee":
            //     $this->data['committee_tab'] = "disabled active";
            //     $this->crud->query->whereNotNull('committee_id');
            // break;
            default:
                $this->data['ministry_tab'] = "disabled active";
                $this->crud->query->whereNull('committee_id');
            break;

        }
    }

    public function getScriptJs($action){
        $name='';
        $meeting_request_id='';
        if($action=='edit'){
            $meeting_request_id = "'meeting_request_id':".$this->crud->getCurrentEntryId();
        }else{
            $name ="$('#name-lc').val('मन्त्रिपरिषद् बैठक')";
        }
        
        return "
        $(document).ready(function(){
            $('.committee_id').hide();
            if($('#meeting_for').val() == '0'){
                $('.committee_id').hide();
                $.ajax({
                    url:'/admin/get-agenda-table',
                    type:'get',
                    data:{
                        'action':'".$action."',
                        ".$meeting_request_id."
                    },
                    success:response=>{
                        $('.agenda_table_div').html(response);
                    }
                });
            }else if($('#meeting_for').val() == '1'){
                $('.committee_id').show();
                $.ajax({
                    url:'/admin/get-committee-agenda-table',
                    type:'get',
                    data:{
                        'committee_id':$('#committee_id').val(),
                        'action':'".$action."',
                        ".$meeting_request_id."
                    },
                    success:response=>{
                        $('.agenda_table_div').html(response);
                    }
                });
            }".$name."
            $('.meeting_for').change(function() {
                $('.committee_id').hide();
                if($('#meeting_for').val() == '0'){
                    $('.committee_id').hide();
                    $('#name-lc').val('मन्त्रिपरिषद् बैठक');
                    $.ajax({
                        url:'/admin/get-agenda-table',
                        type:'get',
                        data:{
                            'action':'".$action."',
                            ".$meeting_request_id."
                        },
                        success:response=>{
                            $('.agenda_table_div').html(response);
                        }
                    });
                }else if($('#meeting_for').val() == '1'){
                    $('.committee_id').show();
                    $('#name-lc').val('समिति बैठक');
                    $.ajax({
                        url:'/admin/get-committee-agenda-table',
                        type:'get',
                        data:{
                            'committee_id':$('.committee_id option:selected').val(),
                            'action':'".$action."',
                            ".$meeting_request_id."
                        },
                        success:response=>{
                            $('.agenda_table_div').html(response);
                        }
                    });
                }
            });
            $('.committee_id').change(function() {
                $('#name-lc').val($('#committee_id option:selected').text()+' बैठक');
                $.ajax({
                    url:'/admin/get-committee-agenda-table',
                    type:'get',
                    data:{
                        'committee_id':$('#committee_id').val(),
                        'action':'".$action."',
                        ".$meeting_request_id."
                    },
                    success:response=>{
                        $('.agenda_table_div').html(response);
                    }
                });
            });
        });
        ";
    }
    // public function tabLinks(){
    //     return  $this->setEcMeetingRequestTabs();
    // }

    public function addFilters()
    {
        // fiscal year filter
        $this->crud->addFilter([
            'name'=>'fiscal_year_id',
            'label'=> 'आर्थिक वर्ष',
            'type'=>'select2'
          ], function() {
              return MstFiscalYear::orderBy('id', 'DESC')->pluck('code', 'id')->toArray();
          }, function($value) {
              $this->crud->addClause('where', 'fiscal_year_id', $value);
          });
        // month filter


        // $this->crud->addFilter([
        //     'name'=>'meeting_code',
        //     'label'=> 'बैठक कोड',
        //     'type'=>'select2'
        //   ],
        //   false,
        //    function($value) {
        //       $this->crud->addClause('where', 'meeting_code', $value);
        //   });
    }

    protected function setupListOperation()
    {
        $is_mailed = [];
        if(!backpack_user()->hasRole('minister')){
            $is_mailed = [
                'name' => 'is_mailed',
                'label' => trans('common.is_mailed'),
                'type' => 'check',
            ];
        }
        $cols = [
            $this->addRowNumberColumn(),
            [
                'name' => 'meeting_code',
                'type' =>'model_function',
                'function_name' =>'meeting_attendance',
                'label' => trans('common.meeting_code'),
            ],
            // [
            //     'name' => 'name_lc',
            //     'label' => trans('common.name_lc').'<br>'.trans('common.name_en'),
            //     'function_name' => 'name',
            //     'type' => 'model_function'
            // ],
            // [
            //     'name' => 'name_'.lang(),
            //     'label' => trans('common.name_'.lang()),
            //     'type' => 'text'
            // ],
            // [
            //     'name' => 'meeting_id',
            //     'type' => 'select',
            //     'entity' => 'meeting',
            //     'attribute' => 'name_'.lang(),
            //     'model' => MstMeeting::class,
            //     'label' => trans('common.meeting'),
            // ],
            [
                'name' => 'fiscal_year_id',
                'type' => 'select',
                'entity' => 'fiscal_year',
                'attribute' => 'code',
                'model' => MstFiscalYear::class,
                'label' => trans('common.fiscal_year'),
            ],
            [
                'name'=>'remarks',
                'type'=>'model_function',
                'label' => 'बैठक आहवान अवस्था',
                'function_name' => 'getMeetinRequestStatus',
            ],
            // [
            //     'name' => 'ministry',
            //     'type' => 'select',
            //     'entity' => 'ministry',
            //     'attribute' => 'name_'.lang(),
            //     'model' => Ministry::class,
            //     'label' => trans('common.ministry'),
            // ],
            [
                'name' => 'start_date_bs',
                'label' => trans('common.start_date_bs').'<br>'.trans('common.start_date_ad'),
                'function_name' => 'start_date',
                'type' => 'model_function'
            ],
            [
                'name' => 'start_time',
                'label' => trans('common.start_time'),
                'type' => 'model_function',
                'function_name' => 'startTime'
            ],
            $is_mailed,
            // [
            //     'name' => 'agenda',
            //     'type' => 'custom_repeatable',
            //     'label' => trans('common.agenda'),
            //     'columns' => [
            //         'agenda' => trans('common.agenda'),
            //         'step_id' => trans('common.step'),
            //     ]
            // ],
        ];
        $this->crud->addColumns(array_filter($cols));
    }

    protected function setupCreateOperation()
    {

        $agendas = Agenda::whereNull('ec_meeting_request_id')->where('is_approved',true)->where('is_hold',false)->get();
        // if(!count($agendas) > 0 && $this->crud->getActionMethod() != 'edit'){
        //     \Alert::warning('कुनै पनि प्रस्ताव स्वीकृत नभएकोले बैठक आहवान हुन सकेन !')->flash();
        //     return redirect('ec-meeting-request');
        // }
        $meeting_agenda_table = null;

        if($this->crud->getActionMethod() == 'edit'){



            $meeting_agenda_table = [
                'name' => 'direct_agenda_table',
                'label' => 'ठाडो प्रस्ताव',
                'fake'=>true,
                'value' => $this->crud->getCurrentEntryId(),
                'type' => 'direct_agenda_table',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
                'attributes' => [
                    'id' => 'meeting_request_id',
                ]
            ];
        }
        $fiscal_year_id = AppSetting::first()->fiscal_year_id;
        CRUD::setValidation(EcMeetingRequestRequest::class);
        if(!$this->user->hasRole('committee')){
            if($this->crud->getActionMethod()=='edit'){
                $meeting_for = [
                    'name'    => 'meeting_for',
                    'label'   => 'बैठक',
                    'type'    => 'select_from_array',
                    'options' => [
                        0 => 'मन्त्रिपरिषद् बैठक',
                        1 => 'समिति बैठक'
                    ],
                    // 'default' => 0,
                    'attributes' => [
                        'id' => 'meeting_for',
                        'disabled' => 'disabled',
                    ],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4 meeting_for',
                    ],

                ];
            }else{
                $meeting_for = [
                    'name'    => 'meeting_for',
                    'label'   => 'बैठक',
                    'type'    => 'select_from_array',
                    'options' => [
                        0 => 'मन्त्रिपरिषद् बैठक',
                        1 => 'समिति बैठक'
                    ],
                    // 'default' => 0,
                    'attributes' => [
                        'id' => 'meeting_for',
                    ],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4 meeting_for',
                    ],

                ];
            }

            $committee_id = [
                'label' => trans('common.committee'),
                'type' => 'select2',
                'name' => 'committee_id', // the db column for the foreign key
                'entity' => 'committee', // the method that defines the relationship in your Model
                'attribute' => 'name_lc', // foreign key attribute that is shown to user
                'model' => 'App\Models\Committee',
                'attributes' => [
                    'id' => 'committee_id',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 committee_id',
                ],
            ];
        }else{
            $meeting_for = [
                'name' =>'meeting_for',
                'type' => 'hidden',
                'value' => 1,
                'attributes' => [
                    'id' => 'meeting_for',
                ],
                'wrapperAttributes' => [
                    'class' => 'meeting_for',
                ],
            ];
            $committee_id = [
                'name' =>'committee_id',
                'type' => 'hidden',
                'value' => $this->user->committee_id,
                'default' => $this->user->committee_id,
                'attributes' => [
                    'id' => 'committee_id',
                ],
                'wrapperAttributes' => [
                    'class' => 'committee_id',
                ],
            ];
        }
        $meeting_code = null;
        if($this->crud->getActionMethod()=='edit'){
            $meeting_code = [
                    'name' => 'meeting_code',
                    'type' => 'text',
                    'label' => trans('common.meeting_code'),
                    'wrapper' => [
                        'class' => 'form-group col-md-4'
                    ],
                    'attributes' => [
                        'readonly' => 'readonly',
                    ],
                ];
        }
        $arr = [
            $meeting_code,
            $meeting_for,
            $committee_id,
            [
                'name' => 'fiscal_year_id',
                'type' => 'select2',
                'entity' => 'fiscal_year',
                'attribute' => 'code',
                'model' => MstFiscalYear::class,
                'label' => trans('common.fiscal_year'),
                'default' => $fiscal_year_id,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'disabled' => 'disabled',
                ],
                'options'   => (function ($query) {
                    return $query->orderBy('code', 'DESC')->get();
                }),
            ],
            [
                'name' => '3',
                'type' => 'custom_html',
                'value' => '<br>',

            ],
            [
                'name' => 'name_lc',
                'label' => trans('common.name_lc'),
                'type' => 'text',
                'attributes' => [
                    'id' => 'name-lc',
                    'required' => 'required',
                    'max-length' => 200,
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'start_date_bs',
                'type' => 'nepali_date_disabled',
                'label' => trans('common.start_date_bs'),
                'attributes' => [
                    'id' => 'start_date_bs',
                    'maxlength' => '10',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'start_time',
                'label' => trans('common.start_time'),
                'type' => 'time',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'plain_html_3',
                'type' => 'custom_html',
                'value' => '<div class="col-md-4"></div>',

            ],
            [
                'name' => 'plain_html_4',
                'type' => 'custom_html',
                'value' => '<div id="agenda_table_div" class="agenda_table_div"></div>',

            ],
            $meeting_agenda_table,
            $this->addRemarksField(),
        ];
        $this->crud->addFields(array_filter($arr));
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $fiscal_year = MstFiscalYear::find($request->fiscal_year_id)->code;
        $year = getCurrentNepaliYear();
        $agendas = Agenda::whereNull('ec_meeting_request_id')->where('is_approved',true)->where('is_hold',false)->get();

        if($request->meeting_for == 0){
            $count = EcMeetingRequest::where('start_date_bs', 'LIKE', `%$year%`)->where('deleted_uq_code', 1)->count();
            if($count){
                $count = ($count+1).'/'. $year;
            }else{
                $count = '1/'. $year;
            }
            $request->request->set('committee_id', null);
            $request->request->set('meeting_code', $count);
        }else{
            $count = EcMeetingRequest::where('start_date_bs', 'LIKE', `%$year%`)->where('committee_id', $request->committee_id)->where('deleted_uq_code', 1)->count();
            if($count){
                $count = ($count+1).'/'. $year;
            }else{
                $count = '1/'. $year;
            }
            $request->request->set('meeting_code', $count);
        }

        $date_ad = convert_ad_from_bs($request->start_date_bs);
        $request->request->set('start_date_ad', $date_ad);


        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();
        $request= $request->except('_token','_save_and_back','_http_referrer');
        // insert item in the db
        $item = $this->crud->create($request);
        $this->data['entry'] = $this->crud->entry = $item;

        if($item->meeting_for == 1){
            $agendas = TransferedAgenda::whereNull('meeting_request_id')
            ->where('committee_id',$item->committee_id)->where('is_hold',false)->get();
            foreach($agendas as $agenda){
                $agenda->meeting_request_id = $item->id;
                $agenda->save();

                AgendaHistory::create([
                    'ec_meeting_request_id' => $item->id,
                    'agenda_id' => $agenda->agenda->id,
                    'ministry_id' => $agenda->ministry_id,
                    'committee_id' => $agenda->committee_id,
                    'transfered_agenda_id' => $agenda->id,
                    'step_id' => 1,
                ]);
            }
        }else{
           

           
                foreach($agendas as $agenda){
                    $agenda->ec_meeting_request_id = $item->id;
                    $agenda->step_id = 1;
                    $agenda->save();
    
                    AgendaHistory::create([
                        'ec_meeting_request_id' => $item->id,
                        'agenda_id' => $agenda->id,
                        'ministry_id' => $agenda->ministry_id,
                        'step_id' => 1,
                    ]);

                }
           
        }


        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return redirect(backpack_url('ec-meeting-request/'));
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $id = $this->crud->getCurrentEntryId();
        $currentEntity = EcMeetingRequest::find($id);
        $fiscal_year = MstFiscalYear::find($request->fiscal_year_id)->code;
        $year = getCurrentNepaliYear();

        try{
            if($request->agenda_step_id){
                foreach ($request->agenda_step_id as $key => $step) {
                    if($request->meeting_for == 0){
                        Agenda::whereId($key)->update([
                            'step_id' => $step,
                        ]);
                        AgendaHistory::whereAgendaId($key)->update([
                            'step_id' => $step,
                        ]);
                    }else{
                        AgendaHistory::whereTransferedAgendaId($key)->update([
                            'step_id' => $step,
                        ]);
                    }
                }
            }
        }catch(Exception $e){
            dd($e);
        }

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update the row in the db
        $request= $request->except('_token','_save_and_back','_http_referrer');
        $item = $this->crud->update($request['id'],$request);
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function getMinistryMembers(Request $request,$meeting_request_id){

        $meeting_request = EcMeetingRequest::findOrFail($meeting_request_id);

        $selected_ministry_ids = AgendaHistory::select('ministry_id')->where('ec_meeting_request_id', $meeting_request_id)->get();

        $id = $this->crud->getCurrentEntryId();
        $members = DB::select("SELECT ecmp.id, ecmp.name_lc, ecmp.name_en,
        ecmp.email,ecmp.mobile_number, ecc.id as ministry_id, ecc.name_en as ministry_name_en,
        ecc.name_lc as ministry_name_lc, 0=1 as is_minute_mailed
                        FROM ec_ministry ecc
                        left join ec_ministry_members as ecm on ecm.ministry_id = ecc.id
                        left join ec_mp as ecmp on ecmp.id = ecm.mp_id
                        WHERE
                            ecm.deleted_uq_code = 1
                            GROUP BY ecmp.id, ecmp.name_lc,ecmp.mobile_number, ecmp.name_en, ecmp.email, ecc.id, ecc.name_en, ecc.name_lc
                            order by ecc.id
                            ");
        $ministry_id = [];
        foreach($members as $member){

            array_push($ministry_id, $member->ministry_id);

            foreach($selected_ministry_ids as $selected_ministry_id){
                if($selected_ministry_id->ministry_id == $member->ministry_id){
                    $member->is_selected = true;
                }else{
                    $member->is_selected = false;
                }
            }
        }
        $ministrys = Ministry::whereIn('id',$ministry_id)->get();

        foreach($ministrys as $ministry){
            foreach($selected_ministry_ids as $selected_ministry_id){
                if($selected_ministry_id->ministry_id == $ministry->id){
                    $ministry->is_selected = true;
                }else{
                    $ministry->is_selected = false;
                }
            }
        }

        return view('sendEmail.sendEmail',compact('members','id','ministrys','meeting_request'));
    }

    public function sendEmail(Request $request){
        
        if(isset($request->is_email) && $request->is_email=="on"){
            $id = $this->crud->getCurrentEntryId();
            $data = $this->getAgendaDetails($id);

            if(isset($request->is_sms) && $request->is_sms=="on"){
                $this->sendSms($request->ministry_member_phone,$request->meeting_request_date_bs,$request->meeting_request_time);
            }

            $emails = $request->ministry_member;
            // dd($emails);
            $emails = array_filter($emails);
            $path = Storage::disk('uploads')->getAdapter()->getPathPrefix();
            $pdf_full_path = $path.$data['meeting_request']->pdf_path;
            $extension = substr(strrchr($pdf_full_path,'.'),1);

            try {
                if (isset($emails)) {
                    dispatch(new SendEmailToEcMeetingRequest($emails, $pdf_full_path, $extension));
                    EcMeetingRequest::whereId($id)->update([
                        'is_mailed' => true,
                    ]);
                    \Alert::success(trans('common.emailSendSuccessful'))->flash();
                } else {
                    \Alert::error(trans('common.noMinistryMemberSelected'))->flash();
                }
            } catch (Exception $e) {
                dd($e);
            }
           
        }elseif(isset($request->is_sms) && $request->is_sms=="on"){
            $this->sendSms($request->ministry_member_phone,$request->meeting_request_date_bs,$request->meeting_request_time);
        }
         // Clear notifications
         DB::beginTransaction();
         try{
            $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_request_id',  $this->crud->getCurrentEntryId())->get();
            if ($notification_read) {
                foreach ($notification_read as $notification) {
                    $notification->update([
                        'read_at' => now(), // Set the read_at column to the current timestamp
                    ]);
                }
            }

            DB::commit();
         }catch(Exception $e){
            DB::rollBack();
            dd($e);
         }

        return redirect()->back();
    }


    public function sendSms($ministry_member_phone,$meeting_request_date_bs,$meeting_request_time){

        $Phone_no_get = $ministry_member_phone;
        $Phone_no = array_filter($Phone_no_get);
        try{
            if(isset($Phone_no)){
                foreach($Phone_no as $phone){
                        $message = "मन्त्रिपरिषदको बैठक यहि मिति $meeting_request_date_bs, समय $meeting_request_time मा बस्ने निर्णय भएकोले हजुरको उपस्थितिको लागि सादर अनुरोध छ| ";
                        $sms = new SmsHelper();
                        $sms->send($phone,$message);
                }
                \Alert::success(trans('common.smsSendSuccessful'))->flash();
            }else{
                \Alert::error(trans('common.noMinistryMemberSelected'))->flash();
            }
        }catch(Exception $e){
            dd($e);
        }




    }

    public function getAgendTable(Request $request){
        $action_status = null;
        if($request->action_status){
            $action_status = $request->action_status;
        }
        if($request->comittees){
            $comittees = Ministry::whereIn('id',$request->comittees)->where('deleted_uq_code',1)->get();
            return view('ecMeeting.meeting_agenda', ['comittees' => $comittees,'meeting_request_id'=>false,'action_status'=>$action_status])->render();
        }else{
            return false;
        }
    }

    public function getToggleData(Request $request){
        $ministry_id = $request->comittee;
        $agenda_id = $request->agenda;
        $agenda = Agenda::where('ministry_id',$ministry_id)->findOrFail($agenda_id);
        $status = $request->option;

        if($request->option=="2"){
            $agenda->is_rejected = false;
            $agenda->ec_meeting_request_id = $request->meeting_request_id;
            $agenda->save();
        }else if($request->option=="1"){
            $agenda->is_rejected = false;
            $agenda->ec_meeting_request_id = $request->meeting_request_id;
            $agenda->save();
        }else{
            $agenda->is_rejected = true;
            $agenda->ec_meeting_request_id = null;
            $agenda->save();
        }
    }

    public function applyForMeetingAttend($id){
        $mp_id = backpack_user()->mp_id;
        $meeting_attend = MeetingAttendanceDetail::select('apply_for_meeting_attendance','requested_date_bs','requested_date_ad','remarks')->whereMpId($mp_id)->whereMeetingRequestId($id)->first();

        if(backpack_user()->mp_id && $meeting_attend){

            $data = [
                'id' => $id,
                'mp_id' => $mp_id,
                'apply_for_meeting_attendance' => $meeting_attend->apply_for_meeting_attendance,
                'requested_date_bs' => $meeting_attend->requested_date_bs,
                'requested_date_ad' => $meeting_attend->requested_date_ad,
                'remarks' => $meeting_attend->remarks,
            ];
        }else{
            $data = [
                'id' => $id,
                'mp_id' => $mp_id,
                'requested_date_bs' => convert_bs_from_ad(),
                'requested_date_ad' => Carbon::now()->format('Y-m-d'),
            ];
        }
        return view('applyForMeetingAttend',$data);
    }

    public function applyForMeetingAttendConfirmation(Request $request,$id){
        try{
            MeetingAttendanceDetail::updateOrCreate([
                    'meeting_request_id' => $id,
                    'mp_id' => $request->mp_id,
                ],
                [
                    'apply_for_meeting_attendance' => $request->meeting_attend,
                    'requested_date_bs' => $request->requested_date_bs,
                    'requested_date_ad' => $request->requested_date_ad,
                    'remarks' => $request->remarks,
            ]);
            \Alert::success(trans('You have applied to attend the meeting.'))->flash();
        }catch(Exception $e){
            dd($e);
        }
        return redirect()->back();
    }

    public function ministryMeetingAgendaPdf($id){
        $data = $this->getAgendaDetails($id);

        $path = Storage::disk('uploads')->getAdapter()->getPathPrefix();
        $path = $path.$data['meeting_request']->pdf_path;

        return response()->file($path);
    }

    public function directAgendaView($ec_meeting_request_id)
    {

    $ministries = Ministry::where('deleted_uq_code',1)->get();
    $agenda_types = MstAgendaType::where('deleted_uq_code',1)->get();
    $meeting_request_detail = EcMeetingRequest::select('fiscal_year_id')->whereId($ec_meeting_request_id)->first();
    // $ec_meeting_request_id = $meeting_request_detail->meeting_request_id;
    $fiscal_year_id = $meeting_request_detail->fiscal_year_id;
    $agendas = AgendaHistory::select('agendas.agenda_code','agendas.id','agenda_histories.id as agenda_history_id','agendas.agenda_title',
                                    'agendas.agenda_number','agenda_histories.transfered_to','em.name_lc as ministry_name','agenda_histories.agenda_decision_type_id',
                                    'agenda_histories.decision_of_cabinet','agenda_histories.decision_of_committee','mat.id as agenda_type_id','agenda_histories.file_upload')
                            ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
                            ->leftJoin('mst_agenda_types as mat','agendas.agenda_type_id','=','mat.id')
                            ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')
                            ->where('agenda_histories.ec_meeting_request_id',$ec_meeting_request_id)
                            ->get();
    $agendaCode = Agenda::where('fiscal_year_id',$fiscal_year_id)->where('is_direct_agenda',true)->pluck('agenda_code')->toArray();
    $arr_initial_nos = [];
    if($agendaCode){
        foreach($agendaCode as $val){
            $arr_initial_nos[] = explode('-', $val)[0];
            $year = explode('-', $val)[1];
        }
        $max_val = max($arr_initial_nos);

        $agenda_code = ($max_val + 1) .'-'.$year;
    }else{
        $agenda_code =' 1-2080';

    }

    return view('agenda.directAgenda',compact('ministries','agenda_types','meeting_request_detail','ec_meeting_request_id','fiscal_year_id','agenda_code'));

    }

    // Meeting Request Detail For Direct Agenda
    public function meetingRequestDetailDirectAgenda(Request $request){

        $meeting_request_id = $request->meeting_request_id;
        $agenda_decision_type = AgendaDecisionType::where('is_active', true)->orderBy('display_order','ASC')->get();
        // dd($agenda_decision_type);
        $ministries = Ministry::where('deleted_uq_code',1)->get();
        $fiscal_years = MstFiscalYear::where('deleted_uq_code',1)->get();
        $agenda_types = MstAgendaType::where('deleted_uq_code',1)->get();
        $meeting_request_detail = EcMeetingRequest::select('id','start_date_bs','fiscal_year_id')->whereId($meeting_request_id)->get()->first();

        $agendas = AgendaHistory::select('agendas.agenda_code','agendas.id','agenda_histories.id as agenda_history_id','agendas.agenda_title',
                                        'agendas.agenda_number','agenda_histories.transfered_to','em.name_lc as ministry_name','agenda_histories.agenda_decision_type_id',
                                        'agenda_histories.decision_of_cabinet','agenda_histories.decision_of_committee','mat.id as agenda_type_id','agenda_histories.file_upload')
                                ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
                                ->leftJoin('mst_agenda_types as mat','agendas.agenda_type_id','=','mat.id')
                                ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')
                                ->where('agenda_histories.ec_meeting_request_id',$meeting_request_id)
                                ->where('agendas.is_direct_agenda',true)
                                ->orderBy('agendas.created_at','ASC')
                                ->get();

        return response()->json(['agenda'=>view('agenda.agenda_list_direct_agenda_request',compact('agendas','agenda_decision_type','ministries','fiscal_years','agenda_types'))->render(),
                                'meeting_request_detail'=>$meeting_request_detail]);
    }

    public function getAgendaDetails($id){
        $data = [];
        $data['meeting_request'] = EcMeetingRequest::select('start_date_bs','start_time','meeting_code','pdf_path')->whereId($id)->get()->first();
        $data['meeting_date_bs'] = convertToNepaliNumber($data['meeting_request']->start_date_bs);
        $data['meeting_start_time'] = convertToNepaliNumber(Carbon::parse($data['meeting_request']->start_time)->format('g:i:s A'));
        $data['agendas'] = AgendaHistory::select('agendas.agenda_number','agendas.agenda_title','agenda_histories.transfered_to',
        'em.name_lc as ministry_name','mat.name_lc as agenda_type','agenda_histories.file_upload as file_upload')
                    ->where('agenda_histories.ec_meeting_request_id',$id)
                    ->where('agendas.deleted_uq_code',1)
                    ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
                    ->leftJoin('mst_agenda_types as mat','agendas.agenda_type_id','=','mat.id')
                    ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')->get();
        $data['app_setting'] = AppSetting::select('letter_head_title_1','letter_head_title_2','letter_head_title_3','letter_head_title_4')->where('deleted_uq_code','1')->orderBy('updated_at', 'desc')->first();

        return $data;
    }

    public function submitToChiefSecretary($id){
        // $users = User::role(Config::get('chief_secretary'))->get();
        // $data = $this->getAgendaDetails($id);

        // // upload file to public storage
        // $pdf_path = 'MeetingAgendas/'.$data['meeting_request']->meeting_code.'/MeetingAgenda.pdf';
        // $html = view('agenda.agenda_pdf', $data)->render();
        // $pdf = PdfPrint::storeprintPortrait($html,"MeetingAgenda.pdf");
        // Storage::disk('uploads')->put($pdf_path, $pdf);

        // try{
        //     EcMeetingRequest::whereId($id)->update([
        //         'pdf_path' => $pdf_path,
        //     ]);
        // }catch(Exception $e){
        //     dd($e);
        // }

        // // fetch saved file and send email with attachment
        // $path = Storage::disk('uploads')->getAdapter()->getPathPrefix();
        // $pdf_full_path = $path.$pdf_path;
        // $extension = substr(strrchr($pdf_full_path,'.'),1);

        // try{
        //     if($users->count() > 0){
        //         foreach($users as $user){
        //             $email = $user->email;
        //             Mail::send('agenda.agenda', [],function($message) use($email,$pdf_full_path,$extension) {
        //                 $message->to($email)
        //                 ->from(env('MAIL_USERNAME'))
        //                 ->subject('बैठक प्रस्ताब विवरण')
        //                 ->attach($pdf_full_path, ['as'=>'MeetingAgenda'.$extension,'mime'=>'application/'.$extension]);
        //             });
        //         }
        //     }

        //     EcMeetingRequest::whereId($id)->update([
        //         'is_submitted_to_chief_secretary' => true,
        //     ]);
        //     return 1;
        // }catch(Exception $e){
        //     dd($e);
        // }
    }

    // public function approve($meeting_request_id){
    //     $meeting_request = EcMeetingRequest::findOrFail($meeting_request_id);
    //     $meeting_request->is_approved_by_chief_secretary = TRUE;
    //     $meeting_request->save();
    //     return redirect()->back();

    // }


    // Submit Meeting Request from agenda ministry_creator
    public function submitMeetingRequest($meeting_request_id){

        DB::beginTransaction();
        try{

            $users = User::role(Config::get('chief_secretary'))->get();

            $data = $this->getAgendaDetails($meeting_request_id);

            // upload file to public storage
            $pdf_path = 'MeetingAgendas/'.$data['meeting_request']->meeting_code.'/MeetingAgenda.pdf';
            $html = view('agenda.agenda_pdf', $data)->render();
            $pdf = PdfPrint::storeprintPortrait($html,"MeetingAgenda.pdf");
            Storage::disk('uploads')->put($pdf_path, $pdf);

            // dd(intval($meeting_request_id),$pdf_path);

            EcMeetingRequest::whereId(intval($meeting_request_id))->update([
                'pdf_path' => $pdf_path,
                'is_submitted' => true,
                'level_id' => 2,
            ]);

            $meeting_request = EcMeetingRequest::find($meeting_request_id);
            $data = [
                'status_id' => 1,
                'roles_id' => getRoleId(),
                'data'=> $meeting_request->getMeetingRequestApprovalStatusNotify(),
                'agenda_id' => null,
                'meeting_request_id' => $meeting_request_id,
                'meeting_minute_id' => null,
                'ministry_id' => null,
                'type' => 'MeetingRequest',

            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_request_id', $meeting_request_id)->get();
            
            if ($notification_read) {
                foreach ($notification_read as $notification) {
                    $notification->update([
                        'read_at' => now(), // Set the read_at column to the current timestamp
                    ]);
                }
            }
            event(new AgendaApprovedRejected($data));




            // // fetch saved file and send email with attachment
            // $path = Storage::disk('uploads')->getAdapter()->getPathPrefix();
            // $pdf_full_path = $path.$pdf_path;
            // $extension = substr(strrchr($pdf_full_path,'.'), 1);


            // try{
            //     if($users->count() > 0){
            //         foreach($users as $user){
            //             $email = $user->email;
            //             Mail::send('agenda.agenda', [],function($message) use($email,$pdf_full_path,$extension) {
            //                 $message->to($email)
            //                 ->from(env('MAIL_USERNAME'))
            //                 ->subject('बैठक प्रस्ताब विवरण')
            //                 ->attach($pdf_full_path, ['as'=>'MeetingAgenda'.$extension,'mime'=>'application/'.$extension]);
            //             });
            //         }
            //     }

            //     return 1;
            // }catch(Exception $e){
            //     dd($e);
            // }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Success',
            ], 200);;
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }

    // Print request meeting pdf
    public function meetingRequestPdf($meeting_request_id){

            $data = $this->getAgendaDetails($meeting_request_id);
            $pdf_path = 'MeetingAgendas/'.$data['meeting_request']->meeting_code.'/MeetingAgenda.pdf';
            $html = view('agenda.agenda_pdf', $data)->render();

            PdfPrint::printPortrait($html,"MeetingAgenda.pdf");
    }

    // Meeting Request Approve
    public function meetingRequestApproval($meeting_request_id){
        $level_id = 1;
        $meetings_minute_id = $meeting_request_id;
        // Cabinet 2nd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))){
            $level_id = 3;

        }
        // Cabinet 3rd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){
            $level_id = 4;
        }

        DB::beginTransaction();
        try{

            MeetingsRequestApprovalHistory::create([
                'status_id' => 1,
                'role_id' => getRoleId(),
                'meetings_request_id' => intval($meeting_request_id),
                'date_ad'=>dateToday(),
                'date_bs'=>convert_bs_from_ad(),
            ]);
            EcMeetingRequest::whereId($meeting_request_id)->update([
                'level_id' => $level_id,
            ]);
            if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){
                EcMeetingRequest::whereId($meeting_request_id)->update([
                    'is_approved' => true,
                ]);
            }

            $meeting_request = EcMeetingRequest::find($meeting_request_id);
            $data = [
                'status_id' => 1,
                'roles_id' => getRoleId(),
                'data'=> $meeting_request->getMeetingRequestApprovalStatusNotify(),
                'agenda_id' => null,
                'meeting_request_id' => $meeting_request_id,
                'meeting_minute_id' => null,
                'ministry_id' => null,
                'type' => 'MeetingRequest',


            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_request_id', $meeting_request_id)->get();
            
            if ($notification_read) {
                foreach ($notification_read as $notification) {
                    $notification->update([
                        'read_at' => now(), // Set the read_at column to the current timestamp
                    ]);
                }
            }
            event(new AgendaApprovedRejected($data));


            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Success',
            ], 200);;
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }
    //agenda rejection view
    public function meetingRequestRejectView($id){
        return view('ecMeeting.meeting_request_rejection_view',compact('id'));
    }
    //agenda rejection event
    public function meetingRequestRejection(Request $request,$meeting_request_id){
        DB::beginTransaction();
        try{

            $level_id = 1;

            // Cabinet 2nd level Permissions
            if(backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))){
                $level_id = 1;
            }
            // Cabinet 3rd level Permissions
            if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){
                $level_id = 2;
                EcMeetingRequest::whereId($meeting_request_id)->update([
                    'is_approved' => true,
                ]);
            }
            MeetingsRequestApprovalHistory::create([
                'status_id' => 0,
                'remarks'=>$request->remarks,
                'role_id' => getRoleId(),
                'meetings_request_id' => $meeting_request_id,
                'date_ad'=>dateToday(),
                'date_bs'=>convert_bs_from_ad(),
            ]);
            //if agenda is rejected by cabinet_approver, make agenda submission false
            if($this->user_role == Config::get('roles.name.cabinet_approver')){
                EcMeetingRequest::whereId($meeting_request_id)->update(['is_submitted' => false]);
            }
            if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){
                EcMeetingRequest::whereId($meeting_request_id)->update([
                    'is_approved' => false,
                ]);
            }
            EcMeetingRequest::whereId($meeting_request_id)->update([
                'level_id' => $level_id,
            ]);

            $meeting_request = EcMeetingRequest::find($meeting_request_id);
            $data = [
                'status_id' => 0,
                'roles_id' => getRoleId(),
                'data'=> $meeting_request->getMeetingRequestRejectionStatusNotify(). $request->remarks,
                'agenda_id' => null,
                'meeting_request_id' => $meeting_request_id,
                'meeting_minute_id' => null,
                'ministry_id' => null,
                'type' => 'MeetingRequest',

            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_request_id', $meeting_request_id)->get();
            
            if ($notification_read) {
                foreach ($notification_read as $notification) {
                    $notification->update([
                        'read_at' => now(), // Set the read_at column to the current timestamp
                    ]);
                }
            }
            event(new AgendaApprovedRejected($data));

            DB::commit();
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
        return redirect()->back();
    }



    // Destroy
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        DB::beginTransaction();
        try{

            $agendaHistory = AgendaHistory::where('agenda_id', $id);
            $meetingsRequestApprovalHistory = MeetingsRequestApprovalHistory::where('meetings_request_id', $id);

            if($agendaHistory){
                $agendaHistory->delete();
            }
            if($meetingsRequestApprovalHistory){
                $meetingsRequestApprovalHistory->delete();
            }

            $id = $this->crud->getCurrentEntryId() ?? $id;

            $this->crud->delete($id);

            DB::commit();
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
        return redirect()->back();

        // get entry ID from Request (makes sure its the last ID for nested resources)


    }
}
