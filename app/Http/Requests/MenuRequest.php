<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
        if(isset($request->parent_id)){
            $parent_id = $request->request->get('parent_id');
            $parent_check=$parent_check.",id,parent_id,".$parent_id;
        }
        if(isset($request->display_order)){
            $display_order = $request->request->get('display_order');
            if(isset($request->parent_id)){
                $parent_check=$parent_check.",display_order,".$display_order;
            }else{
                $parent_check=$parent_check.",id,display_order,".$display_order;
            }
        } 
        if(isset($request->parent_id) || isset($request->display_order)){
            $parent_check=$parent_check.",deleted_uq_code,1";
        }else{
            $parent_check=$parent_check.",id,deleted_uq_code,1";
        }
        return [
            'type_id' => 'required',
            'parent_id'=>'required_unless:type_id,0',
            'title' => 'required|min:2|max:255',
            'display_order' => 'sometimes|unique:menus,display_order'.$parent_check
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
