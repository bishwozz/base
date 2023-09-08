<?php

namespace App\Imports;

use App\Models\MstLevel;
use App\Models\MinistryDarbandi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class MinistryDarbandiImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $skipCount = 2;
        foreach($collection as $row){
            if($skipCount>0){
                $skipCount--;
                continue; //skip the current iteration
            }
            $level_id = MstLevel::where('name_lc',$row[0])->first()->id;
            if($level_id){
                $ministry_id = request('ministry_id');
                $total_darbandi = $row[1];
                $perm_darbandi = $row[2];
                $temp_darbandi = $row[3];
                $vacant_darbandi = $row[4];
                $comment = $row[5];
                $ministryDarbandi = MinistryDarbandi::create([
                    'ministry_id' => $ministry_id,
                    'level_id' => $level_id,
                    'total_darbandi'    => $total_darbandi,
                    'perm_darbandi'     => $perm_darbandi,
                    'temp_darbandi' => $temp_darbandi,
                    'vacant_darbandi' => $vacant_darbandi,
                    'comment'    => $comment,
                    'created_by' => backpack_user()->id,
                ]);
            }
        }
        return true;
    }
}
