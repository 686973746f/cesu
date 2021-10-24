<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelfReportValidationRequest extends FormRequest
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
            'patientmsg' => 'nullable|string|max:250',
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
            'gender' => 'required',
            'bdate' => "required|date|before:tomorrow",
            'cs' => 'required',
            'nationality' => 'required',
            'mobile' => 'required|numeric|digits:11|starts_with:09',
            'phoneno' => 'nullable|numeric',
            'email' => 'nullable|email',
            'philhealth' => 'nullable|regex:/^([0-9-]+)$/',
            'isPregnant' => ($this->gender == 'FEMALE') ? 'required|numeric' : 'nullable',
            'lmp' => ($this->isPregnant == 1) ? 'required|date|before_or_equal:today' : 'nullable',
            'address_province' => 'required',
            'saddress_province' => 'required',
            'address_city' => 'required',
            'saddress_city' => 'required',
            'address_brgy' => 'required',
            'address_street' => 'required|not_in:N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE',
            'address_houseno' => 'required|not_in:N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE',
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
            'occupation_lotbldg' => ($this->haveOccupation == 1) ? 'required|not_in:N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE' : 'nullable',
            'occupation_street' => ($this->haveOccupation == 1) ? 'required|not_in:N,NA,NONE,n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE' : 'nullable',
            'occupation_mobile' => 'nullable|numeric|digits:11|starts_with:09',
			'occupation_email' => 'nullable|email',
            
            'pType' => 'required',
            'isHealthCareWorker' => 'required',
            'healthCareCompanyName' => ($this->isHealthCareWorker == "1") ? 'required' : 'nullable',
            'healthCareCompanyLocation' => ($this->isHealthCareWorker == "1") ? 'required' : 'nullable',
            'isOFW' => 'required',
            'OFWCountyOfOrigin' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'OFWPassportNo' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'ofwType' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'isFNT' => 'required',
            'FNTCountryOfOrigin' => ($this->isFNT == "1") ? 'required' : 'nullable',
            'FNTPassportNo' => ($this->isFNT == "1") ? 'required' : 'nullable',
            'isLSI' => 'required',
            'LSIProvince' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'LSICity' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'lsiType' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'isLivesOnClosedSettings' => 'required',
            'institutionType' => ($this->isLivesOnClosedSettings == "1") ? 'required' : 'nullable',
            'institutionName' => ($this->isLivesOnClosedSettings == "1") ? 'required' : 'nullable',
            'havePreviousCovidConsultation' => 'required',
            'dateOfFirstConsult' => ($this->havePreviousCovidConsultation == "1") ? 'required|date' : 'nullable|date',
            'facilityNameOfFirstConsult' => ($this->havePreviousCovidConsultation == "1") ? 'required' : 'nullable',
            'testedPositiveUsingRTPCRBefore' => 'required',
            'testedPositiveNumOfSwab' => 'required',
            'testedPositiveLab' => ($this->testedPositiveUsingRTPCRBefore == "1") ? 'required' : 'nullable',
            'testedPositiveSpecCollectedDate' => ($this->testedPositiveUsingRTPCRBefore == "1") ? 'required|date' : 'nullable|date',
            'testDateCollected1' => 'required|date',
            'testDateReleased1' => 'nullable|date',
            'testLaboratory1' => 'nullable',
            'testType1' => 'required',
            'testTypeOtherRemarks1' => ($this->testType1 == "OTHERS") ? 'required' : 'nullable',
            'antigenKit1' => ($this->testType1 == "ANTIGEN") ? 'required' : 'nullable',
            'vaccineq1' => 'required|numeric',
            'howManyDose' => ($this->vaccineq1 == 1) ? 'required|numeric' : 'nullable',
            'nameOfVaccine' => ($this->vaccineq1 == 1) ? 'required' : 'nullable',
            'vaccineq1' => 'required|numeric',
            'howManyDose' => ($this->vaccineq1 == 1) ? 'required|numeric' : 'nullable',
            'nameOfVaccine' => ($this->vaccineq1 == 1) ? 'required' : 'nullable',
            'vaccinationDate1' => ($this->vaccineq1 == 1) ? 'required|date' : 'nullable|date',
            'vaccinationFacility1' => 'nullable',
            'vaccinationRegion1' => 'nullable',
            'haveAdverseEvents1' => ($this->vaccineq1 == 1) ? 'required|numeric' : 'nullable',
            'vaccinationDate2' => ($this->vaccineq1 == 1 && $this->howManyDose == 2) ? 'required|date' : 'nullable|date',
            'vaccinationFacility2' => 'nullable',
            'vaccinationRegion2' => 'nullable',
            'haveAdverseEvents2' => ($this->vaccineq1 == 1 && $this->howManyDose == 2) ? 'required|numeric' : 'nullable',
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
            'result_file' => 'required|mimes:jpg,png,jpeg,pdf|max:5048',
        ];
    }
}
