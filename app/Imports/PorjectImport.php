<?php

namespace App\Imports;

use App\Models\PtProject;
use Illuminate\Support\Collection;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstFiscalYear;
use Maatwebsite\Excel\Concerns\ToCollection;

class PorjectImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $skipCount = 1;
        foreach($collection as $row){
            if($skipCount>0){
                $skipCount--;
                continue; //skip the current iteration
            }
            $fiscal_year_id = MstFiscalYear::where('code',$row[0])->first()->id;
            $ministry_id = MstMinistry::where('name_lc',$row[1])->first()->id;
            if($fiscal_year_id && $ministry_id){
                $project_code = $row[2];
                $project_name = $row[3];
                $expenditure_title=$row[4];
                $project_budget=$row[5];
                $comment = $row[6];
                $ministryDarbandi = PtProject::create([
                    'fiscal_year_id' => $fiscal_year_id,
                    'ministry_id' => $ministry_id,
                    'project_code'     => $project_code,
                    'project_name'    => $project_name,
                    'expenditure_title'    => $expenditure_title,
                    'project_budget'    => $project_budget,
                    'comment'     => $comment,
                    'created_by' => backpack_user()->id,
                ]);
            }
        }
        return true;
    }
}
