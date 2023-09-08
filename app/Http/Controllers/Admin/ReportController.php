<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Agenda;
use App\Utils\PdfPrint;
use App\Models\Ministry;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Models\MstAgendaType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstNepaliMonth;

class ReportController extends Controller
{
    public function index(){
        $data = [
            'fiscal_years'  =>  MstFiscalYear::select('code','id')->get(),
            'ministries'       =>  Ministry::all(),
            'months'        => MstNepaliMonth::all(),
            'agenda_types'        => MstAgendaType::all(),

        ];
        return view('reports.report_index', $data);
    }

    // public function export(){
    //     // dd(request()->month);

    //     $request = request()->except(['_token',]);
    //     $addClause = '1=1';
    //     $output = [];
    //     $i = 0;
    //     if(request()->fiscal_year_id){
    //         $addClause = $addClause." and aa.fiscal_year_id ='".request()->fiscal_year_id."'";
    //     }
    //     if(request()->month){
    //         $addClause = $addClause." and LTRIM(SPLIT_PART(REPLACE(aa.minister_approval_date_bs, '/', '-'), '-', 2), '0') ='".request()->month."'";
    //     }
    //     if(request()->ministry_id){
    //         $addClause = $addClause." and aa.ministry_id ='".request()->ministry_id."'";
    //     }
    //     if(request()->agenda_type_id){
    //         $addClause = $addClause." and aa.agenda_type_id ='".request()->agenda_type_id."'";
    //     }

    //     $query = DB::select("SELECT
    //                     aa.agenda_number,
    //                     aa.year,
    //                     aa.agenda_title,
    //                     aa.submitted_date_time,
    //                     aa.agenda_description,
    //                     aa.minister_approval_date_bs,
    //                     emr.meeting_code,
    //                     fy.code AS fiscal_year,
    //                     em.name_lc AS ministry,
    //                     mat.name_lc AS agenda_type,
    //                     emp.name_lc AS mp_name,
    //                     mpst.name_lc AS post_name,
    //                     aah.date_bs
    //                 FROM
    //                     agendas AS aa
    //                 LEFT JOIN
    //                     ec_meetings_requests AS emr ON emr.id = aa.ec_meeting_request_id
    //                 LEFT JOIN
    //                     mst_fiscal_years AS fy ON fy.id = aa.fiscal_year_id
    //                 LEFT JOIN
    //                     mst_agenda_types AS mat ON mat.id = aa.agenda_type_id
    //                 LEFT JOIN
    //                     ec_ministry AS em ON em.id = aa.ministry_id
    //                 LEFT JOIN
    //                     agenda_approval_history AS aah ON aah.agenda_id = aa.id
    //                         AND aah.status_id = 1
    //                         AND aah.role_id = 4
    //                 LEFT JOIN
    //                     ec_ministry_members AS emm ON aa.ministry_id = emm.id
    //                 LEFT JOIN
    //                     ec_mp AS emp ON emp.id = emm.mp_id
    //                 LEFT JOIN
    //                     mst_posts AS mpst ON mpst.id = emp.post_id
    //                 WHERE
    //                     aah.status_id IS NOT NULL
    //                 AND
    //                     {$addClause}
    //                 ");

    //         // dd(request());
    //     if(isset(request()->is_report_data) && request()->is_report_data == True){
    //         $reports = $query;
    //         $this->data['columns'] = ['क्र.सं.','कार्यसूची संख्या','बैठक संख्या','आ.व.','प्रस्ताव दर्ता गर्ने मन्त्रालय','रस्तावको प्रकार','प्रस्तावको विषय','प्रस्ताव संख्या','प्रस्ताव स्वीकृत मिति'];
    //         foreach($reports as $data){
    //             $i++;
    //             $output[] = [
    //                 'क्र.सं.'                => $i,
    //                 'कार्यसूची संख्या'           =>$data->agenda_number,
    //                 'बैठक संख्या'             =>$data->meeting_code,
    //                 'आ.व.'                =>$data->fiscal_year,
    //                 'प्रस्ताव दर्ता गर्ने मन्त्रालय'     =>$data->ministry,
    //                 'प्रस्तावको प्रकार'           =>$data->agenda_type,
    //                 'प्रस्तावको विषय'            =>$data->agenda_title,
    //                 'प्रस्ताव संख्या'             =>$data->meeting_code,
    //                 'प्रस्ताव स्वीकृत मिति'         =>$data->submitted_date_time,

    //             ];
    //         }
    //         $this->data['output']   =   $output;
    //         return view('reports.common_report', $this->data);
    //     }else{
    //         $reports = $query;
    //         // dd($reports);
    //         $view='reports.report_excel';

    //         // return view($view, compact('reports',))->render();
    //         $html = view($view, compact('reports',))->render();
    //         $fileName=trans('menu.report');
    //         $res = PdfPrint::printLandscape($html, $fileName);
    //     }


    // }

    public function export(){
        // dd(request()->month);

        $request = request()->except(['_token',]);
        $addClause = '1=1';
        $output = [];
        $i = 0;
        if(request()->fiscal_year_id){
            $addClause = $addClause." and aa.fiscal_year_id ='".request()->fiscal_year_id."'";
        }
        if(request()->month){
            $addClause = $addClause." and LTRIM(SPLIT_PART(REPLACE(aa.minister_approval_date_bs, '/', '-'), '-', 2), '0') ='".request()->month."'";
        }
        if(request()->ministry_id){
            $addClause = $addClause." and aa.ministry_id ='".request()->ministry_id."'";
        }
        if(request()->agenda_type_id){
            $addClause = $addClause." and aa.agenda_type_id ='".request()->agenda_type_id."'";
        }

        $query = DB::select("SELECT
                        aa.agenda_number,
                        aa.year,
                        aa.agenda_title,
                        aa.submitted_date_time,
                        aa.agenda_description,
                        aa.minister_approval_date_bs,
                        emr.meeting_code,
                        fy.code AS fiscal_year,
                        em.name_lc AS ministry,
                        mat.name_lc AS agenda_type,
                        emp.name_lc AS mp_name,
                        mpst.name_lc AS post_name,
                        aah.date_bs
                    FROM
                        agendas AS aa
                    LEFT JOIN
                        ec_meetings_requests AS emr ON emr.id = aa.ec_meeting_request_id
                    LEFT JOIN
                        mst_fiscal_years AS fy ON fy.id = aa.fiscal_year_id
                    LEFT JOIN
                        mst_agenda_types AS mat ON mat.id = aa.agenda_type_id
                    LEFT JOIN
                        ec_ministry AS em ON em.id = aa.ministry_id
                    LEFT JOIN
                        agenda_approval_history AS aah ON aah.agenda_id = aa.id
                            AND aah.status_id = 1
                            AND aah.role_id = 4
                    LEFT JOIN
                        ec_ministry_members AS emm ON aa.ministry_id = emm.id
                    LEFT JOIN
                        ec_mp AS emp ON emp.id = emm.mp_id
                    LEFT JOIN
                        mst_posts AS mpst ON mpst.id = emp.post_id
                    WHERE
                        aah.status_id IS NOT NULL
                    AND
                        {$addClause}
                    ");

            // dd(request());
        if(isset(request()->is_report_data) && request()->is_report_data == True){
            $reports = $query;
            $this->data['columns'] = ['क्र.सं.','कार्यसूची संख्या','बैठक संख्या','आ.व.','प्रस्ताव दर्ता गर्ने मन्त्रालय','रस्तावको प्रकार','प्रस्तावको विषय','प्रस्ताव संख्या','प्रस्ताव स्वीकृत मिति'];
            foreach($reports as $data){
                $i++;
                $output[] = [
                    'क्र.सं.'                => $i,
                    'कार्यसूची संख्या'           =>$data->agenda_number,
                    'बैठक संख्या'             =>$data->meeting_code,
                    'आ.व.'                =>$data->fiscal_year,
                    'प्रस्ताव दर्ता गर्ने मन्त्रालय'     =>$data->ministry,
                    'प्रस्तावको प्रकार'           =>$data->agenda_type,
                    'प्रस्तावको विषय'            =>$data->agenda_title,
                    'प्रस्ताव संख्या'             =>$data->meeting_code,
                    'प्रस्ताव स्वीकृत मिति'         =>$data->submitted_date_time,

                ];
            }
            $this->data['output']   =   $output;
            return view('reports.common_report', $this->data);
        }else{
            $date = Carbon::now()->toDateString();
            return Excel::download(new ReportExport, $date.'.xlsx');
        }


    }

}
