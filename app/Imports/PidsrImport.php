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
use App\Models\Hepatitis;
use App\Models\Influenza;
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

                    $ctr = 0;

                    if($proceed) {
                        $c = Ames::create([
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'NHTS' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'DateRep' => $row[$ctr++],
                            'DateInv' => $row[$ctr++],
                            'Investigator' => $row[$ctr++],
                            'ContactNum' => $row[$ctr++],
                            'InvDesig' => $row[$ctr++],
                            'Fever' => $row[$ctr++],
                            'BehaviorChng' => $row[$ctr++],
                            'Seizure' => $row[$ctr++],
                            'Stiffneck' => $row[$ctr++],
                            'bulgefontanel' => $row[$ctr++],
                            'MenSign' => $row[$ctr++],
                            'ClinDiag' => $row[$ctr++],
                            'OtherDiag' => $row[$ctr++],
                            'JE' => $row[$ctr++],
                            'VacJeDate' => $row[$ctr++],
                            'JEDose' => $row[$ctr++],
                            'Hib' => $row[$ctr++],
                            'VacHibDate' => $row[$ctr++],
                            'HibDose' => $row[$ctr++],
                            'PCV10' => $row[$ctr++],
                            'VacPCV10Date' => $row[$ctr++],
                            'PCV10Dose' => $row[$ctr++],
                            'PCV13' => $row[$ctr++],
                            'VacPCV13Date' => $row[$ctr++],
                            'PCV13Dose' => $row[$ctr++],
                            'MeningoVacc' => $row[$ctr++],
                            'VacMeningoDate' => $row[$ctr++],
                            'MeningoVaccDose' => $row[$ctr++],
                            'MeasVacc' => $row[$ctr++],
                            'VacMeasDate' => $row[$ctr++],
                            'MeasVaccDose' => $row[$ctr++],
                            'MMR' => $row[$ctr++],
                            'VacMMRDate' => $row[$ctr++],
                            'MMRDose' => $row[$ctr++],
                            'plcDaycare' => $row[$ctr++],
                            'plcBrgy' => $row[$ctr++],
                            'plcHome' => $row[$ctr++],
                            'plcSchool' => $row[$ctr++],
                            'plcdormitory' => $row[$ctr++],
                            'plcHC' => $row[$ctr++],
                            'plcWorkplace' => $row[$ctr++],
                            'plcOther' => $row[$ctr++],
                            'Travel' => $row[$ctr++],
                            'PlaceTravelled' => $row[$ctr++],
                            'FrmTrvlDate' => $row[$ctr++],
                            'ToTrvlDate' => $row[$ctr++],
                            'CSFColl' => $row[$ctr++],
                            'D8CSFTaken' => $row[$ctr++],
                            'TymCSFTaken' => $row[$ctr++],
                            'D8CSFHospLab' => $row[$ctr++],
                            'TymCSFHospLab' => $row[$ctr++],
                            'CSFAppearance' => $row[$ctr++],
                            'GramStain' => $row[$ctr++],
                            'GramStainResult' => $row[$ctr++],
                            'culture' => $row[$ctr++],
                            'CultureResult' => $row[$ctr++],
                            'OtherTest' => $row[$ctr++],
                            'OtherTestResult' => $row[$ctr++],
                            'D8CSFSentRITM' => $row[$ctr++],
                            'D8CSFReceivedRITM' => $row[$ctr++],
                            'CSFSampVol' => $row[$ctr++],
                            'D8CSFTesting' => $row[$ctr++],
                            'CSFResult' => $row[$ctr++],
                            'Serum1Col' => $row[$ctr++],
                            'D8Serum1Taken' => $row[$ctr++],
                            'D8Serum1HospLab' => $row[$ctr++],
                            'D8Serum1Sent' => $row[$ctr++],
                            'D8Seruml1Received' => $row[$ctr++],
                            'Serum1SampVol' => $row[$ctr++],
                            'D8Serum1Testing' => $row[$ctr++],
                            'Serum1Result' => $row[$ctr++],
                            'Serum2Col' => $row[$ctr++],
                            'D8Serum2Taken' => $row[$ctr++],
                            'D8Serum2HospLab' => $row[$ctr++],
                            'D8Serum2Sent' => $row[$ctr++],
                            'D8Serum2Received' => $row[$ctr++],
                            'Serum2SampVol' => $row[$ctr++],
                            'D8Serum2testing' => $row[$ctr++],
                            'Serum2Result' => $row[$ctr++],
                            'AESCaseClass' => $row[$ctr++],
                            'BmCaseClass' => $row[$ctr++],
                            'AESOtherAgent' => $row[$ctr++],
                            'ConfirmBMTest' => $row[$ctr++],
                            'FinalDiagnosis' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'DateDisch' => $row[$ctr++],
                            'DateDied' => $row[$ctr++],
                            'RecoverSequelae' => $row[$ctr++],
                            'SequelaeSpecs' => $row[$ctr++],
                            'TransTo' => $row[$ctr++],
                            'HAMA' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'CASECLASS' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'Occupation' => $row[$ctr++],
                            'Workplace' => $row[$ctr++],
                            'WorkAddress' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'Fever' => $row[$ctr++],
                            'Nausea' => $row[$ctr++],
                            'Headache' => $row[$ctr++],
                            'DryCough' => $row[$ctr++],
                            'SoreThroat' => $row[$ctr++],
                            'TroubleSwallowing' => $row[$ctr++],
                            'TroubleBreathing' => $row[$ctr++],
                            'StomachPain' => $row[$ctr++],
                            'VomitingBlood' => $row[$ctr++],
                            'BloodyDiarrhea' => $row[$ctr++],
                            'SweatingExcessively' => $row[$ctr++],
                            'ExtremeTiredness' => $row[$ctr++],
                            'PainOrTightChest' => $row[$ctr++],
                            'SoreMuscles' => $row[$ctr++],
                            'NeckPain' => $row[$ctr++],
                            'ItchySkin' => $row[$ctr++],
                            'BlackScab' => $row[$ctr++],
                            'SkinLesions' => $row[$ctr++],
                            'DescribeLesion' => $row[$ctr++],
                            'OtherSS' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'OccupAnimalAgriculture' => $row[$ctr++],
                            'ExpToAnthVaccAnimal' => $row[$ctr++],
                            'ExpToAnimalProducts' => $row[$ctr++],
                            'ContactLiveDeadAnimal' => $row[$ctr++],
                            'TravelBeyondResidence' => $row[$ctr++],
                            'WorkInLaboratory' => $row[$ctr++],
                            'HHMembersExpSimilarSymp' => $row[$ctr++],
                            'EatenUndercookedMeat' => $row[$ctr++],
                            'ReceivedLettersPackage' => $row[$ctr++],
                            'OpenedMailsForOthers' => $row[$ctr++],
                            'NearOpenedEnveloped' => $row[$ctr++],
                            'Cutaneous' => $row[$ctr++],
                            'CaseClassification' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'DateDied' => $row[$ctr++],
                            'Gastrointestinal' => $row[$ctr++],
                            'Pulmonary' => $row[$ctr++],
                            'Meningeal' => $row[$ctr++],
                            'UnknownClinicalForm' => $row[$ctr++],
                            'Specimen1' => $row[$ctr++],
                            'DateSpecimen1Taken' => $row[$ctr++],
                            'ResultSpecimen1' => $row[$ctr++],
                            'DateResult1' => $row[$ctr++],
                            'SpecifyOrganism1' => $row[$ctr++],
                            'Specimen2' => $row[$ctr++],
                            'DateSpecimen2Taken' => $row[$ctr++],
                            'Result2' => $row[$ctr++],
                            'SpecifyOrganism2' => $row[$ctr++],
                            'ResultSpecimen2' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'DateResult2' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Region' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'CaseClass' => $row[$ctr++],
                            'DCaseRep' => $row[$ctr++],
                            'DCASEINV' => $row[$ctr++],
                            'DayswidSymp' => $row[$ctr++],
                            'Fever' => $row[$ctr++],
                            'Arthritis' => $row[$ctr++],
                            'Hands' => $row[$ctr++],
                            'Feet' => $row[$ctr++],
                            'Ankles' => $row[$ctr++],
                            'OthSite' => $row[$ctr++],
                            'Arthralgia' => $row[$ctr++],
                            'PeriEdema' => $row[$ctr++],
                            'SkinMani' => $row[$ctr++],
                            'SkinDesc' => $row[$ctr++],
                            'Myalgia' => $row[$ctr++],
                            'BackPain' => $row[$ctr++],
                            'Headache' => $row[$ctr++],
                            'Nausea' => $row[$ctr++],
                            'MucosBleed' => $row[$ctr++],
                            'Vomiting' => $row[$ctr++],
                            'Asthenia' => $row[$ctr++],
                            'MeningoEncep' => $row[$ctr++],
                            'OthSymptom' => $row[$ctr++],
                            'ClinDx' => $row[$ctr++],
                            'DCollected' => $row[$ctr++],
                            'DSpecSent' => $row[$ctr++],
                            'SerIgM' => $row[$ctr++],
                            'IgM_Res' => $row[$ctr++],
                            'DIgMRes' => $row[$ctr++],
                            'SerIgG' => $row[$ctr++],
                            'IgG_Res' => $row[$ctr++],
                            'DIgGRes' => $row[$ctr++],
                            'RT_PCR' => $row[$ctr++],
                            'RT_PCRRes' => $row[$ctr++],
                            'DRtPCRRes' => $row[$ctr++],
                            'VirIso' => $row[$ctr++],
                            'VirIsoRes' => $row[$ctr++],
                            'DVirIsoRes' => $row[$ctr++],
                            'TravHist' => $row[$ctr++],
                            'PlaceofTravel' => $row[$ctr++],
                            'Residence' => $row[$ctr++],
                            'BldTransHist' => $row[$ctr++],
                            'Reporter' => $row[$ctr++],
                            'ReporterContNum' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'EPIID' => $iepiid,
                            'DateDied' => $row[$ctr++],
                            'Icd10Code' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'Recstatus' => $row[$ctr++],
                            'UniqueKey' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'StoolCulture' => $row[$ctr++],
                            'Organism' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'DateDied' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'CASECLASS' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'DptDoses' => $row[$ctr++],
                            'DateLastDose' => $row[$ctr++],
                            'CaseClassification' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'DateDied' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $row[$ctr++],
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'Type' => $row[$ctr++],
                            'LabResult' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'DateDied' => $row[$ctr++],
                            'TypeOfHepatitis' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'CASECLASS' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DONSET' => $row[$ctr++],
                            'Fever' => $row[$ctr++],
                            'FeverOnset' => $row[$ctr++],
                            'RashChar' => $row[$ctr++],
                            'RashSores' => $row[$ctr++],
                            'SoreOnset' => $row[$ctr++],
                            'Palms' => $row[$ctr++],
                            'Fingers' => $row[$ctr++],
                            'FootSoles' => $row[$ctr++],
                            'Buttocks' => $row[$ctr++],
                            'MouthUlcers' => $row[$ctr++],
                            'Pain' => $row[$ctr++],
                            'Anorexia' => $row[$ctr++],
                            'BM' => $row[$ctr++],
                            'SoreThroat' => $row[$ctr++],
                            'NausVom' => $row[$ctr++],
                            'DiffBreath' => $row[$ctr++],
                            'Paralysis' => $row[$ctr++],
                            'MeningLes' => $row[$ctr++],
                            'OthSymptoms' => $row[$ctr++],
                            'AnyComp' => $row[$ctr++],
                            'Complic8' => $row[$ctr++],
                            'Investigator' => $row[$ctr++],
                            'ContactNum' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $row[$ctr++],
                            'ReportToInvestigation' => $row[$ctr++],
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'Travel' => $row[$ctr++],
                            'ProbExposure' => $row[$ctr++],
                            'OthExposure' => $row[$ctr++],
                            'OtherCase' => $row[$ctr++],
                            'RectalSwabColl' => $row[$ctr++],
                            'VesicFluidColl' => $row[$ctr++],
                            'StoolColl' => $row[$ctr++],
                            'ThroatSwabColl' => $row[$ctr++],
                            'DateStooltaken' => $row[$ctr++],
                            'DateStoolsent' => $row[$ctr++],
                            'DateStoolRecvd' => $row[$ctr++],
                            'StoolResult' => $row[$ctr++],
                            'StoolOrg' => $row[$ctr++],
                            'StoolResultD8' => $row[$ctr++],
                            'VFSwabtaken' => $row[$ctr++],
                            'VFSwabsent' => $row[$ctr++],
                            'VFSwabRecvd' => $row[$ctr++],
                            'VesicFluidRes' => $row[$ctr++],
                            'VesicFluidOrg' => $row[$ctr++],
                            'VFSwabResultD8' => $row[$ctr++],
                            'ThroatSwabtaken' => $row[$ctr++],
                            'ThroatSwabsent' => $row[$ctr++],
                            'ThroatSwabRecvd' => $row[$ctr++],
                            'ThroatSwabResult' => $row[$ctr++],
                            'ThroatSwabOrg' => $row[$ctr++],
                            'ThroatSwabResultD8' => $row[$ctr++],
                            'RectalSwabtaken' => $row[$ctr++],
                            'RectalSwabsent' => $row[$ctr++],
                            'RectalSwabRecvd' => $row[$ctr++],
                            'RectalSwabResult' => $row[$ctr++],
                            'RectalSwabOrg' => $row[$ctr++],
                            'RectalSwabResultD8' => $row[$ctr++],
                            'CaseClass' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'WFDiag' => $row[$ctr++],
                            'Death' => $row[$ctr++],
                            'DCaseRep' => $row[$ctr++],
                            'DCASEINV' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'LabResult' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'DateDied' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $iepiid,
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'CASECLASS' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SARI' => $row[$ctr++],
                            'Organism' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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
                            'Icd10Code' => $row[$ctr++],
                            'RegionOfDrU' => $row[$ctr++],
                            'ProvOfDRU' => $row[$ctr++],
                            'MuncityOfDRU' => $row[$ctr++],
                            'DRU' => $row[$ctr++],
                            'AddressOfDRU' => $row[$ctr++],
                            'PatientNumber' => $row[$ctr++],
                            'FullName' => $row[$ctr++],
                            'FirstName' => $row[$ctr++],
                            'FamilyName' => $row[$ctr++],
                            'AgeYears' => $row[$ctr++],
                            'AgeMons' => $row[$ctr++],
                            'AgeDays' => $row[$ctr++],
                            'Sex' => $row[$ctr++],
                            'DOB' => $row[$ctr++],
                            'Region' => $row[$ctr++],
                            'Province' => $row[$ctr++],
                            'Muncity' => $row[$ctr++],
                            'Streetpurok' => $row[$ctr++],
                            'Admitted' => $row[$ctr++],
                            'DAdmit' => $row[$ctr++],
                            'DOnset' => $row[$ctr++],
                            'LabRes' => $row[$ctr++],
                            'Serovar' => $row[$ctr++],
                            'CaseClassification' => $row[$ctr++],
                            'Outcome' => $row[$ctr++],
                            'DateDied' => $row[$ctr++],
                            'Occupation' => $row[$ctr++],
                            'DateOfEntry' => $row[$ctr++],
                            'AdmitToEntry' => $row[$ctr++],
                            'OnsetToAdmit' => $row[$ctr++],
                            'MorbidityMonth' => $row[$ctr++],
                            'MorbidityWeek' => $row[$ctr++],
                            'EPIID' => $row[$ctr++],
                            'UniqueKey' => $row[$ctr++],
                            'RECSTATUS' => $row[$ctr++],
                            'SentinelSite' => $row[$ctr++],
                            'DeleteRecord' => $row[$ctr++],
                            'Year' => $row[$ctr++],
                            'NameOfDru' => $row[$ctr++],
                            'District' => $row[$ctr++],
                            'ILHZ' => $row[$ctr++],
                            'Barangay' => $row[$ctr++],
                            'TYPEHOSPITALCLINIC' => $row[$ctr++],
                            'SENT' => $row[$ctr++],
                            'ip' => $row[$ctr++],
                            'ipgroup' => $row[$ctr++],
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

                    }
                }
            }
        }
        else if($this->sd == 'MEASLES') {
            
        }
        else if($this->sd == 'MENINGITIS') {
            
        }
        else if($this->sd == 'MENINGO') {
            
        }
        else if($this->sd == 'NNT') {
            
        }
        else if($this->sd == 'NT') {
            
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
