<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcMpTenureRequest extends FormRequest
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
            'mp_id' => 'required',
            'date_from_bs' => 'required',
            'date_from_ad' =>'required',
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
            'mp_id' =>'menu.ecMps',
            'date_from_bs' => 'common.date_from_bs',
            'date_from_ad' =>'common.date_from_ad'
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
