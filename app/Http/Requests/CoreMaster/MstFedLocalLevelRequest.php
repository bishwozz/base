<?php

namespace App\Http\Requests\CoreMaster;

use Illuminate\Foundation\Http\FormRequest;

class MstFedLocalLevelRequest extends FormRequest
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
        $id_check = request()->request->get('id') ? ",".request()->request->get('id') : ",NULL";
        $name_lc = $this->request->get('name_lc');
        $name_lc_check = $id_check.",id,name_lc,".$name_lc.",deleted_uq_code,1";
        $name_en = $this->request->get('name_en');
        $name_en_check =$id_check.",id,name_en,".$name_en.",deleted_uq_code,1";
        $code = $this->request->get('code');
        $code_check = $id_check.",id,code,".$code.",deleted_uq_code,1";
        return [
            'code' => 'max:20|unique:mst_fed_local_levels,code'.$code_check,
            'name_en' => 'max:200|unique:mst_fed_local_levels,name_en'.$name_en_check,
            'name_lc' => 'required|max:200|unique:mst_fed_local_levels,name_lc'.$name_lc_check,
            'district_id' => 'required',
            'level_type_id' => 'required',
            'remarks' => 'max:500',
            'gps_lat' => 'max:20',
            'gps_long' => 'max:20',
            
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
