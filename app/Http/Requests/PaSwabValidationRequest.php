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
        return [
            'linkcode' => 'required|string',
            'linkcode2nd' => 'required|string',
            'pType' => 'required',
            'isForHospitalization' => 'required|numeric',
            'interviewDate' => 'required|date|before_or_equal:today',
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
            'bdate' => "required|date|before:tomorrow",
            'gender' => 'required',
            'isPregnant' => ($this->gender == 'FEMALE') ? 'required|numeric' : 'nullable',
            'lmp' => ($this->isPregnant == 1) ? 'required|date|before_or_equal:today' : 'nullable',
            'cs' => 'required',
            'nationality' => 'required',
            'mobile' => 'required|numeric|digits:11',
            'philhealth' => 'nullable|regex:/^([0-9-]+)$/',
            'phoneno' => 'nullable|numeric',
            'email' => 'nullable|email',
            'address_province' => 'required',
            'saddress_province' => 'required',
            'address_city' => 'required',
            'saddress_city' => 'required',
            'address_brgy' => 'required',
            'address_street' => 'required',
            'address_houseno' => 'required',
            
            'haveOccupation' => 'required|numeric',
            'occupation' => ($this->haveOccupation == 1) ? 'required' : 'nullable', 
            'occupation_name' => 'nullable',
            'natureOfWork' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'natureOfWorkIfOthers' => ($this->haveOccupation == 1 && $this->natureOfWork == 'OTHERS') ? 'required' : 'nullable',

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
            'expoDateLastCont' => ($this->expoitem1 == "1") ? 'required|date' : 'nullable|date',

            'contact1Name' => 'nullable|string',
            'contact1No' => 'nullable|numeric|digits:11',
            'contact2Name' => 'nullable|string',
            'contact2No' => 'nullable|numeric|digits:11',
            'contact3Name' => 'nullable|string',
            'contact3No' => 'nullable|numeric|digits:11',
            'contact4Name' => 'nullable|string',
            'contact4No' => 'nullable|numeric|digits:11',
        ];
    }
}
