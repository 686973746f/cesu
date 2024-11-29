<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\Records;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class TkcExcelImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        if($row['hasbeenpositive'] == "true") {
            //Check if TKC ID exists
            $check1 = Forms::where('tkc_id', $row['tkc_id'])
            ->first();

            if(!($check1)) {
                dd($row);
                if($row['last_name'] != "" && $row['first_name'] != "") {
                    $lname = mb_strtoupper(str_replace([' ','-'], '', $row['last_name']));
                    $fname = mb_strtoupper(str_replace([' ','-'], '', $row['first_name']));
                    $mname = ($row['middle_name'] != "") ? mb_strtoupper(str_replace([' ','-'], '', $row['middle_name'])) : NULL;
                    $suffix = ($row['suffix'] != "") ? mb_strtoupper(str_replace([' ','-','.'], '', $row['suffix'])) : NULL;
                    $bdate = ($row['birthdate'] != "") ? Carbon::parse($row['birthdate'])->format('Y-m-d') : '1900-01-01';

                    $check = Records::tkcIfDuplicateFound($lname, $fname, $mname, $suffix, $bdate);

                    if(is_null($check)) {
                        //Create NEW Patient Record
                        $final_houseno = '';

                        if($row['house_lot_number'] != "") {
                            $final_houseno = $row['house_lot_number'];
                        }

                        if($row['house_village_name'] != "") {
                            $final_houseno = $final_houseno.' '.$row['house_village_name'];
                        }

                        if($row['apartment_unit_number'] != "") {
                            $final_houseno = $final_houseno.' '.$row['apartment_unit_number'];
                        }

                        if($row['apartment_floor_number'] != "") {
                            $final_houseno = $final_houseno.' '.$row['apartment_floor_number'];
                        }

                        if($row['apartment_building_name'] != "") {
                            $final_houseno = $final_houseno.' '.$row['apartment_building_name'];
                        }

                        if($row['current_address_house_number'] != "") {
                            $final_houseno = $final_houseno.' '.$row['current_address_house_number'];
                        }

                        //42 = CESU BOT
                        $c = Records::create([
                            'is_confidential' => 0,
                            'user_id' => 42,
                            'status' => 'pending',
                            'lname' => mb_strtoupper(str_replace(['.',':'], '', $row['last_name'])),
                            'fname' => str_replace(['.',':'], '', $row['first_name']),
                            'mname' => (!is_null($mname)) ? mb_strtoupper($row['middle_name']) : NULL,
                            'gender' => mb_strtoupper($row['sex']),
                            'isPregnant' => ($row['illness_pregnancy'] == 'TRUE') ? 1 : 0,
                            'cs' => ($row['civil_status'] != '') ? mb_strtoupper($row['civil_status']) : 'SINGLE',
                            'nationality' => ($row['nationality'] != '') ? mb_strtoupper($row['nationality']) : 'FILIPINO',
                            'bdate' => $bdate,
                            'mobile' => $row['cellphone_number'],
                            'phoneno' => $row['telephone_number'],
                            'email' => $row['email_address'],
                            'philhealth' => $row['philhealth_number'],
                            'address_houseno' => mb_strtoupper($final_houseno),
                            'address_street' => mb_strtoupper($row['current_address_street']),
                            'address_brgy' => mb_strtoupper($row['current_address_barangay']),
                            'address_city' => 'GENERAL TRIAS',
                            'address_cityjson' => '042108',
                            'address_province' => 'CAVITE',
                            'address_provincejson' => '0421',
                            'permaaddressDifferent' => 0,
                            'permaaddress_houseno' => mb_strtoupper($final_houseno),
                            'permaaddress_street' => mb_strtoupper($row['current_address_street']),
                            'permaaddress_brgy' => mb_strtoupper($row['current_address_barangay']),
                            'permaaddress_city' => 'GENERAL TRIAS',
                            'permaaddress_cityjson' => '042108',
                            'permaaddress_province' => 'CAVITE',
                            'permaaddress_provincejson' => '0421',
                            'permamobile' => $row['cellphone_number'],
                            'permaphoneno' => ($row['telephone_number'] != "") ? $row['telephone_number'] : NULL,
                            'permaemail' => ($row['email_address'] != "") ? $row['email_address'] : NULL,
                            'hasOccupation' => ($row['occupation'] != "") ? 1 : 0,
                            'occupation' => ($row['occupation'] != "") ? mb_strtoupper($row['occupation']) : NULL,
                            'worksInClosedSetting' => 0,
                            'occupation_lotbldg' => NULL,
                            'occupation_street' => ($row['work_address'] != "") ? mb_strtoupper($row['work_address']) : NULL,
                            'occupation_brgy' => NULL,
                            'occupation_city' => NULL,
                            'occupation_cityjson' => NULL,
                            'occupation_province' => NULL,
                            'occupation_provincejson' => NULL,
                            'occupation_name' => ($row['employer_name'] != "") ? mb_strtoupper($row['employer_name']) : NULL,
                            'occupation_mobile' => ($row['office_phone'] != "") ? mb_strtoupper($row['office_phone']) : NULL,
                            'occupation_email' => NULL,
                            
                            'natureOfWork' => NULL,
                            'natureOfWorkIfOthers' => NULL,

                            'vaccinationDate1',
                            'vaccinationName1',
                            'vaccinationNoOfDose1',
                            'vaccinationFacility1',
                            'vaccinationRegion1',
                            'haveAdverseEvents1',

                            'vaccinationDate2',
                            'vaccinationName2',
                            'vaccinationNoOfDose2',
                            'vaccinationFacility2',
                            'vaccinationRegion2',
                            'haveAdverseEvents2',

                            'vaccinationDate3',
                            'vaccinationName3',
                            'vaccinationNoOfDose3',
                            'vaccinationFacility3',
                            'vaccinationRegion3',
                            'haveAdverseEvents3',

                            'vaccinationDate4',
                            'vaccinationName4',
                            'vaccinationNoOfDose4',
                            'vaccinationFacility4',
                            'vaccinationRegion4',
                            'haveAdverseEvents4',

                            'remarks' => NULL,
                            'sharedOnId' => NULL,

                            'isHCW' => ($row['sp_hcw'] == 'TRUE') ? 1 : 0,
                            'isPriority' => 0,
                            'isindg' => 0,
                            'indg_specify' => NULL,

                            'address_lat' => $row['current_address_lat'],
                            'address_lng' => $row['current_address_lng'],
                            'address_region_psgc' => $row['current_address_region_psgc'],
                            'address_province_psgc' => $row['current_address_province_psgc'],
                            'address_muncity_psgc' => $row['current_address_city_psgc'],
                            'address_brgy_psgc' => $row['current_address_barangay_psgc'],

                            'perma_address_lat' => NULL,
                            'perma_address_lng' => NULL,
                            'perma_address_region_psgc' => NULL,
                            'perma_address_province_psgc' => NULL,
                            'perma_address_muncity_psgc' => NULL,
                            'perma_address_brgy_psgc' => NULL,
                            
                            'occupation_address_lat' => $row['work_address_lat'],
                            'occupation_address_lng' => $row['work_address_lng'],
                            'occupation_address_region_psgc' => NULL,
                            'occupation_address_province_psgc' => NULL,
                            'occupation_address_muncity_psgc' => NULL,
                            'occupation_address_brgy_psgc' => NULL,

                            'from_tkc' => 1,
                        ]);
                    }

                    $existing_case_check = Forms::where('records_id', $c->id)->first();

                    $timestamp = Carbon::parse($row['created_at']);

                    //get age in years, month, days
                    $birthdate = Carbon::parse($c->bdate);
                    $currentDate = Carbon::now($row['created_at']);

                    $get_ageyears = $birthdate->diffInYears($currentDate);
                    $get_agemonths = $birthdate->diffInMonths($currentDate);
                    $get_agedays = $birthdate->diffInDays($currentDate);

                    //CREATE NEW FORM
                    $create_form = Forms::create([
                        'from_tkc' => 1,
                        'system_isverified' => 1,

                        'isPriority' => 0,
                        'reinfected' => 0,
                        'morbidityMonth' => Carbon::parse($row['created_at'])->format('Y-m-d'),
                        'morbidityTime' => Carbon::parse($row['created_at'])->format('Y-m-d H:i:s'),
                        'majikCode' => NULL, //FOR ANTIGEN
                        //'updated_by' => $row['updated_by'],
                        'status' => 'PENDING',
                        'dateReported' => Carbon::parse($row['date_result_received'])->format('Y-m-d H:i:s'),
                        'status_by' => $row['status_by'],
                        'status_remarks' => $row['status_remarks'],
                        'user_id' => 42,
                        'records_id' => $c->id,
                        'isExported' => 0,
                        //'exportedDate' => $row['exportedDate'],
                        'isPresentOnSwabDay' => 0,
                        'isForHospitalization' => 0,
                        'drunit' => ($row['disease_reporting_unit']) ? mb_strtoupper($row['disease_reporting_unit']) : mb_strtoupper($row['nonhealth_dru']),
                        'drregion' => $row['drregion'],
                        'drprovince' => $row['drprovince'],
                        'interviewerName' => mb_strtoupper($row['created_by_name']),
                        'interviewerMobile' => '09190664324',
                        'interviewDate' => date('Y-m-d', strtotime($row['investigation_date'])),
                        //'informantName' => $row['informantName'],
                        //'informantRelationship' => $row['informantRelationship'],
                        //'informantMobile' => $row['informantMobile'],
                        'existingCaseList' => 2,
                        //'ecOthersRemarks' => $row['ecOthersRemarks'],
                        'pType' => 'PROBABLE',
                        //'ccType' => $row['ccType'],
                        //'is_primarycc' => $row['is_primarycc'],
                        //'is_secondarycc' => $row['is_secondarycc'],
                        //'is_tertiarycc' => $row['is_tertiarycc'],
                        //'is_primarycc_date' => $row['is_primarycc_date'],
                        //'is_secondarycc_date' => $row['is_secondarycc_date'],
                        //'is_tertiarycc_date' => $row['is_tertiarycc_date'],
                        //'is_primarycc_date_set' => $row['is_primarycc_date_set'],
                        //'is_secondarycc_date_set' => $row['is_secondarycc_date_set'],
                        //'is_tertiarycc_date_set' => $row['is_tertiarycc_date_set'],
                        'testingCat' => 'A4',
                        'havePreviousCovidConsultation' => 0,
                        'dateOfFirstConsult' => $row['dateOfFirstConsult'],
                        'facilityNameOfFirstConsult' => $row['facilityNameOfFirstConsult'],
                        'dispoType' => $row['dispoType'],
                        'dispoName' => $row['dispoName'],
                        'dispoDate' => $row['dispoDate'],
                        'healthStatus' => $row['healthStatus'],
                        'caseClassification' => $row['caseClassification'],
                        'date_of_positive' => $row['date_of_positive'],
                        'confirmedVariantName' => $row['confirmedVariantName'],
                        'vaccinationDate1' => $row['vaccinationDate1'],
                        'vaccinationName1' => $row['vaccinationName1'],
                        'vaccinationNoOfDose1' => $row['vaccinationNoOfDose1'],
                        'vaccinationFacility1' => $row['vaccinationFacility1'],
                        'vaccinationRegion1' => $row['vaccinationRegion1'],
                        'haveAdverseEvents1' => $row['haveAdverseEvents1'],
                        'vaccinationDate2' => $row['vaccinationDate2'],
                        'vaccinationName2' => $row['vaccinationName2'],
                        'vaccinationNoOfDose2' => $row['vaccinationNoOfDose2'],
                        'vaccinationFacility2' => $row['vaccinationFacility2'],
                        'vaccinationRegion2' => $row['vaccinationRegion2'],
                        'haveAdverseEvents2' => $row['haveAdverseEvents2'],
                        'isHealthCareWorker' => $row['isHealthCareWorker'],
                        'healthCareCompanyName' => $row['healthCareCompanyName'],
                        'healthCareCompanyLocation' => $row['healthCareCompanyLocation'],
                        'isOFW' => $row['isOFW'],
                        'OFWCountyOfOrigin' => $row['OFWCountyOfOrigin'],
                        'OFWPassportNo' => $row['OFWPassportNo'],
                        'ofwType' => $row['ofwType'],
                        'isFNT' => $row['isFNT'],
                        'FNTCountryOfOrigin' => $row['FNTCountryOfOrigin'],
                        'FNTPassportNo' => $row['FNTPassportNo'],
                        'isLSI' => $row['isLSI'],
                        'LSICity' => $row['LSICity'],
                        'LSICityjson' => $row['LSICityjson'],
                        'LSIProvince' => $row['LSIProvince'],
                        'LSIProvincejson' => $row['LSIProvincejson'],
                        'lsiType' => $row['lsiType'],
                        'isLivesOnClosedSettings' => $row['isLivesOnClosedSettings'],
                        'institutionType' => $row['institutionType'],
                        'institutionName' => $row['institutionName'],
                        'isIndg' => $row['isIndg'],
                        'indgSpecify' => $row['indgSpecify'],
                        'dateOnsetOfIllness' => $row['dateOnsetOfIllness'],
                        'SAS' => $row['SAS'],
                        'SASFeverDeg' => $row['SASFeverDeg'],
                        'SASOtherRemarks' => $row['SASOtherRemarks'],
                        'COMO' => $row['COMO'],
                        'COMOOtherRemarks' => $row['COMOOtherRemarks'],
                        'PregnantLMP' => $row['PregnantLMP'],
                        'PregnantEDC' => $row['PregnantEDC'],
                        'PregnantHighRisk' => $row['PregnantHighRisk'],
                        'diagWithSARI' => $row['diagWithSARI'],
                        'imagingDoneDate' => $row['imagingDoneDate'],
                        'imagingDone' => $row['imagingDone'],
                        'imagingResult' => $row['imagingResult'],
                        'imagingOtherFindings' => $row['imagingOtherFindings'],
                        'testedPositiveUsingRTPCRBefore' => $row['testedPositiveUsingRTPCRBefore'],
                        'testedPositiveSpecCollectedDate' => $row['testedPositiveSpecCollectedDate'],
                        'testedPositiveLab' => $row['testedPositiveLab'],
                        'testedPositiveNumOfSwab' => $row['testedPositiveNumOfSwab'],
                        'testDateCollected1' => $row['testDateCollected1'],
                        'testDateReleased1' => $row['testDateReleased1'],
                        'oniTimeCollected1' => $row['oniTimeCollected1'],
                        'testLaboratory1' => $row['testLaboratory1'],
                        'testType1' => $row['testType1'],
                        'testTypeAntigenRemarks1' => $row['testTypeAntigenRemarks1'],
                        'antigenKit1' => $row['antigenKit1'],
                        'antigen_id1' => $row['antigen_id1'],
                        'antigenLotNo1' => $row['antigenLotNo1'],
                        'testTypeOtherRemarks1' => $row['testTypeOtherRemarks1'],
                        'testResult1' => $row['testResult1'],
                        'testResultOtherRemarks1' => $row['testResultOtherRemarks1'],
                        'testDateCollected2' => $row['testDateCollected2'],
                        'oniTimeCollected2' => $row['oniTimeCollected2'],
                        'testDateReleased2' => $row['testDateReleased2'],
                        'testLaboratory2' => $row['testLaboratory2'],
                        'testType2' => $row['testType2'],
                        'testTypeAntigenRemarks2' => $row['testTypeAntigenRemarks2'],
                        'antigenKit2' => $row['antigenKit2'],
                        'antigen_id2' => $row['antigen_id2'],
                        'antigenLotNo2' => $row['antigenLotNo2'],
                        'testTypeOtherRemarks2' => $row['testTypeOtherRemarks2'],
                        'testResult2' => $row['testResult2'],
                        'testResultOtherRemarks2' => $row['testResultOtherRemarks2'],
                        'outcomeCondition' => $row['outcomeCondition'],
                        'outcomeRecovDate' => $row['outcomeRecovDate'],
                        'outcomeDeathDate' => $row['outcomeDeathDate'],
                        'deathImmeCause' => $row['deathImmeCause'],
                        'deathAnteCause' => $row['deathAnteCause'],
                        'deathUndeCause' => $row['deathUndeCause'],
                        'contriCondi' => $row['contriCondi'],
                        'expoitem1' => $row['expoitem1'],
                        'expoDateLastCont' => $row['expoDateLastCont'],
                        'expoitem2' => $row['expoitem2'],
                        'intCountry' => $row['intCountry'],
                        'intDateFrom' => $row['intDateFrom'],
                        'intDateTo' => $row['intDateTo'],
                        'intWithOngoingCovid' => $row['intWithOngoingCovid'],
                        'intVessel' => $row['intVessel'],
                        'intVesselNo' => $row['intVesselNo'],
                        'intDateDepart' => $row['intDateDepart'],
                        'intDateArrive' => $row['intDateArrive'],
                        'placevisited' => $row['placevisited'],
                        'locName1' => $row['locName1'],
                        'locAddress1' => $row['locAddress1'],
                        'locDateFrom1' => $row['locDateFrom1'],
                        'locDateTo1' => $row['locDateTo1'],
                        'locWithOngoingCovid1' => $row['locWithOngoingCovid1'],
                        'locName2' => $row['locName2'],
                        'locAddress2' => $row['locAddress2'],
                        'locDateFrom2' => $row['locDateFrom2'],
                        'locDateTo2' => $row['locDateTo2'],
                        'locWithOngoingCovid2' => $row['locWithOngoingCovid2'],
                        'locName3' => $row['locName3'],
                        'locAddress3' => $row['locAddress3'],
                        'locDateFrom3' => $row['locDateFrom3'],
                        'locDateTo3' => $row['locDateTo3'],
                        'locWithOngoingCovid3' => $row['locWithOngoingCovid3'],
                        'locName4' => $row['locName4'],
                        'locAddress4' => $row['locAddress4'],
                        'locDateFrom4' => $row['locDateFrom4'],
                        'locDateTo4' => $row['locDateTo4'],
                        'locWithOngoingCovid4' => $row['locWithOngoingCovid4'],
                        'locName5' => $row['locName5'],
                        'locAddress5' => $row['locAddress5'],
                        'locDateFrom5' => $row['locDateFrom5'],
                        'locDateTo5' => $row['locDateTo5'],
                        'locWithOngoingCovid5' => $row['locWithOngoingCovid5'],
                        'locName6' => $row['locName6'],
                        'locAddress6' => $row['locAddress6'],
                        'locDateFrom6' => $row['locDateFrom6'],
                        'locDateTo6' => $row['locDateTo6'],
                        'locWithOngoingCovid6' => $row['locWithOngoingCovid6'],
                        'locName7' => $row['locName7'],
                        'locAddress7' => $row['locAddress7'],
                        'locDateFrom7' => $row['locDateFrom7'],
                        'locDateTo7' => $row['locDateTo7'],
                        'locWithOngoingCovid7' => $row['locWithOngoingCovid7'],
                        'localVessel1' => $row['localVessel1'],
                        'localVesselNo1' => $row['localVesselNo1'],
                        'localOrigin1' => $row['localOrigin1'],
                        'localDateDepart1' => $row['localDateDepart1'],
                        'localDest1' => $row['localDest1'],
                        'localDateArrive1' => $row['localDateArrive1'],
                        'localVessel2' => $row['localVessel2'],
                        'localVesselNo2' => $row['localVesselNo2'],
                        'localOrigin2' => $row['localOrigin2'],
                        'localDateDepart2' => $row['localDateDepart2'],
                        'localDest2' => $row['localDest2'],
                        'localDateArrive2' => $row['localDateArrive2'],
                        'contact1Name' => $row['contact1Name'],
                        'contact1No' => $row['contact1No'],
                        'contact2Name' => $row['contact2Name'],
                        'contact2No' => $row['contact2No'],
                        'contact3Name' => $row['contact3Name'],
                        'contact3No' => $row['contact3No'],
                        'contact4Name' => $row['contact4Name'],
                        'contact4No' => $row['contact4No'],
                        'remarks' => $row['remarks'],
                        'facility_remarks' => $row['facility_remarks'],
                        'ccid_list' => $row['ccid_list'],
                        'is_disobedient' => $row['is_disobedient'],
                        'disobedient_remarks' => $row['disobedient_remarks'],
                        'antigenqr' => $row['antigenqr'],
                        'sent' => $row['sent'],

                        'age_years' => $get_ageyears,
                        'age_months' => $get_agemonths,
                        'age_days' => $get_agedays,

                        'tkc_id' => $row['tkc_id'],
                        'tkc_lgu_id' => $row['lgu_id'],
                        'tkc_casetracking_status' => $row['case_tracking_status'],
                        'tkc_created_by' => $row['created_by_name'],
                        'tkc_date_verified' => $row['tkc_date_verified'],
                        'tkc_verified_assessment' => $row['verified_assessment'],
                        'tkc_nonhealth_dru' => $row['tkc_nonhealth_dru'],
                        'tkc_sentinel_reporting_unit' => $row['sentinel_reporting_unit'],
                        'system_isverified' => $row['system_isverified'],
                        'from_tkc' => 1,
                        
                        'morb_week' => $timestamp->format('W'),
                        'morb_month' => $timestamp->format('n'),
                        'year' => $timestamp->format('Y'),
                    ]);
                }
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
