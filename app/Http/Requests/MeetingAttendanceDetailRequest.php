<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class MeetingAttendanceDetailRequest extends FormRequest
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
        $mp_id = $this->request->get('mp_id');
        $meeting_request_id = $this->request->get('meeting_request_id');
        // $mp_id_check = $id_check.",deleted_uq_code,1";
        $mp_id_check = $id_check.",id,mp_id,".$mp_id.",meeting_request_id,".$meeting_request_id.",deleted_uq_code,1";

        return [
            // 'mp_id' => 'required|unique:meeting_attendance_details,mp_id,' . $mp_id . ',meeting_request_id,' . $meeting_request_id .','.$id_check,
            'mp_id' => 'required|unique:meeting_attendance_details,mp_id'.$mp_id_check,
            // 'requested_date_bs' => 'required|max:10',
            // 'requested_date_ad' => 'required',
            // 'present_time' => 'required',
            // 'signature' => 'required',
            // 'agenda' => [
            //     'required',
            //     function($attribute, $value, $fail){
            //         $fieldGroups = $value?json_decode($value):[];

            //         if(count($fieldGroups) == 0){
            //             return $fail('Agenda आवश्यक छ');
            //         }

            //         $attributes = [
            //             'agenda' => 'Agenda',
            //         ];

            //         $message = [
            //             'required' => ':attribute आवश्यक छ',
            //         ];

            //         foreach($fieldGroups as $key => $group){
            //             $fieldGroupValidator = Validator::make((array) $group,[
            //                 'agenda' => 'required',
            //             ],$message,$attributes);

            //             if($fieldGroupValidator->fails()){
            //                 return $fail($fieldGroupValidator->errors()->all());
            //             }
            //         }
            //     }
            // ],
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
            'meeting_id' => trans('common.meeting'),
            'mp_id' => trans('common.mp'),
            'requested_date_bs' => trans('common.requested_date_bs'),
            'requested_date_ad' => trans('common.requested_date_ad'),
            'present_time' => trans('common.present_time'),
            'signature' => trans('common.signature'),
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
        ];
    }
}
