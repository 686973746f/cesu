<?php

namespace App\Imports;

use Carbon\Carbon;
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
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            //double entry checking
            $check1 = Records::where('lname', mb_strtoupper($row[6]))
            ->where('fname', mb_strtoupper($row[7]))
            ->where(function ($query) use ($row) {
                $query->where('mname', mb_strtoupper($row[8]))
                ->orWhereNull('mname');
            })
            ->where('bdate', Date::excelToDateTimeObject($row[9])->format('Y-m-d'))
            ->where('gender', strtoupper($row[11]))
            ->first();

            if($check1) {
                $param1 = 1;
                $where = '(Existing in the Records Page)';
            }
            else {
                $param1 = 0;
            }

            $check2 = PaSwabDetails::where('lname', mb_strtoupper($row[6]))
            ->where('fname', mb_strtoupper($row[7]))
            ->where(function ($query) use ($row) {
                $query->where('mname', mb_strtoupper($row[8]))
                ->orWhereNull('mname');
            })
            ->where('bdate', Date::excelToDateTimeObject($row[9])->format('Y-m-d'))
            ->where('gender', strtoupper($row[11]))
            ->where('status', 'pending')
            ->first();

            if($check2) {
                $param2 = 1;
                $where = '(Existing in Pa-Swab Page, waiting for Approval)';
            }
            else {
                $param2 = 0;
            }

            if($param1 == 1 || $param2 == 1) {
            
            }
            else {
                if($row[11] == 'MALE') {
                    $isPregnant = 0;
                }
                else {
                    $isPregnant = ($row[23] == 'Y') ? 1 : 0;
                }
                
                $records = auth()->user()->records()->create([
                    'status' => 'approved',
                    'lname' => mb_strtoupper($row[6]),
                    'fname' => mb_strtoupper($row[7]),
                    'mname' => (!is_null($row[8])) ? mb_strtoupper($row[8]) : null,
                    'gender' => strtoupper($row[11]),
                    'isPregnant' => $isPregnant,
                    'cs' => 'SINGLE', //walang civil status column sa NEW DOH Excel
                    'nationality' => strtoupper($row[12]),
                    'bdate' => Date::excelToDateTimeObject($row[9])->format('Y-m-d'),
                    'mobile' => $row[18],
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
                    $dispoDate = Date::excelToDateTimeObject($row[2])->format('Y-m-d').' 08:00:00';
                }
                else if($row[41] == 'DONE QUARANTINE') {
                    $dispoType = 4;
                    $dispoDate = (!is_null($row[44])) ? Date::excelToDateTimeObject($row[44])->format('Y-m-d') : Date::excelToDateTimeObject($row[2])->format('Y-m-d');
                }
                else if($row[41] == 'SELF QUARANTINE') {
                    $dispoType = 3;
                    $dispoName = NULL;
                    $dispoDate = Date::excelToDateTimeObject($row[2])->format('Y-m-d').' 08:00:00';
                }
                else {
                    $dispoType = 1;
                    $dispoName = mb_strtoupper($row[41]);
                    $dispoDate = Date::excelToDateTimeObject($row[2])->format('Y-m-d').' 08:00:00';
                }

                //Outcome
                if($row[45] == 'ACTIVE') {
                    $outcome = 'Active';
                }
                else if ($row[45] == 'RECOVERED') {
                    $outcome = 'Recovered';
                    $dateRecovered = Date::excelToDateTimeObject($row[2])->format('Y-m-d');
                }
                else if ($row[45] == 'EXPIRED' || $row[45] == 'DIED') {
                    $outcome = 'Died';
                    $dateDied = Date::excelToDateTimeObject($row[47])->format('Y-m-d');
                    $cod = (!is_null($row[48])) ? mb_strtoupper($row[48]) : NULL;
                }
                else {
                    $outcome = 'Active';
                }

                //For Symptoms
                $symptomsList = array();
                $otherSymptoms = array();
                if($row[25] == 'Y') {
                    array_push($symptomsList, 'Fever');
                }
                if($row[26] == 'Y') {
                    array_push($symptomsList, 'Cough');
                }
                
                if($row[27] == 'Y') {
                    array_push($otherSymptoms, 'Colds');
                }
                if($row[28] == 'Y') {
                    array_push($otherSymptoms, 'DOB');
                }
                if($row[29] == 'Y') {
                    array_push($symptomsList, 'Anosmia (Loss of Smell)');
                }
                if($row[30] == 'Y') {
                    array_push($symptomsList, 'Ageusia (Loss of Taste)');
                }
                if($row[31] == 'Y') {
                    array_push($symptomsList, 'Sore throat');
                }
                if($row[32] == 'Y') {
                    array_push($symptomsList, 'Diarrhea');
                }
                if(!is_null($row[33])) {
                    array_push($otherSymptoms, mb_strtoupper($row[33]));
                }

                $forms = auth()->user()->form()->create([
                    'majikCode' => NULL,
                    'status' => 'approved',
                    'isPresentOnSwabDay' => 1,
                    'records_id' => $records->id,
                    'drunit' => (!is_null($row[3])) ? mb_strtoupper($row[3]) : 'CHO GENERAL TRIAS',
                    'drregion' => mb_strtoupper($row[4]),
                    'drprovince' => mb_strtoupper($row[5]),
                    'interviewerName' => 'BROAS, LUIS',
                    'interviewerMobile' => '09190664324',
                    'interviewDate' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
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
                    'isHealthCareWorker' => ($row[20] == 'Y') ? '1' : '0',
                    'healthCareCompanyName' => ($row[20] == 'Y') ? mb_strtoupper($row[21]) : NULL,
                    'healthCareCompanyLocation' => NULL,

                    'isOFW' => ($row[54] == 'Y') ? '1' : '0',
                    'OFWCountyOfOrigin' => ($row[54] == 'Y') ? mb_strtoupper($row[55]) : NULL,
                    'ofwType' => 1,

                    //WALA NAMANG FOREIGN NATIONAL TRAVELER COLUMN SA EXCEL BRUH
                    'isFNT' => '0',
                    'lsiType' => NULL,
                    'FNTCountryOfOrigin' => NULL,

                    'isLSI' => ($row[52] == 'Y') ? '1' : '0',
                    'LSICity' => ($row[52] == 'Y') ? mb_strtoupper($row[53]) : NULL,
                    'LSIProvince' => ($row[52] == 'Y') ? mb_strtoupper($row[53]) : NULL,

                    'isLivesOnClosedSettings' => '0',
                    'institutionType' => NULL,
                    'institutionName' => NULL,
                    'indgSpecify' => NULL,

                    'dateOnsetOfIllness' => (!is_null($row[24])) ? Date::excelToDateTimeObject($row[24])->format('Y-m-d') : Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
                    'SAS' => (!empty($symptomsList)) ? implode(",", $symptomsList) : NULL,
                    'SASFeverDeg' => ($row[25] == 'Y') ? '38' : NULL,
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

                    'testDateCollected1' => (!is_null($row[36])) ? Date::excelToDateTimeObject($row[36])->format('Y-m-d') : NULL,
                    'oniTimeCollected1' => NULL,
                    'testDateReleased1' => NULL,
                    'testLaboratory1' => NULL,
                    'testType1' => $ttype,
                    'testTypeAntigenRemarks1' => ($ttype == "ANTIGEN") ? mb_strtoupper($row[37]) : NULL,
                    'antigenKit1' => ($ttype == "ANTIGEN") ? 'ABBOTT' : NULL,
                    'testTypeOtherRemarks1' => NULL,
                    'testResult1' => 'PENDING',
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
                    'outcomeRecovDate' => (isset($dateRecovered)) ? $dateRecovered : NULL,
                    'outcomeDeathDate' => (isset($dateDied)) ? $dateDied : NULL,
                    'deathImmeCause' => (isset($cod)) ? $cod : NULL,
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

    public function startRow(): int {
        return 2;
     }
}
