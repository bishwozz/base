<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ministry;
use Illuminate\Http\Request;
use App\Models\AgendaHistory;
use App\Models\MstAgendaType;
use App\Models\EcMeetingRequest;
use App\Models\AgendaDecisionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\CoreMaster\AppSetting;
use App\Models\CoreMaster\MstFiscalYear;
use App\Utils\PdfPrint;


class DashboardController extends Controller
{
    protected $user;
    public function index(Request $request)
    {
       if($request){
       }
        if(backpack_user()->hasRole('minister')){
            return view(backpack_view('mp_dashboard'));
            // return redirect()->route('ec-meeting-request.index');
        }else{
            $meeting_years= DB::table('ec_meetings_requests')->groupBy('start_date_bs')->pluck('start_date_bs');
            $year_wise_data=[];
            foreach($meeting_years as $meeting_year){
                $year_wise_data[explode('-',$meeting_year)[0]]['meeting_count']=DB::table('ec_meetings_requests')->where('start_date_bs','LIKE',explode('-',$meeting_year)[0].'-%')->count();
                $year_wise_data[explode('-',$meeting_year)[0]]['decision_count']=DB::table('agenda_histories as ah')
                                                                                    ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
                                                                                    ->where('emr.start_date_bs','LIKE',explode('-',$meeting_year)[0].'-%')
                                                                                    ->whereNotNull('ah.agenda_decision_type_id')
                                                                                    ->count();
            }
            $totals['total_meeting_count'] = EcMeetingRequest::count();
            $totals['total_decision_count'] = AgendaHistory::whereNotNull('agenda_decision_type_id')->count();

            $fiscal_years= DB::table('ec_meetings_requests as emr')
                            ->leftJoin('mst_fiscal_years as mfy','emr.fiscal_year_id','mfy.id')
                            ->select('emr.fiscal_year_id','mfy.code')->groupBy('emr.fiscal_year_id','mfy.code')->get();
            $fiscal_year_wise_data=[];
            $decision_type_wise_data=[];
            $decision_types = MstAgendaType::all();
            // $test_data = DB::table('agenda_histories as ah')
            //             ->selectRaw('mfy.code','count(ah.fiscal_year_id)')
            //             ->leftJoin('mst_fiscal_years as mfy','emr.fiscal_year_id','mfy.id')
            //             ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
            //             ->whereNotNull('ah.agenda_decision_type_id')
            //             ->groupBy('mfy.fiscal_year_id');
            //             dd($test_data);
            foreach($fiscal_years as $fiscal_year){
                $fiscal_year_wise_data[$fiscal_year->code]['meeting_count'] = DB::table('ec_meetings_requests')->where('fiscal_year_id',$fiscal_year->fiscal_year_id)->count();
                $fiscal_year_wise_data[$fiscal_year->code]['decision_count'] = DB::table('agenda_histories as ah')
                                                                                    ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
                                                                                    ->where('emr.fiscal_year_id',$fiscal_year->fiscal_year_id)
                                                                                    ->whereNotNull('ah.agenda_decision_type_id')
                                                                                    ->count();
                foreach($decision_types as $decision_type){
                    $decision_type_wise_data[$fiscal_year->code][$decision_type->name_lc]=DB::table('agenda_histories as ah')
                                                                                        ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
                                                                                        ->where('emr.fiscal_year_id',$fiscal_year->fiscal_year_id)
                                                                                        ->where('ah.agenda_decision_type_id',$decision_type->id)
                                                                                        ->count();
                }
            }
            $data = [
                'fiscal_years' => MstFiscalYear::all(),
                'fiscal_year_id' => AppSetting::pluck('fiscal_year_id')->first(),
                'fiscal_year_wise_data' => $fiscal_year_wise_data,
                'year_wise_data' => $year_wise_data,
                'decision_type_wise_data' => $decision_type_wise_data,
                'decision_types' => $decision_types,
                'totals' => $totals,
                'lang' => lang(),
                'ministries' => Ministry::all(),
            ];
            return view(backpack_view('dashboard'),$data);
        }
    }
    // public function getChartData(Request $request)
    // {  
    //     $fiscal_year_id = $request->fiscal_year_id;
    //     //for all  fiscal_year\
    //     $agendas = DB::table('mst_agenda_types as mat')
    //     ->leftjoin('agendas as ag','ag.agenda_type_id','mat.id')
    //     ->select('mat.name_lc as name_lc','mat.name_en as name_en','mat.id')
    //     ->selectRaw('count(distinct(ag.id))');
    //     // dd($agendas);
    //     if($fiscal_year_id  != 'all'){
    //         $agendas = $agendas->where('ag.fiscal_year_id',$fiscal_year_id);
    //     }


    //     $agendas = $agendas->groupBy('mat.name_lc','mat.name_en','mat.id')
    //                         ->get();
    //     //format data for charts
    //     $datas = [] ;
    //     $labels = [];
    //     $total_agenda_count=0;
    //     foreach($agendas as $row){
    //         $total_agenda_count += $row->count;
    //         $labels [] =  lang() == 'lc' ?  $row->name_lc: $row->name_en;
    //         $datas [] = $row->count;
    //     }
    //     $data['step_wise_data']['main'] = $agendas->toArray();                    
    //     $data['step_wise_data']['chart']['labels'] = $labels;                    
    //     $data['step_wise_data']['chart']['data'] = $datas;

    //     $data['lang'] = lang();
    //     $data['total_agenda_count'] = $total_agenda_count;
    //     // $data['step_wise_data']['chart']['step_id_count'] = $agendas->count();


    //     return view('vendor.backpack.base.inc.dashboard_charts', ['agendas1' => $agendas1])->render();
    //     // dd( $data);
    // }
    // public function lang()
    // {
    //     $lang = App::getLocale();

    //     $lang = $lang == 'np'? 'lc' : $lang;
    //     return $lang;
    // }

    public function manual(){
        $location = public_path()."/Manual/E_Cabinet_Manual.pdf";
        $filename = 'E_Cabinet_Manual.pdf';

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ];
        return response()->file($location,$headers);
    }



    public function dashboardData(Request $request){
        
        $fiscal_year_id = $request->fiscal_year_id;

        //for  new_agendas count
        $new_agendas = DB::table('mst_agenda_types as mat')
        ->leftjoin('agendas as ag','ag.agenda_type_id','mat.id')
        ->select('mat.name_lc as name_lc','mat.name_en as name_en','mat.id')
        ->selectRaw('count(distinct(ag.id))');
        // filter fisical_year
        if($fiscal_year_id  != 'all'){
            $new_agendas = $new_agendas->where('ag.fiscal_year_id',$fiscal_year_id);
        }
        $new_agendas = $new_agendas->groupBy('mat.name_lc','mat.name_en','mat.id')->get();

        // for step_wise_datas data count 
        $step_wise_datas  = DB::table('mst_steps as ms')
                ->leftjoin('agenda_histories as ag','ag.step_id','ms.id')
                ->leftjoin('agendas as a','a.id','ag.agenda_id')
                ->select('ms.name_lc as name_lc','ms.name_en as name_en','ms.id as id')
                ->selectRaw('count(ag.step_id)');
        // filter fisical_year
        if($fiscal_year_id  != 'all'){
            $step_wise_datas = $step_wise_datas->where('a.fiscal_year_id',$fiscal_year_id);
        }
        $step_wise_datas = $step_wise_datas->groupBy('ms.name_lc','ms.name_en','ms.id')->get();

        return view('vendor.backpack.base.inc.dashboardAgenda', ['step_wise_datas'=>$step_wise_datas,'new_agendas' => $new_agendas,'lang' => lang()])->render();
    }

    public function loadDashboardTable(Request $request){
        $ministry_id = $request->ministry_id;
        $ministry_clause='1=1';
        if($ministry_id && $ministry_id !== 'all'){
            $ministry_clause='ah.ministry_id='.$ministry_id;
        }
        $meeting_years= DB::table('ec_meetings_requests')->orderBy('start_date_bs')->groupBy('start_date_bs')->pluck('start_date_bs');
        $year_wise_data=[];
        foreach($meeting_years as $meeting_year){
            $year_wise_data[explode('-',$meeting_year)[0]]['meeting_count']=DB::table('ec_meetings_requests')->where('start_date_bs','LIKE',explode('-',$meeting_year)[0].'-%')->count();
            $year_wise_data[explode('-',$meeting_year)[0]]['decision_count']=DB::table('agenda_histories as ah')
                                                                                ->leftJoin('ec_ministry as em','ah.ministry_id','em.id')
                                                                                ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
                                                                                ->where('emr.start_date_bs','LIKE',explode('-',$meeting_year)[0].'-%')
                                                                                ->whereNotNull('ah.agenda_decision_type_id')
                                                                                ->whereRaw($ministry_clause)
                                                                                ->count();
        }

        $totals['total_meeting_count'] = EcMeetingRequest::count();
        $totals['total_decision_count'] = DB::table('agenda_histories as ah')->whereRaw($ministry_clause)->whereNotNull('agenda_decision_type_id')->count();

        $fiscal_years= DB::table('ec_meetings_requests as emr')
                        ->leftJoin('mst_fiscal_years as mfy','emr.fiscal_year_id','mfy.id')
                        ->select('emr.fiscal_year_id','mfy.code')
                        ->groupBy('emr.fiscal_year_id','mfy.code')
                        ->get();
        $fiscal_year_wise_data=[];
        $decision_type_wise_data=[];
        $decision_types = MstAgendaType::all();
        // $test_data = DB::table('agenda_histories as ah')
        //             ->selectRaw('mfy.code','count(ah.fiscal_year_id)')
        //             ->leftJoin('mst_fiscal_years as mfy','emr.fiscal_year_id','mfy.id')
        //             ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
        //             ->whereNotNull('ah.agenda_decision_type_id')
        //             ->groupBy('mfy.fiscal_year_id');
        //             dd($test_data);
        foreach($fiscal_years as $fiscal_year){
            $fiscal_year_wise_data[$fiscal_year->code]['meeting_count'] = DB::table('ec_meetings_requests')->where('fiscal_year_id',$fiscal_year->fiscal_year_id)->count();
            $fiscal_year_wise_data[$fiscal_year->code]['decision_count'] = DB::table('agenda_histories as ah')
                                                                                ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
                                                                                ->where('emr.fiscal_year_id',$fiscal_year->fiscal_year_id)
                                                                                ->whereNotNull('ah.agenda_decision_type_id')
                                                                                ->whereRaw($ministry_clause)
                                                                                ->count();
            foreach($decision_types as $decision_type){
                $decision_type_wise_data[$fiscal_year->code][$decision_type->name_lc]=DB::table('agenda_histories as ah')
                                                                                    ->leftJoin('ec_meetings_requests as emr','ah.ec_meeting_request_id','emr.id')
                                                                                    ->where('emr.fiscal_year_id',$fiscal_year->fiscal_year_id)
                                                                                    ->where('ah.agenda_decision_type_id',$decision_type->id)
                                                                                    ->whereRaw($ministry_clause)
                                                                                    ->count();
            }
        }
        if($request->selected_type == ''){
            $selected_type = 'yearWiseChart';
        }else{
            $selected_type = $request->selected_type;
        }
        if($request->selected_fiscal_year == ''){
            $selected_fiscal_year = AppSetting::find(1)->fiscal_year_id;
            $fy_code = AppSetting::find(1)->fiscalYearEntity->code;
        }else{
            $selected_fiscal_year = $request->selected_fiscal_year;
            $fy_code =MstFiscalYear::find($selected_fiscal_year)->code;
        }
        $data = [
            'fiscal_years' => MstFiscalYear::orderBy('id','desc')->get(),
            'fiscal_year_wise_data' => $fiscal_year_wise_data,
            'year_wise_data' => $year_wise_data,
            'decision_type_wise_data' => $decision_type_wise_data,
            'decision_types' => $decision_types,
            'totals' => $totals,
            'lang' => lang(),
            'ministries' => Ministry::all(),
            'selected_type'=>$selected_type,
            'selected_fiscal_year'=>$selected_fiscal_year,
            'fy_code'=>$fy_code
        ];

        $view =  view('vendor.backpack.base.inc.dashboard_tables',$data)->render();
        return response()->json(['view'=>$view,'data'=>$data]);
    }

    public function pdf_view(){
        $html = view('admin.ecabinet')->render();
        // $html = view('admin.ecabinet');
        $pdf = PdfPrint::PrintPortrait($html,"MeetingAgenda.pdf");
        return $pdf;
    }
}



