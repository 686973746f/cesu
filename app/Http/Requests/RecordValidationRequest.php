<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
		if($this->howManyDoseVaccine == 2) {
            $allow_booster_question = true;
        }
        else {
            if($this->vaccineName == 'JANSSEN') {
                $allow_booster_question = true;
            }
            else {
                $allow_booster_question = false;
            }
        }

        return [
			'is_confidential' => 'nullable',
			'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
			'gender' => 'required',
			'cs' => 'required',
			'nationality' => 'required',
			'bdate' => 'required|date|before:tomorrow',
			'mobile' => 'required|numeric|digits:11|starts_with:09',
			'phoneno' => 'nullable|numeric',
			'email' => 'nullable|email',
			'philhealth' => 'nullable|regex:/^[0-9]+$/',
            'address_houseno' => [
                'required',
                'different:address_brgy',
                'different:address_street',
                'regex:/(^[a-zA-Z0-9 ]+$)+/',
                Rule::notIn('NEAR BRGY HALL', 'NEAR BARANGAY HALL', 'NONE', '000')
            ],
            'address_street' => [
                'required',
                'different:address_brgy',
                'different:address_houseno',
                'regex:/(^[a-zA-Z0-9 ]+$)+/',
                Rule::notIn('NEAR BRGY HALL', 'NEAR BARANGAY HALL', 'NONE', '000')
            ],
			'address_brgy' => 'required',
			'address_city' => 'required',
			'address_province' => 'required',
			'permaaddress_houseno' => ($this->paddressdifferent == 1) ? 'required|not_in:NEAR BRGY HALL,NEAR BARANGAY HALL|regex:/(^[a-zA-Z0-9 ]+$)+/' : 'nullable',
			'permaaddress_street' => ($this->paddressdifferent == 1) ? 'required|not_in:NEAR BRGY HALL,NEAR BARANGAY HALL|regex:/(^[a-zA-Z0-9 ]+$)+/' : 'nullable',
			'permaaddress_brgy' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permaaddress_city' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permaaddress_province' => ($this->paddressdifferent == 1) ? 'required' : 'nullable',
			'permamobile' => ($this->paddressdifferent == 1) ? 'required|numeric|digits:11|starts_with:09' : 'nullable|numeric|digits:11|starts_with:09',
			'permaphoneno' => 'nullable|present|numeric',
			'permaemail' => 'nullable|present|email',

			'occupation' => ($this->hasoccupation == 1) ? 'required' : 'nullable',
			'worksInClosedSetting' => ($this->hasoccupation == 1) ? 'required' : 'nullable',
			'occupation_lotbldg' => 'nullable|different:occupation_brgy|different:occupation_street|not_in:NEAR BRGY HALL,NEAR BARANGAY HALL|regex:/(^[a-zA-Z0-9 ]+$)+/',
			'occupation_street' => 'nullable|different:occupation_brgy|different:occupation_lotbldg|not_in:NEAR BRGY HALL,NEAR BARANGAY HALL|regex:/(^[a-zA-Z0-9 ]+$)+/',
			'occupation_brgy' => 'nullable',
			'occupation_city' => 'nullable',
			'occupation_province' => 'nullable',
			'occupation_name' => 'nullable',
			'occupation_mobile' => (auth()->user()->isCompanyAccount()) ? 'nullable|numeric' : 'nullable|numeric|digits:11|starts_with:09',
			'occupation_email' => 'nullable|email',

			'natureOfWork' => ($this->hasoccupation == 1) ? 'required' : 'nullable',
			'natureOfWorkIfOthers' => ($this->hasoccupation == 1 && $this->natureOfWork == 'Others') ? 'required' : 'nullable',

			//2.5 Vaccination
            'howManyDoseVaccine' => 'nullable|numeric',
            'vaccineName' => (!is_null($this->howManyDoseVaccine)) ? 'required' : 'nullable',

            'vaccinationDate1' => (!is_null($this->howManyDoseVaccine)) ? 'required|date|before_or_equal:today' : 'nullable|date',
            'haveAdverseEvents1' => (!is_null($this->howManyDoseVaccine)) ? 'required|numeric' : 'nullable|numeric',
            'vaccinationFacility1' => 'nullable|string|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'vaccinationRegion1' => 'nullable|string|regex:/(^[a-zA-Z0-9 ]+$)+/',
        
            'vaccinationDate2' => (!is_null($this->howManyDoseVaccine) && $this->howManyDoseVaccine == 2) ? 'required|date|after:vaccinationDate1|before_or_equal:today' : 'nullable|date',
            'haveAdverseEvents2' => (!is_null($this->howManyDoseVaccine) && $this->howManyDoseVaccine == 2) ? 'required|numeric' : 'nullable|numeric',
            'vaccinationFacility2' => 'nullable|string|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'vaccinationRegion2' => 'nullable|string|regex:/(^[a-zA-Z0-9 ]+$)+/',

			'haveBooster' => ($allow_booster_question) ? 'required' : 'nullable',
            'vaccinationName3' => ($this->haveBooster == 1) ? 'required' : 'nullable',
            'vaccinationDate3' => ($this->haveBooster == 1) ? 'required|date|after:vaccinationDate2|before_or_equal:today' : 'nullable',
            'haveAdverseEvents3' => ($this->haveBooster == 1) ? 'required' : 'nullable',
            'vaccinationFacility3' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'vaccinationRegion3' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
        ];
    }
}
