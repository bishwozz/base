<?php

namespace App\Http\Controllers\api;

use App\Models\EcMp;
use App\Models\User;
use App\Models\Ministry;
use Illuminate\Http\Request;
use App\Models\MinistryMember;
use App\Http\Controllers\Controller;

class EcMpController extends Controller
{
    public function getEcMp(Request $request, $value)
    {
        $search_term = $request->input('q');

        $form = collect($request->input('form'))->pluck('value', 'name');

        $page = $request->input('page');

        $options = EcMp::query();

        // if no category has been selected, show no options
        if (!data_get($form, $value)) { //countryvanne table ma search gareko using id

            return [];
        }

        // if a category has been selected, only show articles in that category
        if (data_get($form, $value)) {
                $allMinistryMpIds = MinistryMember::where('ministry_id', $form[$value])->pluck('mp_id')->toArray();
                // Fetch mp_id values used by existing users
                $existingUserMpIds = User::whereNotNull('mp_id')->pluck('mp_id')->toArray();
                // dd($existingUserMpIds,$allMinistryMpIds);

                // Find the difference between allMinistryMpIds and existingUserMpIds
                $availableMpIds = array_diff($allMinistryMpIds, $existingUserMpIds);

                $options = $options->whereIn('id', $availableMpIds);


        }

        // if a search term has been given, filter results to match the search term
        if ($search_term) {
            $options = $options->where('name', 'ILIKE', "%$search_term%"); //
        }

        return $options->paginate(10);
    }

}