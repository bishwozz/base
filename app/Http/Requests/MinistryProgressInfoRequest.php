<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinistryProgressInfoRequest extends FormRequest
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

        $fiscal_year_id = $this->request->get('fiscal_year_id');
        $fiscal_year_check =$id_check.",id,fiscal_year_id,".$fiscal_year_id;


        $month_id = $this->request->get('month_id');
        $month_check = $fiscal_year_check.",month_id,".$month_id;

        $ministry_id = $this->request->get('ministry_id');
        $ministry_check = $month_check.",ministry_id,".$ministry_id;

        return [
            'fiscal_year_id' => 'required|unique:ministry_progress_info,fiscal_year_id'.$ministry_check,
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
            'fiscal_year_id.unique' => 'यो आर्थिक वर्ष, महिना र मन्त्रालयको प्रगति विवरण प्रविस्ट गरिसकिएको छ !!',
        ];
    }
}
