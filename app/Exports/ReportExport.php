<?php

namespace App\Exports;

use App\Models\Agenda;
use App\Models\Report;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        $reports  = $this->reports();
        $data = array(
            'reports' => $reports,
        );
        return view('reports.report_excel', $data);
    }

    public function reports()
    {
        $request = request()->except(['_token',]);
        $addClause = '1=1';
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


        $reports = $query;
        return $reports;
    }
}
