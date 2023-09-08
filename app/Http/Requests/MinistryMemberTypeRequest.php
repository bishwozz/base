<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinistryMemberTypeRequest extends FormRequest
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
        return [
            'name_lc'=>'required|max:200|unique:mst_ministry_member_type,name_lc'.$name_lc_check,
            'name_en'=>'max:200|unique:mst_ministry_member_type,name_en'.$name_en_check,
            'remarks' => 'max:500',
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
            'name_lc' => trans('common.name_lc'),
            'name_en' => trans('common.name_en'),
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
            'required' => ':attribute आवश्यक छ',
            'unique' => ':attribute पहिलेनै प्रयोग भैसकेको छ',
        ];
    }
}
