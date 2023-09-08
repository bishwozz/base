<?php

namespace App\Http\Controllers\Admin;

use App\Models\PtProject;
use App\Base\Helpers\PdfPrint;
use App\Base\BaseCrudController;
use App\Models\PtProjectMilestone;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\MstMilestonesStatus;
use App\Models\MinistryProgramProgress;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\CoreMaster\MstNepaliMonth;
use App\Models\PtProjectMilestoneDetails;
use App\Http\Requests\PtProjectMilestoneRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class PtProjectMilestoneCrudController extends BaseCrudController
{

    public function setup()
    {
        CRUD::setModel(\App\Models\PtProjectMilestone::class);
        CRUD::setRoute('/admin/pt-project/'.$this->parent('project_id').'/milestone');
        CRUD::setEntityNameStrings('माइलस्टोन', 'माइलस्टोन');
        $this->setUpLinks(['index']);
        $mode = $this->crud->getActionMethod();
        if(in_array($mode,['index','edit'])){
            $pt_project = PtProject::find($this->parent('project_id'));
            $this->data['custom_title'] =$pt_project->project_name;
        }
    }

    public function tabLinks()
    {
        $links = [];
        $links[] = ['label' => 'कार्यक्रम/आयोजना', 'href' => backpack_url('pt-project/'.$this->parent('project_id').'/edit')];
        $links[] = ['label' => 'कार्यक्रम/आयोजना माइलस्टोन', 'href' => $this->crud->route];

        return $links;
    }


    public function store()
    {


        
        $this->crud->hasAccessOrFail('create');
        
        $request = $this->crud->validateRequest();

        $milestonesum = PtProjectMilestone::where('project_id', $request->project_id)->sum('milestone_score');
        if(($milestonesum + $request->milestone_score)<=100){

            $data = [
                'name'     => $request->name,
                'project_id'    => $request->project_id,
                'milestone_score'     => $request->milestone_score,
                'description'   => $request->description,
                'to_date_bs'   => $request->to_date_bs,
                'to_date_ad' => $request->to_date_ad,
                'is_active'       => $request->is_active,
                'created_by' => backpack_user()->id,
            ];
            DB::beginTransaction();
            try {
                $milestone  = PtProjectMilestone::create($data);
    
                DB::commit();
                // show a success message
                \Alert::success(trans('backpack::crud.insert_success'))->flash();
                return redirect(backpack_url('pt-project/'.$request->project_id.'/milestone'));
    
            } catch (\Throwable $th) {
                DB::rollback();
                \Alert::error($th->getMessage())->flash();
                return Redirect::back()->with('error', $th->getMessage());
            }
            
        } else {
            $difference = 100 - $milestonesum;
            $message = 'स्कोर '.$difference.' भन्दा कम वा बराबर हुनुपर्छ';
            \Alert::error($message)->flash();
            return Redirect::back()->with('error', $message);
        }


    }


    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'नाम',
            ],
            [
                'name' => 'milestone_score',
                'type' => 'text',
                'label' => 'माइलस्टोन स्कोर',
            ],
            [
                'name' => 'to_date_bs',
                'type' => 'text',
                'label' => 'मिति सम्म (बि.सं.)',
            ],
            [
                'name' => 'to_date_ad',
                'type' => 'text',
                'label' => 'मिति सम्म (ई.सं.)',
            ],
           
        ];
        $this->crud->addColumns(array_filter($col));

        if ($this->parent('project_id')== null) {
            abort(404);
        } else {
            $this->crud->addClause('where', 'project_id', $this->parent('project_id'));
        }
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(PtProjectMilestoneRequest::class);
        $this->addPtProjectMilestoneFields();

    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function delete(Request $request){
        DB::beginTransaction();
        try {

            $milestonItem = PtProjectMilestone::findOrFail(request()->milestone_id);
            $milestone_id = $milestonItem->id;
            $milestonItem->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Congratulations',
                'message' => 'Milestone Deleted Successfully.'
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            // show a success message
            return response()->json([
                'status' => 'error',
                'title' => 'Oops',
                'message' => 'Please Try Again',
                'error' => $th
            ]);
        }



        return true;
    }


    public function addPtProjectMilestoneFields(){
        $arr = [
            [
                'type' => 'hidden',
                'name' => 'project_id',
                'value' => $this->parent('project_id'),
            ],

            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'नाम',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                'required' => 'required',
                'maxlength'=>'100',
                ],
            ],
            [
                'name' => 'milestone_score',
                'type' => 'number',
                'label' => 'माइलस्टोन स्कोर',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => 'required',
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
                'label' => trans('मिति सम्म (इ.स.)'),
                'attributes'=>[
                    'id'=>'to_date_ad',
                    ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'विवरण',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
                'attributes' => [
                    'maxlength' => '300',
                    'class' => 'form-control fixed-textarea',
                ],
            ],

            [
                'name' => 'is_active',
                'label' => 'सक्रिय हो ?',
                'type' => 'radio',
                'options'     => [
                    0 => 'होइन',
                    1 => 'हो',
                ],
                'inline' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],

            ],


        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }

    public function getTimeLineData($project_id)
    {
        $project = PtProject::find($project_id);
        $project_progresses = MinistryProgramProgress::where('project_id',$project_id)->get();
        $months = MstNepaliMonth::all();
        $milestones = PtProjectMilestone::where('project_id',$project_id)->get();
        $project_chart=[];
        $reporting_fiscal_years = DB::table('pt_project as pp')
                            ->select('mfy.code','mfy.id as reporting_fiscal_year_id')
                            ->leftJoin('ministry_program_progress as mpp','mpp.project_id','pp.id')
                            ->leftJoin('mst_fiscal_years as mfy','mpp.reporting_fiscal_year_id','mfy.id')
                            ->where('mpp.project_id',$project_id)
                            ->distinct('mfy.code')->get();
        foreach($reporting_fiscal_years as $reporting_fiscal_year){
            if($reporting_fiscal_year->code){
                $project_chart[$reporting_fiscal_year->code]=[];
                foreach($milestones as $milestone){
                    foreach($months as $month){
                        $project_chart[$reporting_fiscal_year->code][$milestone->name][$month->name_lc]=[];
                        $ministry_program_progress = MinistryProgramProgress::where(['project_id'=>$project_id,'month_id'=>$month->id,'reporting_fiscal_year_id'=>$reporting_fiscal_year->reporting_fiscal_year_id])->first();
                        if($ministry_program_progress){
                            $milestone_detail = PtProjectMilestoneDetails::where(['progress_id'=>$ministry_program_progress->id,'milestone_id'=>$milestone->id])->first();
                            if($milestone_detail){
                                $project_chart[$reporting_fiscal_year->code][$milestone->name][$month->name_lc]['status_name']=$milestone_detail->milestoneStatus->name;
                                $project_chart[$reporting_fiscal_year->code][$milestone->name][$month->name_lc]['status_colour']=$milestone_detail->milestoneStatus->status_colour;

                            }
                        }
                    }
                }
            }
        }
        return  [
            'months' => $months,
            'project' => $project,
            'project_chart' => $project_chart,
            'statuses' => MstMilestonesStatus::all(),
        ];
    }
    public function timelineChart($project_id){
        $data = $this->getTimeLineData($project_id);
        
        return view('admin.project.timelineChart',$data);
    }

    public function printTimelineBar($project_id){
        $data = $this->getTimeLineData($project_id);
        $html = view('admin.report.project_timeline',$data)->render();

        // $barChartView = $this->timelineChart($project_id)->render();
        $pdf = PdfPrint::printPortrait($html, 'ProjectTimeLineChart');
    }

    public function destroy($id)
    {
        
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->crud->getCurrentEntry()->milestoneProgress->each->delete();

        return $this->crud->delete($id);
    }
}
