<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\OfficeDetail;
use App\Exports\ReportExport;
use App\Base\Helpers\PdfPrint;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;
use App\Http\Requests\MinistryReportRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\OfficeDetailCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class MinistryReportCrudController extends CrudController
{
    public function setup()
    {
        CRUD::setModel(OfficeDetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministry-report');
        CRUD::setEntityNameStrings('ministry report', 'ministry reports');
    }

    protected function setupListOperation()
    {
        $this->crud->setFromDb();
        $this->crud->removeAllButtonsFromStack('line');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function filter()
    {
        $this->setFilters();
        $this->data['crud'] = $this->crud;
        $custom_button = [
            'name'      => 'Print Pdf',
            'type'     => 'submit',
            'class' => 'btn btn-success',
            'icon'=>'fa fa-file-pdf-o'
        ];
        $this->data['custom_save_button'] = $custom_button;
        // $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        $this->data['filter_url'] = $this->crud->route . '/filter';
        return view('admin.filter.filter', $this->data);
    }


    public function setFilters()
    {
        if(isset(backpack_user()->ministry->id)){
            $ministry = MstMinistry::where('id', backpack_user()->ministry->id )->pluck('name_lc','id')->toArray();
        }else{
            $ministry = MstMinistry::all()->pluck('name_lc','id')->toArray();
        }

        $fiscal_year = MstFiscalYear::all()->pluck('code','id')->toArray();

        $filter = [

            'मन्त्रालयको नाम' => [ 'name' => 'ministry_id', 'options' => array_filter($ministry),'class' => 'form-group col-md-4'],
            'आर्थिक वर्ष' => [ 'name' => 'fiscal_year_id', 'options' => array_filter($fiscal_year),'class' => 'form-group col-md-4'],
        ];


        $this->prepareFilter($filter);
    }

    public function prepareFilter($data)
    {

        $arr = [];
        foreach ($data as $key => $value) {
            $options = array_merge(['0' => '-'], $value['options']);
            $arr[] =  [   // select_from_array
                'name' => $value['name'],
                'label' => $key,
                'type' => 'select_from_array',
                'options' => $options,
                'allows_null' => false,
                'default' => '',
                'wrapperAttributes' => [
                    'class' => $value['class'] ?? 'form-group col-md-2',
                ]
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ];
        }
        $this->crud->addFields($arr);

    }

    public function report()
    {
        $user = backpack_user();
        $ministry_id = request()->ministry_id;
        $fiscal_year_id = request()->fiscal_year_id;
        $addClause = "1=1 ";
        if($ministry_id){
            $addClause = $addClause."and mpp.ministry_id =".$ministry_id;
        }
        if($fiscal_year_id){
            $addClause = $addClause."and mpp.fiscal_year_id =".$fiscal_year_id;
        }

        $query = DB::select("SELECT mm.name_lc,pp.project_name,ppm.name as milestone_name,ppm.to_date_bs as milestone_date,
                    mms.name as status_name,pmd.milestone_percent,mfy.code as fiscal_year,mfy.id as fiscal_year_id
                    FROM mst_ministries mm
                    left join pt_project pp on pp.ministry_id = mm.id
                    left join pt_project_milestones ppm on ppm.project_id = pp.id
                    left join progress_milestones_details pmd on pmd.project_id = ppm.project_id
                    left join mst_milestones_status mms on mms.id = pmd.milestone_status_id
                    left join mst_fiscal_years mfy on mfy.id = pp.fiscal_year_id
            WHERE
                {$addClause}
            ");
        $reports = $query;

        $request = request()->all();
        $view='admin.report.ministry_report';
        $html = view($view, compact('reports','request'))->render();
        $fileName=isset(backpack_user()->ministry_id)?backpack_user()->ministry->name_lc:null.' '.'report';
        $res = PdfPrint::printPortrait($html, $fileName);

    }

    public function export()
    {
        $date = Carbon::now()->toDateString();
        $ministry = backpack_user()->ministry?backpack_user()->ministry->name_lc:null;
        return Excel::download(new ReportExport, $ministry.' प्रतिवेदन '.$date.'.xlsx');

    }
}
