<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProgramInformation;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\CoreMaster\AppSetting;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\PtProject;

class MinistryDataController extends Controller
{
    public function getMinistryData($ministry_id){
        $ministery = MstMinistry::findOrFail($ministry_id);
        $secretary_name = $ministery->secretary_name_lc;
        $secretary_no = $ministery->secretary_contact_number;
        $information_officer_name = $ministery->information_officer_name_lc;
        $information_officer_no = $ministery->information_officer_contact_number;
        $data = [
            'secretary_name'=>$secretary_name,
            'secretary_no'=>$secretary_no,
            'information_officer_name'=>$information_officer_name,
            'information_officer_no'=>$information_officer_no,
        ];
        return $data;
    }


    public function getMinistryProject(Request $request, $value1, $value2)
    {
        $search_term = $request->input('q');

        $form = collect($request->input('form'))->pluck('value', 'name');

        $page = $request->input('page');

        $options = PtProject::query(); //model ma query gareko

        // if no category has been selected, show no options
        if (!data_get($form, $value1, $value2)) { //countryvanne table ma search gareko using id

            return [];
        }

        // if a category has been selected, only show articles in that category
        if (data_get($form, $value1, $value2)) {
                $ministry = MstMinistry::find($form[$value1]);
                $fiscal_year = MstFiscalYear::find($form[$value2]);
                $options = $options->where('ministry_id', $ministry->id)->where('fiscal_year_id', $fiscal_year->id);
        }
        // if a search term has been given, filter results to match the search term
        if ($search_term) {
            $options = $options->where('name', 'ILIKE', "%$search_term%"); //
        }

        return $options->paginate(10);
    }

    public function getProject($ministry_id, $fiscal_year_id) {
        $projects = PtProject::where('ministry_id', $ministry_id)->where('fiscal_year_id', $fiscal_year_id)->get();
        return $projects;
        
    }
}
