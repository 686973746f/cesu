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
use Carbon\Carbon;

class DOHExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(array $id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function sheets(): array 
    {
        $sheets = [];

        $sheets[] = new SuspectedCaseSheet($this->id);
        $sheets[] = new ProbableCaseSheet($this->id);
        $sheets[] = new ConfirmedCaseSheet($this->id);
        $sheets[] = new NegativeCaseSheet($this->id);

        return $sheets;
    }
}

class SuspectedCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles {
    public function __construct(array $id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Forms::whereIn('id', $this->id)
        ->where('caseClassification', 'Suspect')
        ->orderby('testDateCollected1', 'asc')
        ->orderby('testDateCollected2', 'asc')
        ->get();
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
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
            Carbon::createFromFormat('Y-m-d', $form->created_at)->format('M'),
            Carbon::createFromFormat('Y-m-d', $form->created_at)->format('W'),
            date('m/d/Y', strtotime($form->interviewDate)),
            $form->drunit,
            '4A', //must be automated
            'GENERAL TRIAS', //must be automated
            $form->records->lname,
            $form->records->fname,
            (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            substr($form->records->gender,0,1),
            $form->records->nationality,
            'IV A',
            
        ];

        /*
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
        */
    }

    public function headings(): array {
        return [
            'MM (Morbidity Month)',
            'MW (Morbidity Week',
            'DATE REPORTED',
            'DRU',
            'REGION OF DRU',
            'MUNCITY OF DRU',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'DOB',
            'AGE (AGE IN YEARS)',
            'SEX(M/F)',
            'NATIONALITY',
            'REGION',
            'PROVINCE/HUC',
            'MUNICIPALITY/CITY',
            'BARANGAY',
            'HOUSE N. AND STREET OR NEAREST LANDMARK',
            'CONTACT N.',
            'OCCUPATION',
            'HEALTHCARE WORKER(Y/N)',
            'PLACE OF WORK',
            'SEVERITY OF THE CASE (ASYMTOMATIC,MILD,MODERATE,SEVERE,CRITICAL)',
            'PREGNANT (Y/N)',
            'ONSET OF ILLNESS',
            'FEVER(Y/N)',
            'COUGH (Y/N)',
            'COLDS (Y/N)',
            'DOB (Y/N)',
            'LOSS OF SMELL (Y/N)',
            'LOSS OF TASTE (Y/N)',
            'SORETHROAT (Y/N)',
            'DIARRHEA (Y/N)',
            'OTHER SYMPTOMS',
            'W. COMORBIDITY (Y/N)',
            'COMORBIDITY (HYPERTENSIVE, DIABETIC, WITH HEART PROBLEM, AND OTHERS)',
            'DATE OF SPECIMEN COLLECTION',
            'ANTIGEN (POSITIVE/NEGATIVE)',
            'PCR(POSITIVE/NEGATIVE)',
            'RDT(+IGG, +IGM,NEGATIVE)',
            'CLASSIFICATION (CONFIRMED,SUSPECTED,PROBABLE,FOR VALIDATION)',
            'QUARANTINE STATUS (ADMITTED,HOME QUARANTINE,TTMF,CLEARED,DISCHARGED)',
            'NAME OF FACILITY (FOR FACILITY QUARANTINE AND ADMITTED)',
            'DATE START OF QUARANTINE',
            'DATE COMPLETED QUARANTINE (FOR HOME AND FACILITY QUARANTINE)',
            'OUTCOME(ALIVE/RECOVERED/DIED)',
            'DATE RECOVERED',
            'DATE DIED',
            'CAUSE OF DEATH',
            'W. TRAVEL HISTORY(Y/N)',
            'PLACE OF TRAVEL',
            'DATE OF TRAVEL',
            'LSI (Y/N)',
            'ADDRESS(LSI)',
            'OFW(Y/N)',
            'PLACE OF ORIGIN (OFW)',
            'DATE OF ARRIVAL (OFW)',
            'AUTHORIZED PERSON OUTSIDE RESIDENCE (Y/N)',
            'LOCAL/IMPORTED CASE',
            'RETURNING OVERSEAS FILIPINO (Y/N)',
            'REMARKS',
        ];
        /*
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
        */
    }
}

class ProbableCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles {
    public function __construct(array $id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Forms::whereIn('id', $this->id)
        ->where('caseClassification', 'Probable')
        ->orderby('testDateCollected1', 'asc')
        ->orderby('testDateCollected2', 'asc')
        ->get();
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
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
            'PENDING',
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
    public function __construct(array $id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Forms::whereIn('id', $this->id)
        ->where('caseClassification', 'Confirmed')
        ->orderby('testDateCollected1', 'asc')
        ->orderby('testDateCollected2', 'asc')
        ->get();
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
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
            '2019-Ncov Viral RNA Detected',
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

class NegativeCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles {
    public function __construct(array $id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Forms::whereIn('id', $this->id)
        ->where('caseClassification', 'Non-COVID-19 Case')
        ->orderby('testDateCollected1', 'asc')
        ->orderby('testDateCollected2', 'asc')
        ->get();
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }

    public function title(): string
    {
        return 'NEGATIVE RESULTS';
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
            '2019-nCoV Viral RNA not Detected',
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