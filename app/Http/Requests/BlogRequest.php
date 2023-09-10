<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
        $id_check=$id_check.",id,deleted_uq_code,1";
        return [
            'title' => 'required|min:2|max:255',
            'category_id' => 'required',
            'description' => 'required|min:5|max:500',
            'display_order' => 'sometimes|unique:blogs,display_order'.$id_check
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
            'category_id.required' => 'Category Field is required, if you don\'t find the category then go to the category tab on left sidebar and add your new category',
        ];
    }
}
