<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcMpRequest extends FormRequest
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
        $id_check = Request()->request->get('id') ? ",".Request()->request->get('id') : ",NULL";
        $email = request()->get('email');
        $id = Request()->request->get('id');
        $mobile_number = request()->get('mobile_number');
        $email_check = $id_check.",id,email,".$email.",deleted_uq_code,1";
        $mobile_number_check = $id_check.",id,mobile_number,".$mobile_number.",deleted_uq_code,1";

        return [
            // 'signature_path' => 'required|max:700000',
            'photo_path' => 'max:700000',
            'display_order' => 'required',
            'mobile_number' => 'required|max:10|min:10|unique:ec_mp,mobile_number'.$mobile_number_check,
            'name_lc'=>'required',
            'gender_id' =>'required',
            'email' => 'required|email|unique:ec_mp,email'.$email_check,
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
           'signature_path' =>trans('common.signature'),
           'photo_path' =>trans('mp.photo'),
           'name_lc' => trans('common.name_lc'),
           'gender_id' => trans('menu.signature'),
           'display_order' =>trans('common.display_order'),
           'email' =>trans('common.email')
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
            'max' =>':attribute 500 KB भन्दा कम राख्नु होला! '
        ];
    }
}
