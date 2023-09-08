<?php

namespace App\Http\Controllers\Admin;

use App\Models\PtProject;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use App\Models\PtProjectMilestone;
use Illuminate\Support\Facades\DB;
use App\Models\MstMilestonesStatus;
use App\Models\CoreMaster\MstMinistry;
use App\Models\MinistryProgramProgress;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;
use App\Models\PtProjectMilestoneDetails;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\MinistryProgramProgressRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryProgramProgressCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryProgramProgressCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MinistryProgramProgress::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry-program-progress');
        CRUD::setEntityNameStrings('कार्यक्रम प्रगति', 'कार्यक्रम प्रगतिहरु');
        $this->addFilters();
        $this->crud->enableExportButtons();
        $this->crud->enableResponsiveTable();
    }

    public function create(){

        CRUD::setValidation(MinistryProgramProgressRequest::class);
        if(backpack_user()->ministry_id){
            $ministries = MstMinistry::find(backpack_user()->ministry->id);
        } else {
            $ministries = MstMinistry::all();

        }
        $fiscal_years = MstFiscalYear::orderBy('id','DESC')->get();
        $projects = PtProject::all();
        
        $months = MstNepaliMonth::select('name_lc', 'id')->get();

        $default_fiscal_year = backpack_user()->ministry?backpack_user()->ministry->appSetting->fiscal_year_id:null;

        $data =[
            'ministries' => $ministries,
            'fiscal_years' => $fiscal_years,
            'projects' => $projects,
            'default_fiscal_year' => $default_fiscal_year,
            'months' => $months
        ];
        return view('admin.project_progress.create',$data);
    }


    public function edit($id){

        $progress = MinistryProgramProgress::find($id);
        $fiscal_years = MstFiscalYear::all();
        $projects = PtProject::where('ministry_id',$progress->ministry_id)->where('fiscal_year_id',$progress->fiscal_year_id)->get();
        $milestone_progresses = PtProjectMilestoneDetails::where('project_id',$progress->project_id)->where('progress_id',$id)->where('deleted_uq_code',1)->get();


        

        $finalResults = [];
        $finalResults = $milestone_progresses->map(function ($milestone_progress)  {
            $earlierProgress = PtProjectMilestoneDetails::where('milestone_id', $milestone_progress->milestone_id)->where('deleted_uq_code',1)->where('id', '<', $milestone_progress->id)->orderBy('id', 'desc')->first();
            
            if($earlierProgress) {

                $earlierStatusId = $earlierProgress->milestone_status_id;
                if($earlierStatusId == 8){
                    $isEarlierCompleted = true;
                } else {
                    $isEarlierCompleted = false;
                }

                $earlierStatusName = $earlierProgress->milestoneStatus->name;
            } else {
                $earlierStatusName = '-';
                $isEarlierCompleted = false;
            }

            $data = [];
            $data['milestone'] = $milestone_progress;
            $data['is_completed'] = $milestone_progress->milestone->is_completed;
            $data['is_completed_status_id'] = $milestone_progress->milestone->is_completed?8:null;
            $data['is_earlier_completed'] = $isEarlierCompleted;
            $data['earlierStatusName'] = $earlierStatusName;
            $data['status'] = MstMilestonesStatus::where('display_order', '>=', $milestone_progress->milestone_status_id)->get();
            return $data;
        });

        

        

        
        $months = MstNepaliMonth::select('name_lc', 'id')->get();

        $data =[
            'fiscal_years' => $fiscal_years,
            'projects' => $projects,
            'progress' => $progress,
            'milestone_progress' => $finalResults,
            'months' => $months,
        ];


        return view('admin.project_progress.edit',$data);
    }


    public function rules($request)
    {
        return [
            'month_id' => 'required|unique:ministry_program_progress,month_id,NULL,id,project_id,' . $request->project_id,
        ];
    }

    public function messages()
    {
        return [
            'month_id.required' => 'The month ID field is required.',
            'month_id.unique' => 'The month ID has already been taken for the selected project.',
        ];
    }



    public function addprogressRecord(Request $request)
    {
        $this->crud->hasAccessOrFail('create');

        $validator = Validator::make($request->all(), $this->rules($request), $this->messages());

        if ($validator->fails()) {
            // Handle validation failure
            return response()->json([
                'status' => 'fail',
                'title' => 'Duplicate Entry !!',
                'message' => 'यो कार्यक्रम र महिनाको प्रगति विवरण प्रविस्ट भैसकेको छ !!',
            ]);
        }

        // dd($request);


        $project_id = $request->project_id;
        $data = [
            'ministry_id'     => $request->ministry_id,
            'fiscal_year_id'    => $request->fiscal_year_id,
            'reporting_fiscal_year_id'    => $request->reporting_fiscal_year_id,
            'project_id'     => $request->project_id,
            'month_id'   => $request->month_id,
            'created_by' => backpack_user()->id,
        ];
        DB::beginTransaction();
        try {
            $projectprogress  = MinistryProgramProgress::create($data);

            $milestoneIds = $request->input('milestone_id');
            $milestoneStatuses = $request->input('milestone_status');
            $milestone_progress = $request->input('milestone_progress');
            
            $count = count($milestoneIds);
            
            for ($i = 0; $i < $count; $i++) {
                DB::table('progress_milestones_details')->insert([
                    'project_id' => $project_id,
                    'progress_id' => $projectprogress->id,
                    'milestone_id' => $milestoneIds[$i],
                    'milestone_status_id' => $milestoneStatuses[$i],
                    'milestone_percent' => $milestone_progress[$i],
                    'created_by' => backpack_user()->id,
                ]);

                if($milestoneStatuses[$i] == 8){
                    $ptprojectmilestone = PtProjectMilestone::find($milestoneIds[$i]);
                    $ptprojectmilestone->is_completed = true;
                    $ptprojectmilestone->save();
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'बधाई छ',
                'message' => 'परियोजना माइलस्टोन प्रगति प्रविष्टि सफल.',
                'url' => backpack_url('ministry-program-progress')
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            // show a success message
            return response()->json([
                'status' => 'error',
                'title' => 'उफ्',
                'message' => 'फेरि प्रयास गर्नुहोस',
                'error' => $th
            ]);
        }

    }

    public function editprogressRecord(Request $request)
    {
        $this->crud->hasAccessOrFail('update');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $program_progess = MinistryProgramProgress::where('id', $request->program_progess_id)->first();
        if($program_progess){
            
            

            $milestoneIds = $request->input('milestone_id');
            $milestoneStatuses = $request->input('milestone_status');
            $milestone_progress = $request->input('milestone_progress');
            $count = count($milestoneIds);
                
            for ($i = 0; $i < $count; $i++) {
                $progress_milestones_details = PtProjectMilestoneDetails::where('progress_id', $request->program_progess_id)->where('id', $milestoneIds[$i])->first();
                $progress_milestones_details->milestone_status_id = $milestoneStatuses[$i];
                $progress_milestones_details->milestone_percent = $milestone_progress[$i];
                $progress_milestones_details->save();
            }
            if($progress_milestones_details) {
                return response()->json([
                    'status' => 'success',
                    'title' => 'बधाई छ',
                    'message' => 'परियोजना माइलस्टोन प्रगति प्रविष्टि सफल.',
                    'url' => backpack_url('ministry-program-progress')
                ]);

            } else {
                // show a success message
                return response()->json([
                    'status' => 'error',
                    'title' => 'उफ्',
                    'message' => 'फेरि प्रयास गर्नुहोस'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'उफ्',
                'message' => 'रेकर्ड फेला परेन'
            ]);
        }




        // dd($request);



    }

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
        $this->crud->addFilter([
            'name'=>'month_id',
            'label'=> 'महिना',
            'type'=>'select2'
          ], function() {
              return MstNepaliMonth::all()->pluck('name_lc', 'id')->toArray();
          }, function($value) { 
              $this->crud->addClause('where', 'month_id', $value);
          });

        

        if(backpack_user()->hasAnyRole('admin|superadmin'))
        {
        $this->crud->addFilter([
            'name'=>'ministry_id',
            'label'=> 'मन्त्रालयको नाम',
            'type'=>'select2'
          ], function() {
              return MstMinistry::all()->pluck('name_lc', 'id')->toArray();
          }, function($value) { 
              $this->crud->addClause('where', 'ministry_id', $value);
          });
        }        // ministry filter

        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        

        $columns = [
            $this->addRowNumber(),
            [
                'name'=>'reporting_fiscal_year_id',
                'type'=>'model_function',
                'label'=>'प्रगति आर्थिक वर्ष',
                'function_name'=>'reportingFiscalYearName'
            ],
            [
                'name'=>'month_id',
                'type'=>'model_function',
                'label' => 'महिना',
                'function_name'=>'monthNmae'
            ],
            $this->addMinistryColumn(),
            // [
            //     'name'=>'ministry_id',
            //     'type'=>'model_function',
            //     'label' => 'मन्त्रालयको नाम',
            //     'function_name'=>'ministryName'
            // ],
            [
                'name'=>'project_id',
                'type'=>'model_function',
                'label' => 'कार्यक्रमको नाम',
                'function_name'=>'projectName'
            ],
        ];
        $this->crud->addColumns(array_filter($columns));

        $this->crud->query->where('deleted_uq_code',1);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MinistryProgramProgressRequest::class);

        

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function destroy($id)
    {
        
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->crud->getCurrentEntry()->ProgressMilestoneDetails->each->delete();

        return $this->crud->delete($id);
    }
}
