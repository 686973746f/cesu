<?php

namespace App\Exports;

use App\Models\Forms;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class FormsExport implements FromCollection, WithMapping, WithHeadings
{

    //use Exportable;
    
    public function __construct(array $id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if(in_array(0, $this->id)) {
            return Forms::all();
        }
        else {
            return Forms::findMany($this->id);
        }
    }

    public function map($form): array {
        $arr_existingCase = explode(",", $form->existingCaseList);
        $arr_testingcat = explode(",", $form->testingCat);
        $arr_sas = explode(",", $form->SAS);
        $arr_como = explode(",", $form->COMO);
        $arr_placeVisited = explode(",", $form->placevisited);

        if($form->expoitem2 == 0) {
            $ei2str = "NO";
        }
        else if($form->expoitem2 == 1) {
            $ei2str = "YES, LOCAL";
        }
        else if ($form->expoitem2 == 2) {
            $ei2str = "YES, LOCAL";
        }
        else if ($form->expoitem2 == 3) {
            $ei2str = "UNKNOWN";
        }

        if(is_null($form->testType2)) {
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected1));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
            $displayFirstLaboratory = strtoupper($form->testLaboratory1);
            $displayFirstTestType = $form->testType1;
            $displayFirstTestTypeOtherRemarks = ($form->testType1 == "OTHERS" || $form->testType1 == "ANTIGEN") ? $form->testTypeOtherRemarks1 : "N/A";
            $displayFirstTestResult = $form->testResult1;
            $displayFirstTestResultOtherRemarks = ($form->testResult1 == "OTHERS") ? $form->testResultOtherRemarks1 : "N/A";

            $displaySecondTestDateCollected = "N/A";
            $displaySecondTestDateRelease = "N/A";
            $displaySecondLaboratory = "N/A";
            $displaySecondTestType = "N/A";
            $displaySecondTestTypeOtherRemarks = "N/A";
            $displaySecondTestResult = "N/A";
            $displaySecondTestResultOtherRemarks = "N/A";
        }
        else {
            //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected2));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
            $displayFirstLaboratory = strtoupper($form->testLaboratory2);
            $displayFirstTestType = $form->testType2;
            $displayFirstTestTypeOtherRemarks = ($form->testType2 == "OTHERS" || $form->testType2 == "ANTIGEN") ? $form->testTypeOtherRemarks2 : "N/A";
            $displayFirstTestResult = $form->testResult2;
            $displayFirstTestResultOtherRemarks = ($form->testResult2 == "OTHERS") ? $form->testResultOtherRemarks2 : "N/A";

            $displaySecondTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected1));
            $displaySecondTestDateRelease = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
            $displaySecondLaboratory = strtoupper($form->testLaboratory1);
            $displaySecondTestType = $form->testType1;
            $displaySecondTestTypeOtherRemarks = ($form->testType1 == "OTHERS" || $form->testType1 == "ANTIGEN") ? $form->testTypeOtherRemarks1 : "N/A";
            $displaySecondTestResult = $form->testResult1;
            $displaySecondTestResultOtherRemarks = ($form->testResult1 == "OTHERS") ? $form->testResultOtherRemarks1 : "N/A";
        }

        return [
            $form->drunit,
            $form->drregion,
            (!is_null($form->records->philhealth)) ? $form->records->philhealth : "N/A",
            strtoupper($form->interviewerName),
            $form->interviewerMobile,
            date('m/d/Y', strtotime($form->interviewDate)),
            (!is_null($form->informantName)) ? strtoupper($form->informantName) : 'N/A',
            (!is_null($form->informantRelationship)) ? strtoupper($form->informantRelationship) : 'N/A',
            (!is_null($form->informantMobile)) ? $form->informantMobile : 'N/A',

            (in_array("1", $arr_existingCase)) ? "YES" : "NO",
            (in_array("2", $arr_existingCase)) ? "YES" : "NO",
            (in_array("3", $arr_existingCase)) ? "YES" : "NO",
            (in_array("4", $arr_existingCase)) ? "YES" : "NO",
            (in_array("5", $arr_existingCase)) ? "YES" : "NO",
            (in_array("6", $arr_existingCase)) ? "YES" : "NO",
            (in_array("7", $arr_existingCase)) ? "YES" : "NO",
            (in_array("8", $arr_existingCase)) ? "YES" : "NO",
            (in_array("9", $arr_existingCase)) ? "YES" : "NO",
            (in_array("10", $arr_existingCase)) ? "YES" : "NO",
            (in_array("11", $arr_existingCase)) ? "YES" : "NO",
            (in_array("11", $arr_existingCase)) ? $form->ecOthersRemarks : "N/A",
            
            $form->pType,
            (in_array("A", $arr_testingcat)) ? "YES" : "NO",
            (in_array("B", $arr_testingcat)) ? "YES" : "NO",
            (in_array("C", $arr_testingcat)) ? "YES" : "NO",
            (in_array("D", $arr_testingcat)) ? "YES" : "NO",
            (in_array("E", $arr_testingcat)) ? "YES" : "NO",
            (in_array("F", $arr_testingcat)) ? "YES" : "NO",
            (in_array("G", $arr_testingcat)) ? "YES" : "NO",
            (in_array("H", $arr_testingcat)) ? "YES" : "NO",
            (in_array("I", $arr_testingcat)) ? "YES" : "NO",
            (in_array("J", $arr_testingcat)) ? "YES" : "NO",

            $form->records->lname,
            $form->records->fname,
            $form->records->mname,
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            $form->records->gender,
            $form->records->cs,
            $form->records->nationality,
            (!is_null($form->records->occupation)) ? $form->records->occupation : 'N/A',
            $form->records->worksInClosedSetting,

            //current address
            $form->records->address_houseno,
            $form->records->address_street,
            $form->records->address_brgy,
            $form->records->address_city,
            $form->records->address_province,
            (!is_null($form->records->phoneno)) ? $form->records->phoneno : 'N/A',
            $form->records->mobile,
            (!is_null($form->records->email)) ? $form->records->email : 'N/A',

            //perma address
            $form->records->permaaddress_houseno,
            $form->records->permaaddress_street,
            $form->records->permaaddress_brgy,
            $form->records->permaaddress_city,
            $form->records->permaaddress_province,
            (!is_null($form->records->permaphoneno)) ? $form->records->permaphoneno : 'N/A',
            $form->records->permamobile,
            (!is_null($form->records->permaemail)) ? $form->records->permaemail : 'N/A',

            (!is_null($form->records->occupation_lotbldg)) ? $form->records->occupation_lotbldg : 'N/A',
            (!is_null($form->records->occupation_street)) ? $form->records->occupation_street : 'N/A',
            (!is_null($form->records->occupation_brgy)) ? $form->records->occupation_brgy : 'N/A',
            (!is_null($form->records->occupation_city)) ? $form->records->occupation_city : 'N/A',
            (!is_null($form->records->occupation_province)) ? $form->records->occupation_province : 'N/A',
            (!is_null($form->records->occupation_name)) ? $form->records->occupation_name : 'N/A',
            (!is_null($form->records->occupation_mobile)) ? $form->records->occupation_mobile : 'N/A',
            (!is_null($form->records->occupation_email)) ? $form->records->occupation_email : 'N/A',

            ($form->isHealthCareWorker == 1) ? 'YES' : 'NO',
            ($form->isHealthCareWorker == 1) ? strtoupper($form->healthCareCompanyName)." - ".strtoupper($form->healthCareCompanyLocation) : 'N/A',
            
            ($form->isOFW == 1) ? 'YES' : 'NO',
            ($form->isOFW == 1) ? strtoupper($form->OFWCountyOfOrigin) : 'N/A',
            ($form->isOFW == 1 && $form->ofwType == 1) ? "YES" : "NO",
            ($form->isOFW == 1 && $form->ofwType == 2) ? "YES" : "NO",

            ($form->isFNT == 1) ? 'YES' : 'NO',
            ($form->isFNT == 1) ? strtoupper($form->FNTCountryOfOrigin) : 'N/A',

            ($form->isLSI == 1) ? 'YES' : 'NO',
            ($form->isLSI == 1) ? strtoupper($form->LSICity).", ".strtoupper($form->LSIProvince) : 'N/A',
            ($form->isLSI == 1 && $form->lsiType == 1) ? 'YES' : 'NO',
            ($form->isLSI == 1 && $form->lsiType == 0) ? 'YES' : 'NO',

            ($form->isLivesOnClosedSettings == 1) ? 'YES' : 'NO',
            ($form->isLivesOnClosedSettings == 1) ? strtoupper($form->institutionType) : 'N/A',
            ($form->isLivesOnClosedSettings == 1) ? strtoupper($form->institutionName) : 'N/A',

            ($form->isIndg == 1) ? "YES" : "NO",
            ($form->isIndg == 1) ? strtoupper($form->indgSpecify) : "N/A",

            ($form->havePreviousCovidConsultation == 1) ? 'YES' : 'NO',
            (!is_null($form->dateOfFirstConsult)) ? date("m/d/Y", strtotime($form->dateOfFirstConsult)) : 'N/A',
            (!is_null($form->facilityNameOfFirstConsult)) ? strtoupper($form->facilityNameOfFirstConsult) : 'N/A',
            
            ($form->dispoType == 1) ? 'YES' : 'NO',
            ($form->dispoType == 1) ? strtoupper($form->dispoName) : 'N/A',
            ($form->dispoType == 1) ? date("m/d/Y H:i", strtotime($form->dispoDate)) : 'N/A',

            ($form->dispoType == 2) ? 'YES' : 'NO',
            ($form->dispoType == 2) ? strtoupper($form->dispoName) : 'N/A',
            ($form->dispoType == 2) ? date("m/d/Y H:i", strtotime($form->dispoDate)) : 'N/A',

            ($form->dispoType == 3) ? 'YES' : 'NO',
            ($form->dispoType == 3) ? date("m/d/Y H:i", strtotime($form->dispoDate)) : 'N/A',

            ($form->dispoType == 4) ? 'YES' : 'NO',
            ($form->dispoType == 4) ? date("m/d/Y", strtotime($form->dispoDate)) : 'N/A',

            ($form->dispoType == 5) ? 'YES' : 'NO',
            ($form->dispoType == 5) ? strtoupper($form->dispoName) : 'N/A',

            ($form->healthStatus == "Asymptomatic") ? 'YES' : 'NO',
            ($form->healthStatus == "Mild") ? 'YES' : 'NO',
            ($form->healthStatus == "Moderate") ? 'YES' : 'NO',
            ($form->healthStatus == "Severe") ? 'YES' : 'NO',
            ($form->healthStatus == "Critical") ? 'YES' : 'NO',

            ($form->caseClassification == "Suspect") ? 'YES' : 'NO',
            ($form->caseClassification == "Probable") ? 'YES' : 'NO',
            ($form->caseClassification == "Confirmed") ? 'YES' : 'NO',
            ($form->caseClassification == "Non-COVID-19 Case") ? 'YES' : 'NO',

            (!is_null($form->dateOnsetOfIllness)) ? date("m/d/Y", strtotime($form->dateOnsetOfIllness)) : 'N/A',
            (in_array("Asymptomatic", $arr_sas)) ? "YES" : "NO",
            (in_array("Fever", $arr_sas)) ? "YES" : "NO",
            (in_array("Fever", $arr_sas)) ? $form->SASFeverDeg : "",
            (in_array("Cough", $arr_sas)) ? "YES" : "NO",
            (in_array("General Weakness", $arr_sas)) ? "YES" : "NO",
            (in_array("Fatigue", $arr_sas)) ? "YES" : "NO",
            (in_array("Headache", $arr_sas)) ? "YES" : "NO",
            (in_array("Myalgia", $arr_sas)) ? "YES" : "NO",
            (in_array("Sore throat", $arr_sas)) ? "YES" : "NO",
            (in_array("Coryza", $arr_sas)) ? "YES" : "NO",
            (in_array("Dyspnea", $arr_sas)) ? "YES" : "NO",
            (in_array("Anorexia", $arr_sas)) ? "YES" : "NO",
            (in_array("Nausea", $arr_sas)) ? "YES" : "NO",
            (in_array("Vomiting", $arr_sas)) ? "YES" : "NO",
            (in_array("Diarrhea", $arr_sas)) ? "YES" : "NO",
            (in_array("Altered Mental Status", $arr_sas)) ? "YES" : "NO",
            (in_array("Anosmia (Loss of Smell)", $arr_sas)) ? "YES" : "NO",
            (in_array("Ageusia (Loss of Taste)", $arr_sas)) ? "YES" : "NO",
            (in_array("Others", $arr_sas)) ? "YES" : "NO",
            (in_array("Others", $arr_sas)) ? strtoupper($form->SASOtherRemarks) : "N/A",

            (in_array("None", $arr_como)) ? "YES" : "NO",
            (in_array("Hypertension", $arr_como)) ? "YES" : "NO",
            (in_array("Diabetes", $arr_como)) ? "YES" : "NO",
            (in_array("Heart Disease", $arr_como)) ? "YES" : "NO",
            (in_array("Lung Disease", $arr_como)) ? "YES" : "NO",
            (in_array("Gastrointestinal", $arr_como)) ? "YES" : "NO",
            (in_array("Genito-urinary", $arr_como)) ? "YES" : "NO",
            (in_array("Neurological Disease", $arr_como)) ? "YES" : "NO",
            (in_array("Cancer", $arr_como)) ? "YES" : "NO",
            (in_array("Others", $arr_como)) ? "YES" : "NO",
            (in_array("Others", $arr_como)) ? strtoupper($form->COMOOtherRemarks) : "N/A",
            ($form->records->isPregnant == 1) ? "YES" : "NO",
            ($form->records->isPregnant == 1) ? date('m/d/Y', strtotime($form->PregnantLMP)) : "N/A",
            ($form->PregnantHighRisk == 1) ? "YES" : "NO",

            ($form->diagWithSARI == 1) ? "YES" : "NO",
            ($form->imagingDone != "None") ? date('m/d/Y', strtotime($form->imagingDoneDate)) : "N/A",
            $form->imagingDone,
            $form->imagingResult,
            ($form->imagingResult == "OTHERS") ? strtoupper($form->imagingOtherFindings) : "N/A",

            ($form->testedPositiveUsingRTPCRBefore == 1) ? "YES" : "NO",
            ($form->testedPositiveUsingRTPCRBefore == 1) ? date("m/d/Y", strtotime($form->testedPositiveSpecCollectedDate)) : "N/A",
            ($form->testedPositiveUsingRTPCRBefore == 1) ? strtoupper($form->testedPositiveLab) : "N/A",
            strval($form->testedPositiveNumOfSwab),

            $displayFirstTestDateCollected,
            (!is_null($form->oniTimeCollected1)) ? date('g:i A', strtotime($form->oniTimeCollected1)) : '',
            $displayFirstTestDateRelease,
            $displayFirstLaboratory,
            $displayFirstTestType,
            $displayFirstTestTypeOtherRemarks,
            $displayFirstTestResult,
            $displayFirstTestResultOtherRemarks,

            $displaySecondTestDateCollected,
            $displaySecondTestDateRelease,
            $displaySecondLaboratory,
            $displaySecondTestType,
            $displaySecondTestTypeOtherRemarks,
            $displaySecondTestResult,
            $displaySecondTestResultOtherRemarks,

            ($form->outcomeCondition == "Active") ? "YES" : "NO",
            ($form->outcomeCondition == "Recovered") ? "YES" : "NO",
            ($form->outcomeCondition == "Recovered") ? date("m/d/Y", strtotime($form->outcomeRecovDate)) : "N/A",
            ($form->outcomeCondition == "Died") ? "YES" : "NO",
            ($form->outcomeCondition == "Died") ? date("m/d/Y", strtotime($form->outcomeDeathDate)) : "N/A",
            ($form->outcomeCondition == "Died") ? strtoupper($form->deathImmeCause) : "N/A",
            ($form->outcomeCondition == "Died") ? strtoupper($form->deathAnteCause) : "N/A",
            ($form->outcomeCondition == "Died") ? strtoupper($form->deathUndeCause) : "N/A",
            ($form->outcomeCondition == "Died") ? strtoupper($form->contriCondi) : "N/A",

            ($form->expoitem1 == 1) ? "YES" : "NO",
            ($form->expoitem1 == 1) ? date("m/d/Y", strtotime($form->expoDateLastCont)) : "N/A",

            $ei2str,
            ($form->expoitem2 == 2) ? strtoupper($form->intCountry) : 'N/A',
            ($form->expoitem2 == 2) ? date('m/d/Y', strtotime($form->intDateFrom)) : 'N/A',
            ($form->expoitem2 == 2) ? date('m/d/Y', strtotime($form->intDateTo)) : 'N/A',
            ($form->expoitem2 == 2 && $form->intWithOngoingCovid != "N/A") ? $form->intWithOngoingCovid : 'N/A',
            ($form->expoitem2 == 2) ? $form->intVessel : 'N/A',
            ($form->expoitem2 == 2) ? $form->intVesselNo : 'N/A',
            ($form->expoitem2 == 2) ? $form->intDateDepart : 'N/A',
            ($form->expoitem2 == 2) ? $form->intDateArrive : 'N/A',

            ($form->expoitem2 == 1 && in_array("Health Facility", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("Health Facility", $arr_placeVisited)) ? strtoupper($form->locName1) : "N/A",
            ($form->expoitem2 == 1 && in_array("Health Facility", $arr_placeVisited)) ? strtoupper($form->locAddress1) : "N/A",
            ($form->expoitem2 == 1 && in_array("Health Facility", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateFrom1)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Health Facility", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateTo1)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Health Facility", $arr_placeVisited) && $form->locWithOngoingCovid1 != "N/A") ? $form->locWithOngoingCovid1 : "N/A",

            ($form->expoitem2 == 1 && in_array("Closed Settings", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("Closed Settings", $arr_placeVisited)) ? strtoupper($form->locName2) : "N/A",
            ($form->expoitem2 == 1 && in_array("Closed Settings", $arr_placeVisited)) ? strtoupper($form->locAddress2) : "N/A",
            ($form->expoitem2 == 1 && in_array("Closed Settings", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateFrom2)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Closed Settings", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateTo2)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Closed Settings", $arr_placeVisited) && $form->locWithOngoingCovid2 != "N/A") ? $form->locWithOngoingCovid2 : "N/A",

            ($form->expoitem2 == 1 && in_array("School", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("School", $arr_placeVisited)) ? strtoupper($form->locName3) : "N/A",
            ($form->expoitem2 == 1 && in_array("School", $arr_placeVisited)) ? strtoupper($form->locAddress3) : "N/A",
            ($form->expoitem2 == 1 && in_array("School", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateFrom3)) : "N/A",
            ($form->expoitem2 == 1 && in_array("School", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateTo3)) : "N/A",
            ($form->expoitem2 == 1 && in_array("School", $arr_placeVisited) && $form->locWithOngoingCovid3 != "N/A") ? $form->locWithOngoingCovid3 : "N/A",

            ($form->expoitem2 == 1 && in_array("Workplace", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("Workplace", $arr_placeVisited)) ? strtoupper($form->locName4) : "N/A",
            ($form->expoitem2 == 1 && in_array("Workplace", $arr_placeVisited)) ? strtoupper($form->locAddress4) : "N/A",
            ($form->expoitem2 == 1 && in_array("Workplace", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateFrom4)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Workplace", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateTo4)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Workplace", $arr_placeVisited) && $form->locWithOngoingCovid4 != "N/A") ? $form->locWithOngoingCovid4 : "N/A",

            ($form->expoitem2 == 1 && in_array("Market", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("Market", $arr_placeVisited)) ? strtoupper($form->locName5) : "N/A",
            ($form->expoitem2 == 1 && in_array("Market", $arr_placeVisited)) ? strtoupper($form->locAddress5) : "N/A",
            ($form->expoitem2 == 1 && in_array("Market", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateFrom5)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Market", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateTo5)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Market", $arr_placeVisited) && $form->locWithOngoingCovid5 != "N/A") ? $form->locWithOngoingCovid5 : "N/A",

            ($form->expoitem2 == 1 && in_array("Social Gathering", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("Social Gathering", $arr_placeVisited)) ? strtoupper($form->locName6) : "N/A",
            ($form->expoitem2 == 1 && in_array("Social Gathering", $arr_placeVisited)) ? strtoupper($form->locAddress6) : "N/A",
            ($form->expoitem2 == 1 && in_array("Social Gathering", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateFrom6)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Social Gathering", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateTo6)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Social Gathering", $arr_placeVisited) && $form->locWithOngoingCovid6 != "N/A") ? $form->locWithOngoingCovid6 : "N/A",

            ($form->expoitem2 == 1 && in_array("Others", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("Others", $arr_placeVisited)) ? strtoupper($form->locName7) : "N/A",
            ($form->expoitem2 == 1 && in_array("Others", $arr_placeVisited)) ? strtoupper($form->locAddress7) : "N/A",
            ($form->expoitem2 == 1 && in_array("Others", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateFrom7)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Others", $arr_placeVisited)) ? date('m/d/Y', strtotime($form->locDateTo7)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Others", $arr_placeVisited) && $form->locWithOngoingCovid7 != "N/A") ? $form->locWithOngoingCovid7 : "N/A",

            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited)) ? "YES" : "NO",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localVessel1)) ? strtoupper($form->localVessel1) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localVesselNo1)) ? strtoupper($form->localVesselNo1) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localOrigin1)) ? strtoupper($form->localOrigin1) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localDateDepart1)) ? date('m/d/Y', strtotime($form->localDateDepart1)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localDest1)) ? strtoupper($form->localDest1) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localDateArrive1)) ? date('m/d/Y', strtotime($form->localDateArrive1)) : "N/A",

            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localVessel2)) ? strtoupper($form->localVessel2) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localVesselNo2)) ? strtoupper($form->localVesselNo2) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localOrigin2)) ? strtoupper($form->localOrigin2) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localDateDepart2)) ? date('m/d/Y', strtotime($form->localDateDepart2)) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localDest2)) ? strtoupper($form->localDest2) : "N/A",
            ($form->expoitem2 == 1 && in_array("Transport Service", $arr_placeVisited) && !is_null($form->localDateArrive2)) ? date('m/d/Y', strtotime($form->localDateArrive2)) : "N/A",

            (!is_null($form->contact1Name)) ? mb_strtoupper($form->contact1Name) : "N/A",
            (!is_null($form->contact1No)) ? $form->contact1No : "N/A",
            (!is_null($form->contact2Name)) ? mb_strtoupper($form->contact2Name) : "N/A",
            (!is_null($form->contact2No)) ? $form->contact2No : "N/A",
            (!is_null($form->contact3Name)) ? mb_strtoupper($form->contact3Name) : "N/A",
            (!is_null($form->contact3No)) ? $form->contact3No : "N/A",
            (!is_null($form->contact4Name)) ? mb_strtoupper($form->contact4Name) : "N/A",
            (!is_null($form->contact4No)) ? $form->contact4No : "N/A",

            (!is_null($form->isPresentOnSwabDay) && $form->isPresentOnSwabDay == 1) ? 'YES' : 'NO',
            ($form->isForHospitalization == 1) ? 'YES' : 'NO',
        ];
    }

    public function headings(): array
    {
        return [
            'Disease Reporting Unit',
            'DRU Region and Province',
            'Philhealth No. *',
            'Name of Interviewer',
            'Contact Number of Interviewer',
            'Date of Interview [MM/DD/YYYY]',
            'Name of Informant (If patient unavailable)',
            'Relationship',
            'Contact Number of Informant',
            'Not Applicable (New case) [YES/NO]',
            'Not applicable (Unknown) [YES/NO]',
            'Update symptoms [YES/NO]',
            'Update health status [YES/NO]',
            'Update outcome [YES/NO]',
            'Update case classification [YES/NO]',
            'Update lab result [YES/NO]',
            'Update chest imaging findings [YES/NO]',
            'Update disposition [YES/NO]',
            'Update exposure / travel history [YES/NO]',
            'Others [YES/NO]',
            'Others, specify:',
            'Type of Client [PROBABLE/CLOSE CONTACT/TESTING]',
            'Testing Category / Subgroup A [YES/NO]',
            'Testing Category / Subgroup B [YES/NO]',
            'Testing Category / Subgroup C [YES/NO]',
            'Testing Category / Subgroup D [YES/NO]',
            'Testing Category / Subgroup E [YES/NO]',
            'Testing Category / Subgroup F [YES/NO]',
            'Testing Category / Subgroup G [YES/NO]',
            'Testing Category / Subgroup H [YES/NO]',
            'Testing Category / Subgroup I [YES/NO]',
            'Testing Category / Subgroup J [YES/NO]',
            'Last Name',
            'First Name (and Suffix)',
            'Middle Name',
            'Birthday [MM/DD/YYYY]',
            'Age',
            'Sex',
            'Civil Status',
            'Nationality',
            'Occupation',
            'Works in a closed setting [YES/NO]',

            'House No./Lot/Bldg',
            'Street/ Purok/ Sitio',
            'Barangay',
            'Municipality/ City',
            'Province',
            'Home Phone no. (&Area Code)',
            'Cellphone No.',
            'Email adress',

            'House No./Lot/Bldg',
            'Street/ Purok/ Sitio',
            'Barangay',
            'Municipality/ City',
            'Province',
            'Home Phone no. (&Area Code)',
            'Cellphone No.',
            'Email adress',
            
            'Lot/Bldg',
            'Street',
            'Barangay',
            'Municipality / City',
            'Province',
            'Name of Workplace',
            'Phone No. / Cellphone No.',
            'Email adress',

            'Health Care Worker [YES/NO]',
            'Name and location of health facility',

            'Returning overseas Filipino [YES/NO]',
            'Country of Origin',
            'OFW [YES/NO]',
            'Non-OFW [YES/NO]',

            'Foreign National Traveler [YES/NO]',
            'Country of origin',

            'Locally stranded individual / APOR / Traveler [YES/NO]',
            'City / Municipality & Province of Origin',
            'Locally Stranded individual [YES/NO]',
            'Authorized Person Outside Residence / Local Traveler [YES/NO]',

            'Lives in Closed settings [YES/NO]',
            'Specify Type of institution (e.g. prisons, residential facilities, retirement communities, care homes, camps',
            'Specify Name of institution',

            'Indigenous Person [YES/NO]',
            'Specify group',
            
            'Have previous COVID-19 related consultation? [YES/NO]',
            'Date of first consult (MM/DD/YYYY)',
            'Name of facility where first consult was done',

            'Admitted in Hospital [YES/NO]',
            'Name of Hospital',
            'Date and Time admitted in hospital',

            'Admitted in Isolation / quarantine facility [YES/NO]',
            'Name of Isolation / quarantine facility',
            'Date and Time Isolated / quarantined facility',

            'In home isolation / quarantine [YES/NO]',
            'Date and time isolated / quarantined at home',

            'Discharged to home [YES/NO]',
            'if Discharged: date of discharge (mm/dd/yyyy)',

            'Others [YES/NO]',
            'Others: State reason',

            'Asymptomatic [YES/NO]',
            'Mild [YES/NO]',
            'Moderate [YES/NO]',
            'Severe [YES/NO]',
            'Critical [YES/NO]',
            'Suspect [YES/NO]',
            'Probable [YES/NO]',
            'Confirmed [YES/NO]',
            'Non-Covid-19 Case [YES/NO]',

            'Date of Onset of Illness (mm/dd/yyyy)',
            'Asymptomatic [YES/NO]',
            'Fever [YES/NO]',
            '°C [N/A if empty]',
            'Cough [YES/NO]',
            'General Weakness [YES/NO]',
            'Fatigue [YES/NO]',
            'Headache [YES/NO]',
            'Myalgia [YES/NO]',
            'Sorethroat [YES/NO]',
            'Coryza [YES/NO]',
            'Dyspnea [YES/NO]',
            'Anorexia [YES/NO]',
            'Nausea [YES/NO]',
            'Vomiting [YES/NO]',
            'Diarrhea [YES/NO]',
            'Altered Mental Status [YES/NO]',
            'Anosmia (loss of smell) [YES/NO]',
            'Ageusia (loss of taste) [YES/NO]',
            'Others [YES/NO]',
            'Others: Specify',

            'None [YES/NO]',
            'Hypertension [YES/NO]',
            'Diabetes [YES/NO]',
            'Heart Disease [YES/NO]',
            'Lung Disease [YES/NO]',
            'Gastrointestinal [YES/NO]',
            'Genito-Urinary [YES/NO]',
            'Neurological Disease [YES/NO]',
            'Cancer [YES/NO]',
            'Others [YES/NO]',
            'Others, specify',
            'Pregnant? [YES/NO]',
            'LMP (mm/dd/yyyy)',
            'High risk pregnancy? [YES/NO]',

            'Was diagnosed to have Sever Acute Respiratory Illness? [YES/NO]',
            'Date done [MM/DD/YYYY]',
            'Imaging done',
            'Results',
            'Other findings, specify',

            'Have you ever tested positive using RT-PCR before? [YES/NO]',
            'Date of Specimen Collection [MM/DD/YYYY]',
            'Laboratory',
            'No. of Previous RT-PCR swabs done',

            'Date Collected 1 [MM/DD/YYYY]',
            'Time Collected (ONI)',
            'Date Released 1 [MM/DD/YYYY]',
            'Laboratory 1',
            'Type of Test 1',
            'Type of Test Reason 1',
            'Results 1',
            'Results Others Specify 1',

            'Date Collected 2 [MM/DD/YYYY]',
            'Date Released 2 [MM/DD/YYYY]',
            'Laboratory 2',
            'Type of Test 2',
            'Type of Test Reason 2',
            'Results 2',
            'Results Others Specify 2',

            'Active (Currently admitted or in isolation / quarantine) [YES/NO]',
            'Recovered [YES/NO]',
            'Date of recovery (MM/DD/YYYY)',
            'Died [YES/NO]',
            'date of death (MM/DD/YYYY)',
            'Immediate Cause',
            'Antecedent cause',
            'Underlying cause',
            'Contributory Conditions',

            'History of exposure to known probable and/or confirmed COVID-19 case 14 days before the onset of signs and symptoms? OR If Asymptomatic, 14 days before swabbing or specimen collection? [YES/NO]',
            'Date of last contact [MM/DD/YYYY]',
            'Has the patient been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms? OR If Asymptomatic, 14 days before swabbing or specimen collection? [YES/NO]',
            'If International Travel, country of origin',
            'Inclusive Travel dates FROM [MM/DD/YYYY]',
            'Inclusive Travel dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',
            'Airline/ Sea Vessel',
            'Flight/ Vessel No.',
            'Date of Departure (mm/dd/yyyy)',
            'Date of Arrival in PH (mm/dd/yyyy)',

            'Health facility [YES/NO]',
            'Name of Place',
            'Address',
            'Inclusive Travel Dates FROM [MM/DD/YYYY]',
            'Inclusive Travel Dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',

            'Closed Settings [YES/NO]',
            'Name of Place',
            'Address',
            'Inclusive Travel Dates FROM [MM/DD/YYYY]',
            'Inclusive Travel Dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',

            'School [YES/NO]',
            'Name of Place',
            'Address',
            'Inclusive Travel Dates FROM [MM/DD/YYYY]',
            'Inclusive Travel Dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',

            'Workplace [YES/NO]',
            'Name of Place',
            'Address',
            'Inclusive Travel Dates FROM [MM/DD/YYYY]',
            'Inclusive Travel Dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',

            'Market [YES/NO]',
            'Name of Place',
            'Address',
            'Inclusive Travel Dates FROM [MM/DD/YYYY]',
            'Inclusive Travel Dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',

            'Social Gatherings [YES/NO]',
            'Name of Place',
            'Address',
            'Inclusive Travel Dates FROM [MM/DD/YYYY]',
            'Inclusive Travel Dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',

            'Others [YES/NO]',
            'Name of Place',
            'Address',
            'Inclusive Travel Dates FROM [MM/DD/YYYY]',
            'Inclusive Travel Dates TO [MM/DD/YYYY]',
            'With ongoing COVID-19 community transmission? [YES/NO]',

            'Transport Service [YES/NO]',
            'Airline / Sea vessel / Bus line / Train 1',
            'Flight / Vessel / Bus No. 1',
            'Place of Origin 1',
            'Departure Date 1 [MM/DD/YYYY]',
            'Destination 1',
            'Date of Arrival (MM/DD/YYY) 1',

            'Airline / Sea vessel / Bus line / Train 2',
            'Flight / Vessel / Bus No. 2',
            'Place of Origin 2',
            'Departure Date 2 [MM/DD/YYYY]',
            'Destination 2',
            'Date of Arrival (MM/DD/YYY) 2',

            'Name 1',
            'Contact Number 1',
            'Name 2',
            'Contact Number 2',
            'Name 3',
            'Contact Number 3',
            'Name 4',
            'Contact Number 4',

            'Attended?',
            'for Hospitalization?',
        ];
    }
}
