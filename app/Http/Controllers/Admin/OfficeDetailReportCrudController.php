<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\OfficeDetail;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Base\Helpers\PdfPrint;
use App\Models\MinistryActLaw;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CoreMaster\AppSetting;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;
use App\Http\Requests\MinistryReportRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\OfficeDetailCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class OfficeDetailReportCrudController extends CrudController
{
    public function setup()
    {
        CRUD::setModel(OfficeDetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/office-report');
        CRUD::setEntityNameStrings('office detail report', 'office detail reports');
    }


    public function index(){
        if(backpack_user()->ministry){
            $ministry = MstMinistry::where('id', backpack_user()->ministry->id );
        }else{
            $ministry = MstMinistry::all();
        }
        $fiscal_year = MstFiscalYear::orderByDesc('id')->get();

        $fiscal_year_id = AppSetting::where('ministry_id',backpack_user()->ministry_id)->pluck('ministry_id')->first();


        $data = [
            'ministries' => $ministry,
            'fiscal_years' => $fiscal_year,
            'fiscal_year_id' => $fiscal_year_id,
        ];
        return view('admin.Reports.index',$data);
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
            [
                'name' => 'legend0',
                'type' => 'custom_html',
                'value' => '<legend>&nbsp;&nbsp;</legend>',
                'options' => '',
            ],

            'मन्त्रालयको नाम' => [ 'name' => 'ministry_id', 'options' => array_filter($ministry),'class' => 'form-group col-md-4'],
            'आर्थिक वर्ष' => [ 'name' => 'fiscal_year_id', 'options' => array_filter($fiscal_year),'class' => 'form-group col-md-4'],
        ];


        $this->prepareFilter($filter);
    }

    public function prepareFilter($data)
    {

        $arr = [];
        $arr[] =  [
            'name' => 'legend0',
            'type' => 'custom_html',
            'value' => '<legend>&nbsp;&nbsp;कार्यालय विवरण :</legend>',
        ];
        foreach ($data as $key => $value) {
            if(!$key == 0){
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
        }
        $this->crud->addFields($arr);

    }

    public function report(Request $request)
    {
        $user = backpack_user();
        $output = [];
        $i = 0;
        $ministry_id = $request->ministry_id;
        $fiscal_year_id = $request->fiscal_year_id;
        $this->data['report_type']   =   $request->report_type;

        switch($request->report_type){
            case 'milestone':
                $milestone_clause = "1=1";
                if($fiscal_year_id){
                    $milestone_clause = $milestone_clause."and pp.fiscal_year_id =".$fiscal_year_id;
                }
                if($ministry_id){
                    $milestone_clause = $milestone_clause."and mm.id =".$ministry_id;
                }

                $milestone_report = DB::table('pt_project_milestones as ppm')
                    ->leftJoin('pt_project as pp', 'pp.id', 'ppm.project_id')
                    ->leftJoin('mst_ministries as mm', 'mm.id', 'pp.ministry_id')
                    ->leftJoin('progress_milestones_details as pmd', 'pmd.milestone_id', 'ppm.id')
                    ->leftJoin('mst_fiscal_years as mfy', 'mfy.id', 'pp.fiscal_year_id')
                    ->leftJoin('mst_milestones_status as mms', 'mms.id', 'pmd.milestone_status_id')
                    ->leftJoin('ministry_program_progress as mpp', 'mpp.id', 'pmd.progress_id')
                    ->leftJoin('mst_nepali_months as mnm', 'mnm.id', 'mpp.month_id')
                    ->select('mm.id as ministry_id',
                            'mm.name_lc as ministry_name',
                            'ppm.id as milestone_id',
                            'pmd.milestone_status_id',
                            'pp.project_name',
                            'mm.name_lc as ministry',
                            'mfy.code',
                            'pp.project_code',
                            'pp.project_budget',
                            'ppm.name as milestone_name',
                            'pmd.milestone_percent',
                            'mms.name as status_name',
                            'ppm.to_date_bs as milestone_date',
                            'mnm.name_lc as month'
                            )
                    ->whereRaw($milestone_clause)
                    ->where('ppm.deleted_uq_code', 1)
                    ->where('mpp.deleted_uq_code', 1)
                    ->orderBy('mm.id', 'asc')
                    ->orderBy('mnm.id', 'asc')
                    ->get();

                    $this->data['columns'] = ['क्र.सं.','आर्थिक वर्ष', 'परियोजना', 'परियोजना बजेट','महिना','माइलस्टोन','माइलस्टोन मिति','माइलस्टोन प्रतिशत','स्थिति'];
                    // dd($milestone_report);
                    foreach ($milestone_report as $i => $data) {
                        $ministryName = $data->ministry_name;
                        $projectName = $data->project_name;

                        $output[$ministryName][$projectName]['project'] = [
                            'fy' => $data->code,
                            'budget' => $data->project_budget,
                        ];

                        $output[$ministryName][$projectName]['milestone'][] = [
                            'महिना' => $data->month,
                            'माइलस्टोन' => $data->milestone_name,
                            'माइलस्टोन मिति' => $data->milestone_date,
                            'माइलस्टोन प्रतिशत' => $data->milestone_percent,
                            'स्थिति' => $data->status_name,
                        ];
                    }
                // dd($output);
                $this->data['output']   =   $output;
                $this->data['report_name'] = 'योजना क्रियाकलाप अनुसार प्रगति विवरण';

                break;

            case 'progress':
                    $ministry_progress_clause = "1=1";
                    if($ministry_id){
                        $ministry_progress_clause = $ministry_progress_clause."and mm.id =".$ministry_id;
                    }
                    if($fiscal_year_id){
                        $ministry_progress_clause = $ministry_progress_clause."and mfy.id =".$fiscal_year_id;
                    }

                    $ministry_progress_report = DB::table('ministry_progress_info as mpi')
                            ->leftJoin('mst_ministries as mm','mm.id','mpi.ministry_id')
                            ->leftJoin('mst_nepali_months as mnm','mnm.id','mpi.month_id')
                            ->leftJoin('mst_fiscal_years as mfy','mfy.id','mpi.fiscal_year_id')
                            ->select('mpi.id',
                                    'mpi.current_progress_financial',
                                    'mpi.capital_progress_financial',
                                    'mpi.total_progress_financial',
                                    'mpi.current_progress_physical',
                                    'mpi.capital_progress_physical',
                                    'mpi.total_progress_physical',
                                    'mpi.beruju_farchyat_percent',
                                    'mm.name_lc as ministry_name',
                                    'mnm.name_lc as month',
                                    'mfy.code'
                                )
                            ->whereRaw($ministry_progress_clause)
                            ->where('mpi.deleted_uq_code',1)
                            ->orderBy('mm.id','asc')
                            ->orderBy('mnm.id','asc')
                            ->get();
                    // dd($ministry_progress_report);
                    $this->data['columns'] = ['क्र.सं.', 'आर्थिक वर्ष', 'महिना', 'वित्तीय खर्च जम्मा (%)', 'भौतिक प्रगति जम्मा (%)','बेरुजु फछर्यौटको प्रगति (%)'];
                    foreach($ministry_progress_report as $data){

                        $i++;
                        $output[$data->ministry_name][] = [
                            'क्र.सं.'                   =>  $i,
                            'आर्थिक वर्ष'               =>  $data->code,
                            'महिना'                   =>  $data->month,
                            'वित्तीय खर्च जम्मा (%)'           =>  $data->total_progress_financial,
                            'भौतिक प्रगति जम्मा (%)'           =>  $data->total_progress_physical,
                            'बेरुजु फछर्यौटको प्रगति (%)'  =>  $data->beruju_farchyat_percent,
                        ];


                    }
                    $this->data['output']   =   $output;
                    $this->data['report_name'] = 'वित्तीय तथा भौतिक प्रगति विवरण';

                break;
            case 'law':
                $acts_laws_clause = "1=1";
                if($ministry_id){
                    $acts_laws_clause = $acts_laws_clause."and mm.id =".$ministry_id;
                }
                // if($fiscal_year_id){
                //     $acts_laws_clause = $acts_laws_clause."and mfy.id =".$fiscal_year_id;
                // }

                $acts_laws_report = DB::table('ministry_act_laws as mal')
                    ->leftJoin('mst_ministries as mm','mal.ministry_id','mm.id')
                    ->select('mal.*','mm.name_lc as ministry_name')
                    ->whereRaw($acts_laws_clause)
                    ->where('mal.deleted_uq_code',1)
                    ->orderBy('mm.id','asc')
                    ->get();

                $this->data['columns'] = ['क्र.सं.', 'ऐन/कानुनको नाम'=>['class'=>'text-left pl-3'], 'प्रकार'=>['class'=>'text-left pl-3'],'स्थिति'=>['class'=>'text-left pl-3']];
                foreach($acts_laws_report as $data){
                    $i++;
                    $output[$data->ministry_name][] = [
                        'क्र.सं.'         =>  $i,
                        'ऐन/कानुनको नाम'    =>  $data->name,
                        'प्रकार'           =>  MinistryActLaw::$type[$data->type],
                        'स्थिति'           =>  MinistryActLaw::$status[$data->status],
                    ];
                }
                $this->data['output']   =   $output;
                $this->data['report_name'] = 'ऐन कानुन निर्माणको अवस्था';

                break;
            // case 'bidding':
            case 'office':
                    $office_details_clause = "1=1";
                    if($ministry_id){
                        $office_details_clause = $office_details_clause."and mm.id =".$ministry_id;
                    }
                    if($fiscal_year_id){
                        $office_details_clause = $office_details_clause."and mfy.id =".$fiscal_year_id;
                    }

                    $office_details_reports = DB::table('office_details as od')
                        ->leftJoin('mst_ministries as mm','od.ministry_id','mm.id')
                        ->leftJoin('mst_fiscal_years as mfy','mfy.id','od.fiscal_year_id')
                        ->select('mm.id','mm.name_lc as ministry_name','od.is_information_updated','od.internal_control_system','od.ladies_friendly_toilet','od.disable_friendly_toilet',
                        'od.current_two_wheeler','od.current_four_wheeler','od.required_two_wheeler','od.required_four_wheeler','od.public_procurement','od.online_procurement_contract',
                        'od.total_operating_contract','od.inspection_monitoring_period','od.inspection_count','mfy.code')
                        ->whereRaw($office_details_clause)
                        ->where('od.deleted_uq_code',1)
                        ->orderBy('mm.id','asc')
                        ->get();

                    $this->data['columns'] = ['क्र.सं.','आर्थिक वर्ष',
                     'आन्तरिक नियन्त्रण प्रणाली',
                     'महिला मैत्री शौचालय',
                     'अपाङ्ग मैत्री शौचालय',
                     'हालको दुई पाङ्ग्रे गाडी',
                     'हालको चार पाङ्ग्रे गाडी',
                     'दुई पाङ्ग्रे सवारी चाहिन्छ',
                     'चार पाङ्ग्रे गाडी चाहिन्छ',
                     'सार्वजनिक खरिद व्यवस्थापन',
                     'अनलाइन खरिद सम्झौता संख्या',
                     'कुल सञ्चालन ठेक्का संख्या',
                     'निरीक्षण/अनुगमन अवधि',
                     'अनुगमन/निरिक्षण सम्पन्न संख्या',
                     'जानकारी अद्यावधिक गरिएको छ'];
                    foreach($office_details_reports as $data){
                        $i++;
                        $output[$data->ministry_name][] = [
                            'क्र.सं.'                  =>  $i,
                            'आर्थिक वर्ष'                  =>  $data->code,
                            'आन्तरिक नियन्त्रण प्रणाली'       =>  OfficeDetail::$internal_control_system[$data->internal_control_system],
                            'महिला मैत्री शौचालय'          =>  OfficeDetail::$toilet_status[$data->ladies_friendly_toilet],
                            'अपाङ्ग मैत्री शौचालय'          =>  OfficeDetail::$toilet_status[$data->disable_friendly_toilet],
                            'हालको दुई पाङ्ग्रे गाडी'        =>  $data->current_two_wheeler,
                            'हालको चार पाङ्ग्रे गाडी'        =>  $data->current_four_wheeler,
                            'दुई पाङ्ग्रे सवारी चाहिन्छ'        =>  $data->required_two_wheeler,
                            'चार पाङ्ग्रे गाडी चाहिन्छ'        =>  $data->required_four_wheeler,
                            'सार्वजनिक खरिद व्यवस्थापन'      =>  OfficeDetail::$public_procurement[$data->public_procurement],
                            'अनलाइन खरिद सम्झौता संख्या'        =>  $data->online_procurement_contract,
                            'कुल सञ्चालन ठेक्का संख्या'          =>  $data->total_operating_contract,
                            'निरीक्षण/अनुगमन अवधि'         =>  OfficeDetail::$inspection_monitoring_period[$data->inspection_monitoring_period],
                            'अनुगमन/निरिक्षण सम्पन्न संख्या'         =>  $data->inspection_count,
                            'जानकारी अद्यावधिक गरिएको छ'    =>  OfficeDetail::$is_information_updated[$data->is_information_updated],
                        ];
                    }
                    $this->data['output']   =   $output;
                    $this->data['report_name'] = 'कार्यालय व्यवस्थापन विवरण';

                break;
            case 'darbandi':
                $darbandi_clause = "1=1";
                if($ministry_id){
                    $darbandi_clause = $darbandi_clause."and mm.id =".$ministry_id;
                }
                // if($fiscal_year_id){
                //     $darbandi_clause = $darbandi_clause."and mfy.id =".$fiscal_year_id;
                // }

                $darbandi_report = DB::table('mst_ministries as mm')
                    ->leftJoin('ministry_darbandi as md','md.ministry_id','mm.id')
                    ->leftJoin('mst_level as ml','md.level_id','ml.id')
                    ->leftJoin('mst_posts as mp','mp.id','md.post_id')
                    ->leftJoin('mst_groups as mg','mg.id','md.group_id')
                    ->select('mm.id','mm.name_lc as ministry_name','ml.name_lc as level','mp.name_lc as post_name','mg.name_lc as group_name','md.total_darbandi','md.perm_darbandi','md.temp_darbandi','md.vacant_darbandi','md.comment')
                    ->whereRaw($darbandi_clause)
                    ->where('md.deleted_uq_code',1)
                    ->where('ml.deleted_uq_code',1)
                    ->orderBy('mm.id','asc')
                    ->orderBy('ml.display_order','asc')
                    ->get();

                $this->data['columns'] = ['क्र.सं.'=> ['rowspan' => 2], 'श्रेणी'=> ['rowspan' => 2],'पद'=> ['rowspan' => 2],'समूह'=> ['rowspan' => 2,'class'=>'text-left ml-2'],'कुल स्वीकृत दरबन्दी'=> ['rowspan' => 2], 'हाल कार्यरत जनशक्ति'=> ['colspan' => 2],'कुल रिक्त दरबन्दी'=> ['rowspan' => 2],'कैफियत'=> ['rowspan' =>2,'class'=>'text-left ml-2']];
                $this->data['columns2'] = ['स्थायी','करार'];
                $this->data['columns_count'] = 1;


                foreach($darbandi_report as $data){
                    $i++;
                    $output[$data->ministry_name][] = [
                        'क्र.सं.' =>  $i,
                        'श्रेणी'   =>  $data->level,
                        'पद'   =>  $data->post_name,
                        'समूह'   =>  $data->group_name,
                        'कुल स्वीकृत दरबन्दी'    =>  $data->total_darbandi,
                        'स्थायी'   =>  $data->perm_darbandi,
                        'करार'  =>  $data->temp_darbandi,
                        'कुल रिक्त दरबन्दी'    =>  $data->vacant_darbandi,
                        'कैफियत'    =>  $data->comment,
                    ];
                }
                $this->data['output']   =   $output;
                $this->data['report_name'] = 'जनसक्ति दरबन्दी विवरण';
                break;
            case 'ministry-budget':
                $ministry_budget_clause = "1=1";
                if($ministry_id){
                    $ministry_budget_clause = $ministry_budget_clause."and mm.id =".$ministry_id;
                }
                if($fiscal_year_id){
                    $ministry_budget_clause = $ministry_budget_clause."and mfy.id =".$fiscal_year_id;
                }
                $ministry_progress_report = DB::table('ministry_budget_info as mbi')
                            ->leftJoin('mst_ministries as mm', 'mm.id', '=', 'mbi.ministry_id')
                            ->leftJoin('mst_fiscal_years as mfy', 'mfy.id', '=', 'mbi.fiscal_year_id')
                            ->leftJoin('ministry_progress_info as mpi', 'mpi.ministry_id', '=', 'mbi.ministry_id')
                            ->select(
                                'mbi.id',
                                'mbi.current_budget',
                                'mbi.capital_budget',
                                'mbi.total_budget',
                                'mm.name_lc as ministry_name',
                                'mfy.code',
                                DB::raw('SUM(mpi.current_progress_financial_amount) AS current_progress_financial_amount'),
                                DB::raw('SUM(mpi.capital_progress_financial_amount) AS capital_progress_financial_amount'),
                                DB::raw('SUM(mpi.total_progress_financial_amount) AS total_progress_financial_amount')
                            )
                            ->whereRaw($ministry_budget_clause)
                            ->where('mbi.deleted_uq_code', 1)
                            ->orderBy('mbi.id', 'asc')
                            ->groupBy(
                                'mbi.id',
                                'mbi.current_budget',
                                'mbi.capital_budget',
                                'mbi.total_budget',
                                'mm.name_lc',
                                'mfy.code'
                            )
                            ->get();

                $this->data['columns'] = ['क्र.सं.'=> ['rowspan' => 2], 'आर्थिक वर्ष'=> ['rowspan' => 2], 'बजेट विनियोजित'=> ['colspan' => 3]];
                $this->data['columns2'] = ['चालु', 'पुँजीगत','जम्मा'];
                $this->data['columns_count'] = 1;

                foreach($ministry_progress_report as $data){
                    $i++;
                    $output[$data->ministry_name][] = [
                        'क्र.सं.'           =>  $i,
                        'आर्थिक वर्ष'        =>  $data->code,
                        'चालु'       =>  $data->current_budget,
                        'पुँजीगत'       =>  $data->capital_budget,
                        'जम्मा'        =>  $data->total_budget,
                    ];
                    $output_total[$data->ministry_name]['current_progress_financial_amount']  =  $data->current_progress_financial_amount;
                    $output_total[$data->ministry_name]['capital_progress_financial_amount']  =  $data->capital_progress_financial_amount;
                    $output_total[$data->ministry_name]['total_progress_financial_amount' ]   =  $data->total_progress_financial_amount;
                }
                // dd($output_total);
                $this->data['output_total']   =   $output_total;
                $this->data['output']   =   $output;
                $this->data['report_name'] = 'मन्त्रालय बजेट विवरण';

                break;
            case 'office-initiative':
                    $office_initiative_clause = "1=1";
                    if($ministry_id){
                        $office_initiative_clause = $office_initiative_clause."and mm.id =".$ministry_id;
                    }
                    if($fiscal_year_id){
                        $office_initiative_clause = $office_initiative_clause."and mfy.id =".$fiscal_year_id;
                    }
                    $office_initiative_report = DB::table('office_initiatives as offi')
                        ->leftJoin('mst_ministries as mm','mm.id','offi.ministry_id')
                        ->leftJoin('mst_fiscal_years as mfy','mfy.id','offi.fiscal_year_id')
                        ->select('offi.id',
                                'offi.innovatives',
                                'offi.achievements',
                                'offi.challenges',
                                'offi.initiatives',
                                'offi.expectations',
                                'mm.name_lc as ministry_name',
                                'mfy.code'
                            )
                        ->whereRaw($office_initiative_clause)
                        ->where('offi.deleted_uq_code',1)
                        ->orderBy('offi.id','asc')
                        ->get();
                    $this->data['columns'] = ['क्र.सं.','आर्थिक वर्ष','नवीनताहरू'=>['class'=>'text-left'],'उपलब्धिहरू'=>['class'=>'text-left'],'चुनौतीहरू'=>['class'=>'text-left'],'पहलहरू'=>['class'=>'text-left'],'अपेक्षाहरू'=>['class'=>'text-left']];

                    foreach($office_initiative_report as $data){
                        $i++;
                        $output[$data->ministry_name][] = [
                            'क्र.सं.'           =>  $i,
                            'आर्थिक वर्ष'        =>  $data->code,
                            'नवीनताहरू'       =>  html_entity_decode(strip_tags($data->innovatives)),
                            'उपलब्धिहरू'       =>  html_entity_decode(strip_tags($data->achievements)),
                            'चुनौतीहरू'        =>  html_entity_decode(strip_tags($data->challenges)),
                            'पहलहरू'        =>  html_entity_decode(strip_tags($data->initiatives)),
                            'अपेक्षाहरू'        =>  html_entity_decode(strip_tags($data->expectations)),
                        ];
                    }
                    $this->data['output']   =   $output;
                    $this->data['report_name'] = 'पहल/अपेक्षाहरु';

                break;
            default:
                $this->data['columns'] = [];
                $this->data['output'] = $output;
                $this->data['report_name'] = '';
            break;
        }
        if(isset($request->is_excel) &&  $request->is_excel == 'true'){
            $fiscal_year = MstFiscalYear::findOrfail($fiscal_year_id);
            $report_name = $this->data['report_name'];
            $code = str_replace(['/', '\\'], '', $fiscal_year->code);
            $file_name = "{$report_name}-{$code}-रिपोर्ट";
            $data = $this->data;
            return Excel::download(new ReportExport($data), ''.$file_name.'.xlsx');
        }else{
            $fiscal_year = MstFiscalYear::findOrfail($fiscal_year_id);
            $file_name = "{$this->data['report_name']}-{$fiscal_year->code}-रिपोर्ट";
            $html = view('admin.report.office_detail_report', $this->data)->render();
            $pdf = PdfPrint::printLandscape($html, ''.$file_name.'.pdf');
            return response($pdf);
        }
    }

}
