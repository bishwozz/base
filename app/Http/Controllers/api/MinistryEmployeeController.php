<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MinistryEmployee;
use App\Http\Controllers\Controller;

class MinistryEmployeeController extends Controller
{
    public function index(Request $request, $value)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = MinistryEmployee::query();
        // if no Ministry has been selected
        if (!data_get($form, $value)) {
            return [];
        }
        // if a district has been selected, only show localevel from that district
        if (data_get($form, $value)) {
            $allMinistryEmployeeIds = MinistryEmployee::where('ministry_id', $form[$value])->pluck('id')->toArray();
            // Fetch mp_id values used by existing users
            $existingUserEmployeeIds = User::pluck('employee_id')->toArray();

            // Find the difference between allMinistryEMployeeIds and existingUserEmployeeIds
            $availableEmployeeIds = array_diff($allMinistryEmployeeIds, $existingUserEmployeeIds);
            
            $options = $options->whereIn('id', $availableEmployeeIds);
        }

        if ($search_term) {
            $results = $options->where('name', 'LIKE', '%' . $search_term . '%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $results;

    }

}
