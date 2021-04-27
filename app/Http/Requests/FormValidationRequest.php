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
            $rec = Records::findOrFail($this->records_id);

            $ridval = 'required';
        }
        else {
            $rec = Forms::findOrFail($this->route('form'));
            $rec = Records::findOrFail($rec->records->id);

            $ridval = '';
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
            'records_id' => $ridval,
            'drunit' => 'required',
            'drregion' => 'required',
            'interviewerName' => 'required',
            'interviewerMobile' => 'required|numeric|digits:11',
            'interviewDate' => 'required|date',
            'informantName' => 'nullable',
            'informantRelationship' => 'nullable',
            'informantMobile' => 'nullable|numeric|digits:11',
            'existingCaseList' => 'required',
            'ecOthersRemarks' => (!is_null($this->existingCaseList) && in_array('11', $this->existingCaseList)) ? 'required' : 'nullable',
            'pType' => 'required',
            'testingCat' => 'required',
            
            //1.5 Special Population
            'isHealthCareWorker' => 'required',
            'healthCareCompanyName' => ($this->isHealthCareWorker == "1") ? 'required' : 'nullable',
            'healthCareCompanyLocation' => ($this->isHealthCareWorker == "1") ? 'required' : 'nullable',
            'isOFW' => 'required',
            'OFWCountyOfOrigin' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'ofwType' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'isFNT' => 'required',
            'FNTCountryOfOrigin' => ($this->isFNT == "1") ? 'required' : 'nullable',
            'isLSI' => 'required',
            'LSIProvince' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'LSICity' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'lsiType' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'isLivesOnClosedSettings' => 'required',
            'institutionType' => ($this->isLivesOnClosedSettings == "1") ? 'required' : 'nullable',
            'institutionName' => ($this->isLivesOnClosedSettings == "1") ? 'required' : 'nullable',
            'isIndg' => 'required',
            'indgSpecify' => ($this->isIndg == "1") ? 'required' : 'nullable',
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
            //2.5 Clinical Information
            'dateOnsetOfIllness' => 'nullable|date',
            'sasCheck' => 'nullable',
            'SASFeverDeg' => (!is_null($this->sasCheck) && in_array('Fever', $this->sasCheck)) ? 'required|numeric' : 'nullable',
            'SASOtherRemarks' => (!is_null($this->sasCheck) && in_array('Others', $this->sasCheck)) ? 'required' : 'nullable',
            'comCheck' => 'required',
            'COMOOtherRemarks' => (!is_null($this->comCheck) && in_array('Others', $this->comCheck)) ? 'required' : 'nullable',
            'PregnantLMP' => ($rec->gender == "FEMALE" && $rec->isPregnant == 1) ? 'required|date' : 'nullable|date',
            'highRiskPregnancy' => ($rec->gender == "FEMALE" && $rec->isPregnant == 1) ? 'required' : 'nullable',
            'diagWithSARI' => 'required',
            'imagingDoneDate' => ($this->imagingDone != "None") ? 'required|date' : 'nullable',
            'imagingDone' => 'required',
            'imagingResult' => ($this->imagingDone != "None") ? 'required' : 'nullable',
            'imagingOtherFindings' => (!is_null($this->imagingResult) && $this->imagingResult != "OTHERS") ? 'required' : 'nullable',

            'testedPositiveUsingRTPCRBefore' => 'required',
            'testedPositiveNumOfSwab' => 'required',
            'testedPositiveLab' => ($this->testedPositiveUsingRTPCRBefore == "1") ? 'required' : 'nullable',
            'testedPositiveSpecCollectedDate' => ($this->testedPositiveUsingRTPCRBefore == "1") ? 'required|date' : 'nullable|date',

            'outcomeCondition' => 'nullable',
            'outcomeRecovDate' => ($this->outcomeCondition == "Recovered") ? 'required|date' : 'nullable|date',
            'outcomeDeathDate' => ($this->outcomeCondition == "Died") ? 'required|date' : 'nullable|date',
            'deathImmeCause' => ($this->outcomeCondition == "Died") ? 'required' : 'nullable',
            'deathAnteCause' => 'nullable',
            'deathUndeCause' => 'nullable',
            'contriCondi' => 'nullable',

            'expoitem1' => 'required',
            'expoDateLastCont' => ($this->expoitem1 == "1") ? 'required|date' : 'nullable|date',

            'expoitem2' => 'required',
            
            'contact1Name' => 'nullable',
            'contact1No' => 'nullable|numeric|digits:11',
            'contact2Name' => 'nullable',
            'contact2No' => 'nullable|numeric|digits:11',
            'contact3Name' => 'nullable',
            'contact3No' => 'nullable|numeric|digits:11',
            'contact4Name' => 'nullable',
            'contact4No' => 'nullable|numeric|digits:11',
            'addContName.*' => 'nullable',
            'addContNo.*' => 'nullable|numeric|digits:11',
            'addContExpSet.*' => 'nullable',
		];
    }
}
