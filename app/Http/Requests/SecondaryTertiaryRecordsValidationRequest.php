<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SecondaryTertiaryRecordsValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'morbidityMonth' => 'required|date|before_or_equal:today',
            'dateReported' => 'required|date|before_or_equal:today',
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
            'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
            'gender' => 'nullable|in:MALE,FEMALE',
            'bdate' => 'nullable|date|before:tomorrow',
            'email' => 'nullable|email',
            'mobile' => 'nullable|numeric|digits:11|starts_with:09',
            'address_houseno' => 'nullable',
			'address_street' => 'nullable',
			'address_brgy' => 'nullable',
			'address_city' => 'nullable',
			'address_province' => 'nullable',
            'temperature' => 'nullable',
            'is_primarycc' => 'sometimes',
            'is_secondarycc' => 'sometimes',
            'is_tertiarycc' => 'sometimes',
            'is_primarycc_date' => 'required_if:is_primarycc,1',
            'is_secondarycc_date' => 'required_if:is_secondarycc,1',
            'is_tertiarycc_date' => 'required_if:is_tertiarycc,1',
        ];
    }
}
