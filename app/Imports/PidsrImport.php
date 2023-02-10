<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Abd;
use App\Models\Aes;
use App\Models\Afp;
use App\Models\Ahf;
use App\Models\Ames;
use App\Models\Diph;
use App\Models\Hfmd;
use App\Models\Chikv;
use App\Models\Dengue;
use App\Models\Anthrax;
use App\Models\Cholera;
use App\Models\Malaria;
use App\Models\Measles;
use App\Models\Meningo;
use App\Models\Hepatitis;
use App\Models\Influenza;
use App\Models\Meningitis;
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
                            $iepiid = $row[31].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[11],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'StoolCulture' => $row[22],
                            'Organism' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $row[25],
                            'DateOfEntry' => $row[26],
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
                            $iepiid = $row[31].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'LabResult' => $row[22],
                            'Organism' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $row[25],
                            'DateOfEntry' => $row[26],
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
                            'ip' => $row[44],
                            'ipgroup' => $row[45],
                            'sari' => $row[46],
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'AES') {
            foreach ($rows as $row) {
                if($row[11] == 'CAVITE' && $row[12] == 'GENERAL TRIAS') {
                    $sf = Afp::where('EPIID', $row[28])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[28].Str::random(3);
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
                            'FullName' => $row[9],
                            'Region' => $row[10],
                            'Province' => $row[11],
                            'Muncity' => $row[12],
                            'Streetpurok' => $row[13],
                            'Sex' => $row[14],
                            'DOB' => $row[15],
                            'AgeYears' => $row[16],
                            'AgeMons' => $row[17],
                            'AgeDays' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DateOfReport' => $row[21],
                            'DateOfInvestigation' => $row[22],
                            'DateOfEntry' => $row[23],
                            'AdmitToEntry' => $row[24],
                            'OnsetToAdmit' => $row[25],
                            'MorbidityMonth' => $row[26],
                            'MorbidityWeek' => $row[27],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[29],
                            'RECSTATUS' => $row[30],
                            'Fever' => $row[31],
                            'DONSETP' => $row[32],
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
                            'FrmTrvlDate' => $row[65],
                            'OtherCases' => $row[66],
                            'InjTrauAnibite' => $row[67],
                            'SpecifyInjTrauAnibite' => $row[68],
                            'Investigator' => $row[69],
                            'ContactNum' => $row[70],
                            'OPVDoses' => $row[71],
                            'DateLastDose' => $row[72],
                            'HotCase' => $row[73],
                            'FirstStoolSpec' => $row[74],
                            'DStool1Taken' => $row[75],
                            'DStool2Taken' => $row[76],
                            'DStool1Sent' => $row[77],
                            'DStool2Sent' => $row[78],
                            'Stool1Result' => $row[79],
                            'Stool2Result' => $row[80],
                            'ExpDffup' => $row[81],
                            'ActDffp' => $row[82],
                            'PhyExam' => $row[83],
                            'ReasonND' => $row[84],
                            'DateDied' => $row[85],
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
                            'DateClass' => $row[96],
                            'VAPP' => $row[97],
                            'CCriteria' => $row[98],
                            'FinalDx' => $row[99],
                            'OtherDiagnosis' => $row[100],
                            'ReportToInvestigation' => $row[101],
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
                            'ActDffup' => $row[112],
                            'DStool1Received' => $row[113],
                            'DStool2Received' => $row[114],
                            'Stool1RecResult' => $row[115],
                            'Stool2RecResult' => $row[116],
                            'SecndStoolSpec' => $row[117],
                            'DateRep' => $row[118],
                            'DateInv' => $row[119],
                            'Year' => $row[120],
                            'SentinelSite' => $row[121],
                            'ClinicalSummary' => $row[122],
                            'DeleteRecord' => $row[123],
                            'NameOfDru' => $row[124],
                            'ToTrvldate' => $row[125],
                            'ILHZ' => $row[126],
                            'District' => $row[127],
                            'Barangay' => $row[128],
                            'TYPEHOSPITALCLINIC' => $row[129],
                            'OCCUPATION' => $row[130],
                            'SENT' => $row[131],
                            'ip' => $row[132],
                            'ipgroup' => $row[133],
                            'Outcome' => $row[134],
                            'DateOutcomeDied' => $row[135],
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
                            $iepiid = $row[33].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'PCRRes' => $row[22],
                            'PCROrganism' => $row[23],
                            'BloodCultRes' => $row[24],
                            'CultureOrganism' => $row[25],
                            'Outcome' => $row[26],
                            'DateDied' => $row[27],
                            'DateOfEntry' => $row[28],
                            'AdmitToEntry' => $row[29],
                            'OnsetToAdmit' => $row[30],
                            'MorbidityMonth' => $row[31],
                            'MorbidityWeek' => $row[32],
                            'EPIID' => $iepiid,
                            'RECSTATUS' => $row[34],
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
                            'ip' => $row[45],
                            'ipgroup' => $row[46],
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
                            $iepiid = $row[119].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'NHTS' => $row[19],
                            'Admitted' => $row[20],
                            'DAdmit' => $row[21],
                            'DOnset' => $row[22],
                            'DateRep' => $row[23],
                            'DateInv' => $row[24],
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
                            'VacJeDate' => $row[37],
                            'JEDose' => $row[38],
                            'Hib' => $row[39],
                            'VacHibDate' => $row[40],
                            'HibDose' => $row[41],
                            'PCV10' => $row[42],
                            'VacPCV10Date' => $row[43],
                            'PCV10Dose' => $row[44],
                            'PCV13' => $row[45],
                            'VacPCV13Date' => $row[46],
                            'PCV13Dose' => $row[47],
                            'MeningoVacc' => $row[48],
                            'VacMeningoDate' => $row[49],
                            'MeningoVaccDose' => $row[50],
                            'MeasVacc' => $row[51],
                            'VacMeasDate' => $row[52],
                            'MeasVaccDose' => $row[53],
                            'MMR' => $row[54],
                            'VacMMRDate' => $row[55],
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
                            'FrmTrvlDate' => $row[67],
                            'ToTrvlDate' => $row[68],
                            'CSFColl' => $row[69],
                            'D8CSFTaken' => $row[70],
                            'TymCSFTaken' => $row[71],
                            'D8CSFHospLab' => $row[72],
                            'TymCSFHospLab' => $row[73],
                            'CSFAppearance' => $row[74],
                            'GramStain' => $row[75],
                            'GramStainResult' => $row[76],
                            'culture' => $row[77],
                            'CultureResult' => $row[78],
                            'OtherTest' => $row[79],
                            'OtherTestResult' => $row[80],
                            'D8CSFSentRITM' => $row[81],
                            'D8CSFReceivedRITM' => $row[82],
                            'CSFSampVol' => $row[83],
                            'D8CSFTesting' => $row[84],
                            'CSFResult' => $row[85],
                            'Serum1Col' => $row[86],
                            'D8Serum1Taken' => $row[87],
                            'D8Serum1HospLab' => $row[88],
                            'D8Serum1Sent' => $row[89],
                            'D8Seruml1Received' => $row[90],
                            'Serum1SampVol' => $row[91],
                            'D8Serum1Testing' => $row[92],
                            'Serum1Result' => $row[93],
                            'Serum2Col' => $row[94],
                            'D8Serum2Taken' => $row[95],
                            'D8Serum2HospLab' => $row[96],
                            'D8Serum2Sent' => $row[97],
                            'D8Serum2Received' => $row[98],
                            'Serum2SampVol' => $row[99],
                            'D8Serum2testing' => $row[100],
                            'Serum2Result' => $row[101],
                            'AESCaseClass' => $row[102],
                            'BmCaseClass' => $row[103],
                            'AESOtherAgent' => $row[104],
                            'ConfirmBMTest' => $row[105],
                            'FinalDiagnosis' => $row[106],
                            'Outcome' => $row[107],
                            'DateOfEntry' => $row[106],
                            'DateDisch' => $row[107],
                            'DateDied' => $row[108],
                            'RecoverSequelae' => $row[109],
                            'SequelaeSpecs' => $row[110],
                            'TransTo' => $row[111],
                            'HAMA' => $row[112],
                            'AdmitToEntry' => $row[113],
                            'OnsetToAdmit' => $row[114],
                            'MorbidityMonth' => $row[115],
                            'MorbidityWeek' => $row[116],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[118],
                            'RECSTATUS' => $row[119],
                            'SentinelSite' => $row[120],
                            'DeleteRecord' => $row[121],
                            'Year' => $row[122],
                            'NameOfDru' => $row[123],
                            'ILHZ' => $row[124],
                            'District' => $row[125],
                            'Barangay' => $row[126],
                            'CASECLASS' => $row[127],
                            'TYPEHOSPITALCLINIC' => $row[128],
                            'SENT' => $row[129],
                            'ip' => $row[130],
                            'ipgroup' => $row[131],
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
                            $iepiid = $row[27].Str::random(3);
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
                            'FullName' => $row[10],
                            'Region' => $row[11],
                            'Province' => $row[12],
                            'Muncity' => $row[13],
                            'Barangay' => $row[14],
                            'Streetpurok' => $row[15],
                            'Sex' => $row[16],
                            'DOB' => $row[17],
                            'AgeYears' => $row[18],
                            'AgeMons' => $row[19],
                            'AgeDays' => $row[20],
                            'Year' => $row[21],
                            'DateOfEntry' => $row[22],
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
                            'DOnset' => $row[33],
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
                            'DAdmit' => $row[55],
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
                            'DateDied' => $row[70],
                            'Gastrointestinal' => $row[71],
                            'Pulmonary' => $row[72],
                            'Meningeal' => $row[73],
                            'UnknownClinicalForm' => $row[74],
                            'Specimen1' => $row[75],
                            'DateSpecimen1Taken' => $row[76],
                            'ResultSpecimen1' => $row[77],
                            'DateResult1' => $row[78],
                            'SpecifyOrganism1' => $row[79],
                            'Specimen2' => $row[80],
                            'DateSpecimen2Taken' => $row[81],
                            'Result2' => $row[82],
                            'SpecifyOrganism2' => $row[83],
                            'ResultSpecimen2' => $row[84],
                            'DeleteRecord' => $row[85],
                            'DateResult2' => $row[86],
                            'NameOfDru' => $row[87],
                            'District' => $row[88],
                            'ILHZ' => $row[89],
                            'TYPEHOSPITALCLINIC' => $row[90],
                            'SENT' => $row[91],
                            'ip' => $row[92],
                            'ipgroup' => $row[93],
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
                            $iepiid = $row[67].Str::random(3);
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
                            'DateOfEntry' => $row[4],
                            'DRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'AddressOfDRU' => $row[14],
                            'ProvOfDRU' => $row[15],
                            'MuncityOfDRU' => $row[16],
                            'DOB' => $row[17],
                            'Admitted' => $row[18],
                            'DAdmit' => $row[19],
                            'DOnset' => $row[20],
                            'CaseClass' => $row[21],
                            'DCaseRep' => $row[22],
                            'DCASEINV' => $row[23],
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
                            'DCollected' => $row[45],
                            'DSpecSent' => $row[46],
                            'SerIgM' => $row[47],
                            'IgM_Res' => $row[48],
                            'DIgMRes' => $row[49],
                            'SerIgG' => $row[50],
                            'IgG_Res' => $row[51],
                            'DIgGRes' => $row[52],
                            'RT_PCR' => $row[53],
                            'RT_PCRRes' => $row[54],
                            'DRtPCRRes' => $row[55],
                            'VirIso' => $row[56],
                            'VirIsoRes' => $row[57],
                            'DVirIsoRes' => $row[58],
                            'TravHist' => $row[59],
                            'PlaceofTravel' => $row[60],
                            'Residence' => $row[61],
                            'BldTransHist' => $row[62],
                            'Reporter' => $row[63],
                            'ReporterContNum' => $row[64],
                            'Outcome' => $row[65],
                            'RegionOfDrU' => $row[66],
                            'EPIID' => $iepiid,
                            'DateDied' => $row[68],
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
                            'ip' => $row[85],
                            'ipgroup' => $row[86],
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
                            $iepiid = $row[31].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'StoolCulture' => $row[22],
                            'Organism' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $row[25],
                            'DateOfEntry' => $row[26],
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
                            'ip' => $row[44],
                            'ipgroup' => $row[45],
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
                            $iepiid = $row[28].Str::random(3);
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
                            'DateOfEntry' => date('Y-m-d', strtotime($row[4])),
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
                            'DOB' => date('Y-m-d', strtotime($row[17])),
                            'Admitted' => $row[18],
                            'DAdmit' => date('Y-m-d', strtotime($row[19])),
                            'DOnset' => date('Y-m-d', strtotime($row[20])),
                            'Type' => $row[21],
                            'LabTest' => $row[22],
                            'LabRes' => $row[23],
                            'ClinClass' => $row[24],
                            'CaseClassification' => $row[25],
                            'Outcome' => $row[26],
                            'RegionOfDrU' => $row[27],
                            'EPIID' => $row[28],
                            'DateDied' => $row[29],
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
                            $iepiid = $row[32].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'DptDoses' => $row[22],
                            'DateLastDose' => $row[23],
                            'CaseClassification' => $row[24],
                            'Outcome' => $row[25],
                            'DateDied' => $row[26],
                            'DateOfEntry' => $row[27],
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
                            'ip' => $row[44],
                            'ipgroup' => $row[45],
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
                            $iepiid = $row[32].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'Type' => $row[22],
                            'LabResult' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $row[25],
                            'TypeOfHepatitis' => $row[26],
                            'DateOfEntry' => $row[27],
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
                            'ip' => $row[45],
                            'ipgroup' => $row[46],
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
                            $iepiid = $row[50].Str::random(3);
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
                            'FullName' => $row[9],
                            'Region' => $row[10],
                            'Province' => $row[11],
                            'Muncity' => $row[12],
                            'Streetpurok' => $row[13],
                            'Sex' => $row[14],
                            'DOB' => $row[15],
                            'AgeYears' => $row[16],
                            'AgeMons' => $row[17],
                            'AgeDays' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DONSET' => $row[21],
                            'Fever' => $row[22],
                            'FeverOnset' => $row[23],
                            'RashChar' => $row[24],
                            'RashSores' => $row[25],
                            'SoreOnset' => $row[26],
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
                            'DateOfEntry' => $row[45],
                            'AdmitToEntry' => $row[46],
                            'OnsetToAdmit' => $row[47],
                            'MorbidityMonth' => $row[48],
                            'MorbidityWeek' => $row[49],
                            'EPIID' => $row[50],
                            'ReportToInvestigation' => $row[51],
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
                            'DateStooltaken' => $row[62],
                            'DateStoolsent' => $row[63],
                            'DateStoolRecvd' => $row[64],
                            'StoolResult' => $row[65],
                            'StoolOrg' => $row[66],
                            'StoolResultD8' => $row[67],
                            'VFSwabtaken' => $row[68],
                            'VFSwabsent' => $row[69],
                            'VFSwabRecvd' => $row[70],
                            'VesicFluidRes' => $row[71],
                            'VesicFluidOrg' => $row[72],
                            'VFSwabResultD8' => $row[73],
                            'ThroatSwabtaken' => $row[74],
                            'ThroatSwabsent' => $row[75],
                            'ThroatSwabRecvd' => $row[76],
                            'ThroatSwabResult' => $row[77],
                            'ThroatSwabOrg' => $row[78],
                            'ThroatSwabResultD8' => $row[79],
                            'RectalSwabtaken' => $row[80],
                            'RectalSwabsent' => $row[81],
                            'RectalSwabRecvd' => $row[82],
                            'RectalSwabResult' => $row[83],
                            'RectalSwabOrg' => $row[84],
                            'RectalSwabResultD8' => $row[85],
                            'CaseClass' => $row[86],
                            'Outcome' => $row[87],
                            'WFDiag' => $row[88],
                            'Death' => $row[89],
                            'DCaseRep' => $row[90],
                            'DCASEINV' => $row[91],
                            'SentinelSite' => $row[92],
                            'Year' => $row[93],
                            'DeleteRecord' => $row[94],
                            'NameOfDru' => $row[95],
                            'District' => $row[96],
                            'ILHZ' => $row[97],
                            'Barangay' => $row[98],
                            'TYPEHOSPITALCLINIC' => $row[99],
                            'SENT' => $row[100],
                            'ip' => $row[101],
                            'ipgroup' => $row[102],
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
                            $iepiid = $row[30].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'LabResult' => $row[22],
                            'Outcome' => $row[23],
                            'DateDied' => $row[24],
                            'DateOfEntry' => $row[25],
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
                            'ip' => $row[45],
                            'ipgroup' => $row[46],
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
                            $iepiid = $row[33].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Province' => $row[16],
                            'Muncity' => $row[17],
                            'Streetpurok' => $row[18],
                            'Admitted' => $row[19],
                            'DAdmit' => $row[20],
                            'DOnset' => $row[21],
                            'LabRes' => $row[22],
                            'Serovar' => $row[23],
                            'CaseClassification' => $row[24],
                            'Outcome' => $row[25],
                            'DateDied' => $row[26],
                            'Occupation' => $row[27],
                            'DateOfEntry' => $row[28],
                            'AdmitToEntry' => $row[29],
                            'OnsetToAdmit' => $row[30],
                            'MorbidityMonth' => $row[31],
                            'MorbidityWeek' => $row[32],
                            'EPIID' => $row[33],
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
                            'ip' => $row[45],
                            'ipgroup' => $row[46],
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
                            $iepiid = $row[31].Str::random(3);
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
                            $iepiid = $row[31].Str::random(3);
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
                            'FullName' => $row[7],
                            'FirstName' => $row[8],
                            'FamilyName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'DOB' => $row[14],
                            'Region' => $row[15],
                            'Muncity' => $row[16],
                            'Province' => $row[17],
                            'Streetpurok' => $row[18],
                            'DOnset' => $row[19],
                            'Parasite' => $row[20],
                            'Admitted' => $row[21],
                            'DAdmit' => $row[22],
                            'CaseClassification' => $row[23],
                            'Outcome' => $row[24],
                            'DateDied' => $row[25],
                            'DateOfEntry' => $row[26],
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
                            'ip' => $row[47],
                            'ipgroup' => $row[48],
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'MEASLES') {
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
                            $iepiid = $row[31].Str::random(3);
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
                if($row[12] == 'CAVITE' && $row[13] == 'GENERAL TRIAS') {
                    $sf = Measles::where('EPIID', $row[52])
                    ->first();

                    if($sf) {
                        if($sf->FamilyName == $row[8] && $sf->FirstName == $row[7]) {
                            $proceed = false;
                        }
                        else {
                            $iepiid = $row[52].Str::random(3);
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
                        'FullName' => $row[9],
                        'Address' => $row[10],
                        'Region' => $row[11],
                        'Province' => $row[12],
                        'Muncity' => $row[13],
                        'Streetpurok' => $row[14],
                        'Sex' => $row[15],
                        'Preggy' => $row[16],
                        'WkOfPreg' => $row[17],
                        'DOB' => $row[18],
                        'AgeYears' => $row[19],
                        'AgeMons' => $row[20],
                        'AgeDays' => $row[21],
                        'Admitted' => $row[22],
                        'DAdmit' => $row[23],
                        'DONSET' => $row[24],
                        'VitaminA' => $row[25],
                        'FeverOnset' => $row[26],
                        'MeasVacc' => $row[27],
                        'Cough' => $row[28],
                        'KoplikSpot' => $row[29],
                        'MVDose' => $row[30],
                        'MRDose' => $row[31],
                        'MMRDose' => $row[32],
                        'LastVacc' => $row[33],
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
                        'RContactNum ' => $row[45],
                        'ContactNum ' => $row[46],
                        'DateOfEntry' => $row[47],
                        'AdmitToEntry' => $row[48],
                        'OnsetToAdmit' => $row[49],
                        'MorbidityMonth' => $row[50],
                        'MorbidityWeek' => $row[51],
                        'EPIID' => $row[52],
                        'ReportToInvestigation' => $row[53],
                        'UniqueKey ' => $row[54],
                        'RECSTATUS' => $row[55],
                        'Reasons' => $row[55],
                        'OtherReasons' => $row[56],
                        'SpecialCampaigns' => $row[57],
                        'Travel' => $row[58],
                        'PlaceTravelled' => $row[59],
                        'TravTiming' => $row[60],
                        'ProbExposure' => $row[61],
                        'OtherExposure' => $row[62],
                        'OtherCase' => $row[63],
                        'RashOnset' => $row[64],
                        'WholeBloodColl' => $row[65],
                        'DriedBloodColl' => $row[66],
                        'OP/NPSwabColl' => $row[67],
                        'DateWBtaken' => $row[68],
                        'DateWBsent' => $row[69],
                        'DateDBtaken' => $row[70],
                        'DateDBsent' => $row[71],
                        'OPNPSwabtaken' => $row[72],
                        'OPNPSwabsent' => $row[73],
                        'OPSwabPCRRes' => $row[74],
                        'OPNpSwabResult' => $row[75],
                        'DateWBRecvd' => $row[76],
                        'DateDBRecvd' => $row[77],
                        'OPNPSwabRecvd' => $row[78],
                        'OraColColl' => $row[79],
                        'OraColD8taken' => $row[80],
                        'OraColD8sent' => $row[81],
                        'OraColD8Recvd' => $row[82],
                        'OraColPCRRes' => $row[83],
                        'FinalClass' => $row[84],
                        'InfectionSource' => $row[85],
                        'Outcome' => $row[86],
                        'FinalDx' => $row[87],
                        'Death' => $row[88],
                        'DCaseRep' => $row[89],
                        'DCASEINV' => $row[90],
                        'SentinelSite' => $row[91],
                        'Year' => $row[92],
                        'DeleteRecord' => $row[93],
                        'WBRubellaIgM' => $row[94],
                        'WBMeaslesIgM' => $row[95],
                        'DBMeaslesIgM' => $row[96],
                        'DBRubellaIgM' => $row[97],
                        'ContactConfirmedCase' => $row[98],
                        'ContactName' => $row[99],
                        'ContactPlace' => $row[100],
                        'ContactDate' => $row[101],
                        'NameOfDru' => $row[102],
                        'District' => $row[103],
                        'ILHZ' => $row[104],
                        'Barangay' => $row[105],
                        'TYPEHOSPITALCLINIC' => $row[106],
                        'SENT' => $row[107],
                        'Labcode' => $row[108],
                        'ContactConfirmedRubella' => $row[109],
                        'TravRegion' => $row[110],
                        'TravMun' => $row[111],
                        'TravProv' => $row[112],
                        'TravBgy' => $row[113],
                        'Travelled' => $row[114],
                        'DateTrav' => $row[115],
                        'Report2Inv' => $row[116],
                        'Birth2RashOnset' => $row[117],
                        'OnsetToReport' => $row[118],
                        'IP' => $row[119],
                        'IPgroup' => $row[120],
                        ]);
                    }
                }
            }
        }
        else if($this->sd == 'MENINGITIS') {
            $c = Meningitis::create([
                'Icd10Code' => $row[],
                'RegionOfDrU' => $row[],
                'MuncityOfDRU' => $row[],
                'ProvOfDRU' => $row[],
                'DRU' => $row[],
                'AddressOfDRU' => $row[],
                'PatientNumber' => $row[],
                'FullName' => $row[],
                'FirstName' => $row[],
                'FamilyName' => $row[],
                'AgeYears' => $row[],
                'AgeMons' => $row[],
                'AgeDays' => $row[],
                'Sex' => $row[],
                'DOB' => $row[],
                'Region' => $row[],
                'Province' => $row[],
                'Muncity' => $row[],
                'Streetpurok' => $row[],
                'Admitted' => $row[],
                'DAdmit' => $row[],
                'DOnset' => $row[],
                'Type' => $row[],
                'Outcome' => $row[],
                'DateDied' => $row[],
                'DateOfEntry' => $row[],
                'AdmitToEntry' => $row[],
                'OnsetToAdmit' => $row[],
                'MorbidityMonth' => $row[],
                'MorbidityWeek' => $row[],
                'EPIID' => $row[],
                'UniqueKey' => $row[],
                'RECSTATUS' => $row[],
                'LabResult' => $row[],
                'Organism' => $row[],
                'SentinelSite' => $row[],
                'DeleteRecord' => $row[],
                'Year' => $row[],
                'NameOfDru' => $row[],
                'District' => $row[],
                'ILHZ' => $row[],
                'Barangay' => $row[],
                'CASECLASS' => $row[],
                'TYPEHOSPITALCLINIC' => $row[],
                'SENT' => $row[],
                'ip' => $row[],
                'ipgroup' => $row[],
            ]);
        }
        else if($this->sd == 'MENINGO') {
            $c = Meningo::create([
                'Icd10Code' => $row[],
                'RegionOfDrU' => $row[],
                'ProvOfDRU' => $row[],
                'MuncityOfDRU' => $row[],
                'NameOfDru' => $row[],
                'DRU' => $row[],
                'AddressOfDRU' => $row[],
                'SentinelSite' => $row[],
                'PatientNumber' => $row[],
                'FirstName' => $row[],
                'FamilyName' => $row[],
                'FullName' => $row[],
                'Region' => $row[],
                'Muncity' => $row[],
                'Province' => $row[],
                'Streetpurok' => $row[],
                'Sex' => $row[],
                'DOB' => $row[],
                'AgeYears' => $row[],
                'AgeMons' => $row[],
                'AgeDays' => $row[],
                'Occupation' => $row[],
                'Workplace' => $row[],
                'WrkplcAddr' => $row[],
                'SchlAddr' => $row[],
                'School' => $row[],
                'Year' => $row[],
                'DateOfEntry' => $row[],
                'AdmitToEntry' => $row[],
                'OnsetToAdmit' => $row[],
                'MorbidityMonth' => $row[],
                'MorbidityWeek' => $row[],
                'EPIID' => $row[],
                'UniqueKey' => $row[],
                'RECSTATUS' => $row[],
                'Admitted' => $row[],
                'DAdmit' => $row[],
                'DOnset' => $row[],
                'Fever' => $row[],
                'Seizure' => $row[],
                'Malaise' => $row[],
                'Headache' => $row[],
                'StiffNeck' => $row[],
                'Cough' => $row[],
                'Rash' => $row[],
                'Vomiting' => $row[],
                'SoreThroat' => $row[],
                'Petechia' => $row[],
                'SensoriumCh' => $row[],
                'RunnyNose' => $row[],
                'Purpura' => $row[],
                'Drowsiness' => $row[],
                'Dyspnea' => $row[],
                'Othlesions' => $row[],
                'OtherSS' => $row[],
                'ClinicalPres' => $row[],
                'CaseClassification' => $row[],
                'Outcome' => $row[],
                'DateDied' => $row[],
                'Bld_CSF' => $row[],
                'Antibiotics' => $row[],
                'CSFSpecimen' => $row[],
                'CultureDone' => $row[],
                'DateCSFTakenCulture' => $row[],
                'CSFCultureResult' => $row[],
                'DateCSFCultureResult' => $row[],
                'CSFCultureOrganism' => $row[],
                'LatexAggluDone' => $row[],
                'DateCSFTakenLatex' => $row[],
                'CSFLatexResult' => $row[],
                'DateCSFLatexResult' => $row[],
                'CSFLatexOrganism' => $row[],
                'GramStainDone' => $row[],
                'CSFGramStainResult' => $row[],
                'DateCSFTakenGramstain' => $row[],
                'GramStainOrganism' => $row[],
                'BloodSpecimen' => $row[],
                'BloodCultureDone' => $row[],
                'BloodCultureResult' => $row[],
                'DateBloodCultureResult' => $row[],
                'DateBloodTakenCulture' => $row[],
                'BloodCultureOrganism' => $row[],
                'DateCSFGramResult' => $row[],
                'Interact' => $row[],
                'ContactName' => $row[],
                'SuspName' => $row[],
                'SuspAddress' => $row[],
                'PlaceInteract' => $row[],
                'DateInteract' => $row[],
                'DaysNum' => $row[],
                'PtTravel' => $row[],
                'PlacePtTravel' => $row[],
                'ContactTravel' => $row[],
                'PlaceContactTravel' => $row[],
                'AttendSocicalGather' => $row[],
                'PlaceSocialGather' => $row[],
                'PatientURTI' => $row[],
                'ContactURTI' => $row[],
                'District' => $row[],
                'InterLocal' => $row[],
                'Barangay' => $row[],
                'TYPEHOSPITALCLINIC' => $row[],
                'DELETERECORD' => $row[],
                'SENT' => $row[],
                'ip' => $row[],
                'ipgroup' => $row[],
            ]);
        }
        else if($this->sd == 'NNT') {
            $c = Nnt::create([
                'Icd10Code' => $row[],
                'RegionOfDrU' => $row[],
                'ProvOfDRU' => $row[],
                'MuncityOfDRU' => $row[],
                'DRU' => $row[],
                'AddressOfDRU' => $row[],
                'PatientNumber' => $row[],
                'FullName' => $row[],
                'FirstName' => $row[],
                'FamilyName' => $row[],
                'AgeYears' => $row[],
                'AgeMons' => $row[],
                'AgeDays' => $row[],
                'Sex' => $row[],
                'DOB' => $row[],
                'Region' => $row[],
                'Province' => $row[],
                'Muncity' => $row[],
                'Streetpurok' => $row[],
                'Admitted' => $row[],
                'DAdmit' => $row[],
                'DOnset' => $row[],
                'RecentAcuteWound' => $row[],
                'WoundSite' => $row[],
                'WoundType' => $row[],
                'OtherWound' => $row[],
                'TetanusToxoid' => $row[],
                'TetanusAntitoxin' => $row[],
                'SkinLesion' => $row[],
                'Outcome' => $row[],
                'DateDied' => $row[],
                'DateOfEntry' => $row[],
                'AdmitToEntry' => $row[],
                'OnsetToAdmit' => $row[],
                'MorbidityMonth' => $row[],
                'MorbidityWeek' => $row[],
                'EPIID' => $row[],
                'UniqueKey' => $row[],
                'RECSTATUS' => $row[],
                'SentinelSite' => $row[],
                'DeleteRecord' => $row[],
                'Year' => $row[],
                'NameOfDru' => $row[],
                'District' => $row[],
                'ILHZ' => $row[],
                'Barangay' => $row[],
                'TYPEHOSPITALCLINIC' => $row[],
                'SENT' => $row[],
                'ip' => $row[],
                'ipgroup' => $row[],
            ]);
        }
        else if($this->sd == 'NT') {
            $c = Nt::create([
                'Icd10Code' => $row[],
                'RegionOfDrU' => $row[],
                'ProvOfDRU' => $row[],
                'MuncityOfDRU' => $row[],
                'DRU' => $row[],
                'AddressOfDRU' => $row[],
                'PatientNumber' => $row[],
                'FirstName' => $row[],
                'FamilyName' => $row[],
                'FullName' => $row[],
                'Address' => $row[],
                'Region' => $row[],
                'Province' => $row[],
                'Muncity' => $row[],
                'Streetpurok' => $row[],
                'Sex' => $row[],
                'DOB' => $row[],
                'AgeYears' => $row[],
                'AgeMons' => $row[],
                'AgeDays' => $row[],
                'Admitted' => $row[],
                'DAdmit' => $row[],
                'DateOfReport' => $row[],
                'DateOfInvestigation' => $row[],
                'Investigator' => $row[],
                'ContactNum' => $row[],
                'First2days' => $row[],
                'After2days' => $row[],
                'FinalDx' => $row[],
                'Trismus' => $row[],
                'ClenFis' => $row[],
                'Opistho' => $row[],
                'StumpInf' => $row[],
                'Year' => $row[],
                'DateOfEntry' => $row[],
                'AdmitToEntry' => $row[],
                'OnsetToAdmit' => $row[],
                'MorbidityMonth' => $row[],
                'MorbidityWeek' => $row[],
                'EPIID' => $row[],
                'ReportToInvestigation' => $row[],
                'UniqueKey' => $row[],
                'RECSTATUS' => $row[],
                'TotPreg' => $row[],
                'Livebirths' => $row[],
                'TTDose' => $row[],
                'LivingKids' => $row[],
                'LastDoseGiven' => $row[],
                'DosesGiven' => $row[],
                'PreVisits' => $row[],
                'ImmunStatRep' => $row[],
                'FirstPV' => $row[],
                'ChldProt' => $row[],
                'PNCHist' => $row[],
                'Reason' => $row[],
                'PlaceDel' => $row[],
                'OtherPlaceDelivery' => $row[],
                'NameAddressHospital' => $row[],
                'OtherInstrument' => $row[],
                'DelAttnd' => $row[],
                'OtherAttendant' => $row[],
                'CordCut' => $row[],
                'StumpTreat' => $row[],
                'OtherMaterials' => $row[],
                'FinalClass' => $row[],
                'Outcome' => $row[],
                'DateDied' => $row[],
                'DONSET' => $row[],
                'Mother' => $row[],
                'DOBtoOnset' => $row[],
                'SentinelSite' => $row[],
                'DeleteRecord' => $row[],
                'NameOfDru' => $row[],
                'District' => $row[],
                'ILHZ' => $row[],
                'Barangay' => $row[],
                'TYPEHOSPITALCLINIC' => $row[],
                'SENT' => $row[],
                'ip' => $row[],
                'ipgroup' => $row[],
            ]);
        }
        else if($this->sd == 'PERT') {
            
        }
        else if($this->sd == 'PSP') {
            
        }
        else if($this->sd == 'RABIES') {
            
        }
        else if($this->sd == 'ROTAVIRUS') {
            
        }
        else if($this->sd == 'TYPHOID') {
            
        }
        else {

        }
    }

    public function startRow(): int {
        return 2;
    }
}
