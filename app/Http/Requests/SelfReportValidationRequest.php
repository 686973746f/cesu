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
        if($this->dispositionType == 1 || $this->dispositionType == 2) {
            $dNameVal = 'required';
            $dDateVal = 'required|date';
        }
        else if ($this->dispositionType == 3 || $this->dispositionType == 4){
            $dNameVal = 'nullable';
            $dDateVal = 'required|date';
        }
        else if ($this->dispositionType == 5) {
            $dNameVal = 'required';
            $dDateVal = 'nullable|date';
        }
        else {
            $dNameVal = 'nullable';
            $dDateVal = 'nullable|date';
        }
        
        return [
            'patientmsg' => 'nullable|string|max:250',
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
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
            'address_street' => 'required|not_in:n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE',
            'address_houseno' => 'required|not_in:n/a,N/A,NOT APPLICABLE,NOTAPPLICABLE',
            'haveOccupation' => 'required|numeric',
            'occupation' => ($this->haveOccupation == 1) ? 'required' : 'nullable', 
            'occupation_name' => 'nullable',
            'natureOfWork' => ($this->haveOccupation == 1) ? 'required' : 'nullable',
            'natureOfWorkIfOthers' => ($this->haveOccupation == 1 && $this->natureOfWork == 'OTHERS') ? 'required' : 'nullable',
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
            'dispositionType' => 'nullable',
            'dispositionName' => $dNameVal,
            'dispositionDate' => $dDateVal,
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
            'expoitem1' => 'required|numeric',
            'expoDateLastCont' => ($this->expoitem1 == "1") ? 'required|date|before_or_equal:today' : 'nullable|date|before_or_equal:today',
            'expoitem2' => 'required',
            'intCountry' => ($this->expoitem2 == "2") ? 'required' : 'nullable',
            'intDateFrom' => ($this->expoitem2 == "2") ? 'required|date' : 'nullable|date',
            'intDateTo' => ($this->expoitem2 == "2") ? 'required|date' : 'nullable|date',
            'intWithOngoingCovid' => ($this->expoitem2 == "2") ? 'required' : 'nullable',
            'intVessel' => ($this->expoitem2 == "2") ? 'required' : 'nullable',
            'intVesselNo' => ($this->expoitem2 == "2") ? 'required' : 'nullable',
            'intDateDepart' => ($this->expoitem2 == "2") ? 'required|date' : 'nullable',
            'intDateArrive' => ($this->expoitem2 == "2") ? 'required|date' : 'nullable',
            'placevisited' => ($this->expoitem2 == "1") ? 'required' : 'nullable',
            'locName1' => (!is_null($this->placevisited) && in_array('Health Facility', $this->placevisited)) ? 'required' : 'nullable',
            'locAddress1' => (!is_null($this->placevisited) && in_array('Health Facility', $this->placevisited)) ? 'required' : 'nullable',
            'locDateFrom1' => (!is_null($this->placevisited) && in_array('Health Facility', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'locDateTo1' => (!is_null($this->placevisited) && in_array('Health Facility', $this->placevisited)) ? 'required|date' : 'nullable',
            'locWithOngoingCovid1' => (!is_null($this->placevisited) && in_array('Health Facility', $this->placevisited)) ? 'required' : 'nullable',
            'locName2' => (!is_null($this->placevisited) && in_array('Closed Settings', $this->placevisited)) ? 'required' : 'nullable',
            'locAddress2' => (!is_null($this->placevisited) && in_array('Closed Settings', $this->placevisited)) ? 'required' : 'nullable',
            'locDateFrom2' => (!is_null($this->placevisited) && in_array('Closed Settings', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'locDateTo2' => (!is_null($this->placevisited) && in_array('Closed Settings', $this->placevisited)) ? 'required|date' : 'nullable',
            'locWithOngoingCovid2' => (!is_null($this->placevisited) && in_array('Closed Settings', $this->placevisited)) ? 'required' : 'nullable',
            'locName3' => (!is_null($this->placevisited) && in_array('School', $this->placevisited)) ? 'required' : 'nullable',
            'locAddress3' => (!is_null($this->placevisited) && in_array('School', $this->placevisited)) ? 'required' : 'nullable',
            'locDateFrom3' => (!is_null($this->placevisited) && in_array('School', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'locDateTo3' => (!is_null($this->placevisited) && in_array('School', $this->placevisited)) ? 'required|date' : 'nullable',
            'locWithOngoingCovid3' => (!is_null($this->placevisited) && in_array('School', $this->placevisited)) ? 'required' : 'nullable',
            'locName4' => (!is_null($this->placevisited) && in_array('Workplace', $this->placevisited)) ? 'required' : 'nullable',
            'locAddress4' => (!is_null($this->placevisited) && in_array('Workplace', $this->placevisited)) ? 'required' : 'nullable',
            'locDateFrom4' => (!is_null($this->placevisited) && in_array('Workplace', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'locDateTo4' => (!is_null($this->placevisited) && in_array('Workplace', $this->placevisited)) ? 'required|date' : 'nullable',
            'locWithOngoingCovid4' => (!is_null($this->placevisited) && in_array('Workplace', $this->placevisited)) ? 'required' : 'nullable',
            'locName5' => (!is_null($this->placevisited) && in_array('Market', $this->placevisited)) ? 'required' : 'nullable',
            'locAddress5' => (!is_null($this->placevisited) && in_array('Market', $this->placevisited)) ? 'required' : 'nullable',
            'locDateFrom5' => (!is_null($this->placevisited) && in_array('Market', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'locDateTo5' => (!is_null($this->placevisited) && in_array('Market', $this->placevisited)) ? 'required|date' : 'nullable',
            'locWithOngoingCovid5' => (!is_null($this->placevisited) && in_array('Market', $this->placevisited)) ? 'required' : 'nullable',
            'locName6' => (!is_null($this->placevisited) && in_array('Social Gathering', $this->placevisited)) ? 'required' : 'nullable',
            'locAddress6' => (!is_null($this->placevisited) && in_array('Social Gathering', $this->placevisited)) ? 'required' : 'nullable',
            'locDateFrom6' => (!is_null($this->placevisited) && in_array('Social Gathering', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'locDateTo6' => (!is_null($this->placevisited) && in_array('Social Gathering', $this->placevisited)) ? 'required|date' : 'nullable',
            'locWithOngoingCovid6' => (!is_null($this->placevisited) && in_array('Social Gathering', $this->placevisited)) ? 'required' : 'nullable',
            'locName7' => (!is_null($this->placevisited) && in_array('Others', $this->placevisited)) ? 'required' : 'nullable',
            'locAddress7' => (!is_null($this->placevisited) && in_array('Others', $this->placevisited)) ? 'required' : 'nullable',
            'locDateFrom7' => (!is_null($this->placevisited) && in_array('Others', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'locDateTo7' => (!is_null($this->placevisited) && in_array('Others', $this->placevisited)) ? 'required|date' : 'nullable',
            'locWithOngoingCovid7' => (!is_null($this->placevisited) && in_array('Others', $this->placevisited)) ? 'required' : 'nullable',
            'localVessel1' => (!is_null($this->placevisited) && in_array('Transport Service', $this->placevisited)) ? 'required' : 'nullable',
            'localVesselNo1' => (!is_null($this->placevisited) && in_array('Transport Service', $this->placevisited)) ? 'required' : 'nullable',
            'localOrigin1' => (!is_null($this->placevisited) && in_array('Transport Service', $this->placevisited)) ? 'required' : 'nullable',
            'localDateDepart1' => (!is_null($this->placevisited) && in_array('Transport Service', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'localDest1' => (!is_null($this->placevisited) && in_array('Transport Service', $this->placevisited)) ? 'required' : 'nullable',
            'localDateArrive1' => (!is_null($this->placevisited) && in_array('Transport Service', $this->placevisited)) ? 'required|date' : 'nullable|date',
            'localVessel2' => 'nullable',
            'localVesselNo2' => 'nullable',
            'localOrigin2' => 'nullable',
            'localDateDepart2' => 'nullable|date',
            'localDest2' => 'nullable',
            'localDateArrive2' => 'nullable|date',
            'contact1Name' => 'nullable|string',
            'contact1No' => 'nullable|numeric|digits:11|starts_with:09',
            'contact2Name' => 'nullable|string',
            'contact2No' => 'nullable|numeric|digits:11|starts_with:09',
            'contact3Name' => 'nullable|string',
            'contact3No' => 'nullable|numeric|digits:11|starts_with:09',
            'contact4Name' => 'nullable|string',
            'contact4No' => 'nullable|numeric|digits:11|starts_with:09',
            'req_file' => 'nullable',
            'result_file' => 'nullable',
        ];
    }
}
