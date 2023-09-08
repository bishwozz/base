<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EcMeetingRequestRequest extends FormRequest
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
        return [
            'name_lc' => 'required|max:200',
            'fiscal_year_id' => 'required',
            'start_date_bs' => 'required',
            'start_time' => 'required',
            'meeting_for' => 'required',
            'committee_id' => 'required_if:meeting_for,==,1',
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
            'fiscal_year_id' => trans('common.fiscal_year'),
            'ministry_id' => trans('common.ministry'),
            'start_date_bs' => trans('common.start_date_bs'),
            'start_time' => trans('common.start_time'),
            'agenda' => trans('common.agenda'),
            'name_lc' => trans('common.name_lc'),
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
