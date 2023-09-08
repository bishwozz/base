<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinistryEmployeeRequest extends FormRequest
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
        $email = request()->get('email');
        $phone_number = request()->get('phone_number');

        // dd($email,$phone_number);


        $email_check = $id_check.",id,email,".$email.",deleted_uq_code,1";
        $phone_number_check = $id_check.",id,phone_number,".$phone_number.",deleted_uq_code,1";

        // dd('required|email|unique:ec_ministry_employees,email'.$email_check);

        return [
            'email' => 'required|email|unique:ec_ministry_employees,email'.$email_check,
            'phone_number' => 'required|unique:ec_ministry_employees,phone_number'.$phone_number_check,
            'post_id' => 'required',
            'full_name' => 'required',
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
            'role_id' => 'भूमिका',
            'email' => 'इमेल',
            'full_name' => 'नाम',
            'phone_number' => 'फोन नं.',
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
            'required' => ':attribute आवश्यक छ',
            'unique' => ':attribute पहिलेनै प्रयोग भैसकेको छ',
            'required_if' => ':attribute आवश्यक छ',
        ];
    }
}
