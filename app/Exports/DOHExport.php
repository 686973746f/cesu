<?php

namespace App\Exports;

use App\Models\Forms;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DOHExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array 
    {
        $sheets = [];

        $sheets[] = new SuspectedCaseSheet();
        $sheets[] = new ProbableCaseSheet();
        $sheets[] = new ConfirmedCaseSheet();


        return $sheets;
    }
}

class SuspectedCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles {
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }

    public function collection()
    {
        return Forms::where('caseClassification', 'Suspect')->get();
    }

    public function title(): string
    {
        return 'SUSPECTED';
    }

    public function map($form): array {
        $arr_sas = explode(",", $form->SAS);
        $arr_como = explode(",", $form->COMO);

        if(is_null($form->testType2)) {
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected1));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
        }
        else {
            //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected2));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
        }

        return [
            'FOR SWAB',
            (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : '',
            $form->drunit,
            $form->interviewerName,
            date('m/d/Y', strtotime($form->interviewDate)),
            $form->records->lname,
            $form->records->fname,
            (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
            (!is_null($form->records->mname)) ? substr($form->records->mname, 0, 1) : "N/A",
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            $form->records->gender,
            $form->records->cs,
            $form->records->nationality,
            '', //passport no, wala pang pagkukunan
            $form->records->address_houseno." / ".$form->records->address_street,
            'IV-A', //region, wala pang naka-defaults sa records table
            $form->records->address_province,
            $form->records->address_city,
            $form->records->address_brgy,
            (!is_null($form->records->phoneno)) ? $form->records->phoneno : "N/A",
            $form->records->mobile,
            (!is_null($form->records->email)) ? $form->records->email : "N/A",
            ($form->records->hasOccupation == 1) ? $form->records->occupation : "N/A",
            ($form->isHealthCareWorker == 1) ? "YES" : "NO",
            ($form->isOFW == 1) ? "YES" : "NO",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_name)) ? $form->occupation_name : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_lotbldg)) ? $form->occupation_lotbldg : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_street)) ? $form->occupation_street : "N/A",
            'IV-A', //default kasi wala namang values sa records table
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_province)) ? $form->occupation_province : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_city)) ? $form->occupation_city : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_brgy)) ? $form->occupation_brgy : "N/A",
            'PH', //default for country
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_mobile)) ? $form->occupation_mobile : "N/A",
            '', //Cellphone No.2 empty kasi di naman hinihingi sa CIF
            ($form->expoitem2 == 1) ? "YES" : "NO",
            ($form->expoitem2 == 1) ? $form->placevisited : "N/A",
            ($form->expoitem1 == 1) ? "YES" : "NO",
            '',
            (!is_null($form->dateOnsetOfIllness)) ? date("m/d/Y", strtotime($form->dateOnsetOfIllness)) : 'N/A',
            ($form->outcomeCondition == "Active") ? "YES" : "NO",
            '', //Health Facility Currently Admitted, currently di na hinihingi
            '',
            '',
            (in_array("Fever", $arr_sas)) ? "YES" : "NO",
            (in_array("Cough", $arr_sas)) ? "YES" : "NO",
            ($form->SASOtherRemarks == "COLDS" || $form->SASOtherRemarks == "COLD") ? "YES" : "NO",
            (in_array("Sore throat", $arr_sas)) ? "YES" : "NO",
            (in_array("Fatigue", $arr_sas)) ? "YES" : "NO",
            (in_array("Diarrhea", $arr_sas)) ? "YES" : "NO",
            (in_array("Others", $arr_sas)) ? strtoupper($form->SASOtherRemarks) : "N/A",
            '', //history of other illness not being recorded
            (in_array("None", $arr_como)) ? "NO" : "YES",
            (in_array("None", $arr_como)) ? "N/A" : $form->COMO,
            ($form->records->isPregnant == 1) ? "YES" : "NO",
            ($form->records->isPregnant == 1) ? date('m/d/Y', strtotime($form->PregnantLMP)) : "N/A",
            ($form->imagingDone != "None") ? "YES" : "NO",
            ($form->imagingDone != "None") ? date('m/d/Y', strtotime($form->imagingDoneDate)) : "N/A",
            $form->imagingResult,
            ($form->imagingResult == "OTHERS") ? strtoupper($form->imagingOtherFindings) : "N/A",
            $form->caseClassification,
            '', //conditions on discharge, wala namang ganito sa CIF v8
            ($form->outcomeCondition == "Recovered") ? date("m/d/Y", strtotime($form->outcomeRecovDate)) : "N/A",
            (!is_null($form->informantName)) ? strtoupper($form->informantName) : 'N/A',
            '',
            '',
            (!is_null($form->informantRelationship)) ? strtoupper($form->informantRelationship) : 'N/A',
            (!is_null($form->informantMobile)) ? $form->informantMobile : 'N/A',
            $form->healthStatus,
            ($form->outcomeCondition == "Recovered") ? date("m/d/Y", strtotime($form->outcomeRecovDate)) : "N/A",
            $form->outcomeCondition,
            ($form->outcomeCondition == "Died") ? date("m/d/Y", strtotime($form->outcomeDeathDate)) : "N/A",
            ($form->outcomeCondition == "Died") ? strtoupper($form->deathImmeCause) : "N/A",
            '', //cluster, wtf walang ganito
            $displayFirstTestDateCollected,
            $displayFirstTestDateRelease,
            '',
            '',
            '',
            '',
        ];
    }

    public function headings(): array {
        return [
            'Laboratory Result',
            'Date Released',
            'Disease Reporting Unit/Hospital',
            'Name of Investigator',
            'Date of Interview',
            'Last Name',
            'First Name',
            'Middle Name',
            'Initial',
            'Birthday (mm/dd/yyy)',
            'Age',
            'Sex',
            'Civil Status',
            'Nationality',
            'Passport No.',
            'House No./Lot/Bldg. Street',
            'Region',
            'Province',
            'Municipality/City',
            'Barangay',
            'Home Phone No.',
            'Cellphone No.',
            'Email Address',
            'Occupation',
            'Health Care Worker',
            'Overseas Employment (for Oversease Filifino Workers)',
            "Employer's Name",
            'Place of Work',
            'Street (Workplace)',
            'Region (Workplace)',
            'Province (Workplace)',
            'City/Municipality (Workplace)',
            'Barangay (Workplace)',
            'Country (Workplace)',
            'Office Phone No.',
            'Cellphone No.2',
            'History of travel/visit/work with a known COVID-19 transmission 14 days before the onset of signs and symptoms',
            'Travel History',
            'History of Exposure to Known COVID-19 Case 14 days before the onset of signs and symptoms',
            'Have you been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms',
            'Date of Onset of Illness (mm/dd/yyyy)',
            'Admitted?',
            'Health Facility Currently Admitted',
            'Date of Admission/ Consultation',
            'With Symptoms prior to specimen collection?',
            'Fever',
            'Cough',
            'Cold',
            'Sore Throat',
            'Difficulty of Breathing',
            'Diarrhea',
            'Other signs/symptoms, specify',
            'Is there any history of other illness?',
            'Comorbidity',
            'Specify Comorbidity',
            'Pregnant?',
            'Last Menstrual Period',
            'Chest XRAY done?',
            'Date Tested Chest XRAY',
            'Chest XRAY Results',
            'Other Radiologic Findings',
            'Classification',
            'Condition on Discharge',
            'Date of Discharge (mm/dd/yyyy)',
            'Lastname (Informant)',
            'Firstname (Informant)',
            'Middlename (Informant)',
            'Relationship (Informant)',
            'Phone No. (Informant)',
            'Health Status',
            'Date Recovered',
            'Outcome',
            'Date Died',
            'Cause Of Death',
            'Cluster',
            'Date Specimen Collected',
            'Date of Release of Result',
            'Number of Positive Cases From PUI',
            'Number Assessed',
            'Number Of PUI',
            'Total Close Contacts',
            'Remarks',
        ];
    }
}

class ProbableCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles {
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }

    public function collection()
    {
        return Forms::where('caseClassification', 'Probable')->get();
    }

    public function title(): string
    {
        return 'PROBABLE';
    }

    public function map($form): array {
        $arr_sas = explode(",", $form->SAS);
        $arr_como = explode(",", $form->COMO);

        if(is_null($form->testType2)) {
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected1));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
        }
        else {
            //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected2));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
        }

        return [
            'FOR SWAB',
            (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : '',
            $form->drunit,
            $form->interviewerName,
            date('m/d/Y', strtotime($form->interviewDate)),
            $form->records->lname,
            $form->records->fname,
            $form->records->mname,
            substr($form->records->mname, 0, 1),
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            $form->records->gender,
            $form->records->cs,
            $form->records->nationality,
            '', //passport no, wala pang pagkukunan
            $form->records->address_houseno." / ".$form->records->address_street,
            'IV-A', //region, wala pang naka-defaults sa records table
            $form->records->address_province,
            $form->records->address_city,
            $form->records->address_brgy,
            (!is_null($form->records->phoneno)) ? $form->records->phoneno : "N/A",
            $form->records->mobile,
            (!is_null($form->records->email)) ? $form->records->email : "N/A",
            ($form->records->hasOccupation == 1) ? $form->records->occupation : "N/A",
            ($form->isHealthCareWorker == 1) ? "YES" : "NO",
            ($form->isOFW == 1) ? "YES" : "NO",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_name)) ? $form->occupation_name : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_lotbldg)) ? $form->occupation_lotbldg : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_street)) ? $form->occupation_street : "N/A",
            'IV-A', //default kasi wala namang values sa records table
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_province)) ? $form->occupation_province : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_city)) ? $form->occupation_city : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_brgy)) ? $form->occupation_brgy : "N/A",
            'PH', //default for country
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_mobile)) ? $form->occupation_mobile : "N/A",
            '', //Cellphone No.2 empty kasi di naman hinihingi sa CIF
            ($form->expoitem2 == 1) ? "YES" : "NO",
            ($form->expoitem2 == 1) ? $form->placevisited : "N/A",
            ($form->expoitem1 == 1) ? "YES" : "NO",
            '',
            (!is_null($form->dateOnsetOfIllness)) ? date("m/d/Y", strtotime($form->dateOnsetOfIllness)) : 'N/A',
            ($form->outcomeCondition == "Active") ? "YES" : "NO",
            '', //Health Facility Currently Admitted, currently di na hinihingi
            '',
            '',
            (in_array("Fever", $arr_sas)) ? "YES" : "NO",
            (in_array("Cough", $arr_sas)) ? "YES" : "NO",
            ($form->SASOtherRemarks == "COLDS" || $form->SASOtherRemarks == "COLD") ? "YES" : "NO",
            (in_array("Sore throat", $arr_sas)) ? "YES" : "NO",
            (in_array("Fatigue", $arr_sas)) ? "YES" : "NO",
            (in_array("Diarrhea", $arr_sas)) ? "YES" : "NO",
            (in_array("Others", $arr_sas)) ? strtoupper($form->SASOtherRemarks) : "N/A",
            '', //history of other illness not being recorded
            (in_array("None", $arr_como)) ? "NO" : "YES",
            (in_array("None", $arr_como)) ? "N/A" : $form->COMO,
            ($form->records->isPregnant == 1) ? "YES" : "NO",
            ($form->records->isPregnant == 1) ? date('m/d/Y', strtotime($form->PregnantLMP)) : "N/A",
            ($form->imagingDone != "None") ? "YES" : "NO",
            ($form->imagingDone != "None") ? date('m/d/Y', strtotime($form->imagingDoneDate)) : "N/A",
            $form->imagingResult,
            ($form->imagingResult == "OTHERS") ? strtoupper($form->imagingOtherFindings) : "N/A",
            $form->caseClassification,
            '', //conditions on discharge, wala namang ganito sa CIF v8
            ($form->outcomeCondition == "Recovered") ? date("m/d/Y", strtotime($form->outcomeRecovDate)) : "N/A",
            (!is_null($form->informantName)) ? strtoupper($form->informantName) : 'N/A',
            '',
            '',
            (!is_null($form->informantRelationship)) ? strtoupper($form->informantRelationship) : 'N/A',
            (!is_null($form->informantMobile)) ? $form->informantMobile : 'N/A',
            $form->healthStatus,
            ($form->outcomeCondition == "Recovered") ? date("m/d/Y", strtotime($form->outcomeRecovDate)) : "N/A",
            $form->outcomeCondition,
            ($form->outcomeCondition == "Died") ? date("m/d/Y", strtotime($form->outcomeDeathDate)) : "N/A",
            ($form->outcomeCondition == "Died") ? strtoupper($form->deathImmeCause) : "N/A",
            '', //cluster, wtf walang ganito
            $displayFirstTestDateCollected,
            $displayFirstTestDateRelease,
            '',
            '',
            '',
            '',
        ];
    }

    public function headings(): array {
        return [
            'Laboratory Result',
            'Date Released',
            'Disease Reporting Unit/Hospital',
            'Name of Investigator',
            'Date of Interview',
            'Last Name',
            'First Name',
            'Middle Name',
            'Initial',
            'Birthday (mm/dd/yyy)',
            'Age',
            'Sex',
            'Civil Status',
            'Nationality',
            'Passport No.',
            'House No./Lot/Bldg. Street',
            'Region',
            'Province',
            'Municipality/City',
            'Barangay',
            'Home Phone No.',
            'Cellphone No.',
            'Email Address',
            'Occupation',
            'Health Care Worker',
            'Overseas Employment (for Oversease Filifino Workers)',
            "Employer's Name",
            'Place of Work',
            'Street (Workplace)',
            'Region (Workplace)',
            'Province (Workplace)',
            'City/Municipality (Workplace)',
            'Barangay (Workplace)',
            'Country (Workplace)',
            'Office Phone No.',
            'Cellphone No.2',
            'History of travel/visit/work with a known COVID-19 transmission 14 days before the onset of signs and symptoms',
            'Travel History',
            'History of Exposure to Known COVID-19 Case 14 days before the onset of signs and symptoms',
            'Have you been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms',
            'Date of Onset of Illness (mm/dd/yyyy)',
            'Admitted?',
            'Health Facility Currently Admitted',
            'Date of Admission/ Consultation',
            'With Symptoms prior to specimen collection?',
            'Fever',
            'Cough',
            'Cold',
            'Sore Throat',
            'Difficulty of Breathing',
            'Diarrhea',
            'Other signs/symptoms, specify',
            'Is there any history of other illness?',
            'Comorbidity',
            'Specify Comorbidity',
            'Pregnant?',
            'Last Menstrual Period',
            'Chest XRAY done?',
            'Date Tested Chest XRAY',
            'Chest XRAY Results',
            'Other Radiologic Findings',
            'Classification',
            'Condition on Discharge',
            'Date of Discharge (mm/dd/yyyy)',
            'Lastname (Informant)',
            'Firstname (Informant)',
            'Middlename (Informant)',
            'Relationship (Informant)',
            'Phone No. (Informant)',
            'Health Status',
            'Date Recovered',
            'Outcome',
            'Date Died',
            'Cause Of Death',
            'Cluster',
            'Date Specimen Collected',
            'Date of Release of Result',
            'Number of Positive Cases From PUI',
            'Number Assessed',
            'Number Of PUI',
            'Total Close Contacts',
            'Remarks',
        ];
    }
}

class ConfirmedCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles {
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
    
    public function collection()
    {
        return Forms::where('caseClassification', 'Confirmed')->get();
    }

    public function title(): string
    {
        return 'CONFIRMED CASES';
    }

    public function map($form): array {
        $arr_sas = explode(",", $form->SAS);
        $arr_como = explode(",", $form->COMO);

        if(is_null($form->testType2)) {
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected1));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
        }
        else {
            //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
            $displayFirstTestDateCollected = date('m/d/Y', strtotime($form->testDateCollected2));
            $displayFirstTestDateRelease = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
        }

        return [
            'FOR SWAB',
            (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : '',
            $form->drunit,
            $form->interviewerName,
            date('m/d/Y', strtotime($form->interviewDate)),
            $form->records->lname,
            $form->records->fname,
            $form->records->mname,
            substr($form->records->mname, 0, 1),
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            $form->records->gender,
            $form->records->cs,
            $form->records->nationality,
            '', //passport no, wala pang pagkukunan
            $form->records->address_houseno." / ".$form->records->address_street,
            'IV-A', //region, wala pang naka-defaults sa records table
            $form->records->address_province,
            $form->records->address_city,
            $form->records->address_brgy,
            (!is_null($form->records->phoneno)) ? $form->records->phoneno : "N/A",
            $form->records->mobile,
            (!is_null($form->records->email)) ? $form->records->email : "N/A",
            ($form->records->hasOccupation == 1) ? $form->records->occupation : "N/A",
            ($form->isHealthCareWorker == 1) ? "YES" : "NO",
            ($form->isOFW == 1) ? "YES" : "NO",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_name)) ? $form->occupation_name : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_lotbldg)) ? $form->occupation_lotbldg : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_street)) ? $form->occupation_street : "N/A",
            'IV-A', //default kasi wala namang values sa records table
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_province)) ? $form->occupation_province : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_city)) ? $form->occupation_city : "N/A",
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_brgy)) ? $form->occupation_brgy : "N/A",
            'PH', //default for country
            ($form->records->hasOccupation == 1 && !is_null($form->occupation_mobile)) ? $form->occupation_mobile : "N/A",
            '', //Cellphone No.2 empty kasi di naman hinihingi sa CIF
            ($form->expoitem2 == 1) ? "YES" : "NO",
            ($form->expoitem2 == 1) ? $form->placevisited : "N/A",
            ($form->expoitem1 == 1) ? "YES" : "NO",
            '',
            (!is_null($form->dateOnsetOfIllness)) ? date("m/d/Y", strtotime($form->dateOnsetOfIllness)) : 'N/A',
            ($form->outcomeCondition == "Active") ? "YES" : "NO",
            '', //Health Facility Currently Admitted, currently di na hinihingi
            '',
            '',
            (in_array("Fever", $arr_sas)) ? "YES" : "NO",
            (in_array("Cough", $arr_sas)) ? "YES" : "NO",
            ($form->SASOtherRemarks == "COLDS" || $form->SASOtherRemarks == "COLD") ? "YES" : "NO",
            (in_array("Sore throat", $arr_sas)) ? "YES" : "NO",
            (in_array("Fatigue", $arr_sas)) ? "YES" : "NO",
            (in_array("Diarrhea", $arr_sas)) ? "YES" : "NO",
            (in_array("Others", $arr_sas)) ? strtoupper($form->SASOtherRemarks) : "N/A",
            '', //history of other illness not being recorded
            (in_array("None", $arr_como)) ? "NO" : "YES",
            (in_array("None", $arr_como)) ? "N/A" : $form->COMO,
            ($form->records->isPregnant == 1) ? "YES" : "NO",
            ($form->records->isPregnant == 1) ? date('m/d/Y', strtotime($form->PregnantLMP)) : "N/A",
            ($form->imagingDone != "None") ? "YES" : "NO",
            ($form->imagingDone != "None") ? date('m/d/Y', strtotime($form->imagingDoneDate)) : "N/A",
            $form->imagingResult,
            ($form->imagingResult == "OTHERS") ? strtoupper($form->imagingOtherFindings) : "N/A",
            $form->caseClassification,
            '', //conditions on discharge, wala namang ganito sa CIF v8
            ($form->outcomeCondition == "Recovered") ? date("m/d/Y", strtotime($form->outcomeRecovDate)) : "N/A",
            (!is_null($form->informantName)) ? strtoupper($form->informantName) : 'N/A',
            '',
            '',
            (!is_null($form->informantRelationship)) ? strtoupper($form->informantRelationship) : 'N/A',
            (!is_null($form->informantMobile)) ? $form->informantMobile : 'N/A',
            $form->healthStatus,
            ($form->outcomeCondition == "Recovered") ? date("m/d/Y", strtotime($form->outcomeRecovDate)) : "N/A",
            $form->outcomeCondition,
            ($form->outcomeCondition == "Died") ? date("m/d/Y", strtotime($form->outcomeDeathDate)) : "N/A",
            ($form->outcomeCondition == "Died") ? strtoupper($form->deathImmeCause) : "N/A",
            '', //cluster, wtf walang ganito
            $displayFirstTestDateCollected,
            $displayFirstTestDateRelease,
            '',
            '',
            '',
            '',
        ];
    }

    public function headings(): array {
        return [
            'Laboratory Result',
            'Date Released',
            'Disease Reporting Unit/Hospital',
            'Name of Investigator',
            'Date of Interview',
            'Last Name',
            'First Name',
            'Middle Name',
            'Initial',
            'Birthday (mm/dd/yyy)',
            'Age',
            'Sex',
            'Civil Status',
            'Nationality',
            'Passport No.',
            'House No./Lot/Bldg. Street',
            'Region',
            'Province',
            'Municipality/City',
            'Barangay',
            'Home Phone No.',
            'Cellphone No.',
            'Email Address',
            'Occupation',
            'Health Care Worker',
            'Overseas Employment (for Oversease Filifino Workers)',
            "Employer's Name",
            'Place of Work',
            'Street (Workplace)',
            'Region (Workplace)',
            'Province (Workplace)',
            'City/Municipality (Workplace)',
            'Barangay (Workplace)',
            'Country (Workplace)',
            'Office Phone No.',
            'Cellphone No.2',
            'History of travel/visit/work with a known COVID-19 transmission 14 days before the onset of signs and symptoms',
            'Travel History',
            'History of Exposure to Known COVID-19 Case 14 days before the onset of signs and symptoms',
            'Have you been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms',
            'Date of Onset of Illness (mm/dd/yyyy)',
            'Admitted?',
            'Health Facility Currently Admitted',
            'Date of Admission/ Consultation',
            'With Symptoms prior to specimen collection?',
            'Fever',
            'Cough',
            'Cold',
            'Sore Throat',
            'Difficulty of Breathing',
            'Diarrhea',
            'Other signs/symptoms, specify',
            'Is there any history of other illness?',
            'Comorbidity',
            'Specify Comorbidity',
            'Pregnant?',
            'Last Menstrual Period',
            'Chest XRAY done?',
            'Date Tested Chest XRAY',
            'Chest XRAY Results',
            'Other Radiologic Findings',
            'Classification',
            'Condition on Discharge',
            'Date of Discharge (mm/dd/yyyy)',
            'Lastname (Informant)',
            'Firstname (Informant)',
            'Middlename (Informant)',
            'Relationship (Informant)',
            'Phone No. (Informant)',
            'Health Status',
            'Date Recovered',
            'Outcome',
            'Date Died',
            'Cause Of Death',
            'Cluster',
            'Date Specimen Collected',
            'Date of Release of Result',
            'Number of Positive Cases From PUI',
            'Number Assessed',
            'Number Of PUI',
            'Total Close Contacts',
            'Remarks',
        ];
    }
}
