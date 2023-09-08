<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinistryProgramProgressRequest extends FormRequest
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

        $month_id = $this->request->get('month_id');
        $month_check = $id_check.",month_id,".$month_id;


        $project_id = $this->request->get('project_id');
        $project_check = $month_check.",project_id,".$project_id;

        return [
            'month_id' => 'required|unique:ministry_program_progress,month_id'.$project_check,
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
            'fiscal_year_id.unique' => 'यो कार्यक्रम र महिना प्रगति विवरण प्रविस्ट भैसकेको छ !!',
        ];
    }
}
