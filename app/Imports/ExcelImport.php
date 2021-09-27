<?php

namespace App\Imports;

use Carbon\Carbon;
use ErrorException;
use App\Models\Forms;
use App\Models\Records;
use App\Models\PaSwabDetails;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ExcelImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    private function transformDateTime($value, string $format = 'Y-m-d')
    {
        if(!is_null($value) && $value != 'N/A') {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
            } catch (\ErrorException $e) {
                if(strtotime($value)) {
                    return Carbon::parse($value)->format('Y-m-d');
                }
                else {
                    return date('Y-m-d');
                } 
            }
        }
        else {
            return date('Y-m-d');
        }
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if(!is_null($row[6])) {
                //double entry checking
                $check1 = Records::where('lname', mb_strtoupper($row[6]))
                ->where('fname', mb_strtoupper($row[7]))
                ->where(function ($query) use ($row) {
                    $query->where('mname', mb_strtoupper($row[8]))
                    ->orWhereNull('mname');
                })
                ->where('gender', strtoupper($row[11]))
                ->first();

                /*
                $check2 = PaSwabDetails::where('lname', mb_strtoupper($row[6]))
                ->where('fname', mb_strtoupper($row[7]))
                ->where(function ($query) use ($row) {
                    $query->where('mname', mb_strtoupper($row[8]))
                    ->orWhereNull('mname');
                })
                ->where('bdate', $this->transformDateTime($row[9]))
                ->where('gender', strtoupper($row[11]))
                ->where('status', 'pending')
                ->first();
                */

                if(!is_null($row[2]) && $row[2] != 'N/A') {
                    $row[2] = $row[2];
                }
                else {
                    $row[2] = $row[36];
                }

                if(!is_null($row[36])) {
                    $row[36] = $row[36];
                }
                else {
                    $row[36] = $row[2];
                }

                //PREPARE RECORDS CREATION
                if($row[11] == 'MALE') {
                    $isPregnant = 0;
                }
                else {
                    $isPregnant = ($row[23] == 'Y' || $row[23] == 'YES') ? 1 : 0;
                }

                //Health Status
                if($row[22] == 'ASYMPTOMATIC') {
                    $healthStatus = 'Asymptomatic';
                }
                else if($row[22] == 'SYMPTOMATIC' || $row[22] == 'MILD -SYMPTOMATIC' || $row[22] == 'MILD - SYMPTOMATIC') {
                    $healthStatus = 'Mild';
                }
                else {
                    $healthStatus = ucfirst($row[22]);
                }

                //PCR Result
                if($row[38] == '2019-Ncov Viral RNA  Detected' || $row[38] == '2019-Ncov Viral RNA Detected') {
                    $ttype = 'OPS';
                    $result = 'POSITIVE';
                }
                else if($row[38] == '2019-nCoV Viral RNA not Detected') {
                    $ttype = 'OPS';
                    $result = 'NEGATIVE';
                }
                else {
                    if(!is_null($row[38])) {
                        $ttype = 'OPS';
                    }
                    else {
                        if(!is_null($row[37]) && $row[37] != 'N/A') {
                            $ttype = 'ANTIGEN';
                        }
                        else {
                            $ttype = 'OPS';
                        }
                    }

                    $result = 'PENDING';
                }

                //Classification
                if($row[40] == 'CONFIRMED CASE') {
                    $classification = 'Confirmed';
                }
                else if($row[40] == 'PROBABLE') {
                    $classification = 'Probable';
                }
                else if($row[40] == 'SUSPECTED') {
                    $classification = 'Suspect';
                }
                else if($row[40] == 'NEGATIVE') {
                    $classification = 'Non-COVID-19 Case'; 
                }
                else {
                    $classification = ucfirst($row[40]);
                }

                //Quarantine Status
                if($row[41] == 'RECOVERED') {
                    $dispoType = 2;
                    $dispoName = 'ISOLATION FACILITY';
                    $dispoDate = $this->transformDateTime($row[2]).' 08:00:00';
                }
                else if($row[41] == 'DONE QUARANTINE') {
                    $dispoType = 4;
                    $dispoName = NULL;
                    $dispoDate = NULL;
                }
                else if($row[41] == 'SELF QUARANTINE') {
                    $dispoType = 3;
                    $dispoName = NULL;
                    $dispoDate = $this->transformDateTime($row[2]).' 08:00:00';
                }
                else {
                    $dispoType = 1;
                    $dispoName = mb_strtoupper($row[41]);
                    $dispoDate = $this->transformDateTime($row[2]).' 08:00:00';
                }

                //Outcome
                if($row[45] == 'ACTIVE') {
                    $outcome = 'Active';
                }
                else if ($row[45] == 'RECOVERED') {
                    $outcome = 'Recovered';
                    $dateRecovered = $this->transformDateTime($row[2]);
                }
                else if ($row[45] == 'EXPIRED' || $row[45] == 'DIED') {
                    $outcome = 'Died';
                    $dateDied = $this->transformDateTime($row[47]);
                    $cod = (!is_null($row[48])) ? mb_strtoupper($row[48]) : NULL;
                }
                else {
                    $outcome = 'Active';
                }

                //For Symptoms
                $symptomsList = array();
                $otherSymptoms = array();
                if($row[25] == 'Y' || $row[25] == 'YES') {
                    array_push($symptomsList, 'Fever');
                }
                if($row[26] == 'Y' || $row[26] == 'YES') {
                    array_push($symptomsList, 'Cough');
                }
                
                if($row[27] == 'Y' || $row[27] == 'YES') {
                    array_push($otherSymptoms, 'Colds');
                }
                if($row[28] == 'Y' || $row[28] == 'YES') {
                    array_push($otherSymptoms, 'DOB');
                }
                if($row[29] == 'Y' || $row[29] == 'YES') {
                    array_push($symptomsList, 'Anosmia (Loss of Smell)');
                }
                if($row[30] == 'Y' || $row[30] == 'YES') {
                    array_push($symptomsList, 'Ageusia (Loss of Taste)');
                }
                if($row[31] == 'Y' || $row[31] == 'YES') {
                    array_push($symptomsList, 'Sore throat');
                }
                if($row[32] == 'Y' || $row[32] == 'YES') {
                    array_push($symptomsList, 'Diarrhea');
                }
                if(!is_null($row[33])) {
                    array_push($otherSymptoms, mb_strtoupper($row[33]));
                }

                if($check1) {
                    $rcheck = Records::find($check1->id);
                    $fcheck = Forms::where('records_id', $rcheck->id)->first();
                    if($fcheck) {
                        $u = Forms::find($fcheck->id);

                        $u->dateReported = (!is_null($row[2])) ? $this->transformDateTime($row[2]).' 00:00:00' : $this->transformDateTime($row[36]);
                        
                        $u->dispoType = $dispoType;
                        $u->dispoName = $dispoName;
                        $u->dispoDate = $dispoDate;

                        $u->healthStatus = $healthStatus;
                        $u->caseClassification = $classification;
                        
                        $u->testDateCollected1 = $this->transformDateTime($row[36]);
                        $u->oniTimeCollected1 = NULL;
                        $u->testDateReleased1 = NULL;
                        $u->testLaboratory1 = NULL;
                        $u->testType1 = $ttype;
                        $u->testTypeAntigenRemarks1 = ($ttype == "ANTIGEN") ? mb_strtoupper($row[37]) : NULL;
                        $u->antigenKit1 = ($ttype == "ANTIGEN") ? 'ABBOTT' : NULL;
                        $u->testTypeOtherRemarks1 = NULL;
                        $u->testResult1 = $result;
                        $u->testResultOtherRemarks1 = NULL;
                        $u->outcomeCondition = $outcome;
                        $u->outcomeRecovDate = (isset($dateRecovered) && $outcome == 'Recovered') ? $dateRecovered : NULL;
                        $u->outcomeDeathDate = (isset($dateDied) && $outcome == 'Died') ? $dateDied : NULL;
                        $u->deathImmeCause = (isset($cod) && $outcome == 'Died') ? $cod : NULL;
                        $u->deathAnteCause = NULL;
                        $u->deathUndeCause = NULL;
                        $u->contriCondi = NULL;

                        $u->save();
                    }
                }
                else {
                    $records = auth()->user()->records()->create([
                        'status' => 'approved',
                        'lname' => mb_strtoupper($row[6]),
                        'fname' => mb_strtoupper($row[7]),
                        'mname' => (!is_null($row[8])) ? mb_strtoupper($row[8]) : NULL,
                        'gender' => strtoupper($row[11]),
                        'isPregnant' => $isPregnant,
                        'cs' => 'SINGLE', //walang civil status column sa NEW DOH Excel
                        'nationality' => strtoupper($row[12]),
                        'bdate' => (!is_null($row[9]) && $row[9] != "N/A") ? $this->transformDateTime($row[9]) : '2021-01-01',
                        'mobile' => (!is_null($row[18])) ? $row[18] : '09190664324',
                        'phoneno' => NULL,
                        'email' => NULL,
                        'philhealth' => NULL, //walang philhealth column sa NEW DOH Excel
                        'address_houseno' => mb_strtoupper($row[17]),
                        'address_street' => 'NEAR BRGY. HALL',
                        'address_brgy' => mb_strtoupper($row[16]),
                        'address_city' => mb_strtoupper($row[15]),
                        'address_cityjson' => '042108', //default for general trias
                        'address_province' => mb_strtoupper($row[14]),
                        'address_provincejson' => '0421', //default for cavite
            
                        'permaaddressDifferent' => 0,
                        'permaaddress_houseno' => mb_strtoupper($row[17]),
                        'permaaddress_street' => 'NEAR BRGY. HALL',
                        'permaaddress_brgy' => mb_strtoupper($row[16]),
                        'permaaddress_city' => mb_strtoupper($row[15]),
                        'permaaddress_cityjson' => '042108', //default for general trias
                        'permaaddress_province' => mb_strtoupper($row[14]),
                        'permaaddress_provincejson' => '0421', //default for cavite
                        'permamobile' => $row[18],
                        'permaphoneno' => NULL,
                        'permaemail' => NULL,
            
                        'hasOccupation' => (!is_null($row[19])) ? 1 : 0,
                        'occupation' => (!is_null($row[19]) || $row[19] == 'N/A') ? mb_strtoupper($row[19]) : NULL,
                        'worksInClosedSetting' => 'UNKNOWN',
                        'occupation_lotbldg' => NULL,
                        'occupation_street' => NULL,
                        'occupation_brgy' => NULL,
                        'occupation_city' => NULL,
                        'occupation_cityjson' => NULL,
                        'occupation_province' => NULL,
                        'occupation_provincejson' => NULL,
                        'occupation_name' => (!is_null($row[21])) ? mb_strtoupper($row[21]) : NULL,
                        'occupation_mobile' => NULL,
                        'occupation_email' => NULL,
        
                        'natureOfWork' => NULL,
                        'natureOfWorkIfOthers' => NULL,
                    ]);

                    $forms = auth()->user()->form()->create([
                        'majikCode' => NULL,
                        'dateReported' => (!is_null($row[2])) ? $this->transformDateTime($row[2]).' 00:00:00' : $this->transformDateTime($row[36]),
                        'status' => 'approved',
                        'isPresentOnSwabDay' => 1,
                        'records_id' => $records->id,
                        'drunit' => (!is_null($row[3])) ? mb_strtoupper($row[3]) : 'CHO GENERAL TRIAS',
                        'drregion' => mb_strtoupper($row[4]),
                        'drprovince' => mb_strtoupper($row[5]),
                        'interviewerName' => 'BROAS, LUIS',
                        'interviewerMobile' => '09190664324',
                        'interviewDate' => (!is_null($row[2])) ? $this->transformDateTime($row[2]) : $this->transformDateTime($row[36]),
                        'informantName' => NULL,
                        'informantRelationship' => NULL,
                        'informantMobile' => NULL,
                        'existingCaseList' => '1',
                        'ecOthersRemarks' => NULL,
                        'pType' => 'PROBABLE',
                        'isForHospitalization' => 0,
                        'testingCat' => 'C',
                        'havePreviousCovidConsultation' => '0',
                        'dateOfFirstConsult' => NULL,
                        'facilityNameOfFirstConsult' => NULL,
    
                        'vaccinationDate1' => NULL,
                        'vaccinationName1' => NULL,
                        'vaccinationNoOfDose1' => NULL,
                        'vaccinationFacility1' => NULL,
                        'vaccinationRegion1' => NULL,
                        'haveAdverseEvents1' => NULL,
    
                        'vaccinationDate2' => NULL,
                        'vaccinationName2' => NULL,
                        'vaccinationNoOfDose2' => NULL,
                        'vaccinationFacility2' => NULL,
                        'vaccinationRegion2' => NULL,
                        'haveAdverseEvents2' => NULL,
                        
                        //PLESE FINISH
                        'dispoType' => $dispoType,
                        'dispoName' => $dispoName,
                        'dispoDate' => $dispoDate,
    
                        'healthStatus' => $healthStatus,
                        'caseClassification' => $classification,
                        'isHealthCareWorker' => ($row[20] == 'Y' || $row[20] == 'YES') ? '1' : '0',
                        'healthCareCompanyName' => ($row[20] == 'Y' || $row[20] == 'YES') ? mb_strtoupper($row[21]) : NULL,
                        'healthCareCompanyLocation' => NULL,
    
                        'isOFW' => ($row[54] == 'Y' || $row[54] == 'YES') ? '1' : '0',
                        'OFWCountyOfOrigin' => ($row[54] == 'Y' || $row[54] == 'YES') ? mb_strtoupper($row[55]) : NULL,
                        'ofwType' => 1,
    
                        //WALA NAMANG FOREIGN NATIONAL TRAVELER COLUMN SA EXCEL BRUH
                        'isFNT' => '0',
                        'lsiType' => NULL,
                        'FNTCountryOfOrigin' => NULL,
    
                        'isLSI' => ($row[52] == 'Y' || $row[52] == 'YES') ? '1' : '0',
                        'LSICity' => ($row[52] == 'Y' || $row[52] == 'YES') ? mb_strtoupper($row[53]) : NULL,
                        'LSIProvince' => ($row[52] == 'Y' || $row[52] == 'YES') ? mb_strtoupper($row[53]) : NULL,
    
                        'isLivesOnClosedSettings' => '0',
                        'institutionType' => NULL,
                        'institutionName' => NULL,
                        'indgSpecify' => NULL,
    
                        'dateOnsetOfIllness' => (!is_null($row[24]) && $row[24] != 'N/A') ? $this->transformDateTime($row[24]) : $this->transformDateTime($row[2]),
                        'SAS' => (!empty($symptomsList)) ? implode(",", $symptomsList) : NULL,
                        'SASFeverDeg' => ($row[25] == 'Y' || $row[25] == 'YES') ? '38' : NULL,
                        'SASOtherRemarks' => (!empty($otherSymptoms)) ? implode(",", $otherSymptoms) : NULL,
                        
                        'COMO' => 'None',
                        'COMOOtherRemarks' => NULL,
    
                        'PregnantLMP' => NULL,
                        'PregnantHighRisk' => ($isPregnant == 1) ? '1' : '0',
                        'imagingDoneDate' => NULL,
                        'imagingDone' => 'None',
                        'imagingResult' => NULL,
                        'imagingOtherFindings' => NULL,
    
                        'testedPositiveUsingRTPCRBefore' => '0',
                        'testedPositiveNumOfSwab' => '0',
                        'testedPositiveLab' => NULL,
                        'testedPositiveSpecCollectedDate' => NULL,
    
                        'testDateCollected1' => $this->transformDateTime($row[36]),
                        'oniTimeCollected1' => NULL,
                        'testDateReleased1' => NULL,
                        'testLaboratory1' => NULL,
                        'testType1' => $ttype,
                        'testTypeAntigenRemarks1' => ($ttype == "ANTIGEN") ? mb_strtoupper($row[37]) : NULL,
                        'antigenKit1' => ($ttype == "ANTIGEN") ? 'ABBOTT' : NULL,
                        'testTypeOtherRemarks1' => NULL,
                        'testResult1' => $result,
                        'testResultOtherRemarks1' => NULL,
    
                        'testDateCollected2' => NULL,
                        'oniTimeCollected2' => NULL,
                        'testDateReleased2' => NULL,
                        'testLaboratory2' => NULL,
                        'testType2' => NULL,
                        'testTypeAntigenRemarks2' => NULL,
                        'antigenKit2' => NULL,
                        'testTypeOtherRemarks2' => NULL,
                        'testResult2' => NULL,
                        'testResultOtherRemarks2' => NULL,
    
                        'outcomeCondition' => $outcome,
                        'outcomeRecovDate' => (!is_null($dateRecovered) && $outcome == 'Recovered') ? $dateRecovered : NULL,
                        'outcomeDeathDate' => (!is_null($dateDied) && $outcome == 'Died') ? $dateDied : NULL,
                        'deathImmeCause' => (!is_null($cod) && $outcome == 'Died') ? $cod : NULL,
                        'deathAnteCause' => NULL,
                        'deathUndeCause' => NULL,
                        'contriCondi' => NULL,
    
                        'expoitem1' => '0',
                        'expoDateLastCont' => NULL,
    
                        'expoitem2' => '0',
                        'intCountry' => NULL,
                        'intDateFrom' => NULL,
                        'intDateTo' => NULL,
                        'intWithOngoingCovid' => 'N/A',
                        'intVessel' => NULL,
                        'intVesselNo' => NULL,
                        'intDateDepart' => NULL,
                        'intDateArrive' => NULL,
    
                        'placevisited' => NULL,
    
                        'locName1' => NULL,
                        'locAddress1' => NULL,
                        'locDateFrom1' => NULL,
                        'locDateTo1' => NULL,
                        'locWithOngoingCovid1' => 'N/A',
    
                        'locName2' => NULL,
                        'locAddress2' => NULL,
                        'locDateFrom2' => NULL,
                        'locDateTo2' => NULL,
                        'locWithOngoingCovid2' => 'N/A',
                        
                        'locName3' => NULL,
                        'locAddress3' => NULL,
                        'locDateFrom3' => NULL,
                        'locDateTo3' => NULL,
                        'locWithOngoingCovid3' => 'N/A',
                        
                        'locName4' => NULL,
                        'locAddress4' => NULL,
                        'locDateFrom4' => NULL,
                        'locDateTo4' => NULL,
                        'locWithOngoingCovid4' => 'N/A',
    
                        'locName5' => NULL,
                        'locAddress5' => NULL,
                        'locDateFrom5' => NULL,
                        'locDateTo5' => NULL,
                        'locWithOngoingCovid5' => 'N/A',
    
                        'locName6' => NULL,
                        'locAddress6' => NULL,
                        'locDateFrom6' => NULL,
                        'locDateTo6' => NULL,
                        'locWithOngoingCovid6' => 'N/A',
    
                        'locName7' => NULL,
                        'locAddress7' => NULL,
                        'locDateFrom7' => NULL,
                        'locDateTo7' => NULL,
                        'locWithOngoingCovid7' => 'N/A',
    
                        'localVessel1' => NULL,
                        'localVesselNo1' => NULL,
                        'localOrigin1' => NULL,
                        'localDateDepart1' => NULL,
                        'localDest1' => NULL,
                        'localDateArrive1' => NULL,
    
                        'localVessel2' => NULL,
                        'localVesselNo2' => NULL,
                        'localOrigin2' => NULL,
                        'localDateDepart2' => NULL,
                        'localDest2' => NULL,
                        'localDateArrive2' => NULL,
    
                        'contact1Name' => NULL,
                        'contact1No' => NULL,
                        'contact2Name' => NULL,
                        'contact2No' => NULL,
                        'contact3Name' => NULL,
                        'contact3No' => NULL,
                        'contact4Name' => NULL,
                        'contact4No' => NULL,
    
                        'remarks' => $row[60],
                    ]);
                }
            }
        }
    }

    public function startRow(): int {
        return 2;
    }
}
