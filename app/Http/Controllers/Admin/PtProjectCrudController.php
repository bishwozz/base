<?php

namespace App\Http\Controllers\Admin;

use App\Models\PtProject;
use App\Imports\PorjectImport;
use App\Base\BaseCrudController;
use App\Models\PtProjectMilestone;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\MstMilestonesStatus;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CoreMaster\MstMinistry;

use App\Http\Requests\PtProjectRequest;
use Illuminate\Support\Facades\Request;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\PtProjectMilestoneDetails;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use function PHPSTORM_META\map;

/**
 * Class PtProjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PtProjectCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\PtProject::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/pt-project');
        CRUD::setEntityNameStrings('कार्यक्रम/आयोजना', 'कार्यक्रम/आयोजना ');
        // $this->crud->addButtonFromModelFunction('line','viewPage','viewPage','beginning');
        $this->crud->addButtonFromView('top', 'projectImport', 'project_import', 'end');
        $this->crud->addButtonFromModelFunction('top', 'excelSample', 'excelSample', 'end');
        $this->crud->addButtonFromView('line', 'project_progress_view', 'project_progress_view', 'begining');

        $this->setUpLinks(['edit']);
        $mode = $this->crud->getActionMethod();
        if(in_array($mode,['edit'])){
            $pt_project = PtProject::find($this->parent('id'));
            $this->data['custom_title'] =$pt_project->project_name;
        }
        $this->crud->addButtonFromModelFunction('line','milestones','milestones','beginning');
        $this->addFilters();

    }

    public function tabLinks()
    {
        $links = [];
            $links[] = ['label' => 'कार्यक्रम/आयोजना', 'href' => backpack_url('pt-project/'.$this->parent('id').'/edit')];
            $links[] = ['label' => 'कार्यक्रम/आयोजना माइलस्टोन', 'href' => backpack_url('pt-project/'.$this->parent('id').'/milestone')];
        return $links;
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

        

        if(backpack_user()->hasAnyRole('admin|superadmin'))
        {
        $this->crud->addFilter([
            'name'=>'ministry_id',
            'label'=> 'मन्त्रालय नाम',
            'type'=>'select2'
          ], function() {
              return MstMinistry::all()->pluck('name_lc', 'id')->toArray();
          }, function($value) { 
              $this->crud->addClause('where', 'ministry_id', $value);
          });
            }        // ministry filter

        
    }

    public function show($id)
    {
        $this->data['crud'] = $this->crud;
        $project = PtProject::all();
        $this->data['projects'] = $project;
        $this->data['milestones'] = DB::table('pt_project_milestones')->select('pt_project_milestones.*')->where('project_id', $id)->get();
        return view('admin.project.view', $this->data);
    }

    public function getMilestoneData(Request $request){

        // Milestone Data
        
        $request = $this->crud->validateRequest();
        if($request->project_id){
            $project =  "mil.project_id = ". $request->project_id;
        }

        

        $milstonData = DB::table('pt_project_milestones as mil')
                        ->whereRaw($project)
                        ->where('mil.deleted_uq_code',1)
                        ->orderBy('mil.id','asc')
                        ->get();

        $finalResults = [];
        $finalResults = $milstonData->map(function ($milestone)  {
            $earlierProgress = PtProjectMilestoneDetails::where('milestone_id', $milestone->id)->where('deleted_uq_code',1)->orderBy('id', 'desc')->first();
            
            
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
            $data['id'] = $milestone->id;
            $data['name'] = $milestone->name;
            $data['is_completed'] = $milestone->is_completed;
            $data['is_completed_status_id'] = $milestone->is_completed?8:null;
            $data['is_earlier_completed'] = $isEarlierCompleted;
            $data['earlierStatusName'] = $earlierStatusName;
            $data['status'] = $this->getMilestoneStatusList($milestone->id);
            return $data;
        });

        $this->data['results'] = $finalResults;

        return view('admin.project_progress.milston_data', $this->data);
    }


    public function getMilestoneStatusList($milestone_id)
    {
        $earlierProgress = PtProjectMilestoneDetails::where('milestone_id', $milestone_id)->orderBy('id', 'desc')->first();

        // dd($earlierProgress);

        if (!$earlierProgress) {
            return MstMilestonesStatus::all();
        }



        $earlierStatusId = $earlierProgress->milestoneStatus->display_order;

        
        $milestoneStatusList = MstMilestonesStatus::where('display_order', '>=', $earlierStatusId)->get();

        return $milestoneStatusList;
    }

    


    protected function setupListOperation()
    {
        $columns = [
            $this->addRowNumber(),
            [
                'label'=>'आर्थिक वर्ष',
                'type'=> 'select',
                'name' => 'fiscal_year_id',
                'entity' => 'fiscalYear',
                'attribute' => 'code',
                'model' => "App\Models\CoreMaster\MstFiscalYear",
            ],

         $this->addMinistryColumn(),
            [
                'name' => 'project_code',
                'type' => 'text',
                'label' => 'कार्यक्रम कोड',
            ],
            [
                'name' => 'project_name',
                'type' => 'text',
                'label' => 'आयोजनाको नाम',
            ],
            [
                'name' => 'project_budget',
                'type' => 'number',
                'label' => 'बजेट',
            ],
            [
                'name'=>'milestone',
                'type'=>'model_function',
                'label' => 'माइलस्टोन शंख्या',
                'function_name' => 'milestoneCount'
            ],
		];
        $this->crud->addColumns(array_filter($columns));

    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(PtProjectRequest::class);
        $this->addPtProjectFields();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function addPtProjectFields(){
        $arr = [
            [
                'name' => 'fiscal_year_id',
                'type' => 'select2',
                'entity'=>'fiscalYear',
                'attribute' => 'code',
                'model'=> MstFiscalYear::class,
                'label' => 'आर्थिक वर्ष',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                'required' => 'required',
                ],
                'options'   => (function ($query) {

                    return $query->orderBy('id', 'DESC')->get();
                }),
                'default' => backpack_user()->ministry?backpack_user()->ministry->appSetting->fiscal_year_id:null
            ],
            $this->addMinistryField(),

            // [
            //     'name' => 'ministry_id',
            //     'type' => 'select2',
            //     'entity'=>'ministry',
            //     'attribute' => 'name_lc',
            //     'model'=> MstMinistry::class,
            //     'label' => 'मन्त्रालय नाम',
            //     'options'   => (function ($query) {
            //         if(backpack_user()->hasAnyRole(['superadmin','admin'])){
            //             return $query->get();
            //         }else{
            //             return $query->where('id',backpack_user()->ministry_id)->get();
            //         }
            //     }),
            //     'wrapper' => [
            //         'class' => 'form-group col-md-4',
            //     ],
            //     'attributes' => [
            //         'required' => 'required',
            //         'id'=>'ministry_id',
            //         'placeholder'=>'select ministry',
            //     ],
            // ],

            [
                'name' => 'project_code',
                'type' => 'text',
                'label' => 'कार्यक्रम कोड',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                    'maxlength'=>'200',
                ],
            ],
            [
                'name' => 'project_name',
                'type' => 'text',
                'label' => 'आयोजनाको नाम',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                'required' => 'required',
                'maxlength'=>'200',
                ],
            ],
            [
                'name' => 'expenditure_title',
                'type' => 'text',
                'label' => 'खर्च शीर्षक',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'maxlength'=>'50',
                ],
            ],
            [
                'name' => 'project_budget',
                'type' => 'number',
                'label' => 'बजेट',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'maxlength'=>'50',
                ],
            ],
            [
                'name' => 'from_date_bs',
                'type' => 'nepali_date',
                'label' => trans(' मिति देखि (बि.स.)'),
                 'attributes'=>
                  [
                    'id'=>'from_date_bs',
                    'relatedId' =>'from_date_ad',
                    'maxlength' =>'10',
                 ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-3',
                 ],
            ],

            [
                'name' => 'from_date_ad',
                'type' => 'date',
                'label' => trans(' मिति देखि (इ.स.)'),
                'attributes'=>
                [
                'id'=>'from_date_ad',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'to_date_bs',
                'type' => 'nepali_date',
                'label' => trans(' मिति सम्म (बि.स.)'),
                 'attributes'=>
                  [
                    'id'=>'to_date_bs',
                    'relatedId' =>'to_date_ad',
                    'maxlength' =>'10',
                 ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-3',
                 ],
            ],

            [
                'name' => 'to_date_ad',
                'type' => 'date',
                'label' => trans('देखि सम्म (इ.स.)'),
                'attributes'=>[
                    'id'=>'to_date_ad',
                    ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'comment',
                'type' => 'textarea',
                'label' => 'टिप्पणी',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
                'attributes' => [
                    'maxlength' => '200',
                    'class' => 'form-control fixed-textarea',
                ],
            ],


        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }
    public function importExcel(){
        $import=Excel::import(new PorjectImport,request()->file('file'));
        if(!$import){
            \Alert::error('The project is already exist')->flash();
        }else{
            \Alert::success('The file has been imported successfully.')->flash();
        }
        return redirect('admin/pt-project')->with('success','data imported successfully');
    }

    public function destroy($id)
    {
        
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->crud->getCurrentEntry()->projectMilestones->each->delete();
        $this->crud->getCurrentEntry()->ministryProgramProgress->each->delete();


        return $this->crud->delete($id);
    }
}
