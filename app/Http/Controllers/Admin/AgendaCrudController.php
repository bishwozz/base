<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Agenda;
use App\Utils\PdfPrint;
use App\Models\Ministry;
use App\Utils\DateHelper;
use Illuminate\Http\Request;
use App\Models\AgendaHistory;
use App\Models\MstAgendaType;
use App\Models\Notifications;
use App\Models\AgendaFileType;
use App\Base\BaseCrudController;
use App\Models\TransferedAgenda;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Http\Requests\AgendaRequest;
use App\Models\AgendaApprovalHistory;
use App\Models\CoreMaster\AppSetting;
use Illuminate\Support\Facades\Route;
use App\Events\AgendaApprovedRejected;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\AgendaButtonHideShowStatus;
use Backpack\ReviseOperation\ReviseOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AgendaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AgendaCrudController extends BaseCrudController
{

    use ReviseOperation;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    private $user;
    private $user_role;


    public function setup()
    {
        $this->user=backpack_user();
        $this->user_role = backpack_user()->getRoleNames()[0];
        CRUD::setModel(\App\Models\Agenda::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/agenda');
        CRUD::setEntityNameStrings(trans('menu.agenda'), trans('menu.agenda'));
        $this->addFilters();
        $this->crud->denyAccess(['create']);

        $this->crud->addClause('orderBy', 'level_id', 'asc');
        $this->crud->orderBy('created_at', 'desc');
      

        $this->data['file_types'] = AgendaFileType::where('deleted_uq_code', 1)->pluck('name','id')->toArray();
        $mode = $this->crud->getActionMethod();

        


        if($mode == 'edit'){
            $files = DB::table('multiple_agenda_files')->where('agenda_id', $this->crud->getCurrentEntryId())->get();
            $this->data['existing_files'] = $files;
        }

        if(!$this->user->hasRole('minister')){

            $this->crud->addButtonFromView('line', 'agendaHistory', 'agendaHistory', 'beginning');
        }
        $this->crud->addButtonFromView('line', 'decision_btn', 'decision_btn', 'beginning');
       
        $this->crud->addButtonFromView('line', 'agendaFile', 'agendaFile', 'beginning');

        // Agenda detail function from model function
        $this->crud->addButtonFromModelFunction('line', 'agendaPrintButton', 'agendaPrintButton', 'beginning');

        //check role wise button access
        $user_role = backpack_user()->getRoleNames()[0];

        $tab = $this->request->agenda_status;
        switch($user_role){

            case Config::get('roles.name.ministry_creator'):
                $this->crud->addButtonFromView('line', 'submitAgendas', 'submit_btn', 'beginning');
                $this->crud->allowAccess('create');
            break;

            case Config::get('roles.name.ministry_reviewer'):

                $this->crud->addClause('whereIn','level_id', [2,3,4,5,6,7]);
                $this->crud->addButtonFromView('line', 'agendaApproveReject', 'agendaApproveReject', 'end');
            break;

            case Config::get('roles.name.ministry_secretary'):

                $this->crud->addClause('whereIn','level_id', [3,4,5,6,7]);
                $this->crud->addButtonFromView('line', 'agendaApproveReject', 'agendaApproveReject', 'end');
            break;
            case Config::get('roles.name.cabinet_creator'):

                $this->crud->addClause('whereIn','level_id', [4,5,6,7]);
                $this->crud->addButtonFromView('line', 'agendaApproveReject', 'agendaApproveReject', 'end');
                $this->crud->addButtonFromView('line', 'addAgendaNum', 'addAgendaNum', 'end');
            break;
            case Config::get('roles.name.cabinet_approver'):

                $this->crud->addClause('whereIn','level_id', [5,6,7]);
                $this->crud->addButtonFromView('line', 'agendaApproveReject', 'agendaApproveReject', 'end');
            break;
            case Config::get('roles.name.chief_secretary'):

                $this->crud->addClause('whereIn','level_id', [6,7]);
                $this->crud->addButtonFromView('line', 'agendaApproveReject', 'agendaApproveReject', 'end');
            break;
            case 'minister':

                $this->crud->addClause('where','agenda_number', '!=', null);
            case 'admin':

            break;

        }

        $this->setCustomTabLinks();
        $this->checkPermission([
            'approveAgenda' => 'approveAgenda',
            'submitAgenda' => 'submitAgenda', 'rejectAgenda' => 'rejectAgenda',
            'updateRejectAgenda' => 'updateRejectAgenda', 'agendaData' => 'agendaData',
            'holdAgenda'=>'holdAgenda','unholdAgenda'=>'unholdAgenda',
            'holdTransferedAgenda'=>'holdTransferedAgenda','unholdTransferedAgenda'=>'unholdTransferedAgenda',
            'transferAgenda'=>'transferAgenda',
            'getCommmitteeWiseAgenda'=>'getCommmitteeWiseAgenda',
            'storeAgendaDecision'=>'storeAgendaDecision',
            'getAgendanumber'=>'getAgendanumber',
            'storeAgendaNumber'=>'storeAgendaNumber',
            'getAgendaTable'=>'getAgendaTable',
            'getCommitteeAgendaTable'=>'getCommitteeAgendaTable',
            'storeCabinetDecision' => 'storeCabinetDecision',
            'storeCommitteeDecision' => 'storeCommitteeDecision',
            'showAgendaDetail' => 'showAgendaDetail',
            'transferAgendaFromCommittee' => 'transferAgendaFromCommittee',
            'agendaFileUploadView' => 'agendaFileUploadView',
            // agenda reject or approve button from view
            'agendaRejectView' => 'agendaRejectView',
            'agendaApproval' => 'agendaApproval',
            'agendaRejection' => 'agendaRejection',
            'showAgenda' => 'showAgenda',
            'getNotifications' => 'getNotifications',
            'notificationMarkAsRead' => 'notificationMarkAsRead',
            'listRevisions' => 'listRevisions',
            'restoreRevision' => 'restoreRevision',
        ]);
        $this->crud->orderBy('updated_at','desc');

        $currentUri = Request()->getRequestUri();
        if($currentUri == '/admin/agenda'){
            $notifications = DB::table('notifications')->where('user_id', $this->user->id)->where('type','Agenda')->where('read_at', null)->get();
            if($notifications){
                foreach($notifications as $notification){
                    Notifications::where('id', $notification->id)->update(['read_at' => now()]);
                }
            }
        }

       
    }

    public function addFilters()
    {
        $user = backpack_user();



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

        if($user->hasRole(Config::get('roles.name.cabinet_creator')) || $user->hasRole(Config::get('roles.name.cabinet_approver')) || $user->hasRole(Config::get('roles.name.chief_secretary')) || $user->hasRole('minister') || $user->hasRole('admin')){
            $this->crud->addFilter([
                'name'=>'ministry_id',
                'label'=> 'मन्त्रालयको नाम',
                'type'=>'select2'
                ], function() {
                    return Ministry::all()->pluck('name_lc', 'id')->toArray();
                }, function($value) {
                    $this->crud->addClause('where', 'agendas.ministry_id', $value);
                });
        }

        // $this->crud->addFilter([
        //     'type'  => 'text',
        //     'name'  => 'year',
        //     'label' => 'बर्ष'
        //   ],
        //   false,
        //   function($value) { // if the filter is active
        //     // $this->crud->addClause('where', 'description', 'LIKE', "%$value%");
        //   });
        
        // $this->crud->addFilters(array_filter());

    }


    protected function setCustomTabLinks()
    {
        $this->data['new_tab'] = "";
        $this->data['all_tab'] = "";
        $this->data['list_tab_header_view'] = 'admin.tab.agenda_tab';
        $tab = $this->request->agenda_status;
        $user = backpack_user();

        // If login from ministry then filter only ministry data
        if(!($user->hasRole(Config::get('roles.name.cabinet_creator')) || $user->hasRole(Config::get('roles.name.cabinet_approver')) || $user->hasRole(Config::get('roles.name.chief_secretary')) || $user->hasRole('admin') )){
            $this->crud->addClause('where','agendas.ministry_id', backpack_user()->ministry_id);
        }

        // Switch Case for tabs
        switch ($tab) {
            case 'new':
                $this->data['new_tab'] = "disabled active";
                if($this->user->hasRole('minister')){

                    $this->crud->query->select('agendas.*')
                    ->leftJoin('agenda_histories', 'agendas.id', '=', 'agenda_histories.agenda_id')
                    ->where('agenda_histories.decision_of_cabinet','=', null)
                    ->where('agendas.agenda_number','!=', null);

                }else{

                    $this->crud->query->select('agendas.*')->leftJoin('agenda_histories', 'agendas.id', '=', 'agenda_histories.agenda_id')->where('agenda_histories.decision_of_cabinet','=', null);
                }
            break;
                // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
                return view($this->crud->getListView(), $this->data);
            case 'all':
                $this->data['all_tab'] = "disabled active";
                if(!backpack_user()->hasRole(Config::get('roles.name.ministry_creator'))){
                    $this->crud->query->where('is_submitted', true);
                }
                // $this->crud->query->where('ec_meeting_request_id', '!==', null);
                $this->crud->query->select('agendas.*')->join('agenda_histories as ah', 'ah.agenda_id', 'agendas.id')
                    ->where('ah.decision_of_cabinet','!=', null);

                // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
                return view($this->crud->getListView(), $this->data);
            break;
            default:

                $this->crud->query->select('agendas.*')->leftJoin('agenda_histories', 'agenda_histories.agenda_id', 'agendas.id')->where('agenda_histories.decision_of_cabinet','=', null);
                $this->data['new_tab'] = "disabled active";
                $this->crud->orderBy('agendas.updated_at','DESC');

            break;
        }
    }

    protected function setupListOperation()
    {

        

        $user = backpack_user();

        $ministry = [];
        if( $user->hasRole('admin') ||  $user->hasRole('minister') || $user->hasRole(Config::get('roles.name.cabinet_creator')) || $user->hasRole(Config::get('roles.name.cabinet_approver')) || $user->hasRole(Config::get('roles.name.chief_secretary')) ){
            $ministry = [
                'name' => 'ministry_id',
                'type' => 'select',
                'label' => trans('common.ministry'),
                'entity' => 'ministry_entity',
                'model' => Ministry::class,
                'attribute' => 'name_lc',
            ];
        }
        $columns = [
            $this->addRowNumberColumn(),
            $ministry,

            [
                'name' => 'agenda_number',
                'label' => trans('common.agendaNumber'),
                'type' => 'text',

            ],

            [
                'name' => 'agenda_type_id',
                'type' => 'select',
                'label' => trans('common.agendaType'),
                'entity' => 'agenda_type',
                'model' => MstAgendaType::class,
                'attribute' => 'name_lc',

            ],
            [
                'name' => 'agenda_title',
                'label' => trans('common.agendaTitle'),
                'type' => 'model_function',
                'function_name' => 'agendaTitle',

            ],

            [
                'name'=>'remarks',
                'type'=>'model_function',
                'label' => trans('common.status'),
                'function_name' => 'getAgendaStatus',
            ],

            // [
            //     'name' => 'decision_taken_or_not',
            //     'type' => 'model_function',
            //     'label' => trans('निर्णय'),
            //     'function_name' => 'decision',
            // ]
        ];

        $this->crud->addColumns(array_filter($columns));

        $mode = $this->crud->getActionMethod();

        if(backpack_user()->ministry_id != null ){

            if($user->hasRole(Config::get('roles.name.cabinet_creator')) || $user->hasRole(Config::get('roles.name.cabinet_approver')) || $user->hasRole(Config::get('roles.name.chief_secretary')) || $user->hasRole('admin')){
                // Do nothing
            }else{

                $this->crud->addClause('where', 'agendas.ministry_id', backpack_user()->ministry_id);
            }
        }

    }

    protected function setupCreateOperation()
    {
        $fiscal_year_id = AppSetting::first()->fiscal_year_id;
        CRUD::setValidation(AgendaRequest::class);
        $ministry = [];
        $minister_approval_date_label =  null;
        $mode = $this->crud->getActionMethod();


        if(backpack_user()->ministry_id == 1){
            $minister_approval_date_label = trans('common.minister_approval_date_bs_mananiya');
        }else{
            $minister_approval_date_label = trans('common.minister_approval_date_bs');

        }            

        if(backpack_user()->hasRole('admin')){
            $ministry = [
                'name' => 'ministry_id',
                'type' => 'select2',
                'label' => trans('common.ministry'),
                'entity' => 'ministry_entity',
                'model' => Ministry::class,
                'attribute' => 'name_lc',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                    'id' => 'ministry_id',
                ],
            ];
        }else{
            $ministry = [
                'name' => 'ministry_id',
                'type' => 'hidden',
                'value' => backpack_user()->ministry_id,
            ];
        }

        if($mode == 'edit'){
            $multiple_file_upload = [
                'name' => 'file_upload',
                'label' => 'फाइल अपलोड',
                'fake'=>true,
                'disk' => 'uploads',
                'value' => $this->crud->getCurrentEntryId(),
                'type' => 'multiple_files_upload',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                    'id' => 'ministry_id',

                ],
            ];
        }else{

            $multiple_file_upload = [
                'name' => 'file_upload',
                'label' => 'फाइल अपलोड',
                'fake'=>true,
                'value' => null,
                'disk' => 'uploads',
                'type' => 'multiple_files_upload',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ];

        }

        $arr = [
            $ministry,
            [
                'name' => 'fiscal_year_id',
                'type' => 'select2',
                'entity' => 'fiscal_year',
                'attribute' => 'code',
                'model' => MstFiscalYear::class,
                'label' => trans('common.fiscal_year'),
                'default' => $fiscal_year_id,
                'wrapper' => [
                    'class' => 'form-group col-md-2',
                    'readonly' =>'readonly'

                ],
                'attributes' => [
                    'disabled'    => 'disabled',
                  ], 
                'options'   => (function ($query) {
                    return $query->orderBy('code', 'DESC')->get();
                }),
            ],
            [
                'name' => 'year',
                'label' => trans('common.year'),
                'type'=>'text',
                'value' => getCurrentNepaliYear(),
                'attributes' => [
                    'readonly' =>'readonly',
                    'disabled'    => 'disabled',

                ],
                'wrapper' => [
                    'class' => 'form-group col-md-1',
                ]
            ],
         
            [
                'name' => 'agenda_type_id',
                'type' => 'select2',
                'entity' => 'agenda_type',
                'attribute' => 'name_lc',
                'model' => MstAgendaType::class,
                'label' => trans('common.agendaType'),
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'name' => 'minister_approval_date_bs',
                'type' => 'nepali_date',
                'label' => $minister_approval_date_label,
                'attributes' => [
                    'id' => 'minister_approval_date_bs',
                    'maxlength' => '10',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],

            [
                'name' => 'agenda_title',
                'label' => trans('common.agendaTitle'),
                'type' => 'text',
                'attributes' => [
                    'required' => 'required',
                    'max-length' => 200,
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            [
                'name' => 'agenda_description',
                'label' => trans('common.agendaDescription'),
                'type' => 'textarea',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            [
                'name' => 'paramarsha_and_others',
                'label' => trans('common.paramarsha_and_others'),
                'type' => 'textarea',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            [
                'name' => 'agenda_reason_and_ministry_sipharis',
                'label' => trans('common.agenda_reason_and_ministry_sipharis'),
                'type' => 'textarea',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            [
                'name' => 'decision_reason',
                'label' => trans('common.decision_reason'),
                'type' => 'textarea',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
             $multiple_file_upload,
        ];

        $mode = $this->crud->getActionMethod();
        $this->crud->addFields(array_filter($arr));

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function update()
    {
        // dd(request());
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        // Ministry 1st level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.ministry_creator'))){

            if($request->is_second_level_user_rejection == true && $request->is_submitted == true){

                $request->request->set('is_second_level_user_approve', false);
                $request->request->set('is_second_level_user_rejection', false);

                $request->request->set('is_third_level_user_approve', false);
                $request->request->set('is_third_level_user_rejection', false);

                $request->request->set('is_cabinet_first_level_user_approve', false);
                $request->request->set('is_cabinet_first_level_user_rejection', false);

                $request->request->set('is_cabinet_second_user_approve', false);
                $request->request->set('is_cabinet_second_level_user_rejection', false);

                $request->request->set('is_approved', false);
                $request->request->set('is_rejected', false);

                $request->request->set('second_level_rejection_remarks', null);
                $request->request->set('rejection_remarks', null);
            }

        }
        // Ministry 2nd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.ministry_reviewer'))){

            if($request->is_second_level_user_rejection == true){

                $request->request->set('is_submitted', false);
                $request->request->set('is_second_level_user_approve', false);
            }
            if($request->is_second_level_user_approve == true){

                $request->request->set('is_third_level_user_rejection', false);
                $request->request->set('is_second_level_user_rejection', false);
                $request->request->set('second_level_rejection_remarks', true);
            }

        }
        // Ministry 3rd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.ministry_secretary'))){

            if($request->is_third_level_user_rejection == true){

                $request->request->set('is_second_level_user_approve', false);
                $request->request->set('is_third_level_user_approve', false);
            }
            if($request->is_third_level_user_approve == true){

                $request->request->set('is_cabinet_first_level_user_rejection', false);
                $request->request->set('is_third_level_user_rejection', false);
                $request->request->set('rejection_remarks', null);
            }

        }
        // Cabinet 1st level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))){

            if($request->is_cabinet_first_level_user_rejection == true){

                $request->request->set('is_third_level_user_approve', false);
                $request->request->set('is_cabinet_first_level_user_approve', false);
            }
            if($request->is_cabinet_first_level_user_approve == true){

                $request->request->set('is_cabinet_second_level_user_rejection', false);
                $request->request->set('is_cabinet_first_level_user_rejection', false);
            }

        }
        // Cabinet 2nd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))){

            if($request->is_cabinet_second_level_user_rejection == true){

                $request->request->set('is_cabinet_first_level_user_approve', false);
                $request->request->set('is_cabinet_second_user_approve', false);
            }
            if($request->is_cabinet_second_user_approve == true){

                $request->request->set('is_rejected', false);
                $request->request->set('is_cabinet_second_level_user_rejection', false);
            }

        }
        // Cabinet 3rd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){

            if($request->is_rejected == true){
                $request->request->set('is_cabinet_second_user_approve', false);
            }

        }

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();


        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        // update the row in the db
        // $item = $this->crud->update($request->id,$request);
        // $this->data['entry'] = $this->crud->entry = $item;

        // Agenda Files save into multiple_agenda_files
        try{
            DB::beginTransaction();

            if($item){

                $files_array = request()->files;
                $check_files_array = request()->hasFile('files');
                if($check_files_array){
                    foreach ($files_array as $values) {
                        foreach ($values as $key => $val) {
                            $ministry = Ministry::find($request['ministry_id']);
                            $office_code = isset($ministry->code)?$ministry->code:null;
                            $agenda_type_id = intval($request['file_type_ids'][$key]);
                            $agenda_file_type_name = AgendaFileType::find($agenda_type_id);
                            $name = $request['names'][$key];

                            if (!$office_code) {
                                $office_code = 'NOMINISTRY';
                            }

                            if ($agenda_file_type_name) {
                                $agenda_file_type_name = $agenda_file_type_name->name;
                            } else {
                                $agenda_file_type_name = 'NOFILETYPE';
                            }

                            $file_name = $office_code . '_' . $agenda_file_type_name . '.pdf';

                            if ($name) {
                                // Check if $value contains '.pdf'
                                if (strpos($name, '.pdf') !== false) {
                                    $parts = explode('.', $name);
                                    $partBeforeDot = $parts[0];
                                    $partAfterDot = $parts[1];
                                    $name = $partBeforeDot;
                                }

                                $file_name = $name . '.pdf';
                            }

                            $disk = 'uploads';
                            $path = 'Agenda/' . $item->id . '/' . $office_code . '/' . $agenda_file_type_name;

                            $file = request()->file('files')[$key];
                            $agenda_id = request()->route()->parameter('id');

                            // Check if this is an existing file
                            if ($file->isValid()) {

                                // If it's an existing file, remove the old file from the storage and the database
                                $existingFile = DB::table('multiple_agenda_files')
                                    ->where('agenda_id', $agenda_id)
                                    ->where('agenda_file_type_id', $agenda_type_id)
                                    ->first();

                                if ($existingFile) {
                                    Storage::disk('uploads')->delete($existingFile->path);
                                    DB::table('multiple_agenda_files')
                                        ->where('agenda_id', $agenda_id)
                                        ->where('agenda_file_type_id', $agenda_type_id)
                                        ->delete();
                                }

                                // Store the updated file
                                $file_path = $file->storeAs($path, $file_name, 'uploads');

                                // Agenda Files save into multiple_agenda_files
                                DB::table('multiple_agenda_files')->insert([
                                    'path' => $file_path,
                                    'name' => $file_name,
                                    'agenda_file_type_id' => $agenda_type_id,
                                    'agenda_id' => $agenda_id,
                                ]);
                            }
                        }
                    }
                }else{
                    $agenda_id = request()->route()->parameter('id');
                    $file_type_ids = request()->file_type_ids;
                    $names = request()->names;

                    // Convert the file_type_ids and names to arrays for exclusion
                    $file_type_ids = is_array($file_type_ids) ? $file_type_ids : [$file_type_ids];
                    $names = is_array($names) ? $names : [$names];


                     // If it's an existing file, remove the old file from the storage and the database
                    $existingFiles = DB::table('multiple_agenda_files')
                        ->where('agenda_id', $agenda_id)
                        ->whereNotIn('agenda_file_type_id', $file_type_ids)
                        ->whereNotIn('name', $names)
                        ->get();

                    if ($existingFiles) {
                        foreach($existingFiles as $exeFile){
                            DB::table('multiple_agenda_files')
                                ->where('id', $exeFile->id)
                                ->delete();
                            Storage::disk('uploads')->delete($exeFile->path);
                        }
                    }
                }

            }


            $ministry = Ministry::find($request->ministry_id);
            $agenda_count = $ministry->agenda_count;
            $ministry->agenda_count = $agenda_count+1;
            $ministry->save();

            $request->request->set('step_id',"1");
            $request->request->set('agenda_code',$request->ministry_id.'-'.($agenda_count+1));

            $request= $request->except('_token','_save_and_back','_http_referrer');
            // insert item in the db
            $item = $this->crud->update($request['id'], $request);

            $this->data['entry'] = $this->crud->entry = $item;





            DB::commit();

            // show a success message
            \Alert::success(trans('backpack::crud.insert_success'))->flash();

            // save the redirect choice for next time
            $this->crud->setSaveAction();

        }catch(Exception $e){
            DB::rollback();
            dd($e);
        }



        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }



    public function holdAgenda($id){
        DB::beginTransaction();
        try{
            Agenda::whereId($id)->update([
                'is_hold' => true,
            ]);
            DB::commit();
            return 1;
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }

    public function unholdAgenda($id){
        DB::beginTransaction();
        try{
            Agenda::whereId($id)->update([
                'is_hold' => false,
            ]);
            DB::commit();
            return 1;
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }
    public function holdTransferedAgenda($id){
        DB::beginTransaction();
        try{
            TransferedAgenda::whereId($id)->update([
                'is_hold' => true,
            ]);
            DB::commit();
            return 1;
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }
    public function unholdTransferedAgenda($id){
        DB::beginTransaction();
        try{
            TransferedAgenda::whereId($id)->update([
                'is_hold' => false,
            ]);
            DB::commit();
            return 1;
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }

    // Submit agenda from agenda ministry_creator
    public function submitAgenda($agenda_id){

        $agenda = Agenda::find($agenda_id);
        $all_fileds = [];

        if(!$agenda->minister_approval_date_bs){
           
            $all_fileds[] = 'विभागीय मन्त्रीबाट स्वीकृत प्राप्त मिति आबस्यक गरिएको छ।';
        }
        if(!$agenda->agenda_title){
            
            $all_fileds[] = 'प्रस्तावको विषय आबस्यक गरिएको छ।';
           
        }
        if(!$agenda->agenda_description){
            
            $all_fileds[] = 'विषयको संक्षिप्त व्यहोरा आबस्यक गरिएको छ।';
           
        }
        if(!$agenda->paramarsha_and_others){
            
            $all_fileds[] = 'प्राप्त परामर्श तथा अन्य प्रासंगिक कुरा आबस्यक गरिएको छ।';
           
        }
        if(!$agenda->agenda_reason_and_ministry_sipharis){
            
            $all_fileds[] = 'प्रस्ताव पेश गर्नु पर्नाको कारण र मन्त्रालयको सिफारिस आबस्यक गरिएको छ।';
           
        }
        if(!$agenda->decision_reason){
            
            $all_fileds[] = 'निर्णय हुनुपर्ने व्यहोरा आबस्यक गरिएको छ।';
           
        }

        $exists_files = DB::table('multiple_agenda_files')->where('agenda_id',$agenda->id)->first();

        if(!$exists_files){
            
            $all_fileds[] = 'प्रस्तावको फाईल आबस्यक गरिएको छ।';
           
        }

        if($all_fileds){
            return response()->json([
                'status' => 'failed',
                'message' => $all_fileds,
            ], 200);
        }
    
        DB::beginTransaction();
        try{
            Agenda::whereId($agenda_id)->update([
                'is_submitted' => true,
                'level_id' => 2,
                'submitted_date_time' => Carbon::now(),
            ]);
            
            $data = [
                'status_id' => 1,
                'roles_id' => getRoleId(),
                'data'=> $agenda->getAgendaApprovalStatusNotify(),
                'agenda_id' => $agenda_id,
                'meeting_request_id' => null,
                'meeting_minute_id' => null,
                'ministry_id' => $agenda->ministry_id,
                'type' => 'Agenda',
                'remarks' => null,
                'userIds' => Request()->selectedUsers,
            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('agenda_id', $agenda_id)->get();
            
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
                'message' => '',
            ], 200);
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }
    // Agenda Approve
    public function agendaApproval($agenda_id){
        $level_id = 1;

        // Ministry 2nd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.ministry_reviewer'))){
            $level_id = 3;
        }
        // Ministry 3rd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.ministry_secretary'))){
            $level_id = 4;

        }
        // Cabinet 1st level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))){
            $level_id = 5;

        }
        // Cabinet 2nd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))){
            $level_id = 6;

        }
        // Cabinet 3rd level Permissions
        if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){
            $level_id = 7;
        }

        DB::beginTransaction();
        try{


           

            

            Agenda::whereId($agenda_id)->update([
                'level_id' => $level_id,
                'is_rejected' => false,
            ]);

            if($this->user_role == Config::get('roles.name.chief_secretary')){
                Agenda::whereId($agenda_id)->update(['is_approved'=> true]);
            }
            $agenda = Agenda::find($agenda_id);
            $data = [
                'status_id' => 1,
                'roles_id' => getRoleId(),
                'data'=> $agenda->getAgendaApprovalStatusNotify(),
                'agenda_id' => $agenda_id,
                'meeting_request_id' => null,
                'remarks' => null,
                'meeting_minute_id' => null,
                'ministry_id' => $agenda->ministry_id,
                'type' => 'Agenda',
                'userIds' => Request()->selectedUsers,


            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('agenda_id', $agenda_id)->get();
            
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
            ], 200);
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
    }
    //agenda rejection view
    public function agendaRejectView($id){
        return view('agenda.agenda_rejection_view',compact('id'));
    }
    //agenda rejection event
    public function agendaRejection($agenda_id){
        DB::beginTransaction();
        try{

            $level_id = 1;

            // Ministry 2nd level Permissions
            if(backpack_user()->hasRole(Config::get('roles.name.ministry_reviewer'))){
                $level_id = 1;
            }
            // Ministry 3rd level Permissions
            if(backpack_user()->hasRole(Config::get('roles.name.ministry_secretary'))){
                $level_id = 2;

            }
            // Cabinet 1st level Permissions
            if(backpack_user()->hasRole(Config::get('roles.name.cabinet_creator'))){
                $level_id = 3;

            }
            // Cabinet 2nd level Permissions
            if(backpack_user()->hasRole(Config::get('roles.name.cabinet_approver'))){
                $level_id = 4;

            }
            // Cabinet 3rd level Permissions
            if(backpack_user()->hasRole(Config::get('roles.name.chief_secretary'))){
                $level_id = 5;
                Agenda::whereId($agenda_id)->update([
                    'is_approved' => false,
                    'is_rejected' => true,
                ]);
            }
          
            
            //if agenda is rejected by ministry_reviewer, make agenda submission false
            if($this->user_role == Config::get('roles.name.ministry_reviewer')){
                Agenda::whereId($agenda_id)->update(['is_submitted'=>false]);
            }
            Agenda::whereId($agenda_id)->update([
                'level_id' => $level_id,
                'is_rejected' => true,

            ]);

            $agenda = Agenda::find($agenda_id);
            $data = [
                'status_id' => 0,
                'roles_id' => getRoleId(),
                'data'=> $agenda->getAgendaRejectionStatusNotify(). Request()->remarks,
                'agenda_id' => $agenda_id,
                'meeting_request_id' => null,
                'meeting_minute_id' => null,
                'type' => 'Agenda',
                'remarks' => Request()->remarks,
                'ministry_id' => $agenda->ministry_id,
                'userIds' => Request()->selectedUsers,

            ];
            $notification_read = Notifications::where('roles_id', getRoleId())->where('agenda_id', $agenda_id)->get();
            
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
            ], 200);
        }catch(\Throwable $th){
            DB::rollback();
            dd($th);
        }
        return redirect()->back();
    }

    private function agendaData(){
        $id = $this->crud->getCurrentEntryId();
        $agenda_status = Agenda::select('is_submitted','is_approved','is_rejected')->whereId($id)->first();

        return $agenda_status;
    }

    public function transferAgenda(Request $request)
    {
        DB::beginTransaction();

        try{


            // transfer to 1 means transfer to committee and 2 means to next meeting
            // $agenda_history = new AgendaHistory();
            // $ministry_id = Agenda::where('id',  $request->agendaHistory_id)->pluck('ministry_id')->first();
            // $agenda_history->transfered_to = $request->transfer_to;
            // $agenda_history->remarks = $request->remarks;
            // $agenda_history->agenda_id = $request->agendaHistory_id;
            // $agenda_history->ministry_id = $ministry_id;
            // $agenda_history->save();
            // 2 means transfered to next meeting and 1 means to committee
            if($request->transfer_to == 2){
                // Agenda::where('id',  $request->agendaHistory_id)->update(['ec_meeting_request_id' => null]);
                $agenda = Agenda::find($request->agendaHistory_id);
                $agendaHistory = AgendaHistory::where('agenda_id',$request->agendaHistory_id)->first();
                $agenda->ec_meeting_request_id = null;
                $agendaHistory->ec_meeting_request_id = null;
                $agenda->save();
                $agendaHistory->delete();
            }else{
                foreach($request->committee_id as $committee_id){
                    $transfered_agendas = new TransferedAgenda();
                    $transfered_agendas->committee_id = $committee_id;
                    $transfered_agendas->agenda_history_id = $request->agendaHistory_id;
                    $transfered_agendas->agenda_id = $agenda_history->agenda_id;
                    $transfered_agendas->ministry_id = $agenda_history->ministry_id;
                    $transfered_agendas->save();
                }
            }

            DB::commit();

            return response()->json(true);
        }catch(Exception $e){
            DB::rollBack();

            dd($e);
        }
    }

    public function transferAgendaFromCommittee(Request $request)
    {
        try{
            // transfer to 1 means transfer to committee and 2 means to next meeting
            $agenda_history = AgendaHistory::find($request->agendaHistory_id);
            $agenda_history->transfered_to = $request->transfer_to;
            // if($request->transfer_to == 1){
            //     $agenda_history->committee_id = $request->committee_id;
            // }
            $agenda_history->remarks = $request->remarks;
            $agenda_history->save();
            $agenda = TransferedAgenda::find($agenda_history->transfered_agenda_id);
            // $ec_meeting_request_id = $agenda_history->agenda->ecMeetingRequest->id;
            // 2 means transfered to next meeting and 1 means to committee
            // if($request->transfer_to == 2){
                $agenda->meeting_request_id = null;
                $agenda->save();
            // }else{
                // foreach($request->committee_id as $committee_id){
                //     $transfered_agendas = new TransferedAgenda();
                //     $transfered_agendas->committee_id = $committee_id;
                //     $transfered_agendas->agenda_history_id = $request->agendaHistory_id;
                //     $transfered_agendas->agenda_id = $agenda_history->agenda_id;
                //     $transfered_agendas->ministry_id = $agenda_history->ministry_id;
                //     $transfered_agendas->save();
                // }
            // }
            return response()->json(true);
        }catch(Exception $e){
            dd($e);
        }
    }

    // Custom View For Creating Direct Agenda
    public function agendaFileUploadView($agenda_id)
    {
        $file_types = AgendaFileType::where('deleted_uq_code',1)->get();
        $agendas = AgendaHistory::select('agendas.agenda_code','agendas.id','agenda_histories.id as agenda_history_id','agendas.agenda_title',
                                        'agendas.agenda_number','agenda_histories.transfered_to','em.name_lc as ministry_name','agenda_histories.agenda_decision_type_id',
                                        'agenda_histories.decision_of_cabinet','agenda_histories.decision_of_committee','mat.id as agenda_type_id','agenda_histories.file_upload')
                                ->leftJoin('agendas','agenda_histories.agenda_id','=','agendas.id')
                                ->leftJoin('mst_agenda_types as mat','agendas.agenda_type_id','=','mat.id')
                                ->leftJoin('ec_ministry as em','em.id','=','agendas.ministry_id')
                                ->where('agendas.id',$agenda_id)
                                ->get();

        return view('agenda.agenda_multiple_file_upload',compact('agendas','file_types','agenda_id'));

    }

    public function storeCabinetDecision(Request $request){
        try{
            $agenda_history = AgendaHistory::find($request->agendaHistory_id);
            $agenda_history->decision_of_cabinet = $request->decision_of_cabinet;
            $agenda_history->save();
            return response()->json(true);
        }catch(Exception $e){
            dd($e);
        }
    }

    // Save Multiple File For Agenda

    public function multipleFileSave(Request $request){
        // Store the validated form request data in the session

        $request->validate([
            'files' => 'required',
        ]);

        // dd($request->all());

        $agenda_id = $request->agenda_id;
        $file_types = $request->agenda_decision_type_ids;
        $names = $request->names;
        // dd($names,$file_types);

        // $pdfDirectory = 'Agendas/' . $agenda_id;
        // $pdfFilename = 'MeetingAgenda.pdf';
        // $file->storeAs($pdfDirectory, $pdfFilename, 'uploads');
        // $pdfPath = $pdfDirectory . '/' . $pdfFilename;

        // $files = $request->file('files');
        // foreach($files as $key => $file){

        //     $name = $names[$key];
        //     $file_type = $file_types[$key];

        //     $filePath = $file->store('uploads'); // Store the file and get its path
        // }


        $fileInfo = [
            'filename' => $file->getClientOriginalName(),
            'path' => $filePath,
            // other relevant information
        ];

        Session::put('form_data', $fileInfo);

        // return

        // dd($request->hasFile('files'));


        // Start a transaction


    }

    public function storeCommitteeDecision(Request $request){
        try{
            $agenda_history = AgendaHistory::find($request->agendaHistory_id);
            $agenda_history->decision_of_committee = $request->decision_of_committee;
            $agenda_history->save();
            return response()->json(true);
        }catch(Exception $e){
            dd($e);
        }
    }

    //get Ministry Wise agenda
    public function getCommmitteeWiseAgenda($ministry_id)
    {
        $ministry_agendas = Agenda::whereMinistryId($ministry_id)
                                ->whereIn('step_id',[3,4])
                                ->where('deleted_uq_code',1)
                                ->orderBy('created_at','DESC')
                                ->get();
        $agenda_id = $ministry_agendas->pluck('id');
        $agenda_histories = DB::table('agenda_histories')->whereIn('agenda_id',$agenda_id)->get();

        foreach($ministry_agendas as $agenda){
            foreach($agenda_histories as $history){
                if($agenda->id == $history->agenda_id){
                    $agenda['decision_of_cabinet'] = $history->decision_of_cabinet;
                    $agenda['decision_of_committee'] = $history->decision_of_committee;
                }
            }
        }
        $data['ministry'] = Ministry::find($ministry_id);
        $data['agendas'] = $ministry_agendas;

        return view('agenda.ministry_wise_agenda',$data);
    }

    public function store()
    {
        // dd(request()->all());
        $this->crud->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        try{
            DB::beginTransaction();


            $ministry = Ministry::find($request->ministry_id);
            $agenda_count = $ministry->agenda_count;
            $ministry->agenda_count = $agenda_count+1;
            $ministry->save();

            $request->request->set('step_id',"1");
            $request->request->set('agenda_code',$request->ministry_id.'-'.($agenda_count+1));

            $request= $request->except('_token','_save_and_back','_http_referrer');
            // insert item in the db
            $item = $this->crud->create($request);

            $this->data['entry'] = $this->crud->entry = $item;

            if($item){

                $files_array = request()->files;

                foreach ($files_array as $values) {
                    foreach ($values as $key => $val) {
                        $office_code = Ministry::find($request['ministry_id']);
                        $agenda_type_id = intval($request['file_type_ids'][$key]);
                        $agenda_file_type_name = AgendaFileType::find($agenda_type_id);
                        $name = $request['names'][$key];

                        if ($office_code) {
                            $office_code = $office_code->code;
                        } else {
                            $office_code = 'NOMINISTRY';
                        }

                        if ($agenda_file_type_name) {
                            $agenda_file_type_name = $agenda_file_type_name->name;
                        } else {
                            $agenda_file_type_name = 'NOFILETYPE';
                        }

                        $file_name = $office_code . '_' . $agenda_file_type_name . '.pdf';

                        if ($name) {
                            // Check if $value contains '.pdf'
                            if (strpos($name, '.pdf') !== false) {
                                $parts = explode('.', $name);
                                $partBeforeDot = $parts[0];
                                $partAfterDot = $parts[1];
                                $name = $partBeforeDot;
                            }

                            $file_name = $name . '.pdf';
                        }

                        $disk = 'uploads';
                        $path = 'Agenda/' . $item->id . '/' . $office_code . '/' . $agenda_file_type_name;

                        $file = request()->file('files')[$key];

                        // Check if this is an existing file
                        if ($file->isValid()) {
                            // If it's an existing file, remove the old file from the storage and the database
                            $existingFile = DB::table('multiple_agenda_files')
                                ->where('agenda_id', $this->crud->entry->id)
                                ->where('agenda_file_type_id', $agenda_type_id)
                                ->first();

                            if ($existingFile) {
                                Storage::disk('uploads')->delete($existingFile->path);
                                DB::table('multiple_agenda_files')
                                    ->where('agenda_id', $this->crud->entry->id)
                                    ->where('agenda_file_type_id', $agenda_type_id)
                                    ->delete();
                            }

                            // Store the updated file
                            $file_path = $file->storeAs($path, $file_name, 'uploads');

                            // Agenda Files save into multiple_agenda_files
                            DB::table('multiple_agenda_files')->insert([
                                'path' => $file_path,
                                'name' => $file_name,
                                'agenda_file_type_id' => $agenda_type_id,
                                'agenda_id' => $this->crud->entry->id,
                            ]);
                        }
                    }
                }

                DB::commit();

                // show a success message
                \Alert::success(trans('backpack::crud.insert_success'))->flash();

                // save the redirect choice for next time
                $this->crud->setSaveAction();

            }
        }catch(Exception $e){
            DB::rollback();
            dd($e);
        }
        return $this->crud->performSaveAction($item->getKey());

    }

    public function getAgendanumber(Request $request){
        $ministry_code = Ministry::where('id', $request->ministry_id)->value('code');
        $agenda_numbers = Agenda::where('ministry_id', $request->ministry_id)->pluck('agenda_number');
        $agenda_arr = [];
        foreach ($agenda_numbers as $no) {
            $startPos = strpos($no, '/') + 1; // Find the position after the forward slash
            $endPos = strpos($no, '-'); // Find the position of the hyphen
            $desiredPortion = substr($no, $startPos, $endPos - $startPos);
            $agenda_arr[] = $desiredPortion;
        }
        $agenda_serial_no = max($agenda_arr) + 1;
        $currentDate = Carbon::now()->toDateString();
        $date_helper = new DateHelper();
        $current_nepali_date = $date_helper->convertBsFromAd($currentDate);

        // Extract year, month, and day
        [$year, $month, $day] = explode('-', $current_nepali_date);

        // Remove the first character from the year
        $year = substr($year, 1);

        // Create the formatted date string
        // $formattedDate = "$year/$month/$day";

        $agenda_code = $ministry_code . '/' . $agenda_serial_no . '-' . $year;

        return response()->json($agenda_code);
    }

    public function storeAgendaNumber(Request $request){
        try{
            DB::beginTransaction();

            $agenda = Agenda::find($request->agenda_id);
            $agenda->agenda_number=$request->agenda_number;
            $agenda->save();
            $notification_read = Notifications::where('roles_id', getRoleId())->where('agenda_id', $request->agenda_id)->get();
            if ($notification_read) {
                foreach ($notification_read as $notification) {
                    $notification->update([
                        'read_at' => now(), // Set the read_at column to the current timestamp
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('agenda.index');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
        }
    }

    public function getAgendaTable(Request $request){
        if($request->action == 'edit'){

            $agendas = AgendaHistory::where('agenda_histories.ec_meeting_request_id',$request->meeting_request_id)
                    ->leftJoin('agendas','agendas.id', 'agenda_id')
                    ->orderBy('agenda_histories.ministry_id', 'ASC')
                    ->orderBy('agenda_histories.created_at', 'DESC')
                    ->where('agendas.is_direct_agenda', false)
                    ->whereNotNull('agenda_histories.ec_meeting_request_id')
                    ->get();
                    foreach($agendas as $agenda){
                        if($agenda->transfered_to == '1')
                            // $agenda['transfered_to_committee'] = TransferedAgenda::whereAgendaId($agenda->agenda_id)->pluck('committee_id');
                            $agenda['transfered_to_committee'] = DB::table('transfered_agendas as ta')->select('ecc.name_en','ecc.name_lc')
                                                                    ->leftjoin('ec_committees as ecc','ecc.id','ta.committee_id')->where('ta.agenda_id',$agenda->agenda_id)
                                                                    ->get();
                    }
            return view('admin.meeting_agenda')->with(['action'=>$request->action,'meeting_request_id'=>$request->meeting_request_id,'agendas'=>$agendas])->render();
        }else{
            $agendas = Agenda::whereNull('ec_meeting_request_id')
                ->where('is_approved',true)->where('is_hold',false)->whereNotNull('agenda_number')
                ->orderBy('ministry_id','ASC')
                ->orderBy('created_at')->get();

            return view('admin.meeting_agenda')->with(['action'=>$request->action,'agendas'=>$agendas])->render();
        }
    }

    public function getCommitteeAgendaTable(Request $request){
        if($request->action == 'edit'){
            $agendas = AgendaHistory::where('ec_meeting_request_id',$request->meeting_request_id)
                    ->orderBy('ministry_id', 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            return view('admin.committee_meeting_agenda')->with(['committee_id'=>$request->committee_id,'action'=>$request->action,'meeting_request_id'=>$request->meeting_request_id,'agendas'=>$agendas])->render();
        }else{
            $agendas = TransferedAgenda::whereNull('meeting_request_id')
                ->where('committee_id',$request->committee_id)
                ->where('is_hold',false)->orderBy('ministry_id','ASC')
                ->orderBy('created_at','DESC')->get();
            return view('admin.committee_meeting_agenda')->with(['committee_id'=>$request->committee_id,'action'=>$request->action,'agendas'=>$agendas])->render();
        }
    }

    public function showAgendaDetail($id){
        $agenda_detail = Agenda::whereId($id)->first();


        return view('agenda.agenda_detail',compact('agenda_detail'));
    }


    // Show Agenda
    public function showAgenda($id){

        $agenda = Agenda::find($id);
        $ministry = Ministry::findOrFail($agenda->ministry_id);
        $app_setting = AppSetting::select('formation_of_council_ministers_date_bs','letter_head_title_1','letter_head_title_2','letter_head_title_3','letter_head_title_4')->where('deleted_uq_code','1')->orderBy('updated_at', 'desc')->first();

        if($ministry->ministry_employee){
            $Secretary = $ministry->ministry_employee->where('post_id',5)->first();
        }
        $data=[
            'agenda' => $agenda,
            'app_setting' => $app_setting,
        ];
        $html = view('admin.ecabinet')->with($data)->render();
        PdfPrint::printPortrait($html, "AgendaView.pdf");
    }

    public function decisionDialog($agenda_id){
        $agenda_details = AgendaApprovalHistory::where('agenda_id',$agenda_id)->orderBy('created_at','DESC')->first();
        if($agenda_details->status_id === 0){
            return view('admin.decision_dialog',compact('agenda_details'));
        }
        else{
            return ;
        }
    }


    public function getNotifications(){
        $user_id = backpack_user()->id;
        $ministry_id = backpack_user()->ministry_id;

        $unread_agenda_ids = null;
        $unread_meeting_request_ids = null;
        $unread_meeting_minute_ids = null;

        $noty_unread_agenda_ids = null;
        $noty_unread_meeting_request_ids = null;
        $noty_unread_meeting_minute_ids = null;

        if(getRoleId() == Config::get('roles.id.ministry_creator') || getRoleId() == Config::get('roles.id.ministry_reviewer') || getRoleId() == Config::get('roles.id.ministry_secretary')){
            $notifications = DB::table('notifications')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('user_id',$user_id)->where('read_at', null)->orderByDesc('created_at')->take(15)->get();
            $count = DB::table('notifications')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('user_id',$user_id)->where('read_at', null)->count();

            $noty_unread_agenda_ids = DB::table('notifications')->select('id')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('read_at', null)->where('type','Agenda')->orderByDesc('created_at')->get();
            $noty_unread_meeting_request_ids = DB::table('notifications')->select('id')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('read_at', null)->where('type','MeetingRequest')->orderByDesc('created_at')->get();
            $noty_unread_meeting_minute_ids = DB::table('notifications')->select('id')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('read_at', null)->where('type','MeetingMinute')->orderByDesc('created_at')->get();

            $unread_agenda_ids = DB::table('notifications')->select('agenda_id')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('read_at', null)->where('type','Agenda')->distinct()->get();
            $unread_meeting_request_ids = DB::table('notifications')->select('meeting_request_id')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('read_at', null)->where('type','MeetingRequest')->distinct()->get();
            $unread_meeting_minute_ids = DB::table('notifications')->select('meeting_minute_id')->where('roles_id', getRoleId())->where('ministry_id',$ministry_id)->where('read_at', null)->where('type','MeetingMinute')->distinct()->get();
        }else{

            $notifications = DB::table('notifications')->where('roles_id', getRoleId())->where('user_id',$user_id)->orderByDesc('created_at')->where('read_at', null)->take(15)->get();
            $count = DB::table('notifications')->where('roles_id', getRoleId())->where('user_id',$user_id)->where('read_at', null)->count();

            $noty_unread_agenda_ids = DB::table('notifications')->select('id')->where('roles_id', getRoleId())->where('read_at', null)->where('type','Agenda')->orderByDesc('created_at')->get();
            $noty_unread_meeting_request_ids = DB::table('notifications')->select('id')->where('roles_id', getRoleId())->where('read_at', null)->where('type','MeetingRequest')->orderByDesc('created_at')->get();
            $noty_unread_meeting_minute_ids = DB::table('notifications')->select('id')->where('roles_id', getRoleId())->where('read_at', null)->where('type','MeetingMinute')->orderByDesc('created_at')->get();

            $unread_agenda_ids = DB::table('notifications')->select('agenda_id')->where('roles_id', getRoleId())->where('read_at', null)->where('type','Agenda')->distinct()->get();
            $unread_meeting_request_ids = DB::table('notifications')->select('meeting_request_id')->where('roles_id', getRoleId())->where('read_at', null)->where('type','MeetingRequest')->distinct()->get();
            $unread_meeting_minute_ids = DB::table('notifications')->select('meeting_minute_id')->where('roles_id', getRoleId())->where('read_at', null)->where('type','MeetingMinute')->distinct()->get();

        }
        // dd($unread_agenda_ids,$unread_meeting_minute_ids,$unread_meeting_request_ids);
        $responseData = [
            'notifications' => $notifications,
            'count' => $count,
            'unread_agenda_ids' => $unread_agenda_ids,
            'unread_meeting_request_ids' => $unread_meeting_request_ids,
            'unread_meeting_minute_ids' => $unread_meeting_minute_ids,
            'noty_unread_agenda_ids' => $noty_unread_agenda_ids,
            'noty_unread_meeting_request_ids' => $noty_unread_meeting_request_ids,
            'noty_unread_meeting_minute_ids' => $noty_unread_meeting_minute_ids,
        ];

        return response()->json($responseData);
    }

    public function notificationMarkAsRead(Request $request){
        $notificationIds = $request->notification_ids;
        foreach($notificationIds as $notification){
            $id = (int) $notification['id'];
            Notifications::where('id', $id)->update(['read_at' => now()]);
        }
        return response()->json(['status' => 'success']);

    }
}
