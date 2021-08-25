<?php

namespace App\Http\Requests;

use App\Models\Forms;
use App\Models\Records;
use Illuminate\Foundation\Http\FormRequest;

class FormValidationRequest extends FormRequest
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
        if($this->method() == "POST") {
            $rec = Records::findOrFail($this->id);
        }
        else {
            $rec = Forms::findOrFail($this->route('form'));
            $rec = Records::findOrFail($rec->records->id);
        }
        
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
            'drunit' => 'required',
            'drregion' => 'required',
            'drprovince' => 'required',
            'interviewerName' => 'required',
            'interviewerMobile' => 'required|numeric|digits:11|starts_with:09',
            'interviewDate' => 'required|date|before_or_equal:today',
            'informantName' => 'nullable',
            'informantRelationship' => 'nullable',
            'informantMobile' => 'nullable|numeric|digits:11',
            'existingCaseList' => 'required',
            'ecOthersRemarks' => (!is_null($this->existingCaseList) && in_array('11', $this->existingCaseList)) ? 'required' : 'nullable',
            'pType' => 'required',
            'isForHospitalization' => 'required|numeric',
            'testingCat' => 'required',
            
            //1.5 Special Population
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
            //2.1 Consultation Information
            'havePreviousCovidConsultation' => 'required',
            'dateOfFirstConsult' => ($this->havePreviousCovidConsultation == "1") ? 'required|date' : 'nullable|date',
            'facilityNameOfFirstConsult' => ($this->havePreviousCovidConsultation == "1") ? 'required' : 'nullable',
            //2.2 Disposition at Time of Report
            'dispositionType' => 'nullable',
            'dispositionName' => $dNameVal,
            'dispositionDate' => $dDateVal,
            //2.3 Health Status at Consult
            'healthStatus' => 'required',
            //2.4 Case Classification
            'caseClassification' => 'required',

            //2.5 Vaccination
            'howManyDoseVaccine' => 'nullable',
            'vaccineName' => (!is_null($this->howManyDoseVaccine)) ? 'required' : 'nullable',

            'vaccinationDate1' => (!is_null($this->howManyDoseVaccine)) ? 'required|date|before_or_equal:today' : 'nullable|date',
            'haveAdverseEvents1' => (!is_null($this->howManyDoseVaccine)) ? 'required|numeric' : 'nullable|numeric',
            'vaccinationFacility1' => 'nullable|string',
            'vaccinationRegion1' => 'nullable|string',
        
            'vaccinationDate2' => (!is_null($this->howManyDoseVaccine) && $this->howManyDoseVaccine == 2) ? 'required|date|before_or_equal:today' : 'nullable|date',
            'haveAdverseEvents2' => (!is_null($this->howManyDoseVaccine) && $this->howManyDoseVaccine == 2) ? 'required|numeric' : 'nullable|numeric',
            'vaccinationFacility2' => 'nullable|string',
            'vaccinationRegion2' => 'nullable|string',
            
            //2.6 Clinical Information
            'dateOnsetOfIllness' => 'nullable|date|before_or_equal:today',
            'sasCheck' => 'nullable',
            'SASFeverDeg' => (!is_null($this->sasCheck) && in_array('Fever', $this->sasCheck)) ? 'required|numeric' : 'nullable',
            'SASOtherRemarks' => (!is_null($this->sasCheck) && in_array('Others', $this->sasCheck)) ? 'required' : 'nullable',
            'comCheck' => 'required',
            'COMOOtherRemarks' => (!is_null($this->comCheck) && in_array('Others', $this->comCheck)) ? 'required' : 'nullable',
            'PregnantLMP' => ($rec->gender == "FEMALE" && $rec->isPregnant == 1) ? 'required|date' : 'nullable|date',
            'highRiskPregnancy' => ($rec->gender == "FEMALE" && $rec->isPregnant == 1) ? 'required' : 'nullable',
            'diagWithSARI' => 'required',
            'imagingDoneDate' => ($this->imagingDone != "None") ? 'required|date' : 'nullable|date',
            'imagingDone' => 'required',
            'imagingResult' => ($this->imagingDone != "None") ? 'required' : 'nullable',
            'imagingOtherFindings' => ($this->imagingDone != "None" && $this->imagingResult == "OTHERS") ? 'required' : 'nullable',

            'testedPositiveUsingRTPCRBefore' => 'required',
            'testedPositiveNumOfSwab' => 'required',
            'testedPositiveLab' => ($this->testedPositiveUsingRTPCRBefore == "1") ? 'required' : 'nullable',
            'testedPositiveSpecCollectedDate' => ($this->testedPositiveUsingRTPCRBefore == "1") ? 'required|date' : 'nullable|date',

            'testDateCollected1' => 'required|date',
            'oniTimeCollected1' => 'nullable',
            'testDateReleased1' => 'nullable|date',
            'testLaboratory1' => 'nullable',
            'testType1' => 'required',
            'testTypeOtherRemarks1' => ($this->testType1 == "OTHERS") ? 'required' : 'nullable',
            'antigenKit1' => ($this->testType1 == "ANTIGEN") ? 'required' : 'nullable',
            'testResult1' => 'required',
            'testResultOtherRemarks1' => ($this->testResult1 == "OTHERS") ? 'required' : 'nullable',

            'testDateCollected2' => 'nullable|date|after_or_equal:testDateCollected1',
            'oniTimeCollected2' => 'nullable',
            'testDateReleased2' => 'nullable|date',
            'testLaboratory2' => 'nullable',
            'testType2' => (!is_null($this->testType2)) ? 'required' : 'nullable',
            'testTypeOtherRemarks2' => ($this->testType2 == "OTHERS") ? 'required' : 'nullable',
            'antigenKit2' => ($this->testType2 == "ANTIGEN") ? 'required' : 'nullable',
            'testResult2' => 'required',
            'testResultOtherRemarks2' => ($this->testResult2 == "OTHERS") ? 'required' : 'nullable',

            'outcomeCondition' => 'required',
            'outcomeRecovDate' => ($this->outcomeCondition == "Recovered") ? 'required|date' : 'nullable|date',
            'outcomeDeathDate' => ($this->outcomeCondition == "Died") ? 'required|date' : 'nullable|date',
            'deathImmeCause' => ($this->outcomeCondition == "Died") ? 'required' : 'nullable',
            'deathAnteCause' => 'nullable',
            'deathUndeCause' => 'nullable',
            'contriCondi' => 'nullable',

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

            'remarks' => 'nullable',
		];
    }
}
