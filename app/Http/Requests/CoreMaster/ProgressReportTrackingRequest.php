<?php

namespace App\Http\Requests\CoreMaster;

use Illuminate\Foundation\Http\FormRequest;

class ProgressReportTrackingRequest extends FormRequest
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
        return [
            // 'name' => 'required|min:5|max:255'
            'fiscal_year_id' => 'required',
            'month_id' => 'required',
            'ministry_id' => 'required',
            'secretary_name' => 'required',
            'secretary_contact_number' => 'required',
            'information_officer_name' => 'required',
            'information_officer_contact_number' => 'required',
            'org_structure_upload' => 'required',
            'total_approved_darbandi' => 'required',
            'current_working_emp_per' => 'required',
            'current_working_emp_cont' => 'required',
            'total_vacant_darbandi' => 'required',
            'employee_details_upload' => 'required',
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
