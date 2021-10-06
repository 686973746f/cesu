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
			'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
			'gender' => 'required',
			'cs' => 'required',
			'nationality' => 'required',
			'bdate' => "required|date|before:tomorrow",
			'mobile' => 'required|numeric|digits:11|starts_with:09|not_in:09190664324',
			'phoneno' => 'nullable|numeric',
			'email' => 'nullable|email',
			'philhealth' => 'nullable|regex:/^([0-9-]+)$/',
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
			'permamobile' => ($this->paddressdifferent == 1) ? 'required|numeric|digits:11|starts_with:09' : 'nullable|numeric|digits:11|starts_with:09',
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
			'occupation_mobile' => (auth()->user()->isCompanyAccount()) ? 'nullable|numeric' : 'nullable|numeric|digits:11|starts_with:09',
			'occupation_email' => 'nullable|email',

			'natureOfWork' => ($this->hasoccupation == 1) ? 'required' : 'nullable',
			'natureOfWorkIfOthers' => ($this->hasoccupation == 1 && $this->natureOfWork == 'Others') ? 'required' : 'nullable',
        ];
    }
}
