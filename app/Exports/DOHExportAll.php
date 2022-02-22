<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Forms;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DOHExportAll implements WithMultipleSheets
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
        //$sheets[] = new ActiveConfirmedCaseSheet();
        //$sheets[] = new NegativeCaseSheet();

        return $sheets;
    }
}

class SuspectedCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithChunkReading {
    public function collection()
    {
        return Forms::where('status', 'approved')
        ->where('caseClassification', 'Suspect')
        ->where('outcomeCondition', 'Active')
        ->orderby('created_at', 'asc')
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
        $arr_othersas = explode(",", $form->SASOtherRemarks);
        $arr_como = explode(",", $form->COMO);

        if(is_null($form->testType2)) {
            $testType = $form->testType1;
            $testDate = date('m/d/Y', strtotime($form->testDateCollected1));
            $testReleased = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
            $testResult = $form->testResult1;
        }
        else {
            //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
            $testType = $form->testType2;
            $testDate = date('m/d/Y', strtotime($form->testDateCollected2));
            $testReleased = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
            $testResult = $form->testResult2;
        }

        if($form->dispoType == 1) {
            $dispo = 'ADMITTED AT HOSPITAL';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 2) {
            $dispo = 'ADMITTED AT ISOLATION FACILITY';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 3) {
            $dispo = 'HOME QUARANTINE';
            $dispoName = "N/A";
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 4) {
            $dispo = 'DISCHARGED';
            $dispoName = "N/A";
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 5) {
            $dispo = 'OTHERS';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }

        return [
            date('m/d/Y', strtotime($form->created_at)),
            Carbon::parse($form->created_at)->format('W'),
            date('m/d/Y', strtotime($form->interviewDate)),
            $form->drunit,
            $form->drregion,
            $form->drprovince,
            $form->records->lname,
            $form->records->fname,
            (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            substr($form->records->gender,0,1),
            $form->records->nationality,
            'IV A', //must be automated?
            $form->records->permaaddress_province,
            $form->records->permaaddress_city,
            $form->records->permaaddress_brgy,
            $form->records->permaaddress_houseno.', '.$form->records->permaaddress_street,
            $form->records->mobile,
            (!is_null($form->records->occupation)) ? $form->records->occupation : "N/A",
            ($form->isHealthCareWorker == 1) ? 'Y' : 'N',
            ($form->isHealthCareWorker == 1) ? $form->healthCareCompanyLocation : 'N/A',
            $form->healthStatus,
            ($form->records->isPregnant == 1) ? 'Y' : 'N',
            (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
            (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
            (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
            (in_array('COLDS', $arr_othersas) || in_array('COLD', $arr_othersas) || in_array('Colds', $arr_sas)) ? 'Y' : 'N',
            (in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
            (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'Y' : 'N',
            (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'Y' : 'N',
            (in_array('Sore throat', $arr_sas)) ? 'Y' : 'N',
            (in_array('Diarrhea', $arr_sas)) ? 'Y' : 'N',
            (!is_null($form->SASOtherRemarks)) ? mb_strtoupper($form->SASOtherRemarks) : 'N/A',
            ($form->COMO != 'None') ? 'Y' : 'N',
            ($form->COMO != 'None') ? $form->COMO : 'N/A',
            $testDate,
            ($testType == 'ANTIGEN') ? $testResult : 'N/A',
            ($testType == 'OPS' || $testType == 'NPS' || $testType == 'OPS AND NPS') ? $testResult : 'N/A',
            ($testType == 'ANTIBODY') ? $testResult : 'N/A',
            $form->caseClassification,
            $dispo,
            $dispoName,
            $dispoDate,
            ($form->dispoType == 4) ? $dispoDate : 'N/A',
            $form->outcomeCondition,
            ($form->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($form->outcomeRecovDate)) : 'N/A',
            ($form->outcomeCondition == 'Died') ? date('m/d/Y', strtotime($form->outcomeDeathDate)) : 'N/A',
            ($form->outcomeCondition == 'Died') ? mb_strtoupper($form->deathImmeCause) : 'N/A',
            ($form->expoitem2 == 1) ? 'Y' : 'N',
            (!is_null($form->placevisited)) ? $form->placevisited : 'N/A',
            (!is_null($form->localDateDepart1)) ? date('m/d/Y', strtotime($form->localDateDepart1)) : 'N/A',
            ($form->isLSI == 1) ? 'Y' : 'N',
            ($form->isLSI == 1) ? $form->LSICity : 'N/A',
            ($form->isOFW == 1 && $form->ofwType == 1) ? 'Y': 'N',
            ($form->isOFW == 1 && $form->ofwType == 1) ? $form->OFWCountyOfOrigin : 'N/A',
            "N/A", //OFW DATE OF ARRIVAL, WALA NAMANG GANITO SA CIF DATABASE ROWS
            ($form->isLSI == 1 && $form->lsiType == 0) ? 'Y' : 'N',
            "UNKNOWN", //LOCAL OR IMPORTED CASE, WALA DING GANITO SA CIF DATABASE ROWS
            ($form->isOFW == 1 && $form->ofwType == 2) ? 'Y': 'N',
            (!is_null($form->remarks)) ? $form->remarks : 'N/A',
        ];
    }

    public function headings(): array {
        return [
            'MM (Morbidity Month)',
            'MW (Morbidity Week)',
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
    }

    public function chunkSize(): int
    {
        return 200;
    }
}

class ProbableCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithChunkReading {
    public function collection()
    {
        return Forms::where('status', 'approved')
        ->where('caseClassification', 'Probable')
        ->where('outcomeCondition', 'Active')
        ->orderby('created_at', 'asc')
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
        $arr_othersas = explode(",", $form->SASOtherRemarks);
        $arr_como = explode(",", $form->COMO);

        if(is_null($form->testType2)) {
            $testType = $form->testType1;
            $testDate = date('m/d/Y', strtotime($form->testDateCollected1));
            $testReleased = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
            $testResult = $form->testResult1;
        }
        else {
            //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
            $testType = $form->testType2;
            $testDate = date('m/d/Y', strtotime($form->testDateCollected2));
            $testReleased = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
            $testResult = $form->testResult2;
        }

        if($form->dispoType == 1) {
            $dispo = 'ADMITTED AT HOSPITAL';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 2) {
            $dispo = 'ADMITTED AT ISOLATION FACILITY';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 3) {
            $dispo = 'HOME QUARANTINE';
            $dispoName = "N/A";
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 4) {
            $dispo = 'DISCHARGED';
            $dispoName = "N/A";
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 5) {
            $dispo = 'OTHERS';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }

        return [
            date('m/d/Y', strtotime($form->created_at)),
            Carbon::parse($form->created_at)->format('W'),
            date('m/d/Y', strtotime($form->interviewDate)),
            $form->drunit,
            $form->drregion,
            $form->drprovince,
            $form->records->lname,
            $form->records->fname,
            (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            substr($form->records->gender,0,1),
            $form->records->nationality,
            'IV A', //must be automated?
            $form->records->permaaddress_province,
            $form->records->permaaddress_city,
            $form->records->permaaddress_brgy,
            $form->records->permaaddress_houseno.', '.$form->records->permaaddress_street,
            $form->records->mobile,
            (!is_null($form->records->occupation)) ? $form->records->occupation : "N/A",
            ($form->isHealthCareWorker == 1) ? 'Y' : 'N',
            ($form->isHealthCareWorker == 1) ? $form->healthCareCompanyLocation : 'N/A',
            $form->healthStatus,
            ($form->records->isPregnant == 1) ? 'Y' : 'N',
            (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
            (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
            (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
            (in_array('COLDS', $arr_othersas) || in_array('COLD', $arr_othersas) || in_array('Colds', $arr_sas)) ? 'Y' : 'N',
            (in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
            (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'Y' : 'N',
            (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'Y' : 'N',
            (in_array('Sore throat', $arr_sas)) ? 'Y' : 'N',
            (in_array('Diarrhea', $arr_sas)) ? 'Y' : 'N',
            (!is_null($form->SASOtherRemarks)) ? mb_strtoupper($form->SASOtherRemarks) : 'N/A',
            ($form->COMO != 'None') ? 'Y' : 'N',
            ($form->COMO != 'None') ? $form->COMO : 'N/A',
            $testDate,
            ($testType == 'ANTIGEN') ? $testResult : 'N/A',
            ($testType == 'OPS' || $testType == 'NPS' || $testType == 'OPS AND NPS') ? $testResult : 'N/A',
            ($testType == 'ANTIBODY') ? $testResult : 'N/A',
            $form->caseClassification,
            $dispo,
            $dispoName,
            $dispoDate,
            ($form->dispoType == 4) ? $dispoDate : 'N/A',
            $form->outcomeCondition,
            ($form->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($form->outcomeRecovDate)) : 'N/A',
            ($form->outcomeCondition == 'Died') ? date('m/d/Y', strtotime($form->outcomeDeathDate)) : 'N/A',
            ($form->outcomeCondition == 'Died') ? mb_strtoupper($form->deathImmeCause) : 'N/A',
            ($form->expoitem2 == 1) ? 'Y' : 'N',
            (!is_null($form->placevisited)) ? $form->placevisited : 'N/A',
            (!is_null($form->localDateDepart1)) ? date('m/d/Y', strtotime($form->localDateDepart1)) : 'N/A',
            ($form->isLSI == 1) ? 'Y' : 'N',
            ($form->isLSI == 1) ? $form->LSICity : 'N/A',
            ($form->isOFW == 1 && $form->ofwType == 1) ? 'Y': 'N',
            ($form->isOFW == 1 && $form->ofwType == 1) ? $form->OFWCountyOfOrigin : 'N/A',
            "N/A", //OFW DATE OF ARRIVAL, WALA NAMANG GANITO SA CIF DATABASE ROWS
            ($form->isLSI == 1 && $form->lsiType == 0) ? 'Y' : 'N',
            "UNKNOWN", //LOCAL OR IMPORTED CASE, WALA DING GANITO SA CIF DATABASE ROWS
            ($form->isOFW == 1 && $form->ofwType == 2) ? 'Y': 'N',
            (!is_null($form->remarks)) ? $form->remarks : 'N/A',
        ];
    }

    public function headings(): array {
        return [
            'MM (Morbidity Month)',
            'MW (Morbidity Week)',
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
    }

    public function chunkSize(): int
    {
        return 200;
    }
}

class ConfirmedCaseSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithChunkReading {
    public function collection()
    {
        return Forms::where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->orderby('created_at', 'asc')
        ->get();
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }

    public function title(): string
    {
        return 'CONFIRMED';
    }

    public function map($form): array {
        $arr_sas = explode(",", $form->SAS);
        $arr_othersas = explode(",", $form->SASOtherRemarks);
        $arr_como = explode(",", $form->COMO);

        if(is_null($form->testType2)) {
            $testType = $form->testType1;
            $testDate = date('m/d/Y', strtotime($form->testDateCollected1));
            $testReleased = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
            $testResult = $form->testResult1;
        }
        else {
            //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
            $testType = $form->testType2;
            $testDate = date('m/d/Y', strtotime($form->testDateCollected2));
            $testReleased = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
            $testResult = $form->testResult2;
        }

        if($form->dispoType == 1) {
            $dispo = 'ADMITTED AT HOSPITAL';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 2) {
            $dispo = 'ADMITTED AT ISOLATION FACILITY';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 3) {
            $dispo = 'HOME QUARANTINE';
            $dispoName = "N/A";
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 4) {
            $dispo = 'DISCHARGED';
            $dispoName = "N/A";
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }
        else if($form->dispoType == 5) {
            $dispo = 'OTHERS';
            $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
            $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
        }

        return [
            date('m/d/Y', strtotime($form->created_at)),
            Carbon::parse($form->created_at)->format('W'),
            date('m/d/Y', strtotime($form->interviewDate)),
            $form->drunit,
            $form->drregion,
            $form->drprovince,
            $form->records->lname,
            $form->records->fname,
            (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
            date('m/d/Y', strtotime($form->records->bdate)),
            $form->records->getAge(),
            substr($form->records->gender,0,1),
            $form->records->nationality,
            'IV A', //must be automated?
            $form->records->permaaddress_province,
            $form->records->permaaddress_city,
            $form->records->permaaddress_brgy,
            $form->records->permaaddress_houseno.', '.$form->records->permaaddress_street,
            $form->records->mobile,
            (!is_null($form->records->occupation)) ? $form->records->occupation : "N/A",
            ($form->isHealthCareWorker == 1) ? 'Y' : 'N',
            ($form->isHealthCareWorker == 1) ? $form->healthCareCompanyLocation : 'N/A',
            $form->healthStatus,
            ($form->records->isPregnant == 1) ? 'Y' : 'N',
            (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
            (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
            (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
            (in_array('COLDS', $arr_othersas) || in_array('COLD', $arr_othersas) || in_array('Colds', $arr_sas)) ? 'Y' : 'N',
            (in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
            (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'Y' : 'N',
            (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'Y' : 'N',
            (in_array('Sore throat', $arr_sas)) ? 'Y' : 'N',
            (in_array('Diarrhea', $arr_sas)) ? 'Y' : 'N',
            (!is_null($form->SASOtherRemarks)) ? mb_strtoupper($form->SASOtherRemarks) : 'N/A',
            ($form->COMO != 'None') ? 'Y' : 'N',
            ($form->COMO != 'None') ? $form->COMO : 'N/A',
            $testDate,
            ($testType == 'ANTIGEN') ? $testResult : 'N/A',
            ($testType == 'OPS' || $testType == 'NPS' || $testType == 'OPS AND NPS') ? $testResult : 'N/A',
            ($testType == 'ANTIBODY') ? $testResult : 'N/A',
            $form->caseClassification,
            $dispo,
            $dispoName,
            $dispoDate,
            ($form->dispoType == 4) ? $dispoDate : 'N/A',
            $form->outcomeCondition,
            ($form->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($form->outcomeRecovDate)) : 'N/A',
            ($form->outcomeCondition == 'Died') ? date('m/d/Y', strtotime($form->outcomeDeathDate)) : 'N/A',
            ($form->outcomeCondition == 'Died') ? mb_strtoupper($form->deathImmeCause) : 'N/A',
            ($form->expoitem2 == 1) ? 'Y' : 'N',
            (!is_null($form->placevisited)) ? $form->placevisited : 'N/A',
            (!is_null($form->localDateDepart1)) ? date('m/d/Y', strtotime($form->localDateDepart1)) : 'N/A',
            ($form->isLSI == 1) ? 'Y' : 'N',
            ($form->isLSI == 1) ? $form->LSICity : 'N/A',
            ($form->isOFW == 1 && $form->ofwType == 1) ? 'Y': 'N',
            ($form->isOFW == 1 && $form->ofwType == 1) ? $form->OFWCountyOfOrigin : 'N/A',
            "N/A", //OFW DATE OF ARRIVAL, WALA NAMANG GANITO SA CIF DATABASE ROWS
            ($form->isLSI == 1 && $form->lsiType == 0) ? 'Y' : 'N',
            "UNKNOWN", //LOCAL OR IMPORTED CASE, WALA DING GANITO SA CIF DATABASE ROWS
            ($form->isOFW == 1 && $form->ofwType == 2) ? 'Y': 'N',
            (!is_null($form->remarks)) ? $form->remarks : 'N/A',
        ];
    }

    public function headings(): array {
        return [
            'MM (Morbidity Month)',
            'MW (Morbidity Week)',
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
    }

    public function chunkSize(): int
    {
        return 200;
    }
}