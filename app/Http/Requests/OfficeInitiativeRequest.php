<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficeInitiativeRequest extends FormRequest
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
            'achievements' => 'required|min:5',
            'innovatives' => 'max:500',
            'achievements' => 'max:500',
            'challenges' => 'max:500',
            'initiatives' => 'max:500',
            'expectations' => 'max:500',
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
            'innovatives.max' => '500 माथि शब्द सीमा पुग्यो !!!',
            'achievements.max' => '500 माथि शब्द सीमा पुग्यो !!!',
            'challenges.max' => '500 माथि शब्द सीमा पुग्यो !!!',
            'initiatives.max' => '500 माथि शब्द सीमा पुग्यो !!!',
            'expectations.max' => '500 माथि शब्द सीमा पुग्यो !!!',
        ];
    }
}
