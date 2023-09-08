<?php

namespace App\Http\Controllers;

use App\Models\OfficeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CoreMaster\MstMinistry;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $ministry_id = $request->ministry_id;
        $section_type = $request->section_type;
        $fiscalYearId = $request->fiscal_year_id;
        $monthId = $request->monthId;

        $ministry_clause = '1=1';
        $month_clause = '1=1';
        $ministry_field = true;
        $fy_clause = '1=1';
     
        if($ministry_id && $ministry_id !== 'all'){
            $ministry_clause='mm.id='.$ministry_id;
        }

        if($monthId != ''){
            $month_clause='mpi.month_id='.$monthId;
        }
        if($fiscalYearId != ''){
            $fy_clause='pp.fiscal_year_id='.$fiscalYearId;
        }

        //filter ministry when ministry user is logged in
        if(backpack_user()->ministry_id)
        {
            $ministry_id = backpack_user()->ministry_id;
            $ministry_clause='mm.id='.$ministry_id;

            //hide ministry field in data
            $ministry_field=false;
        }

        $this->data['ministry_field']= $ministry_field;
        $this->data['selected_ministry_id']= $request->selectedMinistryId;
        $this->data['ministries']=MstMinistry::orderBy('id','asc')->get();
        $ministry_name = MstMinistry::find($request->selectedMinistryId)->name_lc;
        // dd($request->selectedMinistryId,$this->data);
        //switch case using section selected
        switch($section_type){

            case 'milestone':
                if($fiscalYearId != ''){
                    $fy_clause='pp.fiscal_year_id='.$fiscalYearId;
                }
                $milestone_results = DB::table('pt_project_milestones as ppm')
                                            ->leftJoin('pt_project as pp','pp.id','ppm.project_id')
                                            ->leftJoin('mst_ministries as mm','mm.id','pp.ministry_id')
                                            ->leftJoin('progress_milestones_details as pmd','pmd.milestone_id','ppm.id')
                                            ->select('mm.id as ministry_id','mm.name_lc as ministry_name','ppm.id as milestone_id','pmd.milestone_status_id')
                                            ->whereRaw($fy_clause)
                                            ->whereRaw($ministry_clause)
                                            ->where('ppm.deleted_uq_code',1)
                                            ->orderBy('mm.id','asc')
                                            ->get();
            
                $collection = new Collection($milestone_results);

                $uniqueMilestoneIds = $collection->pluck('milestone_id')->unique();
                
                $resultArray = [];
                
                foreach ($uniqueMilestoneIds as $milestoneId) {
                    $filteredItems = $collection->where('milestone_id', $milestoneId);
                    $maxStatusId = $filteredItems->max('milestone_status_id');
                    $maxStatusItem = $filteredItems->where('milestone_status_id', $maxStatusId)->first();
                
                    $resultArray[] = $maxStatusItem;
                }
                
                $resultCollection = new Collection($resultArray);

                $temp_results_arr =[];
                // process milestone _id 
                $temp_results_arr = [];


                foreach ($resultCollection as $milestone) {
                    $ministryId = $milestone->ministry_id;
                    $statusId = $milestone->milestone_status_id;
                    $milestoneId = $milestone->milestone_id;

                
                    if (!isset($temp_results_arr[$ministryId])) {
                        $temp_results_arr[$ministryId]['ministry_name'] = $milestone->ministry_name;
                        $temp_results_arr[$ministryId]['data'] = [
                            'not_started' => 0,
                            'completed' => 0,
                            'wip' => 0,
                        ];
                    }

                    // For status tracking purposes
                    switch ($statusId) {
                        case null:
                            $temp_results_arr[$ministryId]['data']['not_started']++;
                            break;
                        case 8:
                            $temp_results_arr[$ministryId]['data']['completed']++;
                            break;
                        default:
                            $temp_results_arr[$ministryId]['data']['wip']++;
                            break;
                    }
                }

                
                $result_output=$temp_results_arr;
                $this->data['section_type']=$section_type;
                $this->data['final_result']=$result_output;
                $this->data['chart_title']= $ministry_name.'- योजना क्रियाकलाप विवरण';

                $this->data['final_view'] = view('dashboard.milestone_view',$this->data)->render();
                break;

            case 'darbandi':
                $darbandi_results = DB::table('mst_ministries as mm')
                                ->leftJoin('ministry_darbandi as md','md.ministry_id','mm.id')
                                ->leftJoin('mst_level as ml','md.level_id','ml.id')
                                ->select('mm.id','mm.name_lc as ministry','ml.name_lc as level','md.total_darbandi','md.perm_darbandi','md.temp_darbandi','md.vacant_darbandi')
                                ->whereRaw($ministry_clause)
                                ->where('md.deleted_uq_code',1)
                                ->where('ml.deleted_uq_code',1)
                                ->orderBy('mm.id','asc')
                                ->orderBy('ml.display_order','asc')
                                ->get();
                $result_output=[];
                //process query results
                foreach($darbandi_results as $res){
                $result_output[$res->id]['name'] = $res->ministry;
                unset($res->ministry);
                $result_output[$res->id]['data'][] = $res;
                }
                $this->data['section_type']=$section_type;
                $this->data['final_result']=$result_output;
                $this->data['final_view'] = view('dashboard.darbandi_view',$this->data)->render();

            break;

            case 'law':
                 //laws and acts information
                unset($result_output);
                $result_output=[];
                $acts_laws = DB::table('ministry_act_laws as mal')
                                        ->leftJoin('mst_ministries as mm','mal.ministry_id','mm.id')
                                        ->select('mal.*','mm.name_lc as ministry_name')
                                        ->whereRaw($ministry_clause)
                                        ->where('mal.deleted_uq_code',1)
                                        ->orderBy('mm.id','asc')
                                        ->get();


                // Initialize counters for each type and status
                    foreach ($acts_laws as $res) {
                        $result_output[$res->ministry_id] = [
                            'ministry_name' => $res->ministry_name,
                            'type' => [
                                'new' => [0, 0, 0],
                                'ammendment' => [0, 0, 0]
                            ]
                        ];
                    }

                    foreach ($acts_laws as $res) {
                        $ministry_id = $res->ministry_id;
                        $status = $res->status;
                        $type = $res->type;
                    
                        if ($type == 0) {
                            $result_output[$ministry_id]['type']['new'][$status] += 1;
                        } else {
                            $result_output[$ministry_id]['type']['ammendment'][$status] += 1;
                        }
                    }

                $this->data['section_type']=$section_type;
                $this->data['final_result']=$result_output;
                $this->data['chart_title']= $ministry_name.'- ऐन कानुन निर्माणको अवस्था';
                $this->data['final_view'] = view('dashboard.laws_acts_view',$this->data)->render();
                break;

               case 'progress':
                 //physical and financial information
                    unset($result_output);
                    if($fiscalYearId != ''){
                        $fy_clause='mpi.fiscal_year_id='.$fiscalYearId;
                    }
                    $result_output=[];
                    $financial_progress = DB::table('ministry_progress_info as mpi')
                                    ->leftJoin('mst_ministries as mm','mpi.ministry_id','mm.id')
                                    ->leftJoin('mst_nepali_months as mnm','mpi.month_id','mnm.id')
                                    ->select('mm.id as id','mm.name_lc as ministry_name','mnm.name_lc as month','mpi.current_progress_financial','mpi.capital_progress_financial','mpi.total_progress_financial',
                                            'mpi.current_progress_physical','mpi.capital_progress_physical','mpi.total_progress_physical','mpi.beruju_farchyat_percent')
                                    ->whereRaw($ministry_clause)
                                    ->whereRaw($fy_clause)
                                    ->whereRaw($month_clause)
                                    ->where('mpi.deleted_uq_code',1)
                                    ->orderBy('mm.id','asc')
                                    ->orderBy('mnm.id','asc')
                                    ->get();        
                    //process query results
                    foreach($financial_progress as $res){
                        $result_output[$res->id]['ministry_name'] = $res->ministry_name;
                        unset($res->ministry_name);
                        $result_output[$res->id]['data'][] = $res;
                    }
                    $this->data['section_type']=$section_type;
                    $this->data['final_result']=$result_output;
                    $this->data['chart_title']= $ministry_name.'- वित्तीय तथा भौतिक प्रगति विवरण';
                    $this->data['final_view'] = view('dashboard.financial_physical_progress_view',$this->data)->render();

                break;

                case 'bidding':
                case 'office':

                    //office details
                    unset($result_output);
                    if($fiscalYearId != ''){
                        $fy_clause='od.fiscal_year_id='.$fiscalYearId;
                    }
                    $result_output=[];
                    $office_details = DB::table('office_details as od')
                                            ->leftJoin('mst_ministries as mm','od.ministry_id','mm.id')
                                            ->select('mm.id','mm.name_lc as ministry_name','od.is_information_updated','od.internal_control_system','od.ladies_friendly_toilet','od.disable_friendly_toilet',
                                            'od.current_two_wheeler','od.current_four_wheeler','od.required_two_wheeler','od.required_four_wheeler','od.public_procurement','od.online_procurement_contract',
                                            'od.total_operating_contract','od.inspection_monitoring_period','od.inspection_count')
                                            ->whereRaw($ministry_clause)
                                            ->whereRaw($fy_clause)
                                            ->where('od.deleted_uq_code',1)
                                            ->orderBy('mm.id','asc')
                                            ->get();
                    
                    foreach($office_details as $res){
                        $result_output[$res->id] = [
                            'ministry_name'=>$res->ministry_name,
                            'is_information_updated'=>$res->is_information_updated,
                            'internal_control_system'=>OfficeDetail::$internal_control_system[$res->internal_control_system],
                            'ladies_friendly_toilet'=>$res->ladies_friendly_toilet,
                            'disable_friendly_toilet'=>$res->disable_friendly_toilet,
                            'current_two_wheeler'=>$res->current_two_wheeler,
                            'current_four_wheeler'=>$res->current_four_wheeler,
                            'required_two_wheeler'=>$res->required_two_wheeler,
                            'required_four_wheeler'=>$res->required_four_wheeler,

                            'public_procurement'=>OfficeDetail::$public_procurement[$res->public_procurement],
                            'online_procurement_contract'=>$res->online_procurement_contract,
                            'total_operating_contract'=>$res->total_operating_contract,
                            'inspection_monitoring_period'=>OfficeDetail::$inspection_monitoring_period[$res->inspection_monitoring_period],
                            'inspection_count'=>$res->inspection_count,
                        ];
                    }
                    $this->data['section_type']=$section_type;
                    $this->data['final_result']=$result_output;
                    if($section_type == 'bidding'){
                        $this->data['chart_title']= $ministry_name.'- सार्वजनिक खरीद तथा ठेक्का व्यवस्थापन';
                        $view = 'dashboard.procurement_contract_view';
                    }else{
                        $this->data['chart_title']= $ministry_name.'- कार्यालय व्यवस्थापन विवरण';
                        $view = 'dashboard.office_details_view';
                    }
                    $this->data['final_view'] = view($view,$this->data)->render();
                break;    

        }


        // $procurement_contract_details_view = view('dashboard.procurement_contract_view',['result_output'=>$result_output,'ministry_field'=>$ministry_field])->render();
        return response()->json($this->data);
        // return response()->json(['darbandi_view'=>$darbandi_view,
        //                         'financial_physical_progress_view'=>$financial_physical_progress_view,
        //                         'laws_acts_view'=>$laws_acts_view,
        //                         'office_details_view'=>$office_details_view,
        //                         'procurement_contract_details_view'=>$procurement_contract_details_view,
        //                         'darbandi_result'=>$darbandi_results
        //                     ]);
    }
}
