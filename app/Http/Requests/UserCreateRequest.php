<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
        $phone_no = request()->get('phone_no');
        $email_check = $id_check.",id,email,".$email.",deleted_uq_code,1";
        $phone_no_check = $id_check.",id,phone_no,".$phone_no.",deleted_uq_code,1";


     
        return [
            // 'mp_id' => [
            //     'required',
            //     Rule::unique('ec_ministry_members', 'mp_id')->where('deleted_uq_code', 1)->ignore($id),
            // ],
            'email' => 'required|email|unique:users,email'.$email_check,

            'phone_no' => 'required|max:10|min:10|unique:users,phone_no'.$phone_no_check,
            'name'     => 'required',
            'ministry_id'     => 'required',
            'password' => 'required|confirmed',
            'mp_id' => 'required_if:is_ministry_member,1',
            // 'mp_id' => ['required_if:is_ministry_member,1',Rule::unique('ec_mp', 'mp_id')->where('deleted_uq_code', 1)->ignore($id)],
            // 'employee_id' => ['required_if:is_ministry_member,0',Rule::unique('users', 'employee_id')->where('deleted_uq_code', 1)->ignore($id)],
            'employee_id' => ['required_if:is_ministry_member,0',Rule::unique('users', 'employee_id')->where('deleted_uq_code', 1)->ignore($id)],
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

            'mp_id' => 'मन्त्रालय सदस्य',
            'employee_id' => 'मन्त्रालय कर्मचारी',
            'name' => 'नाम',
            'email' => 'इमेल',
            'password' => 'पासवर्ड',
            'phone_no' => 'फोन नं.',
            'ministry_id' => 'मन्त्रालय',


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
            'required_if' => ':attribute आवश्यक छ',
            'required' => ':attribute आवश्यक छ',
            'unique' => ':attribute पहिलेनै प्रबिस्ट भैसकेको छ',


        ];
    }
}
