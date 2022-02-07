<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaSwabValidationRequest extends FormRequest
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
        if($this->howManyDose == 2) {
            $allow_booster_question = true;
        }
        else {
            if($this->nameOfVaccine == 'JANSSEN') {
                $allow_booster_question = true;
            }
            else {
                $allow_booster_question = false;
            }
        }

        return [
            'linkcode' => 'required|string',
            'linkcode2nd' => 'required|string',
            'pType' => 'required',
            'isForHospitalization' => 'required|numeric',
            'interviewDate' => 'required|date|before_or_equal:today',
            'forAntigen' => 'required|numeric',
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
            'bdate' => "required|date|before:tomorrow",
            'gender' => 'required',
            'isPregnant' => ($this->gender == 'FEMALE') ? 'required|numeric' : 'nullable',
            'lmp' => ($this->isPregnant == 1) ? 'required|date|before_or_equal:today' : 'nullable',
            'cs' => 'required',
            'nationality' => 'required',
            'mobile' => 'required|numeric|digits:11|starts_with:09|not_in:09190664324',
            'philhealth' => 'nullable|regex:/^[0-9]+$/',
            'phoneno' => 'nullable|numeric',
            'email' => 'nullable|email',
            'address_province' => 'required',
            'saddress_province' => 'required',
            'address_city' => 'required',
            'saddress_city' => 'required',
            'address_brgy' => 'required',
            'address_street' => 'required|different:address_brgy,address_houseno|not_in:0,00,000,0000,N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE,NEAR BRGY HALL,NEAR BARANGAY HALL|min:3|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'address_houseno' => 'required|different:address_brgy,address_street|not_in:0,00,000,0000,N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE,NEAR BRGY HALL,NEAR BARANGAY HALL|min:3|regex:/(^[a-zA-Z0-9 ]+$)+/',
            
            'haveOccupation' => 'required|numeric',
            'occupation' => ($this->haveOccupation == 1) ? 'required' : 'nullable', 
            'occupation_name' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'natureOfWork' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'natureOfWorkIfOthers' => ($this->haveOccupation == 1 && $this->natureOfWork == 'OTHERS') ? 'required' : 'nullable',
            'worksInClosedSetting' => ($this->haveOccupation == 1) ? 'required' : 'nullable',

            'occupation_province' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'soccupation_province' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'occupation_city' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'soccupation_city' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'occupation_brgy' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'occupation_lotbldg' => ($this->haveOccupation == 1) ? 'required|different:occupation_brgy,occupation_street|not_in:0,00,000,0000,N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE,NEAR BRGY HALL,NEAR BARANGAY HALL|regex:/(^[a-zA-Z0-9 ]+$)+/' : 'nullable',
            'occupation_street' => ($this->haveOccupation == 1) ? 'required|different:occupation_brgy,occupation_lotbldg|not_in:0,00,000,0000,N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE,NEAR BRGY HALL,NEAR BARANGAY HALL|regex:/(^[a-zA-Z0-9 ]+$)+/' : 'nullable',
            'occupation_mobile' => 'nullable|numeric|digits:11|starts_with:09',
			'occupation_email' => 'nullable|email',

            'vaccineq1' => 'required|numeric',
            'howManyDose' => ($this->vaccineq1 == 1) ? 'required|numeric' : 'nullable',
            'nameOfVaccine' => ($this->vaccineq1 == 1) ? 'required' : 'nullable',
            'vaccinationDate1' => ($this->vaccineq1 == 1) ? 'required|date|before_or_equal:today' : 'nullable|date',
            'vaccinationFacility1' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'vaccinationRegion1' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'haveAdverseEvents1' => ($this->vaccineq1 == 1) ? 'required|numeric' : 'nullable',
            'vaccinationDate2' => ($this->vaccineq1 == 1 && $this->howManyDose == 2) ? 'required|date|after:vaccinationDate1|before_or_equal:today' : 'nullable|date',
            'vaccinationFacility2' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'vaccinationRegion2' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'haveAdverseEvents2' => ($this->vaccineq1 == 1 && $this->howManyDose == 2) ? 'required|numeric' : 'nullable',

            'haveBooster' => ($allow_booster_question) ? 'required' : 'nullable',
            'vaccinationName3' => ($this->haveBooster == 1) ? 'required' : 'nullable',
            'vaccinationDate3' => ($this->haveBooster == 1) ? 'required|date|after:vaccinationDate2|before_or_equal:today' : 'nullable',
            'haveAdverseEvents3' => ($this->haveBooster == 1) ? 'required' : 'nullable',
            'vaccinationFacility3' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
            'vaccinationRegion3' => 'nullable|regex:/(^[a-zA-Z0-9 ]+$)+/',
            
            'haveSymptoms' => 'required|numeric',
            'dateOnsetOfIllness' => ($this->haveSymptoms == 1) ? 'required|date' : 'nullable|date',
            'sasCheck' => 'nullable',
            'SASFeverDeg' => (!is_null($this->sasCheck) && in_array('Fever', $this->sasCheck)) ? 'required|numeric' : 'nullable',
            'SASOtherRemarks' => (!is_null($this->sasCheck) && in_array('Others', $this->sasCheck)) ? 'required' : 'nullable',
            
            'comCheck' => 'required',
            'COMOOtherRemarks' => (!is_null($this->comCheck) && in_array('Others', $this->comCheck)) ? 'required' : 'nullable',

            'imagingDoneDate' => ($this->imagingDone != "None") ? 'required|date' : 'nullable|date',
            'imagingDone' => 'required',
            'imagingResult' => ($this->imagingDone != "None") ? 'required' : 'nullable',
            'imagingOtherFindings' => ($this->imagingDone != "None" && $this->imagingResult == "OTHERS") ? 'required' : 'nullable',

            'expoitem1' => 'required|numeric',
            'expoDateLastCont' => ($this->expoitem1 == "1") ? 'required|date|before_or_equal:today' : 'nullable|date|before_or_equal:today',

            'contact1Name' => 'nullable|string',
            'contact1No' => 'nullable|numeric|digits:11|starts_with:09',
            'contact2Name' => 'nullable|string',
            'contact2No' => 'nullable|numeric|digits:11|starts_with:09',
            'contact3Name' => 'nullable|string',
            'contact3No' => 'nullable|numeric|digits:11|starts_with:09',
            'contact4Name' => 'nullable|string',
            'contact4No' => 'nullable|numeric|digits:11|starts_with:09',

            'patientmsg' => 'nullable|string|max:250',
        ];
    }
}
