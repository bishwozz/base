<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\EcMp;
// use App\Models\MstMeeting;
use App\Models\Agenda;
use App\Models\MstPost;
use App\Utils\PdfPrint;
use App\Models\Ministry;
use App\Utils\DateHelper;
use App\Models\MstMeeting;
use Illuminate\Http\Request;
use App\Mail\MeetingSchedule;
use App\Models\AgendaHistory;
use App\Models\MstAgendaType;
use App\Models\Notifications;
use App\Models\MinistryMember;
use App\Base\BaseCrudController;
use App\Models\EcMeetingRequest;
use App\Models\MinistryEmployee;
use App\Models\AgendaDecisionType;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\MeetingMinuteDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Models\AgendaApprovalHistory;
use App\Models\CoreMaster\AppSetting;
use App\Events\AgendaApprovedRejected;
use Illuminate\Support\Facades\Config;
use App\Models\MeetingAttendanceDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\MeetingMinuteApprovalHistory;
use App\Http\Requests\MeetingMinuteDetailRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class MeetingMinuteDetailCrudController extends BaseCrudController
{
    private $user;
    private $action_method;
    public function setup()
    {
        $this->user=backpack_user();
        $this->action_method = $this->crud->getActionMethod();
        $mode = $this->crud->getActionMethod();
        $this->user=backpack_user();
        $this->user_role = backpack_user()->getRoleNames()[0];
        // $this->crud->denyAccess(['create','update','show','edit']);

        

        CRUD::setModel(\App\Models\MeetingMinuteDetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/meeting-minute-detail');
        CRUD::setEntityNameStrings(trans('menu.meetingMinute'), trans('menu.meetingMinute'));

        $this->crud->denyAccess('show');
        $this->crud->addButtonFromModelFunction('line', 'meetingMinutePdf', 'meetingMinutePdf', 'beginning');
        $this->checkPermission(['meetingRequestDetail'=>'meetingRequestDetail','getDataMeetingMinuteDetails'=>'getDataMeetingMinuteDetails',
            'meetingMinuteDetail'=>'meetingMinuteDetail','meetingMinuteDetailPdf'=>'meetingMinuteDetailPdf',
            'uploadDialog'=>'uploadDialog','saveFile'=>'saveFile','meetingAgendaDetailPdf'=>'meetingAgendaDetailPdf','uploadPdf'=>'uploadPdf',
            'printAgenda'=> 'printAgenda','meetingMinuteSendEmail' => 'meetingMinuteSendEmail','getAgendaDecisionTypeContent'=> 'getAgendaDecisionTypeContent',
            'committeeMeetingMinuteDetailPdf'=>'committeeMeetingMinuteDetailPdf','directAgendaView'=>'directAgendaView','saveDirectAgenda'=>'saveDirectAgenda',
            'meetingRequestDetailDirectAgenda' => 'meetingRequestDetailDirectAgenda','printDirectAgenda'=> 'printDirectAgenda',
            'meetingMinuteForMinisterPdf' => 'meetingMinuteForMinisterPdf',
            'saveAgendaDecisionTypeContent' => 'saveAgendaDecisionTypeContent',
            'submitMeetingMinute' => 'submitMeetingMinute',
            'meetingMinuteRejection' => 'meetingMinuteRejection',
            'meetingMinuteRejectView' => 'meetingMinuteRejectView',
            'meetingMinuteApproval' => 'meetingMinuteApproval',
        ]);

        if(backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))){
            $this->crud->allowAccess(['create','delete','edit','update']);
        }

        // Meeting Minute Submit Button
        if($this->user->hasRole(Config::get('roles.name.cabinet_creator'))){

            $this->crud->addButtonFromModelFunction('line', 'sendEmail', 'sendEmail', 'beginning');
            $this->crud->addButtonFromView('line', 'submitMeetingRequest', 'submit_ec_meeting_minute_btn', 'beginning');

        }
     
        // Meeting Minute Approve and Reject Button
        if($this->user->hasRole(Config::get('roles.name.cabinet_approver'))){
            $this->crud->addButtonFromView('line', 'meetingMinuteApproveReject', 'meetingminuteapprovereject', 'end');
            $this->crud->addClause('whereIn','level_id', [2,3,4]);
        }
        if($this->user->hasRole(Config::get('roles.name.chief_secretary'))){
            $this->crud->addButtonFromView('line', 'meetingMinuteApproveReject', 'meetingminuteapprovereject', 'end');
            $this->crud->addClause('whereIn','level_id', [3,4]);
        }
        // pdf Print
        // $this->crud->addButtonFromModelFunction('line', 'printPdf', 'printPdf', 'beginning');

        $att_members =[];
        if($this->user->hasRole('committee')){

            $this->crud->addClause('where', 'committee_id', $this->user->committee_id);

        }else{

            if(in_array($this->action_method,['index','list','search'])){

                $this->setCustomTabLinks();
            }
        }

        if($mode == 'create'){
            $att_members = DB::table('ec_ministry_members as emm')
                ->leftJoin('ec_mp as em','em.id','emm.mp_id')
                ->leftJoin('ec_ministry as emy','emy.id','emm.ministry_id')
                ->leftJoin('mst_posts as mp','mp.id','em.post_id')
                ->select('em.id','em.name_lc as member_name','mp.name_lc as post','emy.id as ministry_id','emy.name_lc as ministry')
                ->where('em.is_active',true)->where('emy.is_active',true)
                ->orderBy('em.display_order')
                ->get();
        }

        if($mode == 'edit')
        {
            $att_members = DB::table('meeting_attendance_details as mad')
                        ->leftJoin('ec_mp as em','em.id','mad.mp_id')
                        ->leftJoin('ec_ministry as emy','emy.id','mad.ministry_id')
                        ->leftJoin('mst_posts as mp','mp.id','em.post_id')
                        ->select('em.id','em.name_lc as member_name','mp.name_lc as post','emy.id as ministry_id','emy.name_lc as ministry','mad.is_present')
                        ->where('em.is_active',true)
                        ->where('emy.is_active',true)
                        ->where('mad.meeting_request_id',$this->crud->getCurrentEntry()->meeting_request_id)
                        ->orderBy('emy.display_order')
                        ->get();

        }
        $this->data['att_members'] = $att_members;

        // Read Notifications
        $currentUri = Request()->getRequestUri();
        if($currentUri == '/admin/meeting-minute-detail'){
            if(getRoleId() == Config::get('roles.id.ministry_creator') || getRoleId() == Config::get('roles.id.ministry_reviewer') || getRoleId() == Config::get('roles.id.ministry_secretary')){
                $notifications = DB::table('notifications')->where('roles_id', getRoleId())->where('ministry_id',backpack_user()->ministry_id)->where('type','MeetingMinute')->where('read_at', null)->get();

            }else{
                $notifications = DB::table('notifications')->where('roles_id', getRoleId())->where('type','MeetingMinute')->where('read_at', null)->get();

            }
            if($notifications){
                foreach($notifications as $notification){
                    Notifications::where('id', $notification->id)->update(['read_at' => now()]);
                }
            }
        }
    }

    // Set Tab Links
    protected function setCustomTabLinks(){
        $this->data['ministry_tab'] = "";
        $this->data['committee_tab'] = "";
        $this->data['list_tab_header_view'] = 'admin.tab.meeting_minute_tab';

        $tab = $this->request->meeting;
        if($tab == null)$tab="ministry";
        Session::put('minute_tab',$tab);

        switch($tab){
            case 'ministry':
                $this->data['ministry_tab'] = "disabled active";
                $this->crud->query->whereNull('committee_id');
                $this->crud->OrderBy('created_at','desc');
            break;
            case 'committee':
                $this->data['committee_tab'] = "disabled active";
                $this->crud->query->whereNotNull('committee_id');
                $this->crud->OrderBy('created_at','desc');
            break;
            default:
                $this->data['ministry_tab'] = "disabled active";
                $this->crud->query->whereNull('committee_id');
                $this->crud->OrderBy('created_at','desc');
            break;
        }
    }

    // List Operation
    protected function setupListOperation()
    {
        $mp = [];
        $file_upload = null;


        if($this->user->hasRole(Config::get('roles.name.cabinet_creator'))){
            $file_upload = [
                'name' => 'file_upload',
                'label' => 'फाइल',
                'type' => 'custom_upload'
            ];
        }
        $columns = [
			$this->addRowNumberColumn(),
            [
                'name' => 'meeting_request_id',
                'label' => trans('common.meeting_request'),
                'type' => 'select',
                'entity' => 'meeting_request',
                'model'=> EcMeetingRequest::class,
                'attribute' => 'meeting_code',
                'attributes' => [
                    'id' => 'meeting_request_id',
                ],
            ],
            $file_upload,
            [
                'name' => 'fiscal_year_id',
                'type' => 'select',
                'label' => trans('common.fiscal_year'),
                'entity' => 'fiscal_year',
                'model' => MstFiscalYear::class,
                'attribute' => 'code'
            ],
            [
                'name'=>'remarks',
                'type'=>'model_function',
                'label' => 'बैठक माइनुट अवस्था',
                'function_name' => 'getMeetinMinuteStatus',
            ],
            [
                'name' => 'is_approved',
                'label' => trans('common.is_verified'),
                'type' => 'check',
            ],
            [
                'name' => 'verified_date_bs',
                'type' => 'model_function',
                'function_name' => 'verifiedDateBS',
                'label' => trans('common.verified_date_bs')
            ],
            [
                'name' => 'is_mailed',
                'label' => trans('common.is_mailed'),
                'type' => 'check',
            ],
		];
        $this->crud->addColumns(array_filter($columns));
    }

    // Create Operation
    protected function setupCreateOperation()
    {

        // 
        $minute_tab = Session::get('minute_tab');


        $meeting_request_ids = null;
        $mode = $this->crud->getActionMethod();
        if($mode=='edit'){
            $meeting_request_ids = EcMeetingRequest::select('meeting_code as meeting_code','id')->where('id',$this->crud->getCurrentEntry()->meeting_request_id)->get();
        }else{
            $meeting_ids = MeetingMinuteDetail::select('meeting_request_id')->where('deleted_uq_code',1)->distinct('meeting_request_id')->get()->pluck('meeting_request_id')->toArray();
            if($minute_tab == "ministry"){
                $meeting_request_ids = EcMeetingRequest::select('meeting_code as meeting_code','id')->where('is_approved', true)->whereNotIn('id',$meeting_ids)->whereNull('committee_id')->orderBy('name_lc', 'ASC')->get();
            }else{
                $meeting_request_ids = EcMeetingRequest::select('meeting_code as meeting_code','id')->where('is_approved', true)->whereNotIn('id',$meeting_ids)->whereNotNull('committee_id')->orderBy('name_lc', 'ASC')->get();
            }
        }
        if(!count($meeting_request_ids) > 0 && $this->crud->getActionMethod() != 'edit'){
            \Alert::warning('कुनै पनि बैठक आहवान स्वीकृत नभएकोले बैठक माइनुट हुन सकेन !')->flash();
            return redirect()->back();
        }

        CRUD::setValidation(MeetingMinuteDetailRequest::class);

       

        // $meeting_content = null;
        $committee_attendance_detail = null;
        $meeting_request_id = [
            'name' => 'meeting_request_id',
            'label' => trans('common.meeting_request_code'),
            'type' => 'select2',
            'entity' => 'meeting_request',
            'model'=> EcMeetingRequest::class,
            'options' => (function ($query) use($meeting_request_ids) {
                // $meeting_ids = MeetingMinuteDetail::select('meeting_request_id')->where('deleted_uq_code',1)->distinct('meeting_request_id')->get()->pluck('meeting_request_id')->toArray();
                return $meeting_request_ids;
            }),

            'attribute' => 'meeting_code',
            'wrapper' =>  [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'id' => 'minute_meeting_request',
            ]
        ];

        if($mode == 'edit'){
            $meeting_agenda_table = [
                'name' => 'meeting_agenda_table',
                'label' => trans('common.meeting_agenda'),
                'fake'=>true,
                'value' => $this->crud->getCurrentEntryId(),
                'type' => 'meeting_agenda_table',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ];
        }else{

            $meeting_agenda_table = [
                'name' => 'meeting_agenda_table',
                'label' => trans('common.meeting_agenda'),
                'fake'=>true,
                'value' => null,
                'type' => 'meeting_agenda_table',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ];

        }

        if($mode == 'create' &&  ($minute_tab == "committee" || $this->user->hasRole('committee'))){
            $committee_attendance_detail = [
                'name' => 'committee_attendance_detail',
                'label' => trans('common.attendance_status'),
                'type' => 'summernote',
            ];
            // $meeting_content=[
            //     'name'  => 'meeting_content',
            //     'label' => trans('common.meeting_content'),
            //     'type'  => 'summernote',
            //     'attributes' => [
            //         'id' => 'meeting_content',
            //     ],
            // ];
        }

        if($mode == 'edit' &&  ($minute_tab == "committee" || $this->user->hasRole('committee'))){
            $committee_attendance_detail = [
                'name' => 'committee_attendance_detail',
                'label' => trans('common.attendance_status'),
                'type' => 'summernote',
            ];
            // $meeting_content=[
            //     'name'  => 'meeting_content',
            //     'label' => trans('common.meeting_content'),
            //     'type'  => 'summernote',
            //     'attributes' => [
            //         'id' => 'meeting_content',
            //     ],
            // ];
        }
        if($mode == 'edit'){
            $meeting_request_id = [
                'name' => 'meeting_request_id',
                'label' => trans('common.meeting_request_code'),
                'type' => 'select2',
                'entity' => 'meeting_request',
                'model'=> EcMeetingRequest::class,
                'options' => (function ($query) use($meeting_request_ids) {
                    // $meeting_ids = MeetingMinuteDetail::select('meeting_request_id')->where('deleted_uq_code',1)->distinct('meeting_request_id')->get()->pluck('meeting_request_id')->toArray();
                    return $meeting_request_ids;
                }),

                'attribute' => 'meeting_code',
                'wrapper' =>  [
                    'class' => 'form-group col-md-4'
                ],
                'attributes' => [
                    'id' => 'minute_meeting_request',
                    'disabled' => 'disabled'
                ],
            ];
        }

        $fields=[
            $meeting_request_id,
            [
                'name' => 'meeting_date',
                'label' => trans('बैठक मिति'),
                'fake'=>true,
                'type' => 'text',
                'wrapper' =>  [
                    'class' => 'form-group col-md-4'
                ],
                'attributes' => [
                    'disabled' => 'disabled',
                    'id' => 'meeting_date',
                ],
            ],
            [
                'name' => 'fiscal_year_id',
                'label' => trans('common.fiscal_year'),
                'type' => 'select2',
                'entity' => 'fiscal_year',
                'model'=> MstFiscalYear::class,
                'attribute' => 'code',
                'wrapper' =>  [
                    'class' => 'form-group col-md-4'
                ],
                'attributes' => [
                    'disabled' => 'disabled',
                    'id' => 'minute_fiscal_year',
                ],
            ],

            [
                'type' => 'custom_html',
                'name' => 'plain_html_1',
                'value' => '<div class="col-md-4"></div>',
            ],
            // [
            //     'name' => 'is_verified',
            //     'label' => trans('common.is_verified'),
            //     'type' => 'toggle',
            //     'options' => [
            //         false => trans('common.no'),
            //         true => trans('common.yes'),
            //     ],
            //     'hide_when' => [
            //         false => ['verified_date_bs','verified_date_ad'],
            //     ],
            //     'inline' => true,
            //     'default' => false,
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-4',
            //         'id' => 'meeting_minute_is_verified'
            //     ],
            //     'attributes' => [
            //         'id' => 'is_verified_value'
            //     ],
            // ],
            // [
            //     'name' => 'verified_date_bs',
            //     'label' => trans('common.verified_date_bs'),
            //     'type' => 'nepali_date',
            //     'wrapperAttributes' => [
            //        'class' => 'form-group col-md-4',
            //     ],
            //     'attributes' => [
            //         'id' => 'verified_date_bs',
            //         'relatedId' => 'verified_date_ad',
            //         'maxlength' => '10',
            //     ],
            // ],
            // [
            //     'name' => 'verified_date_ad',
            //     'label' => trans('common.verified_date_ad'),
            //     'type' => 'date',
            //     'wrapper' => [
            //         'class' => 'form-group col-md-4',
            //     ],
            //     'attributes' => [
            //         'id' => 'verified_date_ad',
            //         'relatedId' => 'verified_date_bs',
            //     ],
            // ],
            $committee_attendance_detail,
            $meeting_agenda_table,

            // $meeting_content,
        ];
        $this->crud->addFields(array_filter($fields));

        if(($this->user->hasRole('admin') && $mode == "edit" && $minute_tab == "ministry") || ($this->user->hasRole(Config::get('roles.name.cabinet_creator'))  && $minute_tab == "ministry")){
            $this->crud->addField(
                [
                    'name' => 'meeting_attendance_table',
                    'label' => trans('common.attendance_status'),
                    'type' => 'meeting_attendance_table',
                    'wrapper' => [
                        'class' => 'form-group col-md-12',
                    ],
                ],

            )->beforeField('meeting_agenda_table');

            $this->crud->addFields([
                [
                    'name' => 'ministry_attendance_status',
                    'type' => 'hidden',
                ],
                [
                    'name' => 'ministry_agenda_decisions',
                    'type' => 'hidden',
                ],
                // [
                //     'name'  => 'meeting_decisions',
                //     'label'   => trans('common.meetingDecision'),
                //     'type'  => 'repeatable_with_action',
                //     'wrapper'=>[
                //         'style'=>'color:blue',
                //     ],
                //     'fields' => [
                //         [
                //             'name'    => 'decision',
                //             'type'    => 'textarea',
                //             'label'   => trans('निर्णयहरु'),
                //             'wrapper' => ['class' => 'form-group col-md-12'],
                //         ],
                //     ],
                //     'new_item_label'  => 'थप', // customize the text of the button
                //     'min_rows'=> 1
                // ],
            ]);
        }
    }

    // Update Operation
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    // Prepare Minute Attendance
    public function prepareMinuteAttendance($meeting_attendance_status)
    {
        $mps=[];
        foreach($meeting_attendance_status as $key=>$attendance){
            $mps[$key]['name'] = EcMp::find($attendance->member_id)->name_lc;
            $mps[$key]['post'] = EcMp::find($attendance->member_id)->post->name_lc;
            $mps[$key]['ministry'] = Ministry::find($attendance->ministry_id)->name_lc;
            $mps[$key]['att_stat'] = $attendance->att_status=="1" ? 'उपस्थित' :'अनुपस्थित';
        }
        $today = convertToNepaliNumber(convert_bs_from_ad());

        return view('meetingMinute.minute_attendance_format',['mps'=>$mps,'today'=>$today])->render();
    }

    // Store Override
    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        $meeting_attendance_status = json_decode($request->ministry_attendance_status);
        $agenda_ministry_decisions = $request->agenda_ministry_decision;
        $agenda_committee_decisions = $request->agenda_committee_decision;

        $committee_id = EcMeetingRequest::whereId($request->meeting_request_id)->pluck('committee_id')->first();
        $request->request->set('committee_id',$committee_id);

        $currentDate = Carbon::now()->toDateString(); // Get the current date in the desired format



        // $att_template = $this->prepareMinuteAttendance($meeting_attendance_status);
        // $request->request->set('meeting_content',$att_template);
        $request = $request->except(['_token','_http_referrer','att_status','_save_action']);

        // insert item in the db
        $item = $this->crud->create($request);
        $this->data['entry'] = $this->crud->entry = $item;

        //save into attendance status
        if($meeting_attendance_status){
            foreach($meeting_attendance_status as $ms)
            {
                $data =[
                    'meeting_request_id'=>$request['meeting_request_id'],
                    'mp_id'=>$ms->member_id,
                    'ministry_id'=>$ms->ministry_id,
                    'is_present'=> ($ms->att_status == "1" ? true:false),
                    'apply_for_meeting_attendance'=>false,
                    'is_mailed'=>false
                ];

                MeetingAttendanceDetail::create($data);
            }
        }

        $pramukh_sachiv = MinistryEmployee::where('date_from_ad', '<', $currentDate)
            ->where('date_to_ad', '>', $currentDate)
            ->where('post_id', 4)
            ->first();

        //save ministry decision for agendas
        // if($agenda_ministry_decisions)
        // {
        //     foreach($agenda_ministry_decisions as $agenda_history_id=>$decision)
        //     {
        //         $data_check = [
        //             'id'=>$agenda_history_id
        //         ];

        //         $data=[
        //             "decision_of_cabinet"=>$decision,
        //             "pramukh_sachiv_id"=>$pramukh_sachiv->id

        //         ];

        //         AgendaHistory::where($data_check)->update($data);
        //     }
        // }
        // if($agenda_committee_decisions)
        // {
        //     foreach($agenda_committee_decisions as $agenda_history_id=>$decision)
        //     {
        //         $data_check = [
        //             'id'=>$agenda_history_id
        //         ];

        //         $data=[
        //             "decision_of_committee"=>$decision
        //         ];

        //         AgendaHistory::where($data_check)->update($data);
        //     }
        // }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    // Update Override
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        $meeting_attendance_status = json_decode($request->ministry_attendance_status);
        $agenda_ministry_decisions = $request->agenda_ministry_decision;
        $agenda_decision_type_id = $request->agenda_decision_type_id;
        $agenda_files = $request->file_upload;
        $agenda_committee_decisions = $request->agenda_committee_decision;

        $committee_id = EcMeetingRequest::whereId($request->meeting_request_id)->pluck('committee_id')->first();
        $request->request->set('committee_id',$committee_id);
        $request = $request->except(['_token','_http_referrer','att_status','_save_action']);

        // update the row in the db
        $item = $this->crud->update($request['id'],$request);
        $this->data['entry'] = $this->crud->entry = $item;

        //update into attendance status
        if($meeting_attendance_status){

            foreach($meeting_attendance_status as $ms)
            {
                $data_check =[
                    'meeting_request_id'=>$request['meeting_request_id'],
                    'mp_id'=>$ms->member_id,
                    'ministry_id'=>$ms->ministry_id,
                ];
                $data = [
                    'is_present'=> ($ms->att_status == "1" ? true:false),
                ];

                MeetingAttendanceDetail::where($data_check)->update($data);
            }
        }

        //save ministry decision for agendas
        if($agenda_ministry_decisions)
        {
            foreach($agenda_ministry_decisions as $agenda_history_id=>$decision)
            {
                $data_check = [
                    'id'=>$agenda_history_id
                ];

                $data=[
                    "decision_of_cabinet"=>$decision
                ];

                AgendaHistory::where($data_check)->update($data);
            }
        }

        // agenda decision type save into aganda histories table
        if($agenda_decision_type_id)
        {
            foreach($agenda_decision_type_id as $agenda_history_id=>$decision_type)
            {
                $data_check = [
                    'id'=>$agenda_history_id
                ];

                $data=[
                    "agenda_decision_type_id"=>$decision_type
                ];

                AgendaHistory::where($data_check)->update($data);
            }
        }



        // if($agenda_files)
        // {
        //     foreach($agenda_files as $agenda_history_id=>$file_upload)
        //     {


        //         // Start a transaction
        //         DB::beginTransaction();
        //         try {
        //             $file = request()->file('file_upload');
        //             $disk = "uploads";
        //             $path  = 'MeetingMinuteDetail/###Agenda###/###MEETING_MINUTE_AGENDA###/Files';
        //             $destination_path = str_replace("###MEETING_MINUTE_AGENDA###", $agenda_history_id, $path);
        //             $data_check = ['id'=> $agenda_history_id ];
        //             $data = ["file_upload" => $destination_path];


        //             // Update the record in the AgendaHistory table
        //             AgendaHistory::where($data_check)->update($data);

        //             // Move the uploaded file to a specific directory
        //             // Generate a unique file name
        //             // $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        //             // Move the uploaded file to storage/uploads directory
        //             // $file->move('storage/uploads', $fileName);
        //             // $files->store('pdfs'); // Specify the desired directory path

        //             // Commit the transaction
        //             DB::commit();
        //         } catch (\Exception $e) {
        //             // Handle the exception and rollback the transaction
        //             DB::rollBack();

        //             // Provide appropriate feedback to the user about the error
        //         }

        //         AgendaHistory::where($data_check)->update($data);
        //     }
        // }
        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
    // Custom View For Creating Direct Agenda
    public function directAgendaView($meeting_minute_detail_id)
    {

    $ministries = Ministry::where('deleted_uq_code',1)->get();
    $agenda_types = MstAgendaType::where('deleted_uq_code',1)->get();
    $meeting_request_detail = MeetingMinuteDetail::select('meeting_request_id','fiscal_year_id')->whereId($meeting_minute_detail_id)->get()->first();
    $ec_meeting_request_id = $meeting_request_detail->meeting_request_id;
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


    // Save Direct Agenda
    public function saveDirectAgenda(Request $request){

        DB::beginTransaction();

        try {
            // Get the input values from the request
            $agendaTitle = $request->input('agenda_title');
            $agendaDescription = $request->input('agenda_description');
            // $fileUpload = $request->input('file_upload');
            $isSubmitted = true;
            $ecMeetingRequestId = $request->input('ec_meeting_request_id');
            $isApproved = true;
            $isRejected = false;
            $isDirectAgenda = true;
            $ministryId = $request->input('ministry_id');
            $fiscalYearId = $request->input('fiscal_year_id');
            $agendaTypeId = $request->input('agenda_type_id');
            $agendaCode = $request->input('agenda_code');

            // Check if the record exists in the database
            // dd($agendaCode);
            // $existingRecord = Agenda::find($id);

            // if (!$existingRecord) {
                // Create a new record
                $agenda = Agenda::create([
                    'agenda_title' => $agendaTitle,
                    'agenda_description' => $agendaDescription,
                    // 'file_upload' => $fileUpload,
                    'is_submitted' => $isSubmitted,
                    'ec_meeting_request_id' => $ecMeetingRequestId,
                    'is_approved' => $isApproved,
                    'is_rejected' => $isRejected,
                    'is_direct_agenda' => $isDirectAgenda,
                    'ministry_id' => $ministryId,
                    'fiscal_year_id' => $fiscalYearId,
                    'agenda_type_id' => $agendaTypeId,
                    'agenda_code' => $agendaCode,
                ]);
                $agenda_id = $agenda->id;

            // } else {

                // Update the existing record
            //     $existingRecord->update([
            //         'agenda_title' => $agendaTitle,
            //         'agenda_description' => $agendaDescription,
            //         // 'file_upload' => $fileUpload,
            //         'is_submitted' => $isSubmitted,
            //         'ec_meeting_request_id' => $ecMeetingRequestId,
            //         'is_approved' => $isApproved,
            //         'is_rejected' => $isRejected,
            //         'ministry_id' => $ministryId,
            //         'fiscal_year_id' => $fiscalYearId,
            //         'agenda_type_id' => $agendaTypeId,
            //         'agenda_code' => $agendaCode,
            //     ]);
            //     $agenda_id = $id;

            // }

            $agendaHistory = AgendaHistory::updateOrCreate(
                [
                    'agenda_id' => $agenda_id,
                    'ec_meeting_request_id' => $ecMeetingRequestId,
                ],
                [
                    'ministry_id' => $ministryId,
                ]
            );
            DB::commit();
            return response()->json(['status' => 'success', 'data' => 2], 200);

            // Return a response or redirect as needed
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);


            // Return a response or redirect with an error message
        }


    }
    // Save Direct Agenda
    public function saveAgendaDecisionTypeContent(Request $request){

        DB::beginTransaction();


        try {
            // Get the input values from the request

            $agenda_history_id = intval($request->agendaId);
            $agenda_decision_type_id = $request->decisionId;
            $decision_of_cabinet = $request->decision_of_cabinet;
            $agendaHistory = AgendaHistory::find($agenda_history_id);

            if ($agendaHistory) {
                $agendaHistory->agenda_decision_type_id = $agenda_decision_type_id;
                $agendaHistory->decision_of_cabinet = $decision_of_cabinet;
                $agendaHistory->save();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'data' => 2], 200);

            // Return a response or redirect as needed
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);


            // Return a response or redirect with an error message
        }


    }

    // Meeting Request Detail
    public function meetingRequestDetail(Request $request){
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
                                ->where('agendas.is_direct_agenda',false)
                                ->get();

        return response()->json(['agenda'=>view('agenda.agenda_list',compact('agendas','agenda_decision_type','ministries','fiscal_years','agenda_types'))->render(),
                                'meeting_request_detail'=>$meeting_request_detail]);
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

        return response()->json(['agenda'=>view('agenda.agenda_list_direct_agenda',compact('agendas','agenda_decision_type','ministries','fiscal_years','agenda_types'))->render(),
                                'meeting_request_detail'=>$meeting_request_detail]);
    }

    // Get Meeting Minute Details
    public function meetingMinuteDetail(){


        $id = $this->crud->getCurrentEntryId();

        $members = DB::select("SELECT ecc.id as ministry_id,ecc.name_en as ministry_name_en, mad.meeting_request_id,
                                ecc.name_lc as ministry_name_lc,ecmp.id, ecmp.name_lc,
                                ecmp.name_en, ecmp.email,mad.is_mailed as is_minute_mailed,ecmp.mobile_number
                                FROM
                                ec_ministry ecc
                                left join ec_ministry_members as ecm on ecm.ministry_id = ecc.id
                                left join meeting_attendance_details mad on ecm.mp_id = mad.mp_id
                                left join ec_meeting_minute_details as emmd on emmd.meeting_request_id = mad.meeting_request_id
                                left join ec_mp as ecmp on ecmp.id = ecm.mp_id
                                WHERE
                                emmd.id ='".$id."' and emmd.deleted_uq_code = 1
                                order by ecc.id");

                                // dd($members);

        $ministry_id = [];
        $ministrys = Ministry::whereIn('id',$ministry_id)->get();
        $meeting_request_id = MeetingMinuteDetail::find($id)->meeting_request_id;

        $selected_ministry_ids = AgendaHistory::select('ministry_id')->where('ec_meeting_request_id', $meeting_request_id)->get();

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
        foreach($ministrys as $ministry){
            foreach($selected_ministry_ids as $selected_ministry_id){
                if($selected_ministry_id->ministry_id == $ministry->id){
                    $ministry->is_selected = true;
                }else{
                    $ministry->is_selected = false;
                }
            }
        }
        

        return view('sendEmail.sendEmail',compact('members','id','ministrys'));
    }

    // Data For Meeting Minute Details
    public function getDataMeetingMinuteDetails($id){

        $app_setting = AppSetting::select('formation_of_council_ministers_date_bs','letter_head_title_1','letter_head_title_2','letter_head_title_3','letter_head_title_4')->where('deleted_uq_code','1')->orderBy('updated_at', 'desc')->first();
        $meeting_minute = MeetingMinuteDetail::select('id','verified_date_bs','meeting_request_id','meeting_decisions','committee_attendance_detail')->whereId($id)->first();

        $agendas = AgendaHistory::select('agendas.agenda_code','agendas.agenda_title','agendas.agenda_number',
                                'agenda_histories.decision_of_cabinet','agenda_histories.decision_of_committee',
                                'agenda_histories.transfered_to','em.name_lc as ministry_name','adt.agenda_decision_content as agenda_decision_content')
                                ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
                                ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')
                                ->leftJoin('agenda_decision_type as adt','adt.id','=','agenda_histories.agenda_decision_type_id')
                                ->where('agenda_histories.ec_meeting_request_id',$meeting_minute->meeting_request_id)
                                ->where('agendas.agenda_number', '!=', null)
                                ->get();

        $meeting_attendance_status = MeetingAttendanceDetail::where('meeting_request_id',$meeting_minute->meeting_request_id)->get();
        $present_count = 0;
        $absent_count = 0;
        $mps=[];
        foreach($meeting_attendance_status as $key=>$attendance){
            $mps[$key]['name'] = EcMp::find($attendance->mp_id)->name_lc;
            $mps[$key]['post'] = EcMp::find($attendance->mp_id)->post->name_lc;
            $ministry = Ministry::find($attendance->ministry_id);
            if($ministry){
                $mps[$key]['ministry'] = $ministry->name_lc;
            }else{
                $mps[$key]['ministry'] = '';
            }
            $mps[$key]['att_stat'] = $attendance->is_present ? 'उपस्थित' :'अनुपस्थित';
            $mps[$key]['attendance'] = $attendance->is_present ? true :false;
        }
        return compact('app_setting','meeting_minute','agendas','mps');
    }

    // Get Agenda Decision Type Content
    public function getAgendaDecisionTypeContent($id){
        $data = AgendaDecisionType::findOrFail($id);
        if($data){
            $res = $data->agenda_decision_content;
            return response()->json($res);
        }
        return response()->json('Please select Decision type');
    }

    // For Generation Minute Pdf
    public function meetingMinuteDetailPdf($id)
    {
        $data = [];
        $minute_detail = $this->getDataMeetingMinuteDetails($id);
        $data['app_setting'] = $minute_detail['app_setting'];
        $data['meeting_minute'] = $minute_detail['meeting_minute'];
        $data['agendas'] = $minute_detail['agendas'];
        $data['mps'] = $minute_detail['mps'];
        $present = 0;
        $absent = 0;
        foreach($data['mps'] as $attendance){
            if($attendance['attendance']){
                $present += 1;
            }else{
                $absent += 1;
            }
        }
        $date_helper = new DateHelper();
        $currentDate = Carbon::now()->toDateString();

        $current_nepali_date = $date_helper->convertBsFromAd($currentDate);

        // Extract year, month, and day
        [$year, $month, $day] = explode('-', $current_nepali_date);

        // Remove the first character from the year
        $year = $year;

        // Create the formatted date string
        $formattedDate = "$year/$month/$day";

        $data['nepali_date'] = $formattedDate;
        $data['present'] = $present;
        $data['absent'] = $absent;


        $html = view('meetingMinute.meetingMinute', $data)->render();
        PdfPrint::printPortrait($html, "MeetingMinute.pdf");
    }
    public function meetingMinuteForMinisterPdf($id)
    {
        $data = [];
        $minute_detail = $this->getDataMeetingMinuteDetails($id);
        $data['app_setting'] = $minute_detail['app_setting'];
        $data['meeting_minute'] = $minute_detail['meeting_minute'];
        $data['agendas'] = $minute_detail['agendas'];
        $meeting_request_id = MeetingMinuteDetail::find($id)->meeting_request->id;
        $data['meeting_request_id'] = $meeting_request_id;

        $agenda_decision_type = AgendaDecisionType::where('is_active', true)->orderBy('display_order','ASC')->get();
        // dd($agenda_decision_type);
        $ministries = Ministry::where('deleted_uq_code',1)->get();
        $fiscal_years = MstFiscalYear::where('deleted_uq_code',1)->get();
        $agenda_types = MstAgendaType::where('deleted_uq_code',1)->get();
        $meeting_request_detail = EcMeetingRequest::select('id','start_date_bs','fiscal_year_id')->whereId($meeting_request_id)->get()->first();

        $normal_agendas = AgendaHistory::select('agendas.agenda_code','agendas.id','agenda_histories.id as agenda_history_id','agendas.agenda_title',
                                        'agendas.agenda_number','agenda_histories.transfered_to','em.name_lc as ministry_name','agenda_histories.agenda_decision_type_id',
                                        'agenda_histories.decision_of_cabinet','agenda_histories.decision_of_committee','mat.id as agenda_type_id','agenda_histories.file_upload')
                                ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
                                ->leftJoin('mst_agenda_types as mat','agendas.agenda_type_id','=','mat.id')
                                ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')
                                ->where('agenda_histories.ec_meeting_request_id',$meeting_request_id)
                                ->where('agendas.is_direct_agenda',false)
                                ->get();

        $direct_agendas = AgendaHistory::select('agendas.agenda_code','agendas.id','agenda_histories.id as agenda_history_id','agendas.agenda_title',
                                        'agendas.agenda_number','agenda_histories.transfered_to','em.name_lc as ministry_name','agenda_histories.agenda_decision_type_id',
                                        'agenda_histories.decision_of_cabinet','agenda_histories.decision_of_committee','mat.id as agenda_type_id','agenda_histories.file_upload')
                                ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
                                ->leftJoin('mst_agenda_types as mat','agendas.agenda_type_id','=','mat.id')
                                ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')
                                ->where('agenda_histories.ec_meeting_request_id',$meeting_request_id)
                                ->where('agendas.is_direct_agenda',true)
                                ->orderBy('agendas.created_at','ASC')
                                ->get();

        $data['normal_agendas'] = $normal_agendas;
        $data['direct_agendas'] = $direct_agendas;
        $data['agenda_decision_type'] = $agenda_decision_type;
        $data['ministries'] = $ministries;
        $data['fiscal_years'] = $fiscal_years;
        $data['agenda_types'] = $agenda_types;

        return view('meetingMinute.meetingMinuteForMinister', $data);
        // PdfPrint::printPortrait($html, "MeetingMinute.pdf");
    }

    // Print Meeting Agenda Detail For Pdf Print
    public function meetingAgendaDetailPdf($id)
    {
        $data = [];
        $minute_detail = $this->getDataMeetingMinuteDetails($id);

        $data['app_setting'] = $minute_detail['app_setting'];
        $data['meeting_minute'] = $minute_detail['meeting_minute'];
        $data['agendas'] = $minute_detail['agendas'];
        $data['mps'] = $minute_detail['mps'];

        $date_helper = new DateHelper();
        $currentDate = Carbon::now()->toDateString();

        $current_nepali_date = $date_helper->convertBsFromAd($currentDate);

        // Extract year, month, and day
        [$year, $month, $day] = explode('-', $current_nepali_date);

        // Remove the first character from the year
        $year = $year;

        // Create the formatted date string
        $formattedDate = "$year/$month/$day";

        $data['nepali_date'] = $formattedDate;


        $html = view('meetingMinute.meetingMinute', $data)->render();
        PdfPrint::printPortrait($html, "MeetingMinute.pdf");
    }

    // Committee Meeting Minute Detail For Pdf Print
    public function committeeMeetingMinuteDetailPdf($id){
        $data = [];
        $minute_detail = $this->getDataMeetingMinuteDetails($id);
        $data['app_setting'] = $minute_detail['app_setting'];
        $data['meeting_minute'] = $minute_detail['meeting_minute'];
        $data['agendas'] = $minute_detail['agendas'];
        $html = view('meetingMinute.committeeMeetingMinute', $data)->render();
        // PdfPrint::printPortrait($html, "MeetingMinute.pdf");
    }

    // Send Email Meeting Minute
    public function meetingMinuteSendEmail(Request $request, $id){
        try {
            // Find the meeting minute detail
            $meetingMinuteDetail = MeetingMinuteDetail::findOrFail($id);

            // Get agenda details for the meeting minute
            $data = $this->getAgendaDetailSendEmail($meetingMinuteDetail->meeting_request_id);
            foreach($data['agendas'] as $agenda){
                //  validate all pdf
                $validate_all_pdf_path = AgendaHistory::where('agenda_id',$agenda->agenda_id)->first();
                if($validate_all_pdf_path){
                    $validate_all_pdf = $validate_all_pdf_path->file_upload;
                    if(!$validate_all_pdf){
                        \Alert::error(trans('common.noFileUploaded'))->flash();
                        return redirect()->back();
                    }
                }else{
                    \Alert::error(trans('common.noFileUploaded'))->flash();
                    return redirect()->back();
                }
            }

            // Iterate over the agendas
            foreach ($data['agendas'] as $agenda) {
                // Get ministry members for the agenda's ministry
                $ministry_members = MinistryMember::select('ec_mp.email')
                    ->leftjoin('ec_mp','ec_mp.id','ec_ministry_members.mp_id')
                    ->where('ministry_id', $agenda->ministry_id)->get();

                    // Iterate over the ministry members
                    foreach ($ministry_members as $member) {
                        $email = $member->email;
                        $agenda_history_id = $agenda->agenda_history_id;

                            if ($email) {
                                // Set the file path and full path
                                $pdf_path = 'AgendasDecision/' . $agenda_history_id . '/MeetingAgenda.pdf';
                                // fetch saved file and send email with attachment

                                $path = Storage::disk('uploads')->getAdapter()->getPathPrefix();
                                $pdf_full_path = $path.$pdf_path;
                                // $extension = substr(strrchr($pdf_full_path,'.'),1);

                                // Send email with attachment
                                Mail::send('agenda.agenda', [], function ($message) use ($email, $pdf_full_path) {
                                    $message->to($email)
                                        ->from(env('MAIL_USERNAME'))
                                        ->subject('प्रस्ताबको निर्णय')
                                        ->attach($pdf_full_path, [ 'as' => 'MeetingAgenda.pdf','mime' => 'application/pdf']);
                                });
                            }

                    }

            }
            // Update meeting minute detail
            $meetingMinuteDetail->update(['is_mailed' => true]);
            // Clear notifications
            DB::beginTransaction();
            try{
                $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_minute_id', $meetingMinuteDetail->id)->get();
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
            // Display success message
            \Alert::success(trans('common.emailSendSuccessful'))->flash();

        } catch (Exception $e) {
            // Handle the exception if any error occurs
            dd($e);
        }

        // Redirect back after processing
        return redirect()->back();
    }

    // Uploading Dialog Display
    protected function uploadDialog($id){
        $post_url = backpack_url('meeting-minute-detail/'.$id.'/savefile');
        return view('admin.upload_dialog',compact('post_url','id'));
    }

    // Save Meeting Minute File
    public function saveFile(Request $request,$id)
    {
        // dd($request->all());
        $actionlist = MeetingMinuteDetail::find($id);
        $actionlist->file_upload = $request->all()['file_upload'];
        $actionlist->save();
        return redirect()->back();
    }

    // Agenda Detail For Sending Email To The Mp's
    public function getAgendaDetailSendEmail($id){
        $data = [];
        $data['meeting_request'] = EcMeetingRequest::select('start_date_bs','start_time','meeting_code','pdf_path')->whereId($id)->get()->first();
        $data['meeting_date_bs'] = convertToNepaliNumber($data['meeting_request']->start_date_bs);
        $data['meeting_start_time'] = convertToNepaliNumber(Carbon::parse($data['meeting_request']->start_time)->format('g:i:s A'));
        $data['agendas'] = AgendaHistory::select('agendas.agenda_number','agendas.agenda_title','agendas.id as agenda_id','agenda_histories.transfered_to',
        'agenda_histories.id as agenda_history_id','em.id as ministry_id',
            'em.name_lc as ministry_name','mat.name_lc as agenda_type','agenda_histories.file_upload as file_upload','ec_mp.email as email')
            ->where('agenda_histories.ec_meeting_request_id',$id)
            ->where('agendas.deleted_uq_code',1)
            ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
            ->leftJoin('mst_agenda_types as mat','agendas.agenda_type_id','=','mat.id')
            ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')
            ->leftJoin('ec_ministry_members as emm','em.id','=','emm.ministry_id')
            ->leftJoin('ec_mp','ec_mp.id','=','emm.mp_id')
            ->get();
        return $data;
    }

    // Upload Pdf Ff Particular Agenda
    public function uploadPdf(Request $request)
    {
        // $agendaHistoryId = $request->input('agenda_history_id');
        if ($request->hasFile('file_upload')) {
            // Start a transaction
            DB::beginTransaction();
            try {
                $file = $request->file('file_upload');
                foreach($file as $key => $val){
                    $agenda_history_id = $key;
                }
                if (is_array($file)) {
                    $file = reset($file); // Retrieve the first element of the array
                }
                $pdfDirectory = 'AgendasDecision/' . $agenda_history_id;
                $pdfFilename = 'MeetingAgenda.pdf';
                $file->storeAs($pdfDirectory, $pdfFilename, 'uploads');
                $pdfPath = $pdfDirectory . '/' . $pdfFilename;
                // Update the record in the AgendaHistory table
                AgendaHistory::where('id', $agenda_history_id)->update(['file_upload' => $pdfPath]);
                // Commit the transaction
                $url = url('/') . 'storage/uploads/';
                $file_link = $url .$pdfPath;
                DB::commit();
                return response()->json(['file_link'=>$file_link,'message' => 'PDF uploaded successfully']);
            }catch(\Exception $e) {
                // Handle the exception and rollback the transaction
                DB::rollBack();
                // Log the error for debugging purposes
                Log::error($e);

                // For example, you can redirect the user with an error message
                return redirect()->back()->with('error', 'An error occurred while uploading the files. Please try again.');
            }
        }

        return redirect()->back()->with('success', 'PDF uploaded successfully.');
    }

    // Print Direct Agenda
    public function printDirectAgenda($id)
    {
        // Find the agenda by its ID
        $agenda = Agenda::findOrFail($id);
        $agenda_history = AgendaHistory::where('agenda_id', $id)->first();
        // Getting data from APP SETTING
        $data['app_setting'] = AppSetting::select('letter_head_title_1','letter_head_title_2','letter_head_title_3','letter_head_title_4')->where('deleted_uq_code',1)->orderBy('updated_at', 'desc')->first();
        $data['agenda'] = $agenda;
        $data['agenda_history'] = $agenda_history;
        $chief_secretary = DB::table('ec_ministry_employees as eme')->select('full_name')->where('post_id', 4)->first();
        if($chief_secretary)
            $chief_secretary = $chief_secretary->full_name;
        else
            $chief_secretary = null;
        $data['chief_secretary'] = $chief_secretary;



        // PDF Print
        $html = view('sendEmail.emailBodyDirectAgenda', $data)->render();

        $pdfName = "direct_agenda_" . Carbon::now()->format('Y-m-d_H-i-s') . ".pdf";

        PdfPrint::printPortrait($html, $pdfName);

        // Return a response indicating the success or failure of the operation
        return response()->json(['message' => 'Agenda printed successfully']);
    }

    // Print Agenda
    public function printAgenda($id)
    {
        // Find the agenda by its ID
        $agenda = Agenda::findOrFail($id);
        $agenda_history = AgendaHistory::where('agenda_id', $id)->first();
        // dd($agenda_history);
        // Create the formatted date string
        $data['agenda'] = $agenda;
        // dd( $agenda->agenda_number);
        $data['agenda_history'] = $agenda_history;
        $html = view('sendEmail.emailBody', $data)->render();
        PdfPrint::printPortrait($html, $agenda->agenda_number.".pdf");

        // Invoke the model method to perform the desired action

        // Return a response indicating the success or failure of the operation
        return response()->json(['message' => 'Agenda printed successfully']);
    }

    // Submit Meeting Minute from agenda ministry_creator
    public function submitMeetingMinute($meeting_minute_id){
        DB::beginTransaction();
        try{

            // For validation
            $meeting_minute = MeetingMinuteDetail::select('agendas.agenda_number','agenda_histories.file_upload')
                ->join('ec_meetings_requests','ec_meetings_requests.id','ec_meeting_minute_details.meeting_request_id')
                ->join('agenda_histories','agenda_histories.ec_meeting_request_id','ec_meetings_requests.id')
                ->join('agendas','agendas.id','agenda_histories.agenda_id')
                ->where('ec_meeting_minute_details.id',$meeting_minute_id)
                ->where('agenda_histories.file_upload', null)
                ->get();

            $all_fileds = [];

            foreach($meeting_minute as $minute){
                $all_fileds[] = 'प्रस्ताब नं. '. $minute->agenda_number. ' को फाइल अपलोड गर्नुहोस'; 
            }

            if($all_fileds){
                return response()->json([
                    'status' => 'failed',
                    'message' => $all_fileds,
                ], 200);
            }
            MeetingMinuteDetail::whereId($meeting_minute_id)->update([
                'is_submitted' => true,
                'level_id' => 2,
            ]);

            $meeting_minute = MeetingMinuteDetail::find($meeting_minute_id);

            $data = [
                'status_id' => 1,
                'roles_id' => getRoleId(),
                'data'=> $meeting_minute->getMeetingMinuteApprovalStatusNotify(),
                'agenda_id' => null,
                'meeting_minute_id' => $meeting_minute_id,
                'meeting_request_id' => null,
                'ministry_id' => null,
                'type' => 'MeetingMinute',

            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_minute_id', $meeting_minute_id)->get();
            
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

    // Meeting Minute Approve
    public function meetingMinuteApproval($meeting_minute_id){
        $level_id = 1;

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

            MeetingMinuteApprovalHistory::create([
                'status_id' => 1,
                'role_id' => getRoleId(),
                'meeting_minute_id' => $meeting_minute_id,
                'date_ad'=>dateToday(),
                'date_bs'=>convert_bs_from_ad(),
            ]);
            MeetingMinuteDetail::whereId($meeting_minute_id)->update([
                'level_id' => $level_id,
            ]);

            // chief_secretary verify meeting minute
            if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){
                MeetingMinuteDetail::whereId($meeting_minute_id)->update([
                    'is_approved' => true,
                    'verified_date_ad'=>dateToday(),
                    'verified_date_bs'=>convert_bs_from_ad(),
                ]);
                  // agenda file store on Agendas table
                  $agendas_ids = DB::table('ec_meeting_minute_details as emmd')
                  ->select('a.id')
                  ->join('ec_meetings_requests as emr','emr.id','emmd.meeting_request_id')
                  ->join('agendas as a', 'a.ec_meeting_request_id', 'emr.id')
                  ->where('emmd.id', $meeting_minute_id)
                  ->get();
                  
                  foreach($agendas_ids as $agenda_id){
                    $agenda = Agenda::find($agenda_id->id);
                    $data = [
                        'agenda' => $agenda,
                    ];
                    // upload Agenda file to public storage
                    $pdf_path = 'AgendaView/'.$agenda_id->id.'/Agenda.pdf';
                    $html = view('admin.ecabinet', $data)->render();
                    $pdf = PdfPrint::storeprintPortrait($html, "AgendaView.pdf");
                    Storage::disk('uploads')->put($pdf_path, $pdf);
                    Agenda::whereId(intval($agenda_id->id))->update([
                        'file_upload' => $pdf_path,
                    ]);

                }
            }

            $meeting_minute = MeetingMinuteDetail::find($meeting_minute_id);
            $data = [
                'status_id' => 1,
                'roles_id' => getRoleId(),
                'data'=> $meeting_minute->getMeetingMinuteApprovalStatusNotify(),
                'agenda_id' => null,
                'meeting_minute_id' => $meeting_minute_id,
                'meeting_request_id' => null,
                'ministry_id' => null,
                'type' => 'MeetingMinute',

            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_minute_id', $meeting_minute_id)->get();
            
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
    //Meeting Minute rejection view
    public function meetingMinuteRejectView($id){
        return view('meetingMinute.meeting_minute_rejection_view',compact('id'));
    }
    //Meeting Minute rejection event
    public function meetingMinuteRejection(Request $request,$meeting_minute_id){
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
                MeetingMinuteDetail::whereId($meeting_minute_id)->update([
                    'is_approved' => false,
                    'is_verified' => false,
                ]);
            }
            MeetingMinuteApprovalHistory::create([
                'status_id' => 0,
                'remarks'=>$request->remarks,
                'role_id' => getRoleId(),
                'meeting_minute_id' => $meeting_minute_id,
                'date_ad'=>dateToday(),
                'date_bs'=>convert_bs_from_ad(),
            ]);

            //if meeting minute is rejected by cabinet_approver, make meeting minute submission false
            if($this->user_role == Config::get('roles.name.cabinet_approver')){
                MeetingMinuteDetail::whereId($meeting_minute_id)->update(['is_submitted' => false]);
            }
            MeetingMinuteDetail::whereId($meeting_minute_id)->update([
                'level_id' => $level_id,
            ]);

            $meeting_minute = MeetingMinuteDetail::find($meeting_minute_id);
            $data = [
                'status_id' => 0,
                'roles_id' => getRoleId(),
                'data'=> $meeting_minute->getMeetingMinuteRejectionStatusNotify(),
                'agenda_id' => null,
                'meeting_minute_id' => $meeting_minute_id,
                'meeting_request_id' => null,
                'ministry_id' => null,
                'type' => 'MeetingMinute',

            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('meeting_minute_id', $meeting_minute_id)->get();
            
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
            // MeetingAttendanceDetail
            $meeting_request_id = MeetingMinuteDetail::where('id', $id)->pluck('meeting_request_id')->first();
            if($meeting_request_id){
                $meetingAttendanceDetails = MeetingAttendanceDetail::where('meeting_request_id', $meeting_request_id)->whereNull('deleted_at')->get();
                if ($meetingAttendanceDetails->count() === 0) {
                    // nothing
                } else{
                    $meetingAttendanceDetails = MeetingAttendanceDetail::where('meeting_request_id', $meeting_request_id)->whereNull('deleted_at');
                    $meetingAttendanceDetails->forceDelete();
                }
            }
            $MeetingMinuteApprovalHistory = MeetingMinuteApprovalHistory::where('meeting_minute_id', $id)->get();

            if(!($MeetingMinuteApprovalHistory->count() === 0)){
                $MeetingMinuteApprovalHistory->delete();
            }

            $id = $this->crud->getCurrentEntryId() ?? $id;

            DB::commit();
            return $this->crud->delete($id);

        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
        return redirect()->back();

        // get entry ID from Request (makes sure its the last ID for nested resources)


    }
}
