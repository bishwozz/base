<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingMinuteDetailRequest extends FormRequest
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
            'meeting_request_id' => 'required',
            'fiscal_year_id' => 'required',
            // 'ministry_id' => 'required',
            // 'file_upload' => 'required|mimes:pdf|max:2048',
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
            'meeting_request_id' => trans('common.meeting_request'),
            'fiscal_year_id' => trans('common.fiscal_year'),
            'ministry_id' => trans('common.ministry'),
            'file_upload' => trans('common.file_upload_common'),
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
            'required' => ':attribute आवश्यक छ'
        ];
    }
}
