<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MinistryMemberRequest extends FormRequest
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
        $id = $this->request->get('id');

        return [
            'mp_id' => [
                'required',
                Rule::unique('ec_ministry_members', 'mp_id')->where('deleted_uq_code', 1)->ignore($id),
            ],
            'date_from_bs' => 'max:10',
            'date_to_bs' => 'max:10',
            'remarks' => 'max:1000',
            'role_id' => 'required_if:allow_user_login,1',

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
            'mp_id' => 'उक्त सदस्य ',
            'role_id' => 'भूमिका',
           
     

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
            'unique' => ':attribute पहिलेनै प्रबिस्ट भैसकेको छ',
            'required_if' => ':attribute आवश्यक छ',
        ];
    }
}
