<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class ParentMenuApiController extends Controller
{
    public function index(Request $request,$value)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Menu::query();//model ma query gareko

        if (!data_get($form, $value)) {
            return [];
        }
        
        // if (data_get($form, $value)) {
        //     $options = $options->where('type', $form[$value]);
        // }
        // if a search term has been given, filter results to match the search 
        if (data_get($form, $value)) {
            $value = ($form[$value] - 1);
            $options = $options->where('type_id', $value);
        }

        if ($search_term) {
            $options = $options->where('type_id', 'LIKE', '%'.$search_term.'%');//k tannalako state ho tesaile
        }
        
        // dd($options->get());

        return $options->paginate(10); 
    }
}
