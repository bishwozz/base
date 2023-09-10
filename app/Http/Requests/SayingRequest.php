<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class SayingRequest extends FormRequest
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
            'said_by' => 'required|min:2|max:255',
            'saying' => 'required|min:2|max:500',
            'display_order' => 'sometimes|unique:sayings,display_order'.$id_check
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
