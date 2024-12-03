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
    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id; // Store the string in a property
    }

    public function model(array $row)
    {
        if($row['hasbeenpositive'] == "true") {
            //Check if TKC ID exists
            $check1 = Forms::where('tkc_id', $row['tkc_id'])
            ->where('from_tkc', 1)
            ->first();

            if(!($check1)) {
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

                        //Check Vaccination
                        $vac_array = collect();
                        $table_params = [];

                        if($row['is_vaccinated'] == 'YES') {
                            $vacdate_array = explode("::", $row['vaccination_date']);
                            $vacname_array = explode("::", $row['vaccine_name']);

                            foreach($vacdate_array as $ind => $vd) {
                                $vac_array->push([
                                    'date' => Carbon::parse($vd)->format('Y-m-d'),
                                    'brand' => Records::convertTkcVaccineToSystem($vacname_array[$ind]),
                                ]);
                            }

                            $vac_array = collect($vac_array)->sortBy('date')->toArray();

                            foreach($vac_array as $ind => $vac) {
                                if($ind == 0) {
                                    $table_params = $table_params + [
                                        'vaccinationDate1' => $vac['date'],
                                        'vaccinationName1' => $vac['brand'],
                                        'vaccinationNoOfDose1' => 1,
                                        'vaccinationFacility1' => NULL,
                                        'vaccinationRegion1' => NULL,
                                        'haveAdverseEvents1' => 0,
                                    ];
                                }
                                else if($ind == 1) {
                                    $table_params = $table_params + [
                                        'vaccinationDate2' => $vac['date'],
                                        'vaccinationName2' => $vac['brand'],
                                        'vaccinationNoOfDose2' => 2,
                                        'vaccinationFacility2' => NULL,
                                        'vaccinationRegion2' => NULL,
                                        'haveAdverseEvents2' => 0,
                                    ];
                                }
                            }
                        }

                        $table_params = $table_params + [
                            'is_confidential' => 0,
                            'user_id' => $this->user_id,
                            'status' => 'approved',
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
                            'worksInClosedSetting' => 'NO',
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
                        ];

                        //42 = CESU BOT
                        $c = Records::create($table_params);
                    }
                    else {
                        $c = $check;
                    }

                    $existing_case_check = Forms::where('records_id', $c->id)->first();

                    $timestamp = Carbon::parse($row['created_at']);

                    //get age in years, month, days
                    $birthdate = Carbon::parse($c->bdate);
                    $currentDate = Carbon::parse($row['created_at']);

                    $get_ageyears = $birthdate->diffInYears($currentDate);
                    $get_agemonths = $birthdate->diffInMonths($currentDate);
                    $get_agedays = $birthdate->diffInDays($currentDate);

                    //Symptoms Array
                    $symptoms_array = [];
                    if($row['symptoms_fever']) {
                        $symptoms_array[] = 'Fever';
                    }

                    if($row['symptoms_cough']) {
                        $symptoms_array[] = 'Cough';
                    }

                    if($row['symptoms_coryza']) {
                        $symptoms_array[] = 'Coryza';
                    }

                    if($row['symptoms_sorethroat']) {
                        $symptoms_array[] = 'Sore throat';
                    }

                    if($row['symptoms_dyspnea']) {
                        $symptoms_array[] = 'Dyspnea';
                    }

                    if($row['symptoms_lossoftaste']) {
                        $symptoms_array[] = 'Ageusia (Loss of Taste)';
                    }

                    if($row['symptoms_lossofsmell']) {
                        $symptoms_array[] = 'Anosmia (Loss of Smell)';
                    }

                    if($row['symptoms_myalgia']) {
                        $symptoms_array[] = 'Myalgia';
                    }

                    if($row['symptoms_fatigue']) {
                        $symptoms_array[] = 'Fatigue';
                    }

                    if($row['symptoms_generalweakness']) {
                        $symptoms_array[] = 'General Weakness';
                    }

                    if($row['symptoms_headache']) {
                        $symptoms_array[] = 'Headache';
                    }

                    if($row['symptoms_diarrhea']) {
                        $symptoms_array[] = 'Diarrhea';
                    }

                    if($row['symptoms_nausea']) {
                        $symptoms_array[] = 'Nausea';
                    }

                    if($row['symptoms_alteredmentalstate']) {
                        $symptoms_array[] = 'Altered Mental Status';
                    }

                    $como_array = [];
                    if($row['illness_hypertension']) {
                        $como_array[] = 'Hypertension';
                    }

                    if($row['illness_diabetes']) {
                        $como_array[] = 'Diabetes';
                    }

                    if($row['illness_heart']) {
                        $como_array[] = 'Heart Disease';
                    }

                    if($row['illness_lungs']) {
                        $como_array[] = 'Lung Disease';
                    }

                    if($row['illness_gastrointestinal']) {
                        $como_array[] = 'Gastrointestinal';
                    }

                    if($row['illness_genitourinary']) {
                        $como_array[] = 'Genito-urinary';
                    }

                    if($row['illness_cancer']) {
                        $como_array[] = 'Cancer';
                    }

                    if($row['illness_neurological']) {
                        $como_array[] = 'Neurological Disease';
                    }

                    if($row['illness_liver']) {
                        $como_array[] = 'Dialysis';
                    }

                    if($row['illness_others']) {
                        $como_array[] = 'Others';
                    }

                    //Outcome
                    if($row['outcome'] == 'Not applicable' || $row['outcome'] == 'Lost to follow-up' || $row['outcome'] == 'Transferred') {
                        $outcome = 'Active';
                    }
                    else if($row['outcome'] == 'Improving' || $row['outcome'] == 'Recovered') {
                        $outcome = 'Recovered';
                    }
                    else {
                        $outcome = $row['outcome'];
                    }

                    //Lab Result Array
                    //Case Classification
                    /*
                    if($row['hasbeenpositive']) {
                        $classification = 'Confirmed';
                    }
                    */

                    $classification = 'Suspect';

                    $labtest_final_array = [];

                    $lab_results = explode('::', $row['lab_result']);
                    $lab_names = explode('::', $row['lab_name']);
                    $lab_datecollecteds = explode('::', $row['date_specimen_collected']);
                    $lab_datereceiveds = explode('::', $row['date_specimen_received']);
                    $lab_dateresultreceiveds = explode('::', $row['date_result_received']);
                    $date_of_positive = NULL;

                    $lab_group = [];

                    foreach(explode("::", $row['lab_info_type']) as $ind => $l) {
                        if($l == 'RTPCR') {
                            $l = 'OPS AND NPS';
                        }

                        $lab_group[] = [
                            'test_type' => $l,
                            'date_collected' => Carbon::parse($lab_datecollecteds[$ind])->format('Y-m-d'),
                            'lab_name' => $lab_names[$ind],
                            'lab_reult' => mb_strtoupper($lab_results[$ind]),
                        ];
                    }

                    /*
                    foreach(explode("::", $row['lab_info_type']) as $ind => $l) {
                        if($l == 'RTPCR') {
                            $l = 'OPS AND NPS';
                        }

                        if($ind == 0) {
                            $testDateCollected = Carbon::parse($lab_datecollecteds[0])->format('Y-m-d');
                            $testLab = $lab_names[0];
                            $labResult = mb_strtoupper($lab_results[0]);
                            $testType = $l;
                        }

                        if($lab_results[$ind] == 'Positive' && $l == 'OPS AND NPS') {
                            $classification = 'Confirmed';
                            $date_of_positive = Carbon::parse($lab_dateresultreceiveds[$ind])->format('Y-m-d');
                            $testType = $l;
                            break;
                        }
                        else if($lab_results[$ind] == 'Positive' && $l == 'ANTIGEN') {
                            $classification = 'Probable';
                            $date_of_positive = Carbon::parse($lab_dateresultreceiveds[$ind])->format('Y-m-d');
                            $testType = $l;
                            break;
                        }
                    }
                    */

                    $dateReported = explode("::", $row['date_result_received']);
                    $invDate_array = explode("::", $row['investigation_date']);

                    if($row['disease_reporting_unit']) {
                        $dru = mb_strtoupper($row['disease_reporting_unit']);
                    }
                    else if($row['nonhealth_dru']) {
                        $dru = mb_strtoupper($row['nonhealth_dru']);
                    }
                    else {
                        $dru = 'UNSPECIFIED';
                    }

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
                        'status' => 'approved',
                        'dateReported' => (!is_null($dateReported[0])) ? Carbon::parse($dateReported[0])->format('Y-m-d H:i:s') : Carbon::parse($row['created_at'])->format('Y-m-d'),
                        //'status_by' => $row['status_by'],
                        //'status_remarks' => $row['status_remarks'],
                        'user_id' => $this->user_id,
                        'records_id' => $c->id,
                        'isExported' => 0,
                        //'exportedDate' => $row['exportedDate'],
                        'isPresentOnSwabDay' => 0,
                        'isForHospitalization' => 0,
                        'drunit' => $dru,
                        //'drregion' => $row['drregion'],
                        //'drprovince' => $row['drprovince'],
                        'interviewerName' => mb_strtoupper($row['created_by_name']),
                        'interviewerMobile' => '09190664324',
                        'interviewDate' => Carbon::parse($invDate_array[0])->format('Y-m-d'),
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
                        'havePreviousCovidConsultation' => '0',
                        'dateOfFirstConsult' => NULL,
                        'facilityNameOfFirstConsult' => NULL,
                        'dispoType' => 4,
                        //'dispoName' => $row['dispoName'],
                        //'dispoDate' => $row['dispoDate'],
                        'healthStatus' => (!$row['health_status']) ? 'Asymptomatic' : $row['health_status'],
                        'caseClassification' => $classification,
                        'date_of_positive' => $date_of_positive,
                        
                        'isHealthCareWorker' => ($row['sp_hcw']) ? '1' : '0',
                        'healthCareCompanyName' => ($row['sp_hcw_healthfacility']) ? mb_strtoupper($row['sp_hcw_healthfacility']) : NULL,
                        'healthCareCompanyLocation' => ($row['sp_hcw_healthfacility_address']) ? mb_strtoupper($row['sp_hcw_healthfacility_address']) : NULL,
                        'isOFW' => '0',
                        //'OFWCountyOfOrigin' => $row['OFWCountyOfOrigin'],
                        //'OFWPassportNo' => $row['OFWPassportNo'],
                        //'ofwType' => $row['ofwType'],
                        'isFNT' => '0',
                        //'FNTCountryOfOrigin' => $row['FNTCountryOfOrigin'],
                        //'FNTPassportNo' => $row['FNTPassportNo'],
                        'isLSI' => ($row['sp_lsi']) ? '1' : '0',
                        //'LSICity' => $row['LSICity'],
                        //'LSICityjson' => $row['LSICityjson'],
                        //'LSIProvince' => $row['LSIProvince'],
                        //'LSIProvincejson' => $row['LSIProvincejson'],
                        //'lsiType' => $row['lsiType'],
                        'isLivesOnClosedSettings' => ($row['sp_closedsettings']) ? '1' : '0',
                        'institutionType' => $row['sp_closedsetting_type'],
                        'institutionName' => $row['sp_closedsetting_name'],
                        'isIndg' => '0',
                        //'indgSpecify' => $row['indgSpecify'],
                        'dateOnsetOfIllness' => ($row['symptoms_onset_at']) ? Carbon::parse($row['symptoms_onset_at'])->format('Y-m-d') : NULL,
                        'SAS' => (!empty($symptoms_array)) ? implode(",", $symptoms_array) : NULL,
                        //'SASFeverDeg' => $row['SASFeverDeg'],
                        //'SASOtherRemarks' => $row['SASOtherRemarks'],
                        'COMO' => (!empty($como_array)) ? implode(",", $como_array) : 'None',
                        //'COMOOtherRemarks' => $row['COMOOtherRemarks'],
                        //'PregnantLMP' => $row['PregnantLMP'],
                        //'PregnantEDC' => $row['PregnantEDC'],
                        'PregnantHighRisk' => '0',
                        'diagWithSARI' => ($row['symptoms_sari']) ? '1' : '0',
                        //'imagingDoneDate' => $row['imagingDoneDate'],
                        'imagingDone' => 'None',
                        //'imagingResult' => $row['imagingResult'],
                        //'imagingOtherFindings' => $row['imagingOtherFindings'],
                        'testedPositiveUsingRTPCRBefore' => '0',
                        //'testedPositiveSpecCollectedDate' => $row['testedPositiveSpecCollectedDate'],
                        //'testedPositiveLab' => $row['testedPositiveLab'],
                        'testedPositiveNumOfSwab' => '0',
                        'testDateCollected1' => $testDateCollected,
                        //'testDateReleased1' => $row['testDateReleased1'],
                        //'oniTimeCollected1' => $row['oniTimeCollected1'],
                        'testLaboratory1' => ($testLab) ? mb_strtoupper($testLab) : NULL,
                        'testType1' => $testType,
                        //'testTypeAntigenRemarks1' => $row['testTypeAntigenRemarks1'],
                        //'antigenKit1' => $row['antigenKit1'],
                        //'antigen_id1' => $row['antigen_id1'],
                        //'antigenLotNo1' => $row['antigenLotNo1'],
                        //'testTypeOtherRemarks1' => $row['testTypeOtherRemarks1'],
                        'testResult1' => $labResult,
                        //'testResultOtherRemarks1' => $row['testResultOtherRemarks1'],

                        //'testDateCollected2' => $row['testDateCollected2'],
                        //'oniTimeCollected2' => $row['oniTimeCollected2'],
                        //'testDateReleased2' => $row['testDateReleased2'],
                        //'testLaboratory2' => $row['testLaboratory2'],
                        //'testType2' => $row['testType2'],
                        //'testTypeAntigenRemarks2' => $row['testTypeAntigenRemarks2'],
                        //'antigenKit2' => $row['antigenKit2'],
                        //'antigen_id2' => $row['antigen_id2'],
                        //'antigenLotNo2' => $row['antigenLotNo2'],
                        //'testTypeOtherRemarks2' => $row['testTypeOtherRemarks2'],
                        //'testResult2' => $row['testResult2'],
                        //'testResultOtherRemarks2' => $row['testResultOtherRemarks2'],

                        'outcomeCondition' => $outcome,
                        'outcomeRecovDate' => ($row['date_recovered']) ? Carbon::parse($row['date_recovered'])->format('Y-m-d') : NULL,
                        'outcomeDeathDate' => ($row['date_death']) ? Carbon::parse($row['date_death'])->format('Y-m-d') : NULL,
                        'deathImmeCause' => $row['cause_of_death_immediate'],
                        'deathAnteCause' => $row['cause_of_death_antecedent'],
                        'deathUndeCause' => $row['cause_of_death_underlying'],
                        'contriCondi' => $row['additional_medical_info'],
                        'expoitem1' => 2,
                        //'expoDateLastCont' => $row['expoDateLastCont'],
                        'expoitem2' => '0',
                        'intWithOngoingCovid' => 'N/A',
                        //'locName1' => $row['locName1'],
                        //'locAddress1' => $row['locAddress1'],
                        //'locDateFrom1' => $row['locDateFrom1'],
                        //'locDateTo1' => $row['locDateTo1'],
                        'locWithOngoingCovid1' => 'N/A',
                        //'locName2' => $row['locName2'],
                        //'locAddress2' => $row['locAddress2'],
                        //'locDateFrom2' => $row['locDateFrom2'],
                        //'locDateTo2' => $row['locDateTo2'],
                        'locWithOngoingCovid2' => 'N/A',
                        //'locName3' => $row['locName3'],
                        //'locAddress3' => $row['locAddress3'],
                        //'locDateFrom3' => $row['locDateFrom3'],
                        //'locDateTo3' => $row['locDateTo3'],
                        'locWithOngoingCovid3' => 'N/A',
                        //'locName4' => $row['locName4'],
                        //'locAddress4' => $row['locAddress4'],
                        //'locDateFrom4' => $row['locDateFrom4'],
                        //'locDateTo4' => $row['locDateTo4'],
                        'locWithOngoingCovid4' => 'N/A',
                        //'locName5' => $row['locName5'],
                        //'locAddress5' => $row['locAddress5'],
                        //'locDateFrom5' => $row['locDateFrom5'],
                        //'locDateTo5' => $row['locDateTo5'],
                        'locWithOngoingCovid5' => 'N/A',
                        //'locName6' => $row['locName6'],
                        //'locAddress6' => $row['locAddress6'],
                        //'locDateFrom6' => $row['locDateFrom6'],
                        //'locDateTo6' => $row['locDateTo6'],
                        'locWithOngoingCovid6' => 'N/A',
                        //'locName7' => $row['locName7'],
                        //'locAddress7' => $row['locAddress7'],
                        //'locDateFrom7' => $row['locDateFrom7'],
                        //'locDateTo7' => $row['locDateTo7'],
                        'locWithOngoingCovid7' => 'N/A',
                        //'localVessel1' => $row['localVessel1'],
                        //'localVesselNo1' => $row['localVesselNo1'],
                        //'localOrigin1' => $row['localOrigin1'],
                        //'localDateDepart1' => $row['localDateDepart1'],
                        //'localDest1' => $row['localDest1'],
                        //'localDateArrive1' => $row['localDateArrive1'],
                        //'localVessel2' => $row['localVessel2'],
                        //'localVesselNo2' => $row['localVesselNo2'],
                        //'localOrigin2' => $row['localOrigin2'],
                        //'localDateDepart2' => $row['localDateDepart2'],
                        //'localDest2' => $row['localDest2'],
                        //'localDateArrive2' => $row['localDateArrive2'],
                        'remarks' => $row['investigation_remarks'],
                        //'facility_remarks' => $row['facility_remarks'],
                        //'ccid_list' => $row['ccid_list'],
                        'is_disobedient' => 0,
                        //'disobedient_remarks' => $row['disobedient_remarks'],
                        //'antigenqr' => $row['antigenqr'],
                        'sent' => 0,

                        'age_years' => $get_ageyears,
                        'age_months' => $get_agemonths,
                        'age_days' => $get_agedays,

                        'tkc_id' => $row['tkc_id'],
                        'tkc_lgu_id' => $row['lgu_id'],
                        'tkc_casetracking_status' => $row['case_tracking_status'],
                        'tkc_created_by' => ($row['created_by_name']) ? mb_strtoupper($row['created_by_name']) : NULL,
                        'tkc_date_verified' => ($row['date_verified']) ? Carbon::parse($row['date_verified'])->format('Y-m-d H:i:s') : NULL,
                        'tkc_verified_assessment' => $row['verified_assessment'],
                        'tkc_nonhealth_dru' => $row['nonhealth_dru'],
                        'tkc_sentinel_reporting_unit' => $row['sentinel_reporting_unit'],
                        'tkc_outcome' => mb_strtoupper($row['outcome']),
                        //'system_isverified' => $row['system_isverified'],
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
