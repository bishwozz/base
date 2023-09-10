<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request=request();
        $id_check = request()->request->get('id') ? ",".request()->request->get('id') : ",NULL";
        $parent_check=$id_check;
        if(isset($request->menu_id)){
            $menu_id = $request->request->get('menu_id');
            $parent_check=$parent_check.",id,menu_id,".$menu_id;
        }
        if(isset($request->name)){
            $category_name = strtolower($request->request->get('name'));
            $parent_check=$parent_check.",name,".$category_name;
        }
        return [
            'menu_id' => 'required',
            'name' => 'required|min:2|max:50|unique:categories,name'.$parent_check
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
