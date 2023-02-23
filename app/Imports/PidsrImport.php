<?php

namespace App\Imports;

use App\Models\Nt;
use Carbon\Carbon;
use App\Models\Abd;
use App\Models\Aes;
use App\Models\Afp;
use App\Models\Ahf;
use App\Models\Nnt;
use App\Models\Psp;
use App\Models\Ames;
use App\Models\Diph;
use App\Models\Hfmd;
use App\Models\Pert;
use App\Models\Chikv;
use App\Models\Dengue;
use App\Models\Rabies;
use App\Models\Anthrax;
use App\Models\Cholera;
use App\Models\Malaria;
use App\Models\Measles;
use App\Models\Meningo;
use App\Models\Typhoid;
use App\Models\Hepatitis;
use App\Models\Influenza;
use App\Models\Rotavirus;
use App\Models\Meningitis;
use Illuminate\Support\Str;
use App\Models\Leptospirosis;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;


class PidsrImport implements ToCollection, WithStartRow
{

    public function __construct($sd) 
    {
        $this->sd = $sd;
    }

    private function tDate($value) {
        if(!is_null($value) && date('Y-m-d', strtotime($value)) != '1970-01-01' && !empty($value)) {
            return date('Y-m-d', strtotime($value));
        }
        else {
            return NULL;
        }
    }

    private function transformDateTime($value, string $format = 'Y-m-d')
    {
        if(!is_null($value) && $value != 'N/A') {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
            } catch (\ErrorException $e) {
                if(strtotime($value)) {
                    return Carbon::parse($value)->format('Y-m-d');
                }
            }
        }
    }

    /**
    * @param Collection $collection
    */
    /*
    public function collection(Collection $collection)
    {
        dd($sd);

        foreach ($rows as $row) {
        
        }
    }
    */

    public function collection(Collection $rows)
    {
        if($this->sd == 'ABD') {
            foreach ($rows as $row) {
                if($row[17] == 'CAVITE' && $row[16] == 'GENERAL TRIAS') {
                    $sf = Abd::where('EPIID', $row[31])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[31].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[31];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Abd::create([
                            'Icd10Code' => $row[0],
                            'RegionOFDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[11],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'StoolCulture' => $row[22],
                            'Organism' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $this->tDate($row[25]),
                            'DateOfEntry' => $this->tDate($row[26]),
                            'AdmitToEntry' => $row[27],
                            'OnsetToAdmit' => $row[28],
                            'MorbidityMonth' => $row[29],
                            'MorbidityWeek' => $row[30],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[32],
                            'RECSTATUS' => $row[33],
                            'SentinelSite' => $row[34],
                            'DeleteRecord' => $row[35],
                            'Year' => $row[36],
                            'NameOfDru' => $row[37],
                            'District' => $row[38],
                            'InterLocal' => $row[39],
                            'Barangay' => $row[40],
                            'CASECLASS' => $row[41],
                            'TYPEHOSPITALCLINIC' => $row[42],
                            'SENT' => $row[43],
                            'ip' => ($row[44] == 'Y') ? 'Y': 'N',
                            'ipgroup' => ($row[44] == 'Y') ? $row[45] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'AES') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Aes::where('EPIID', $row[31])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[31].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[31];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Aes::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'LabResult' => $row[22],
                            'Organism' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $this->tDate($row[25]),
                            'DateOfEntry' => $this->tDate($row[26]),
                            'AdmitToEntry' => $row[27],
                            'OnsetToAdmit' => $row[28],
                            'MorbidityMonth' => $row[29],
                            'MorbidityWeek' => $row[30],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[32],
                            'RECSTATUS' => $row[33],
                            'SentinelSite' => $row[34],
                            'DeleteRecord' => $row[35],
                            'Year' => $row[36],
                            'NameOfDru' => $row[37],
                            'ILHZ' => $row[38],
                            'District' => $row[39],
                            'Barangay' => $row[40],
                            'CASECLASS' => $row[41],
                            'TYPEHOSPITALCLINIC' => $row[42],
                            'SENT' => $row[43],
                            'ip' => ($row[44] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[44] == 'Y') ? $row[45] : NULL,
                            'sari' => $row[46],
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'AFP') {
            foreach ($rows as $row) {
                if($row[11] == 'CAVITE' && $row[12] == 'GENERAL TRIAS') {
                    $sf = Afp::where('EPIID', $row[28])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[28].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[28];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Afp::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[8].', '.$row[7],
                            'Region' => $row[10],
                            'Province' => $row[11],
                            'Muncity' => $row[12],
                            'Streetpurok' => $row[13],
                            'Sex' => $row[14],
                            'DOB' => $this->tDate($row[15]),
                            'AgeYears' => $row[16],
                            'AgeMons' => $row[17],
                            'AgeDays' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DateOfReport' => $this->tDate($row[21]),
                            'DateOfInvestigation' => $this->tDate($row[22]),
                            'DateOfEntry' => $this->tDate($row[23]),
                            'AdmitToEntry' => $row[24],
                            'OnsetToAdmit' => $row[25],
                            'MorbidityMonth' => $row[26],
                            'MorbidityWeek' => $row[27],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[29],
                            'RECSTATUS' => $row[30],
                            'Fever' => $row[31],
                            'DONSETP' => $this->tDate($row[32]),
                            'RArm' => $row[33],
                            'Cough' => $row[34],
                            'ParalysisAtBirth' => $row[35],
                            'LArm' => $row[36],
                            'DiarrheaVomiting' => $row[37],
                            'Asymm' => $row[38],
                            'RLeg' => $row[39],
                            'MusclePain' => $row[40],
                            'LLeg' => $row[41],
                            'Mening' => $row[42],
                            'BrthMusc' => $row[43],
                            'NeckMusc' => $row[44],
                            'Paradev' => $row[45],
                            'Paradir' => $row[46],
                            'FacialMusc' => $row[47],
                            'WorkingDiagnosis' => $row[48],
                            'RASens' => $row[49],
                            'LASens' => $row[50],
                            'RLSens' => $row[51],
                            'LLSens' => $row[52],
                            'RARef' => $row[53],
                            'LARef' => $row[54],
                            'RLRef' => $row[55],
                            'LLRef' => $row[56],
                            'RAMotor' => $row[57],
                            'LAMotor' => $row[58],
                            'RLMotor' => $row[59],
                            'LLMotor' => $row[60],
                            'HxDisorder' => $row[61],
                            'Disorder' => $row[62],
                            'TravelPrior2Illness' => $row[63],
                            'PlaceOfTravel' => $row[64],
                            'FrmTrvlDate' => $this->tDate($row[65]),
                            'OtherCases' => $row[66],
                            'InjTrauAnibite' => $row[67],
                            'SpecifyInjTrauAnibite' => $row[68],
                            'Investigator' => $row[69],
                            'ContactNum' => $row[70],
                            'OPVDoses' => $row[71],
                            'DateLastDose' => $this->tDate($row[72]),
                            'HotCase' => $row[73],
                            'FirstStoolSpec' => $row[74],
                            'DStool1Taken' => $this->tDate($row[75]),
                            'DStool2Taken' => $this->tDate($row[76]),
                            'DStool1Sent' => $this->tDate($row[77]),
                            'DStool2Sent' => $this->tDate($row[78]),
                            'Stool1Result' => $row[79],
                            'Stool2Result' => $row[80],
                            'ExpDffup' => $this->tDate($row[81]),
                            'ActDffp' => $this->tDate($row[82]),
                            'PhyExam' => $row[83],
                            'ReasonND' => $row[84],
                            'DateDied' => $this->tDate($row[85]),
                            'OtherReasonND' => $row[86],
                            'ResPara' => $row[87],
                            'ResParaType' => $row[88],
                            'Atrophy' => $row[89],
                            'RAatrophy' => $row[90],
                            'LAatrophy' => $row[91],
                            'RLatrophy' => $row[92],
                            'LLatrophy' => $row[93],
                            'OthObs' => $row[94],
                            'FClass' => $row[95],
                            'DateClass' => $this->tDate($row[96]),
                            'VAPP' => $row[97],
                            'CCriteria' => $row[98],
                            'FinalDx' => $row[99],
                            'OtherDiagnosis' => $row[100],
                            'ReportToInvestigation' => (is_null($row[101]) || $row[101] == '') ? 0 : $row[101],
                            'Stool1CollectSend' => $row[102],
                            'Stool2CollectSend' => $row[103],
                            'Stool1SentResult' => $row[104],
                            'Stool2SentResult' => $row[105],
                            'Followupindicator' => $row[106],
                            'Stool1OnsetCollect' => $row[107],
                            'Stool2OnsetCollect' => $row[108],
                            'LabResultToClassification' => $row[109],
                            'Stool1ResultToClassify' => $row[110],
                            'Stool2ResultToClassify' => $row[111],
                            'ActDffup' => $this->tDate($row[112]),
                            'DStool1Received' => $this->tDate($row[113]),
                            'DStool2Received' => $this->tDate($row[114]),
                            'Stool1RecResult' => $row[115],
                            'Stool2RecResult' => $row[116],
                            'SecndStoolSpec' => $row[117],
                            'DateRep' => $this->tDate($row[118]),
                            'DateInv' => $this->tDate($row[119]),
                            'Year' => $row[120],
                            'SentinelSite' => $row[121],
                            'ClinicalSummary' => $row[122],
                            'DeleteRecord' => $row[123],
                            'NameOfDru' => $row[124],
                            'ToTrvldate' => $this->tDate($row[125]),
                            'ILHZ' => $row[126],
                            'District' => $row[127],
                            'Barangay' => $row[128],
                            'TYPEHOSPITALCLINIC' => $row[129],
                            'OCCUPATION' => $row[130],
                            'SENT' => $row[131],
                            'ip' => ($row[132] == 'Y') ? 'Y' : 'N', 
                            'ipgroup' => ($row[132] == 'Y') ? $row[133] : NULL,
                            'Outcome' => $row[134],
                            'DateOutcomeDied' => $this->tDate($row[135]),
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'AHF') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Ahf::where('EPIID', $row[33])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[33].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[33];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Ahf::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'PCRRes' => $row[22],
                            'PCROrganism' => $row[23],
                            'BloodCultRes' => $row[24],
                            'CultureOrganism' => $row[25],
                            'Outcome' => $row[26],
                            'DateDied' => $this->tDate($row[27]),
                            'DateOfEntry' => $this->tDate($row[28]),
                            'AdmitToEntry' => $row[29],
                            'OnsetToAdmit' => $row[30],
                            'MorbidityMonth' => $row[31],
                            'MorbidityWeek' => $row[32],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[34],
                            'RECSTATUS' => $row[35],
                            'SentinelSite' => $row[36],
                            'DeleteRecord' => $row[37],
                            'Year' => $row[38],
                            'NameOfDru' => $row[39],
                            'District' => $row[40],
                            'ILHZ' => $row[41],
                            'Barangay' => $row[42],
                            'CASECLASS' => $row[43],
                            'TYPEHOSPITALCLINIC' => $row[44],
                            'SENT' => $row[45],
                            'ip' => ($row[46] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[46] == 'Y') ? $row[47] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'AMES') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Ames::where('EPIID', $row[119])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[119].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[119];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Ames::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'NHTS' => $row[19],
                            'Admitted' => $row[20],
                            'DAdmit' => $this->tDate($row[21]),
                            'DOnset' => $this->tDate($row[22]),
                            'DateRep' => $this->tDate($row[23]),
                            'DateInv' => $this->tDate($row[24]),
                            'Investigator' => $row[25],
                            'ContactNum' => $row[26],
                            'InvDesig' => $row[27],
                            'Fever' => $row[28],
                            'BehaviorChng' => $row[29],
                            'Seizure' => $row[30],
                            'Stiffneck' => $row[31],
                            'bulgefontanel' => $row[32],
                            'MenSign' => $row[33],
                            'ClinDiag' => $row[34],
                            'OtherDiag' => $row[35],
                            'JE' => $row[36],
                            'VacJeDate' => $this->tDate($row[37]),
                            'JEDose' => $row[38],
                            'Hib' => $row[39],
                            'VacHibDate' => $this->tDate($row[40]),
                            'HibDose' => $row[41],
                            'PCV10' => $row[42],
                            'VacPCV10Date' => $this->tDate($row[43]),
                            'PCV10Dose' => $row[44],
                            'PCV13' => $row[45],
                            'VacPCV13Date' => $this->tDate($row[46]),
                            'PCV13Dose' => $row[47],
                            'MeningoVacc' => $row[48],
                            'VacMeningoDate' => $this->tDate($row[49]),
                            'MeningoVaccDose' => $row[50],
                            'MeasVacc' => $row[51],
                            'VacMeasDate' => $this->tDate($row[52]),
                            'MeasVaccDose' => $row[53],
                            'MMR' => $row[54],
                            'VacMMRDate' => $this->tDate($row[55]),
                            'MMRDose' => $row[56],
                            'plcDaycare' => $row[57],
                            'plcBrgy' => $row[58],
                            'plcHome' => $row[59],
                            'plcSchool' => $row[60],
                            'plcdormitory' => $row[61],
                            'plcHC' => $row[62],
                            'plcWorkplace' => $row[63],
                            'plcOther' => $row[64],
                            'Travel' => $row[65],
                            'PlaceTravelled' => $row[66],
                            'FrmTrvlDate' => $this->tDate($row[67]),
                            'ToTrvlDate' => $this->tDate($row[68]),
                            'CSFColl' => $row[69],
                            'D8CSFTaken' => $this->tDate($row[70]),
                            'TymCSFTaken' => $this->tDate($row[71]),
                            'D8CSFHospLab' => $this->tDate($row[72]),
                            'TymCSFHospLab' => $row[73],
                            'CSFAppearance' => $row[74],
                            'GramStain' => $row[75],
                            'GramStainResult' => $row[76],
                            'culture' => $row[77],
                            'CultureResult' => $row[78],
                            'OtherTest' => $row[79],
                            'OtherTestResult' => $row[80],
                            'D8CSFSentRITM' => $this->tDate($row[81]),
                            'D8CSFReceivedRITM' => $this->tDate($row[82]),
                            'CSFSampVol' => $row[83],
                            'D8CSFTesting' => $this->tDate($row[84]),
                            'CSFResult' => $row[85],
                            'Serum1Col' => $row[86],
                            'D8Serum1Taken' => $this->tDate($row[87]),
                            'D8Serum1HospLab' => $this->tDate($row[88]),
                            'D8Serum1Sent' => $this->tDate($row[89]),
                            'D8Seruml1Received' => $this->tDate($row[90]),
                            'Serum1SampVol' => $row[91],
                            'D8Serum1Testing' => $this->tDate($row[92]),
                            'Serum1Result' => $row[93],
                            'Serum2Col' => $row[94],
                            'D8Serum2Taken' => $this->tDate($row[95]),
                            'D8Serum2HospLab' => $this->tDate($row[96]),
                            'D8Serum2Sent' => $this->tDate($row[97]),
                            'D8Serum2Received' => $this->tDate($row[98]),
                            'Serum2SampVol' => $row[99],
                            'D8Serum2testing' => $this->tDate($row[100]),
                            'Serum2Result' => $row[101],
                            'AESCaseClass' => $row[102],
                            'BmCaseClass' => $row[103],
                            'AESOtherAgent' => $row[104],
                            'ConfirmBMTest' => $row[105],
                            'FinalDiagnosis' => $row[106],
                            'Outcome' => $row[107],
                            'DateOfEntry' => $this->tDate($row[108]),
                            'DateDisch' => $this->tDate($row[109]),
                            'DateDied' => $this->tDate($row[110]),
                            'RecoverSequelae' => $row[111],
                            'SequelaeSpecs' => $row[112],
                            'TransTo' => $row[113],
                            'HAMA' => $this->tDate($row[114]),
                            'AdmitToEntry' => $row[115],
                            'OnsetToAdmit' => $row[116],
                            'MorbidityMonth' => $row[117],
                            'MorbidityWeek' => $row[118],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[120],
                            'RECSTATUS' => $row[121],
                            'SentinelSite' => $row[122],
                            'DeleteRecord' => $row[123],
                            'Year' => $row[124],
                            'NameOfDru' => $row[125],
                            'ILHZ' => $row[126],
                            'District' => $row[127],
                            'Barangay' => $row[128],
                            'CASECLASS' => $row[129],
                            'TYPEHOSPITALCLINIC' => $row[130],
                            'SENT' => $row[131],
                            'ip' => ($row[132] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[132] == 'Y') ? $row[133] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'ANTHRAX') {
            foreach ($rows as $row) {
                if($row[12] == 'CAVITE' && $row[13] == 'GENERAL TRIAS') {
                    $sf = Anthrax::where('EPIID', $row[27])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[27].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[27];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Anthrax::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'SentinelSite' => $row[6],
                            'PatientNumber' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'FullName' => $row[9].', '.$row[8],
                            'Region' => $row[11],
                            'Province' => $row[12],
                            'Muncity' => $row[13],
                            'Barangay' => $row[14],
                            'Streetpurok' => $row[15],
                            'Sex' => $row[16],
                            'DOB' => $this->tDate($row[17]),
                            'AgeYears' => $row[18],
                            'AgeMons' => $row[19],
                            'AgeDays' => $row[20],
                            'Year' => $row[21],
                            'DateOfEntry' => $this->tDate($row[22]),
                            'AdmitToEntry' => $row[23],
                            'OnsetToAdmit' => $row[24],
                            'MorbidityMonth' => $row[25],
                            'MorbidityWeek' => $row[26],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[28],
                            'RECSTATUS' => $row[29],
                            'Occupation' => $row[30],
                            'Workplace' => $row[31],
                            'WorkAddress' => $row[32],
                            'DOnset' => $this->tDate($row[33]),
                            'Fever' => $row[34],
                            'Nausea' => $row[35],
                            'Headache' => $row[36],
                            'DryCough' => $row[37],
                            'SoreThroat' => $row[38],
                            'TroubleSwallowing' => $row[39],
                            'TroubleBreathing' => $row[40],
                            'StomachPain' => $row[41],
                            'VomitingBlood' => $row[42],
                            'BloodyDiarrhea' => $row[43],
                            'SweatingExcessively' => $row[44],
                            'ExtremeTiredness' => $row[45],
                            'PainOrTightChest' => $row[46],
                            'SoreMuscles' => $row[47],
                            'NeckPain' => $row[48],
                            'ItchySkin' => $row[49],
                            'BlackScab' => $row[50],
                            'SkinLesions' => $row[51],
                            'DescribeLesion' => $row[52],
                            'OtherSS' => $row[53],
                            'Admitted' => $row[54],
                            'DAdmit' => $this->tDate($row[55]),
                            'OccupAnimalAgriculture' => $row[56],
                            'ExpToAnthVaccAnimal' => $row[57],
                            'ExpToAnimalProducts' => $row[58],
                            'ContactLiveDeadAnimal' => $row[59],
                            'TravelBeyondResidence' => $row[60],
                            'WorkInLaboratory' => $row[61],
                            'HHMembersExpSimilarSymp' => $row[62],
                            'EatenUndercookedMeat' => $row[63],
                            'ReceivedLettersPackage' => $row[64],
                            'OpenedMailsForOthers' => $row[65],
                            'NearOpenedEnveloped' => $row[66],
                            'Cutaneous' => $row[67],
                            'CaseClassification' => $row[68],
                            'Outcome' => $row[69],
                            'DateDied' => $this->tDate($row[70]),
                            'Gastrointestinal' => $row[71],
                            'Pulmonary' => $row[72],
                            'Meningeal' => $row[73],
                            'UnknownClinicalForm' => $row[74],
                            'Specimen1' => $row[75],
                            'DateSpecimen1Taken' => $this->tDate($row[76]),
                            'ResultSpecimen1' => $row[77],
                            'DateResult1' => $this->tDate($row[78]),
                            'SpecifyOrganism1' => $row[79],
                            'Specimen2' => $row[80],
                            'DateSpecimen2Taken' => $this->tDate($row[81]),
                            'Result2' => $row[82],
                            'SpecifyOrganism2' => $row[83],
                            'ResultSpecimen2' => $row[84],
                            'DeleteRecord' => $row[85],
                            'DateResult2' => $this->tDate($row[86]),
                            'NameOfDru' => $row[87],
                            'District' => $row[88],
                            'ILHZ' => $row[89],
                            'TYPEHOSPITALCLINIC' => $row[90],
                            'SENT' => $row[91],
                            'ip' => ($row[92] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[92] == 'Y') ? $row[93] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'CHIKV') {
            foreach ($rows as $row) {
                if($row[1] == 'CAVITE' && $row[2] == 'GENERAL TRIAS') {
                    $sf = Chikv::where('EPIID', $row[67])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[67].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[67];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Chikv::create([
                            'Region' => $row[0],
                            'Province' => $row[1],
                            'Muncity' => $row[2],
                            'Streetpurok' => $row[3],
                            'DateOfEntry' => $this->tDate($row[4]),
                            'DRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[8].', '.$row[7],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'AddressOfDRU' => $row[14],
                            'ProvOfDRU' => $row[15],
                            'MuncityOfDRU' => $row[16],
                            'DOB' => $this->tDate($row[17]),
                            'Admitted' => $row[18],
                            'DAdmit' => $this->tDate($row[19]),
                            'DOnset' => $this->tDate($row[20]),
                            'CaseClass' => $row[21],
                            'DCaseRep' => $this->tDate($row[22]),
                            'DCASEINV' => $this->tDate($row[23]),
                            'DayswidSymp' => $row[24],
                            'Fever' => $row[25],
                            'Arthritis' => $row[26],
                            'Hands' => $row[27],
                            'Feet' => $row[28],
                            'Ankles' => $row[29],
                            'OthSite' => $row[30],
                            'Arthralgia' => $row[31],
                            'PeriEdema' => $row[32],
                            'SkinMani' => $row[33],
                            'SkinDesc' => $row[34],
                            'Myalgia' => $row[35],
                            'BackPain' => $row[36],
                            'Headache' => $row[37],
                            'Nausea' => $row[38],
                            'MucosBleed' => $row[39],
                            'Vomiting' => $row[40],
                            'Asthenia' => $row[41],
                            'MeningoEncep' => $row[42],
                            'OthSymptom' => $row[43],
                            'ClinDx' => $row[44],
                            'DCollected' => $this->tDate($row[45]),
                            'DSpecSent' => $this->tDate($row[46]),
                            'SerIgM' => $row[47],
                            'IgM_Res' => $row[48],
                            'DIgMRes' => $this->tDate($row[49]),
                            'SerIgG' => $row[50],
                            'IgG_Res' => $row[51],
                            'DIgGRes' => $this->tDate($row[52]),
                            'RT_PCR' => $row[53],
                            'RT_PCRRes' => $row[54],
                            'DRtPCRRes' => $this->tDate($row[55]),
                            'VirIso' => $row[56],
                            'VirIsoRes' => $row[57],
                            'DVirIsoRes' => $this->tDate($row[58]),
                            'TravHist' => $row[59],
                            'PlaceofTravel' => $row[60],
                            'Residence' => $row[61],
                            'BldTransHist' => $row[62],
                            'Reporter' => $row[63],
                            'ReporterContNum' => $row[64],
                            'Outcome' => $row[65],
                            'RegionOfDrU' => $row[66],
                            'EPIID' => $iepiid,
                            'DateDied' => $this->tDate($row[68]),
                            'Icd10Code' => $row[69],
                            'MorbidityMonth' => $row[70],
                            'MorbidityWeek' => $row[71],
                            'AdmitToEntry' => $row[72],
                            'OnsetToAdmit' => $row[73],
                            'SentinelSite' => $row[74],
                            'DeleteRecord' => $row[75],
                            'Year' => $row[76],
                            'Recstatus' => $row[77],
                            'UniqueKey' => $row[78],
                            'NameOfDru' => $row[79],
                            'ILHZ' => $row[80],
                            'District' => $row[81],
                            'Barangay' => $row[82],
                            'TYPEHOSPITALCLINIC' => $row[83],
                            'SENT' => $row[84],
                            'ip' => ($row[85] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[85] == 'Y') ? $row[86] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'CHOLERA') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Cholera::where('EPIID', $row[31])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[31].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[31];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Cholera::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'StoolCulture' => $row[22],
                            'Organism' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $this->tDate($row[25]),
                            'DateOfEntry' => $this->tDate($row[26]),
                            'AdmitToEntry' => $row[27],
                            'OnsetToAdmit' => $row[28],
                            'MorbidityMonth' => $row[29],
                            'MorbidityWeek' => $row[30],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[32],
                            'RECSTATUS' => $row[33],
                            'SentinelSite' => $row[34],
                            'DeleteRecord' => $row[35],
                            'Year' => $row[36],
                            'NameOfDru' => $row[37],
                            'District' => $row[38],
                            'ILHZ' => $row[39],
                            'Barangay' => $row[40],
                            'CASECLASS' => $row[41],
                            'TYPEHOSPITALCLINIC' => $row[42],
                            'SENT' => $row[43],
                            'ip' => ($row[44] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[44] == 'Y') ? $row[45] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'DENGUE') {
            foreach ($rows as $row)
            {
                if($row[1] == 'CAVITE' && $row[2] == 'GENERAL TRIAS') {
                    $sf = Dengue::where('EPIID', $row[28])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[28].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[28];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Dengue::create([
                            'Region' => $row[0],
                            'Province' => $row[1],
                            'Muncity' => $row[2],
                            'Streetpurok' => $row[3],
                            'DateOfEntry' => $this->tDate($row[4]),
                            'DRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[8].', '.$row[7],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'AddressOfDRU' => $row[14],
                            'ProvOfDRU' => $row[15],
                            'MuncityOfDRU' => $row[16],
                            'DOB' => $this->tDate($row[17]),
                            'Admitted' => $row[18],
                            'DAdmit' => $this->tDate($row[19]),
                            'DOnset' => $this->tDate($row[20]),
                            'Type' => $row[21],
                            'LabTest' => $row[22],
                            'LabRes' => $row[23],
                            'ClinClass' => $row[24],
                            'CaseClassification' => $row[25],
                            'Outcome' => $row[26],
                            'RegionOfDrU' => $row[27],
                            'EPIID' => $iepiid,
                            'DateDied' => $this->tDate($row[29]),
                            'Icd10Code' => $row[30],
                            'MorbidityMonth' => $row[31],
                            'MorbidityWeek' => $row[32],
                            'AdmitToEntry' => $row[33],
                            'OnsetToAdmit' => $row[34],
                            'SentinelSite' => $row[35],
                            'DeleteRecord' => $row[36],
                            'Year' => $row[37],
                            'Recstatus' => $row[38],
                            'UniqueKey' => $row[39],
                            'NameOfDru' => $row[40],
                            'ILHZ' => $row[41],
                            'District' => $row[42],
                            'Barangay' => $row[43],
                            'TYPEHOSPITALCLINIC' => $row[44],
                            'SENT' => $row[45],
                            'ip' => ($row[46] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[46] == 'Y') ? $row[47] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'DIPH') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Diph::where('EPIID', $row[32])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[32].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[32];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Diph::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'DptDoses' => $row[22],
                            'DateLastDose' => $this->tDate($row[23]),
                            'CaseClassification' => $row[24],
                            'Outcome' => $row[25],
                            'DateDied' => $this->tDate($row[26]),
                            'DateOfEntry' => $this->tDate($row[27]),
                            'AdmitToEntry' => $row[28],
                            'OnsetToAdmit' => $row[29],
                            'MorbidityMonth' => $row[30],
                            'MorbidityWeek' => $row[31],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[33],
                            'RECSTATUS' => $row[34],
                            'SentinelSite' => $row[35],
                            'DeleteRecord' => $row[36],
                            'Year' => $row[37],
                            'NameOfDru' => $row[38],
                            'District' => $row[39],
                            'ILHZ' => $row[40],
                            'Barangay' => $row[41],
                            'TYPEHOSPITALCLINIC' => $row[42],
                            'SENT' => $row[43],
                            'ip' => ($row[44] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[44] == 'Y') ? $row[45] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'HEPATITIS') {
            foreach ($rows as $row) {
                if($row[17] == 'CAVITE' && $row[16] == 'GENERAL TRIAS') {
                    $sf = Hepatitis::where('EPIID', $row[32])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[32].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[32];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Hepatitis::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'Type' => $row[22],
                            'LabResult' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $this->tDate($row[25]),
                            'TypeOfHepatitis' => $row[26],
                            'DateOfEntry' => $this->tDate($row[27]),
                            'AdmitToEntry' => $row[28],
                            'OnsetToAdmit' => $row[29],
                            'MorbidityMonth' => $row[30],
                            'MorbidityWeek' => $row[31],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[33],
                            'RECSTATUS' => $row[34],
                            'SentinelSite' => $row[35],
                            'DeleteRecord' => $row[36],
                            'Year' => $row[37],
                            'NameOfDru' => $row[38],
                            'ILHZ' => $row[39],
                            'District' => $row[40],
                            'Barangay' => $row[41],
                            'CASECLASS' => $row[42],
                            'TYPEHOSPITALCLINIC' => $row[43],
                            'SENT' => $row[44],
                            'ip' => ($row[45] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[45] == 'Y') ? $row[46] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'HFMD') {
            foreach ($rows as $row) {
                if($row[11] == 'CAVITE' && $row[12] == 'GENERAL TRIAS') {
                    $sf = Hfmd::where('EPIID', $row[50])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[50].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[50];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Hfmd::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[8].', '.$row[7],
                            'Region' => $row[10],
                            'Province' => $row[11],
                            'Muncity' => $row[12],
                            'Streetpurok' => $row[13],
                            'Sex' => $row[14],
                            'DOB' => $this->tDate($row[15]),
                            'AgeYears' => $row[16],
                            'AgeMons' => $row[17],
                            'AgeDays' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DONSET' => $this->tDate($row[21]),
                            'Fever' => $row[22],
                            'FeverOnset' => $this->tDate($row[23]),
                            'RashChar' => $row[24],
                            'RashSores' => $row[25],
                            'SoreOnset' => $this->tDate($row[26]),
                            'Palms' => $row[27],
                            'Fingers' => $row[28],
                            'FootSoles' => $row[29],
                            'Buttocks' => $row[30],
                            'MouthUlcers' => $row[31],
                            'Pain' => $row[32],
                            'Anorexia' => $row[33],
                            'BM' => $row[34],
                            'SoreThroat' => $row[35],
                            'NausVom' => $row[36],
                            'DiffBreath' => $row[37],
                            'Paralysis' => $row[38],
                            'MeningLes' => $row[39],
                            'OthSymptoms' => $row[40],
                            'AnyComp' => $row[41],
                            'Complic8' => $row[42],
                            'Investigator' => $row[43],
                            'ContactNum' => $row[44],
                            'DateOfEntry' => $this->tDate($row[45]),
                            'AdmitToEntry' => $row[46],
                            'OnsetToAdmit' => $row[47],
                            'MorbidityMonth' => $row[48],
                            'MorbidityWeek' => $row[49],
                            'EPIID' => $iepiid,
                            'ReportToInvestigation' => (is_null($row[51]) || $row[51] == '') ? 0 : $row[51],
                            'UniqueKey' => $row[52],
                            'RECSTATUS' => $row[53],
                            'Travel' => $row[54],
                            'ProbExposure' => $row[55],
                            'OthExposure' => $row[56],
                            'OtherCase' => $row[57],
                            'RectalSwabColl' => $row[58],
                            'VesicFluidColl' => $row[59],
                            'StoolColl' => $row[60],
                            'ThroatSwabColl' => $row[61],
                            'DateStooltaken' => $this->tDate($row[62]),
                            'DateStoolsent' => $this->tDate($row[63]),
                            'DateStoolRecvd' => $this->tDate($row[64]),
                            'StoolResult' => $row[65],
                            'StoolOrg' => $row[66],
                            'StoolResultD8' => $this->tDate($row[67]),
                            'VFSwabtaken' => $this->tDate($row[68]),
                            'VFSwabsent' => $this->tDate($row[69]),
                            'VFSwabRecvd' => $this->tDate($row[70]),
                            'VesicFluidRes' => $row[71],
                            'VesicFluidOrg' => $row[72],
                            'VFSwabResultD8' => $this->tDate($row[73]),
                            'ThroatSwabtaken' => $this->tDate($row[74]),
                            'ThroatSwabsent' => $this->tDate($row[75]),
                            'ThroatSwabRecvd' => $this->tDate($row[76]),
                            'ThroatSwabResult' => $row[77],
                            'ThroatSwabOrg' => $row[78],
                            'ThroatSwabResultD8' => $this->tDate($row[79]),
                            'RectalSwabtaken' => $this->tDate($row[80]),
                            'RectalSwabsent' => $this->tDate($row[81]),
                            'RectalSwabRecvd' => $this->tDate($row[82]),
                            'RectalSwabResult' => $row[83],
                            'RectalSwabOrg' => $row[84],
                            'RectalSwabResultD8' => $this->tDate($row[85]),
                            'CaseClass' => $row[86],
                            'Outcome' => $row[87],
                            'WFDiag' => $row[88],
                            'Death' => $this->tDate($row[89]),
                            'DCaseRep' => $this->tDate($row[90]),
                            'DCASEINV' => $this->tDate($row[91]),
                            'SentinelSite' => $row[92],
                            'Year' => $row[93],
                            'DeleteRecord' => $row[94],
                            'NameOfDru' => $row[95],
                            'District' => $row[96],
                            'ILHZ' => $row[97],
                            'Barangay' => $row[98],
                            'TYPEHOSPITALCLINIC' => $row[99],
                            'SENT' => $row[100],
                            'ip' => ($row[101] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[101] == 'Y') ? $row[102] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'INFLUENZA') {
            foreach ($rows as $row) {
                if($row[17] == 'CAVITE' && $row[16] == 'GENERAL TRIAS') {
                    $sf = Influenza::where('EPIID', $row[30])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[30].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[30];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Influenza::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'LabResult' => $row[22],
                            'Outcome' => $row[23],
                            'DateDied' => $this->tDate($row[24]),
                            'DateOfEntry' => $this->tDate($row[25]),
                            'AdmitToEntry' => $row[26],
                            'OnsetToAdmit' => $row[27],
                            'MorbidityMonth' => $row[28],
                            'MorbidityWeek' => $row[29],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[31],
                            'RECSTATUS' => $row[32],
                            'SentinelSite' => $row[33],
                            'DeleteRecord' => $row[34],
                            'Year' => $row[35],
                            'NameOfDru' => $row[36],
                            'District' => $row[37],
                            'ILHZ' => $row[38],
                            'Barangay' => $row[39],
                            'CASECLASS' => $row[40],
                            'TYPEHOSPITALCLINIC' => $row[41],
                            'SARI' => $row[42],
                            'Organism' => $row[43],
                            'SENT' => $row[44],
                            'ip' => ($row[45] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[45] == 'Y') ? $row[46] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'LEPTOSPIROSIS') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Leptospirosis::where('EPIID', $row[33])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[33].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[33];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Leptospirosis::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'LabRes' => $row[22],
                            'Serovar' => $row[23],
                            'CaseClassification' => $row[24],
                            'Outcome' => $row[25],
                            'DateDied' => $this->tDate($row[26]),
                            'Occupation' => $row[27],
                            'DateOfEntry' => $this->tDate($row[28]),
                            'AdmitToEntry' => $row[29],
                            'OnsetToAdmit' => $row[30],
                            'MorbidityMonth' => $row[31],
                            'MorbidityWeek' => $row[32],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[34],
                            'RECSTATUS' => $row[35],
                            'SentinelSite' => $row[36],
                            'DeleteRecord' => $row[37],
                            'Year' => $row[38],
                            'NameOfDru' => $row[39],
                            'District' => $row[40],
                            'ILHZ' => $row[41],
                            'Barangay' => $row[42],
                            'TYPEHOSPITALCLINIC' => $row[43],
                            'SENT' => $row[44],
                            'ip' => ($row[45] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[45] == 'Y') ? $row[46] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'MALARIA') {
            /*
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Aes::where('EPIID', $row[31])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[31].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[31];
                        $proceed = true;
                    }
                }
            }
            */

            foreach ($rows as $row) {
                if($row[17] == 'CAVITE' && $row[16] == 'GENERAL TRIAS') {
                    $sf = Malaria::where('EPIID', $row[31])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[31].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[31];
                        $proceed = true;
                    }
                    
                    $ctr = 0;

                    if($proceed) {
                        $c = Malaria::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'DOnset' => $this->tDate($row[19]),
                            'Parasite' => $row[20],
                            'Admitted' => $row[21],
                            'DAdmit' => $row[22],
                            'CaseClassification' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $this->tDate($row[25]),
                            'DateOfEntry' => $this->tDate($row[26]),
                            'AdmitToEntry' => $row[27],
                            'OnsetToAdmit' => $row[28],
                            'MorbidityMonth' => $row[29],
                            'MorbidityWeek' => $row[30],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[32],
                            'RECSTATUS' => $row[33],
                            'TravelHist' => $row[34],
                            'Endemicarea' => $row[35],
                            'BldTrans' => $row[36],
                            'SentinelSite' => $row[37],
                            'PHILMISSite' => $row[38],
                            'DeleteRecord' => $row[39],
                            'Year' => $row[40],
                            'NameOfDru' => $row[41],
                            'District' => $row[42],
                            'ILHZ' => $row[43],
                            'Barangay' => $row[44],
                            'TYPEHOSPITALCLINIC' => $row[45],
                            'SENT' => $row[46],
                            'ip' => ($row[47] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[47] == 'Y') ? $row[48] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'MEASLES') {
            foreach ($rows as $row) {
                if($row[12] == 'CAVITE' && $row[13] == 'GENERAL TRIAS') {
                    $sf = Measles::where('EPIID', $row[52])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[52].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[52];
                        $proceed = true;
                    }

                    $ctr = 0;

                    if($proceed) {
                        $c = Measles::create([
                        'Icd10Code' => $row[0],
                        'RegionOfDrU' => $row[1],
                        'ProvOfDRU' => $row[2],
                        'MuncityOfDRU' => $row[3],
                        'DRU' => $row[4],
                        'AddressOfDRU' => $row[5],
                        'PatientNumber' => $row[6],
                        'FirstName' => $row[7],
                        'FamilyName' => $row[8],
                        'FullName' => $row[8].', '.$row[7],
                        'Address' => $row[10],
                        'Region' => $row[11],
                        'Province' => $row[12],
                        'Muncity' => $row[13],
                        'Streetpurok' => $row[14],
                        'Sex' => $row[15],
                        'Preggy' => $row[16],
                        'WkOfPreg' => $row[17],
                        'DOB' => $this->tDate($row[18]),
                        'AgeYears' => $row[19],
                        'AgeMons' => $row[20],
                        'AgeDays' => $row[21],
                        'Admitted' => $row[22],
                        'DAdmit' => $this->tDate($row[23]),
                        'DONSET' => $this->tDate($row[24]),
                        'VitaminA' => $row[25],
                        'FeverOnset' => $this->tDate($row[26]),
                        'MeasVacc' => $row[27],
                        'Cough' => $row[28],
                        'KoplikSpot' => $row[29],
                        'MVDose' => $row[30],
                        'MRDose' => $row[31],
                        'MMRDose' => $row[32],
                        'LastVacc' => $this->tDate($row[33]),
                        'RunnyNose' => $row[34],
                        'RedEyes' => $row[35],
                        'ArthritisArthralgia' => $row[36],
                        'SwoLympNod' => $row[37],
                        'LympNodLoc' => $row[38],
                        'OthLocation' => $row[39],
                        'OthSymptoms' => $row[40],
                        'AreThereAny' => $row[41],
                        'Complications' => $row[42],
                        'Reporter' => $row[43],
                        'Investigator' => $row[44],
                        'RContactNum' => $row[45],
                        'ContactNum' => $row[46],
                        'DateOfEntry' => $this->tDate($row[47]),
                        'AdmitToEntry' => $row[48],
                        'OnsetToAdmit' => $row[49],
                        'MorbidityMonth' => $row[50],
                        'MorbidityWeek' => $row[51],
                        'EPIID' => $iepiid,
                        'ReportToInvestigation' => (is_null($row[53]) || $row[53] == '') ? 0 : $row[53],
                        'UniqueKey' => $row[54],
                        'RECSTATUS' => $row[55],
                        'Reasons' => $row[56],
                        'OtherReasons' => $row[57],
                        'SpecialCampaigns' => $row[58],
                        'Travel' => $row[59],
                        'PlaceTravelled' => $row[60],
                        'TravTiming' => $row[61],
                        'ProbExposure' => $row[62],
                        'OtherExposure' => $row[63],
                        'OtherCase' => $row[64],
                        'RashOnset' => $this->tDate($row[65]),
                        'WholeBloodColl' => $row[66],
                        'DriedBloodColl' => $row[67],
                        'OP/NPSwabColl' => $row[68],
                        'DateWBtaken' => $this->tDate($row[69]),
                        'DateWBsent' => $this->tDate($row[70]),
                        'DateDBtaken' => $this->tDate($row[71]),
                        'DateDBsent' => $this->tDate($row[72]),
                        'OPNPSwabtaken' => $this->tDate($row[73]),
                        'OPNPSwabsent' => $this->tDate($row[74]),
                        'OPSwabPCRRes' => $row[75],
                        'OPNpSwabResult' => $row[76],
                        'DateWBRecvd' => $this->tDate($row[77]),
                        'DateDBRecvd' => $this->tDate($row[78]),
                        'OPNPSwabRecvd' => $this->tDate($row[79]),
                        'OraColColl' => $row[80],
                        'OraColD8taken' => $this->tDate($row[81]),
                        'OraColD8sent' => $this->tDate($row[82]),
                        'OraColD8Recvd' => $this->tDate($row[83]),
                        'OraColPCRRes' => $row[84],
                        'FinalClass' => $row[85],
                        'InfectionSource' => $row[86],
                        'Outcome' => $row[87],
                        'FinalDx' => $row[88],
                        'Death' => $this->tDate($row[89]),
                        'DCaseRep' => $this->tDate($row[90]),
                        'DCASEINV' => $this->tDate($row[91]),
                        'SentinelSite' => $row[92],
                        'Year' => $row[93],
                        'DeleteRecord' => $row[94],
                        'WBRubellaIgM' => $row[95],
                        'WBMeaslesIgM' => $row[96],
                        'DBMeaslesIgM' => $row[97],
                        'DBRubellaIgM' => $row[98],
                        'ContactConfirmedCase' => $row[99],
                        'ContactName' => $row[100],
                        'ContactPlace' => $row[101],
                        'ContactDate' => $this->tDate($row[102]),
                        'NameOfDru' => $row[103],
                        'District' => $row[104],
                        'ILHZ' => $row[105],
                        'Barangay' => $row[106],
                        'TYPEHOSPITALCLINIC' => $row[107],
                        'SENT' => $row[108],
                        'Labcode' => $row[109],
                        'ContactConfirmedRubella' => $row[110],
                        'TravRegion' => $row[111],
                        'TravMun' => $row[112],
                        'TravProv' => $row[113],
                        'TravBgy' => $row[114],
                        'Travelled' => $row[115],
                        'DateTrav' => $this->tDate($row[116]),
                        'Report2Inv' => $row[117],
                        'Birth2RashOnset' => $row[118],
                        'OnsetToReport' => $row[119],
                        'IP' => ($row[120] == 'Y') ? 'Y' : 'N',
                        'IPgroup' => ($row[120] == 'Y') ? $row[121] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'MENINGITIS') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Meningitis::where('EPIID', $row[30])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[30].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[30];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Meningitis::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'MuncityOfDRU' => $row[2],
                            'ProvOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'Type' => $row[22],
                            'Outcome' => $row[23],
                            'DateDied' => $this->tDate($row[24]),
                            'DateOfEntry' => $this->tDate($row[25]),
                            'AdmitToEntry' => $row[26],
                            'OnsetToAdmit' => $row[27],
                            'MorbidityMonth' => $row[28],
                            'MorbidityWeek' => $row[29],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[31],
                            'RECSTATUS' => $row[32],
                            'LabResult' => $row[33],
                            'Organism' => $row[34],
                            'SentinelSite' => $row[35],
                            'DeleteRecord' => $row[36],
                            'Year' => $row[37],
                            'NameOfDru' => $row[38],
                            'District' => $row[39],
                            'ILHZ' => $row[40],
                            'Barangay' => $row[41],
                            'CASECLASS' => $row[42],
                            'TYPEHOSPITALCLINIC' => $row[43],
                            'SENT' => $row[44],
                            'ip' => ($row[45] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[45] == 'Y') ? $row[46] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'MENINGO') {
            

            foreach ($rows as $row) {
                if($row[14] == 'CAVITE' && $row[13] == 'GENERAL TRIAS') {
                    $sf = Meningo::where('EPIID', $row[32])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[10] && $sf->FirstName == $row[9]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[32].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[32];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Meningo::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'NameOfDru' => $row[4],
                            'DRU' => $row[5],
                            'AddressOfDRU' => $row[6],
                            'SentinelSite' => $row[7],
                            'PatientNumber' => $row[8],
                            'FirstName' => $row[9],
                            'FamilyName' => $row[10],
                            'FullName' => $row[10].', '.$row[9],
                            'Region' => $row[12],
                            'Muncity' => $row[13],
                            'Province' => $row[14],
                            'Streetpurok' => $row[15],
                            'Sex' => $row[16],
                            'DOB' => $this->tDate($row[17]),
                            'AgeYears' => $row[18],
                            'AgeMons' => $row[19],
                            'AgeDays' => $row[20],
                            'Occupation' => $row[21],
                            'Workplace' => $row[22],
                            'WrkplcAddr' => $row[23],
                            'SchlAddr' => $row[24],
                            'School' => $row[25],
                            'Year' => $row[26],
                            'DateOfEntry' => $this->tDate($row[27]),
                            'AdmitToEntry' => $row[28],
                            'OnsetToAdmit' => $row[29],
                            'MorbidityMonth' => $row[30],
                            'MorbidityWeek' => $row[31],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[33],
                            'RECSTATUS' => $row[34],
                            'Admitted' => $row[35],
                            'DAdmit' => $this->tDate($row[36]),
                            'DOnset' => $this->tDate($row[37]),
                            'Fever' => $row[38],
                            'Seizure' => $row[39],
                            'Malaise' => $row[40],
                            'Headache' => $row[41],
                            'StiffNeck' => $row[42],
                            'Cough' => $row[43],
                            'Rash' => $row[44],
                            'Vomiting' => $row[45],
                            'SoreThroat' => $row[46],
                            'Petechia' => $row[47],
                            'SensoriumCh' => $row[48],
                            'RunnyNose' => $row[49],
                            'Purpura' => $row[50],
                            'Drowsiness' => $row[51],
                            'Dyspnea' => $row[52],
                            'Othlesions' => $row[53],
                            'OtherSS' => $row[54],
                            'ClinicalPres' => $row[55],
                            'CaseClassification' => $row[56],
                            'Outcome' => $row[57],
                            'DateDied' => $this->tDate($row[58]),
                            'Bld_CSF' => $row[59],
                            'Antibiotics' => $row[60],
                            'CSFSpecimen' => $row[61],
                            'CultureDone' => $row[62],
                            'DateCSFTakenCulture' => $this->tDate($row[63]),
                            'CSFCultureResult' => $row[64],
                            'DateCSFCultureResult' => $this->tDate($row[65]),
                            'CSFCultureOrganism' => $row[66],
                            'LatexAggluDone' => $row[67],
                            'DateCSFTakenLatex' => $this->tDate($row[68]),
                            'CSFLatexResult' => $row[69],
                            'DateCSFLatexResult' => $this->tDate($row[70]),
                            'CSFLatexOrganism' => $row[71],
                            'GramStainDone' => $row[72],
                            'CSFGramStainResult' => $row[73],
                            'DateCSFTakenGramstain' => $this->tDate($row[75]),
                            'GramStainOrganism' => $row[75],
                            'BloodSpecimen' => $row[76],
                            'BloodCultureDone' => $row[77],
                            'BloodCultureResult' => $row[78],
                            'DateBloodCultureResult' => $this->tDate($row[79]),
                            'DateBloodTakenCulture' => $this->tDate($row[80]),
                            'BloodCultureOrganism' => $row[81],
                            'DateCSFGramResult' => $this->tDate($row[82]),
                            'Interact' => $row[83],
                            'ContactName' => $row[84],
                            'SuspName' => $row[85],
                            'SuspAddress' => $row[86],
                            'PlaceInteract' => $row[87],
                            'DateInteract' => $this->tDate($row[88]),
                            'DaysNum' => $row[89],
                            'PtTravel' => $row[90],
                            'PlacePtTravel' => $row[91],
                            'ContactTravel' => $row[92],
                            'PlaceContactTravel' => $row[93],
                            'AttendSocicalGather' => $row[94],
                            'PlaceSocialGather' => $row[95],
                            'PatientURTI' => $row[96],
                            'ContactURTI' => $row[97],
                            'District' => $row[98],
                            'InterLocal' => $row[99],
                            'Barangay' => $row[100],
                            'TYPEHOSPITALCLINIC' => $row[101],
                            'DELETERECORD' => $row[102],
                            'SENT' => $row[103],
                            'ip' => ($row[104] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[104] == 'Y') ? $row[105] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'NNT') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Nnt::where('EPIID', $row[36])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[36].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[36];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Nnt::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'RecentAcuteWound' => $row[22],
                            'WoundSite' => $row[23],
                            'WoundType' => $row[24],
                            'OtherWound' => $row[25],
                            'TetanusToxoid' => $row[26],
                            'TetanusAntitoxin' => $row[27],
                            'SkinLesion' => $row[28],
                            'Outcome' => $row[29],
                            'DateDied' => $this->tDate($row[30]),
                            'DateOfEntry' => $this->tDate($row[31]),
                            'AdmitToEntry' => $row[32],
                            'OnsetToAdmit' => $row[33],
                            'MorbidityMonth' => $row[34],
                            'MorbidityWeek' => $row[35],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[37],
                            'RECSTATUS' => $row[38],
                            'SentinelSite' => $row[39],
                            'DeleteRecord' => $row[40],
                            'Year' => $row[41],
                            'NameOfDru' => $row[42],
                            'District' => $row[43],
                            'ILHZ' => $row[44],
                            'Barangay' => $row[45],
                            'TYPEHOSPITALCLINIC' => $row[46],
                            'SENT' => $row[47],
                            'ip' => ($row[48] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[48] == 'Y') ? $row[49] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'NT') {
            foreach ($rows as $row) {
                if($row[12] == 'CAVITE' && $row[13] == 'GENERAL TRIAS') {
                    $sf = Nt::where('EPIID', $row[39])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[39].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[39];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Nt::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[8].', '.$row[7],
                            'Address' => $row[10],
                            'Region' => $row[11],
                            'Province' => $row[12],
                            'Muncity' => $row[13],
                            'Streetpurok' => $row[14],
                            'Sex' => $row[15],
                            'DOB' => $this->tDate($row[16]),
                            'AgeYears' => $row[17],
                            'AgeMons' => $row[18],
                            'AgeDays' => $row[19],
                            'Admitted' => $row[20],
                            'DAdmit' => $row[21],
                            'DateOfReport' => $this->tDate($row[22]),
                            'DateOfInvestigation' => $this->tDate($row[23]),
                            'Investigator' => $row[24],
                            'ContactNum' => $row[25],
                            'First2days' => $row[26],
                            'After2days' => $row[27],
                            'FinalDx' => $row[28],
                            'Trismus' => $row[29],
                            'ClenFis' => $row[30],
                            'Opistho' => $row[31],
                            'StumpInf' => $row[32],
                            'Year' => $row[33],
                            'DateOfEntry' => $this->tDate($row[34]),
                            'AdmitToEntry' => $row[35],
                            'OnsetToAdmit' => $row[36],
                            'MorbidityMonth' => $row[37],
                            'MorbidityWeek' => $row[38],
                            'EPIID' => $iepiid,
                            'ReportToInvestigation' => (is_null($row[40]) || $row[40] == '') ? 0 : $row[40],
                            'UniqueKey' => $row[41],
                            'RECSTATUS' => $row[42],
                            'TotPreg' => $row[43],
                            'Livebirths' => $row[44],
                            'TTDose' => $row[45],
                            'LivingKids' => $row[46],
                            'LastDoseGiven' => $this->tDate($row[47]),
                            'DosesGiven' => $row[48],
                            'PreVisits' => $row[49],
                            'ImmunStatRep' => $row[50],
                            'FirstPV' => $this->tDate($row[51]),
                            'ChldProt' => $row[52],
                            'PNCHist' => $row[53],
                            'Reason' => $row[54],
                            'PlaceDel' => $row[55],
                            'OtherPlaceDelivery' => $row[56],
                            'NameAddressHospital' => $row[57],
                            'OtherInstrument' => $row[58],
                            'DelAttnd' => $row[59],
                            'OtherAttendant' => $row[60],
                            'CordCut' => $row[61],
                            'StumpTreat' => $row[62],
                            'OtherMaterials' => $row[63],
                            'FinalClass' => $row[64],
                            'Outcome' => $row[65],
                            'DateDied' => $this->tDate($row[66]),
                            'DONSET' => $this->tDate($row[67]),
                            'Mother' => $row[68],
                            'DOBtoOnset' => $row[69],
                            'SentinelSite' => $row[70],
                            'DeleteRecord' => $row[71],
                            'NameOfDru' => $row[72],
                            'District' => $row[73],
                            'ILHZ' => $row[74],
                            'Barangay' => $row[75],
                            'TYPEHOSPITALCLINIC' => $row[76],
                            'SENT' => $row[77],
                            'ip' => ($row[78] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[78] == 'Y') ? $row[79] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'PERT') {
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Pert::where('EPIID', $row[32])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[32].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[32];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Pert::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'DptDoses' => $row[22],
                            'DateLastDose' => $this->tDate($row[23]),
                            'CaseClassification' => $row[24],
                            'Outcome' => $row[25],
                            'DateDied' => $this->tDate($row[26]),
                            'DateOfEntry' => $this->tDate($row[27]),
                            'AdmitToEntry' => $row[28],
                            'OnsetToAdmit' => $row[29],
                            'MorbidityMonth' => $row[30],
                            'MorbidityWeek' => $row[31],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[33],
                            'RECSTATUS' => $row[34],
                            'SentinelSite' => $row[35],
                            'DeleteRecord' => $row[36],
                            'Year' => $row[37],
                            'NameOfDru' => $row[38],
                            'District' => $row[39],
                            'ILHZ' => $row[40],
                            'Barangay' => $row[41],
                            'TYPEHOSPITALCLINIC' => $row[42],
                            'SENT' => $row[43],
                            'ip' => ($row[44] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[44] == 'Y') ? $row[45] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'PSP') {
            foreach ($rows as $row) {
                if($row[12] == 'CAVITE' && $row[13] == 'GENERAL TRIAS') {
                    $sf = Psp::where('EPIID', $row[28])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[28].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[28];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Psp::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'SentinelSite' => $row[6],
                            'PatientNumber' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'FullName' => $row[9].', '.$row[8],
                            'Region' => $row[11],
                            'Province' => $row[12],
                            'Muncity' => $row[13],
                            'Streetpurok' => $row[14],
                            'Sex' => $row[15],
                            'DOB' => $this->tDate($row[16]),
                            'AgeYears' => $row[17],
                            'AgeMons' => $row[18],
                            'AgeDays' => $row[19],
                            'Admitted' => $row[20],
                            'DAdmit' => $this->tDate($row[21]),
                            'Year' => $row[22],
                            'DateOfEntry' => $this->tDate($row[23]),
                            'AdmitToEntry' => $row[24],
                            'OnsetToAdmit' => $row[25],
                            'MorbidityMonth' => $row[26],
                            'MorbidityWeek' => $row[27],
                            'EPIID' => $iepiid,
                            'ReportToInvestigation' => (is_null($row[29]) || $row[29] == '') ? 0 : $row[29],
                            'UniqueKey' => $row[30],
                            'RECSTATUS' => $row[31],
                            'DOnset' => $this->tDate($row[32]),
                            'PlaceHarvested' => $row[33],
                            'HHMealShare' => $row[34],
                            'CaseClassification' => $row[35],
                            'Outcome' => $row[36],
                            'DateDied' => $this->tDate($row[37]),
                            'DeleteRecord' => $row[38],
                            'NameOfDru' => $row[39],
                            'District' => $row[40],
                            'ILHZ' => $row[41],
                            'Barangay' => $row[42],
                            'TYPEHOSPITALCLINIC' => $row[43],
                            'SENT' => $row[44],
                            'ip' => ($row[45] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[45] == 'Y') ? $row[46] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'RABIES') {
            foreach ($rows as $row) {
                if($row[12] == 'CAVITE' && $row[13] == 'GENERAL TRIAS') {
                    $sf = Rabies::where('EPIID', $row[32])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[32].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[32];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Rabies::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'SentinelSite' => $row[6],
                            'PatientNumber' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'FullName' => $row[9].', '.$row[8],
                            'Region' => $row[11],
                            'Province' => $row[12],
                            'Muncity' => $row[13],
                            'Streetpurok' => $row[14],
                            'Sex' => $row[15],
                            'DOB' => $this->tDate($row[16]),
                            'AgeYears' => $row[17],
                            'AgeMons' => $row[18],
                            'AgeDays' => $row[19],
                            'Weight' => $row[20],
                            'Admitted' => $row[21],
                            'DAdmit' => $this->tDate($row[22]),
                            'DOnset' => $this->tDate($row[23]),
                            'Outcome' => $row[24],
                            'DateDied' => $this->tDate($row[25]),
                            'Year' => $row[26],
                            'DateOfEntry' => $this->tDate($row[27]),
                            'AdmitToEntry' => $row[28],
                            'OnsetToAdmit' => $row[29],
                            'MorbidityMonth' => $row[30],
                            'MorbidityWeek' => $row[31],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[33],
                            'RECSTATUS' => $row[34],
                            'PlaceOfIncidence' => $row[35],
                            'TypeOfExposure' => $row[36],
                            'Category' => $row[37],
                            'BiteSite' => $row[38],
                            'OtherTypeOfExposure' => $row[39],
                            'DateBitten' => $this->tDate($row[40]),
                            'TypeOfAnimal' => $row[41],
                            'OtherTypeOfAnimal' => $row[42],
                            'LabDiagnosis' => $row[43],
                            'LabResult' => $row[44],
                            'AnimalStatus' => $row[45],
                            'OtherAnimalStatus' => $row[46],
                            'DateVaccStarted' => $this->tDate($row[47]),
                            'Vaccine' => $row[48],
                            'AdminRoute' => $row[49],
                            'PostExposureComplete' => $row[50],
                            'AnimalVaccination' => $row[51],
                            'WoundCleaned' => $row[52],
                            'Rabiesvaccine' => $row[53],
                            'DeleteRecord' => $row[54],
                            'Outcomeanimal' => $row[55],
                            'RIG' => $row[56],
                            'NameOfDru' => $row[57],
                            'District' => $row[58],
                            'ILHZ' => $row[59],
                            'Barangay' => $row[60],
                            'CASECLASS' => $row[61],
                            'TYPEHOSPITALCLINIC' => $row[62],
                            'SENT' => $row[63],
                            'ip' => ($row[64] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[64] == 'Y') ? $row[65] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'ROTAVIRUS') {
            foreach ($rows as $row) {
                if($row[18] == 'CAVITE' && $row[19] == 'GENERAL TRIAS') {
                    $sf = Rotavirus::where('EPIID', $row[67])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[10] && $sf->FirstName == $row[9]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[67].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[67];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Rotavirus::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'DRUContactNum' => $row[6],
                            'PatientNumber' => $row[7],
                            'FullName' => $row[10].', '.$row[9],
                            'FirstName' => $row[9],
                            'FamilyName' => $row[10],
                            'MidName' => $row[11],
                            'AgeYears' => $row[12],
                            'AgeMons' => $row[13],
                            'AgeDays' => $row[14],
                            'Sex' => $row[15],
                            'DOB' => $this->tDate($row[16]),
                            'Region' => $row[17],
                            'Province' => $row[18],
                            'Muncity' => $row[19],
                            'Streetpurok' => $row[20],
                            'NHTS' => $row[21],
                            'IVTherapy' => $row[22],
                            'Vomiting' => $row[23],
                            'Admitted' => $row[24],
                            'DAdmit' => $this->tDate($row[25]),
                            'D_ONSET' => $this->tDate($row[26]),
                            'DateRep' => $this->tDate($row[27]),
                            'DateInv' => $this->tDate($row[28]),
                            'Investigator' => $row[29],
                            'ContactNum' => $row[30],
                            'InvDesignation' => $row[31],
                            'Fever' => $row[32],
                            'Temp' => $row[33],
                            'V_ONSET' => $this->tDate($row[34]),
                            'AdmDx' => $row[35],
                            'FinalDx' => $row[36],
                            'DegDehy' => $row[37],
                            'DiarrCases' => $row[38],
                            'Community' => $row[39],
                            'HHold' => $row[40],
                            'School' => $row[41],
                            'RotaVirus' => $row[42],
                            'RVDose' => $row[43],
                            'D8RV1stDose' => $this->tDate($row[44]),
                            'D8RVLastDose' => $this->tDate($row[45]),
                            'StoolColl' => $row[46],
                            'D8StoolTaken' => $this->tDate($row[47]),
                            'D8StoolSent' => $this->tDate($row[48]),
                            'D8StoolRecvd' => $this->tDate($row[49]),
                            'Amount' => $row[50],
                            'StoolQty' => $row[51],
                            'ElisaRes' => $row[52],
                            'D8ElisaRes' => $this->tDate($row[53]),
                            'PCRRes' => $row[54],
                            'OthPCRRes' => $row[55],
                            'Genotype' => $row[56],
                            'D8PCRRes' => $this->tDate($row[57]),
                            'SpecCond' => $row[58],
                            'DateDisch' => $this->tDate($row[59]),
                            'Outcome' => $row[60],
                            'DateDied' => $this->tDate($row[61]),
                            'DateOfEntry' => $this->tDate($row[62]),
                            'AdmitToEntry' => $row[63],
                            'OnsetToAdmit' => $row[64],
                            'MorbidityMonth' => $row[65],
                            'MorbidityWeek' => $row[66],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[68],
                            'RECSTATUS' => $row[69],
                            'SentinelSite' => $row[70],
                            'DeleteRecord' => $row[71],
                            'Year' => $row[72],
                            'NameOfDru' => $row[73],
                            'ILHZ' => $row[74],
                            'District' => $row[75],
                            'Barangay' => $row[76],
                            'TYPEHOSPITALCLINIC' => $row[77],
                            'SENT' => $row[78],
                            'hospdiarrhea' => $row[79],
                            'Datehosp' => $row[80],
                            'classification' => $row[81],
                            'ip' => ($row[82] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[82] == 'Y') ? $row[83] : NULL,
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'TYPHOID') {
            /*
            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Aes::where('EPIID', $row[31])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[31].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[31];
                        $proceed = true;
                    }
                }
            }
            */

            foreach ($rows as $row) {
                if($row[16] == 'CAVITE' && $row[17] == 'GENERAL TRIAS') {
                    $sf = Typhoid::where('EPIID', $row[31])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[9] && $sf->FirstName == $row[8]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[31].strtoupper(Str::random(3));
                            $proceed = true;
                        }
                    }
                    else {
                        $iepiid = $row[31];
                        $proceed = true;
                    }

                    if($proceed) {
                        $c = Typhoid::create([
                            'Icd10Code' => $row[0],
                            'RegionOfDrU' => $row[1],
                            'ProvOfDRU' => $row[2],
                            'MuncityOfDRU' => $row[3],
                            'DRU' => $row[4],
                            'AddressOfDRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FullName' => $row[9].', '.$row[8],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $this->tDate($row[14]),
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $this->tDate($row[20]),
                            'DOnset' => $this->tDate($row[21]),
                            'LabResult' => $row[22],
                            'Organism' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $this->tDate($row[25]),
                            'DateOfEntry' => $this->tDate($row[26]),
                            'AdmitToEntry' => $row[27],
                            'OnsetToAdmit' => $row[28],
                            'MorbidityMonth' => $row[29],
                            'MorbidityWeek' => $row[30],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[32],
                            'RECSTATUS' => $row[33],
                            'SentinelSite' => $row[34],
                            'DeleteRecord' => $row[35],
                            'Year' => $row[36],
                            'NameOfDru' => $row[37],
                            'District' => $row[38],
                            'ILHZ' => $row[39],
                            'Barangay' => $row[40],
                            'CASECLASS' => $row[41],
                            'TYPEHOSPITALCLINIC' => $row[42],
                            'SENT' => $row[43],
                            'ip' => ($row[44] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[44] == 'Y') ? $row[45] : NULL,
                        ]);
                    }
                }
            }
        }
        else {

        }
    }

    public function startRow(): int {
        return 2;
    }
}
