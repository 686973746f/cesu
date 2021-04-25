<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordValidationRequest extends FormRequest
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
			'lname' => 'required|max:50',
    		'fname' => 'required|max:50',
    		'mname' => 'required|max:50',
			'gender' => 'required',
			'cs' => 'required',
			'nationality' => 'required',
			'bdate' => "required|date|before:tomorrow",
			'mobile' => 'required|numeric|digits:11',
			'phoneno' => 'nullable|numeric',
			'email' => 'nullable|email',
			'philhealth' => 'nullable',
			'address_houseno' => 'required',
			'address_street' => 'required',
			'address_brgy' => 'required',
			'address_city' => 'required',
			'address_province' => 'required',
			'permaaddress_houseno' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permaaddress_street' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permaaddress_brgy' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permaaddress_city' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permaaddress_province' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permamobile' => ($this->paddressdifferent == 1) ? 'required|numeric|digits:11' : 'nullable|numeric|digits:11',
			'permaphoneno' => 'nullable|present|numeric',
			'permaemail' => 'nullable|present|email',

			'occupation' => ($this->hasoccupation == 1) ? 'required' : 'nullable',
			'worksInClosedSetting' => ($this->hasoccupation == 1) ? 'required' : 'nullable',
			'occupation_lotbldg' => 'nullable',
			'occupation_street' => 'nullable',
			'occupation_brgy' => 'nullable',
			'occupation_city' => 'nullable',
			'occupation_province' => 'nullable',
			'occupation_name' => 'nullable',
			'occupation_mobile' => 'nullable|numeric|digits:11',
			'occupation_email' => 'nullable|email',
        ];
    }
}
