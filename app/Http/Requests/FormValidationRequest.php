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
            'pType' => 'required',
            'testingCat' => 'required',
            'havePreviousCovidConsultation' => 'required',
            'dateOfFirstConsult' => ($this->havePreviousCovidConsultation == "1") ? 'required|date' : 'nullable|date',
            'facilityNameOfFirstConsult' => ($this->havePreviousCovidConsultation == "1") ? 'required' : 'nullable',
            'admittedInHealthFacility' => ($this->havePreviousCovidConsultation == "1") ? 'required' : 'nullable',
            'dateOfAdmissionInHealthFacility' => ($this->havePreviousCovidConsultation == "1" && $this->admittedInHealthFacility == "1") ? 'required|date' : 'nullable|date',
            'admittedInMultipleHealthFacility' => ($this->havePreviousCovidConsultation == "1" && $this->admittedInHealthFacility == "1") ? 'required' : 'nullable',
            'facilitynameOfFirstAdmitted' => ($this->havePreviousCovidConsultation == "1" && $this->admittedInHealthFacility == "1") ? 'required' : 'nullable',
            'facilityregion' => ($this->havePreviousCovidConsultation == "1" && $this->admittedInHealthFacility == "1") ? 'required' : 'nullable',
            'facilityprovince' => ($this->havePreviousCovidConsultation == "1" && $this->admittedInHealthFacility == "1") ? 'required' : 'nullable',
            'dispositionType' => 'nullable',
            'dispositionName' => $dNameVal,
            'dispositionDate' => $dDateVal,
            'healthStatus' => 'required',
            'caseClassification' => 'required',
            'isHealthCareWorker' => 'required',
            'healthCareCompanyName' => ($this->isHealthCareWorker == "1") ? 'required' : 'nullable',
            'healthCareCompanyLocation' => ($this->isHealthCareWorker == "1") ? 'required' : 'nullable',
            'isOFW' => 'required',
            'OFWCountyOfOrigin' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'isFNT' => 'required',
            'FNTCountryOfOrigin' => ($this->isFNT == "1") ? 'required' : 'nullable',
            'isLSI' => 'required',
            'LSIProvince' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'LSICity' => ($this->isLSI == "1") ? 'required' : 'nullable',
            'isLivesOnClosedSettings' => 'required',
            'institutionType' => ($this->isLivesOnClosedSettings == "1") ? 'required' : 'nullable',
            'institutionName' => ($this->isLivesOnClosedSettings == "1") ? 'required' : 'nullable',
            'oaddresslotbldg' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'oaddressstreet' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'oaddressscity' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'oaddresssprovince' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'oaddressscountry' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'placeofwork' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'employername' => ($this->isOFW == "1") ? 'required' : 'nullable',
            'employercontactnumber' => ($this->isOFW == "1") ? 'required|numeric' : 'nullable|numeric',
            'dateOnsetOfIllness' => 'nullable|date',
            'sasCheck' => 'nullable',
            'SASFeverDeg' => (!is_null($this->sasCheck) && in_array('Fever', $this->sasCheck)) ? 'required|numeric' : 'nullable',
            'SASOtherRemarks' => (!is_null($this->sasCheck) && in_array('Others', $this->sasCheck)) ? 'required' : 'nullable',
            'comCheck' => 'required',
            'COMOOtherRemarks' => (!is_null($this->comCheck) && in_array('Others', $this->comCheck)) ? 'required' : 'nullable',
            'PregnantLMP' => ($rec->gender == "FEMALE" && $rec->isPregnant == 1) ? 'required|date' : 'nullable|date',
            'highRiskPregnancy' => ($rec->gender == "FEMALE" && $rec->isPregnant == 1) ? 'required' : 'nullable',
            'diagWithSARI' => 'required',
            'imaCheck' => 'required',
            'chestRDResult' => (in_array('Chest Radiography', $this->imaCheck)) ? 'required' : 'nullable',
            'chestRDOtherFindings' => (in_array('Chest Radiography', $this->imaCheck) && $this->chestRDResult == "4") ? 'required' : 'nullable',
            'chestCTResult' => (in_array('Chest CT', $this->imaCheck)) ? 'required' : 'nullable',
            'chestCTOtherFindings' => (in_array('Chest CT', $this->imaCheck) && $this->chestCTResult == "4") ? 'required' : 'nullable',
            'lungUSResult' => (in_array('Lung Ultrasound', $this->imaCheck)) ? 'required' : 'nullable',
            'lungUSOtherFindings' => (in_array('Lung Ultrasound', $this->imaCheck) && $this->lungUSResult == "4") ? 'required' : 'nullable',
            'labCheck' => 'required',
            'rtpcr_ops_date_collected' => (!is_null($this->labCheck) && in_array('RT-PCR (OPS)', $this->labCheck)) ? 'required|date' : 'nullable|date',
            'rtpcr_ops_laboratory' => 'nullable',
            'rtpcr_ops_results' => (!is_null($this->labCheck) && in_array('RT-PCR (OPS)', $this->labCheck)) ? 'required' : 'nullable',
            'rtpcr_ops_date_released' => 'nullable|date',
            'rtpcr_nps_date_collected' => (!is_null($this->labCheck) && in_array('RT-PCR (NPS)', $this->labCheck)) ? 'required|date' : 'nullable|date',
            'rtpcr_nps_laboratory' => 'nullable',
            'rtpcr_nps_results' => (!is_null($this->labCheck) && in_array('RT-PCR (NPS)', $this->labCheck)) ? 'required' : 'nullable',
            'rtpcr_nps_date_released' => 'nullable|date',
            'rtpcr_both_date_collected' => (!is_null($this->labCheck) && in_array('RT-PCR (OPS and NPS)', $this->labCheck)) ? 'required|date' : 'nullable',
            'rtpcr_both_laboratory' => 'nullable',
            'rtpcr_both_results' => (!is_null($this->labCheck) && in_array('RT-PCR (OPS and NPS)', $this->labCheck)) ? 'required' : 'nullable',
            'rtpcr_both_date_released' => 'nullable|date',
            'rtpcr_spec_type' => (!is_null($this->labCheck) && in_array('RT-PCR', $this->labCheck)) ? 'required' : 'nullable',
            'rtpcr_spec_date_collected' => (!is_null($this->labCheck) && in_array('RT-PCR', $this->labCheck)) ? 'required|date' : 'nullable|date',
            'rtpcr_spec_laboratory' => 'nullable',
            'rtpcr_spec_results' => (!is_null($this->labCheck) && in_array('RT-PCR', $this->labCheck)) ? 'required' : 'nullable',
            'rtpcr_spec_date_released' => 'nullable|date',
            'antigen_date_collected' => (!is_null($this->labCheck) && in_array('Antigen Test', $this->labCheck)) ? 'required|date' : 'nullable|date',
            'antigen_laboratory' => 'nullable',
            'antigen_results' => (!is_null($this->labCheck) && in_array('Antigen Test', $this->labCheck)) ? 'required' : 'nullable',
            'antigen_date_released' => 'nullable|date',
            'antibody_date_collected' => (!is_null($this->labCheck) && in_array('Antibody Test', $this->labCheck)) ? 'required|date' : 'nullable|date',
            'antibody_laboratory' => 'nullable',
            'antibody_results' => (!is_null($this->labCheck) && in_array('Antibody Test', $this->labCheck)) ? 'required' : 'nullable',
            'antibody_date_released' => 'nullable|date',
            'others_specify' => (!is_null($this->labCheck) && in_array('Others', $this->labCheck)) ? 'required' : 'nullable',
            'others_date_collected' => (!is_null($this->labCheck) && in_array('Others', $this->labCheck)) ? 'required|date' : 'nullable|date',
            'others_laboratory' => 'nullable',
            'others_results' => (!is_null($this->labCheck) && in_array('Others', $this->labCheck)) ? 'required' : 'nullable',
            'others_date_released' => 'nullable|date',
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
            'expoitem1' => 'required',
            'expoDateLastCont' => ($this->expoitem1 == "1") ? 'required|date' : 'nullable|date',
            'expoitem2' => 'required',
            'vOpt' => ($this->expoitem2 == "1" || $this->expoitem2 == "3") ? 'required' : 'nullable',
            'vOpt1_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('1', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('1', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt1_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('1', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('1', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt2_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('2', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('2', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt2_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('2', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('2', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt3_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('3', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('3', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt3_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('3', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('3', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt4_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('4', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('4', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt4_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('4', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('4', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt5_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('5', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('5', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt5_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('5', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('5', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt6_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('6', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('6', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt6_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('6', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('6', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt7_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('7', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('7', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt7_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('7', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('7', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt8_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('8', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('8', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt8_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('8', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('8', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt9_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('9', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('9', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt9_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('9', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('9', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt10_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('10', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('10', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt10_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('10', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('10', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'vOpt11_details' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('11', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('11', $this->vOpt)) ? 'required' : 'nullable',
            'vOpt11_date' => (!is_null($this->vOpt) && $this->expoitem2 == "1" && in_array('11', $this->vOpt) || !is_null($this->vOpt) && $this->expoitem2 == "3" && in_array('11', $this->vOpt)) ? 'required|date' : 'nullable|date',
            'hasTravHistOtherCountries' => 'required',
            'historyCountryOfExit' => ($this->hasTravHistOtherCountries == "1") ? 'required' : 'nullable',
            'country_historyTypeOfTranspo' => ($this->hasTravHistOtherCountries == "1") ? 'required' : 'nullable',
            'country_historyTranspoNo' => ($this->hasTravHistOtherCountries == "1") ? 'required' : 'nullable',
            'country_historyTranspoDateOfDeparture' => ($this->hasTravHistOtherCountries == "1") ? 'required|date' : 'nullable|date',
            'country_historyTranspoDateOfArrival' => ($this->hasTravHistOtherCountries == "1") ? 'required|date' : 'nullable|date',
            'hasTravHistLocal' => 'required',
            'historyPlaceOfOrigin' => ($this->hasTravHistLocal == "1") ? 'required' : 'nullable',
            'local_historyTypeOfTranspo' => ($this->hasTravHistLocal == "1") ? 'required' : 'nullable',
            'local_historyTranspoNo' => ($this->hasTravHistLocal == "1") ? 'required' : 'nullable',
            'local_historyTranspoDateOfDeparture' => ($this->hasTravHistLocal == "1") ? 'required|date' : 'nullable|date',
            'local_historyTranspoDateOfArrival' => ($this->hasTravHistLocal == "1") ? 'required|date' : 'nullable|date',
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
