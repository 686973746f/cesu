<?php

namespace App\Imports;

use App\Models\Nt;
use Carbon\Carbon;
use App\Models\Abd;
use App\Models\Afp;
use App\Models\Nnt;
use App\Models\Ames;
use App\Models\Diph;
use App\Models\Hfmd;
use App\Models\Pert;
use App\Models\Chikv;
use App\Models\Dengue;
use App\Models\Rabies;
use App\Models\Cholera;
use App\Models\Measles;
use App\Models\Meningo;
use App\Models\Typhoid;
use App\Models\Hepatitis;
use App\Models\Influenza;
use App\Models\Rotavirus;
use App\Models\DohFacility;
use Illuminate\Support\Str;
use App\Models\Leptospirosis;
use App\Models\EdcsLaboratoryData;
use App\Models\SevereAcuteRespiratoryInfection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;

class EdcsImport implements WithMultipleSheets, SkipsUnknownSheets
{
    public static function tDate($date) {
        if(!is_null($date) && $date != "" && $date != 'N/A') {
            if(Carbon::hasFormat($date, 'Y-m-d') || Carbon::hasFormat($date, 'm/d/Y')) {
                return date('Y-m-d', strtotime($date));
            }
            else {
                return Carbon::parse(Date::excelToDateTimeObject($date))->format('Y-m-d');
            }
        }
        else {
            return NULL;
        }
    }

    /*
    public static function getMorbMonth($week, $year) {
        $date = Carbon::createFromDate($year, 1, 1);

        // Adjust the date to the first day of the week
        $date->startOfWeek();

        // Add the week number minus one (as weeks are zero-indexed) multiplied by 7 days
        $date->addWeeks($week - 1);

        $month = $date->month;

        return $month;
    }
    */

    public static function getMorbMonth($timestamp) {
        return Carbon::parse($timestamp)->format('n');
    }

    public static function brgySetter($brgy) {
        //BARANGAY SETTER (BECAUSE OF THE POB.)
        if(!is_null($brgy) && !in_array($brgy, ['', 'N/A', 'NONE'])) {
            if(Str::contains($brgy, 'Pob.')) {
                if($brgy == 'Arnaldo Pob.') {
                    $get_brgy = 'ARNALDO POB. (BGY. 7)';
                }
                else if($brgy == 'Bagumbayan Pob.') {
                    $get_brgy = 'BAGUMBAYAN POB. (BGY. 5)';
                }
                else if($brgy == 'Corregidor Pob.') {
                    $get_brgy = 'CORREGIDOR POB. (BGY. 10)';
                }
                else if($brgy == 'Dulong Bayan Pob.') {
                    $get_brgy = 'DULONG BAYAN POB. (BGY. 3)';
                }
                else if($brgy == 'Gov. Ferrer Pob.') {
                    $get_brgy = 'GOV. FERRER POB. (BGY. 1)';
                }
                else if($brgy == 'Ninety Sixth Pob.') {
                    $get_brgy = 'NINETY SIXTH POB. (BGY. 8)';
                }
                else if($brgy == 'Prinza Pob.') {
                    $get_brgy = 'PRINZA POB. (BGY. 9)';
                }
                else if($brgy == 'Sampalucan Pob.') {
                    $get_brgy = 'SAMPALUCAN POB. (BGY. 2)';
                }
                else if($brgy == 'San Gabriel Pob.') {
                    $get_brgy = 'SAN GABRIEL POB. (BGY. 4)';
                }
                else if($brgy == 'Vibora Pob.') {
                    $get_brgy = 'VIBORA POB. (BGY. 6)';
                }
                else {
                    $get_brgy = mb_strtoupper($brgy);
                }
            }
            else {
                $get_brgy = mb_strtoupper($brgy);
            }
        }
        else {
            $get_brgy = NULL;
        }

        return $get_brgy;
    }

    public static function autoMateSubdivision($brgy) {
        if($brgy == 'Arnaldo Pob.') {
            $subd_id = 298;
        }
        else if($brgy == 'Bagumbayan Pob.') {
            $subd_id = 299;
        }
        else if($brgy == 'Corregidor Pob.') {
            $subd_id = 300;
        }
        else if($brgy == 'Dulong Bayan Pob.') {
            $subd_id = 301;
        }
        else if($brgy == 'Gov. Ferrer Pob.') {
            $subd_id = 302;
        }
        else if($brgy == 'Ninety Sixth Pob.') {
            $subd_id = 303;
        }
        else if($brgy == 'Prinza Pob.') {
            $subd_id = 304;
        }
        else if($brgy == 'Sampalucan Pob.') {
            $subd_id = 305;
        }
        else if($brgy == 'San Gabriel Pob.') {
            $subd_id = 306;
        }
        else if($brgy == 'Vibora Pob.') {
            $subd_id = 307;
        }
        else {
            $subd_id = NULL;
        }

        return $subd_id;
    }

    public static function getEdcsFacilityDetails($code, $fname) {
        $s = DohFacility::where('healthfacility_code', $code)
        ->orWhere('facility_name', $fname)
        ->first();

        if($s) {
            return $s;
        }
        else {
            return NULL;
        }
    }

    public function sheets(): array
    {
        return [
            'ABD' => new AbdImport(),
            'abd_view' => new AbdImport(),

            //'AEFI' => new AfpImport(), => NOT ON EDCS ANYMORE
            //'AES' => new AfpImport(), => NOW AMES

            'AFP' => new AfpImport(),
            'afp_view' => new AfpImport(),

            //'AHF' => new AfpImport(), => NOT ON EDCS ANYMORE
            'AMES' => new AmesImport(),
            'ames_view' => new AmesImport(),

            //'ANTHRAX' => new AfpImport(), => NOT ON EDCS ANYMORE
            'CHIKV' => new ChikvImport(),
            'chikungunya_view' => new ChikvImport(),

            'CHOLERA' => new CholeraImport(),
            'cholera_view' => new CholeraImport(),

            'DENGUE' => new DengueImport(),
            'dengue_view' => new DengueImport(),

            'DIPH' => new DiphImport(),
            'diph_view' => new DiphImport(),

            'HEPA' => new HepaImport(),
            'HEPATITIS' => new HepaImport(),
            'hepa_view' => new HepaImport(),

            'HFMD' => new HfmdImport(),
            'hfmd_view' => new HfmdImport(),

            'INFLUENZA' => new InfluenzaImport(),
            'ILI' => new InfluenzaImport(),
            'ili_view' => new InfluenzaImport(),
            
            'LEPTO' => new LeptoImport(),
            'lepto_view' => new LeptoImport(),
            
            //'MALARIA' => new AfpImport(), => NOT ON EDCS ANYMORE
            'MEASLES' => new MeaslesImport(),
            'measles_view' => new MeaslesImport(),

            //'MENINGITIS' => new AfpImport(), => NOT ON EDCS ANYMORE
            'MENINGO' => new MeningoImport(),
            'meningo_view' => new MeningoImport(),

            'NNT' => new NntImport(),
            'non_neonatal_view' => new NntImport(),

            'NT' => new NtImport(),
            'neonatal_view' => new NtImport(),
            
            'PERT' => new PertImport(),
            'PERTUSSIS' => new PertImport(), //MULTIPLE SHEET
            'pertussis_view' => new PertImport(),
            //PSP => NOT ON EDCS ANYMORE
            
            'RABIES' => new RabiesImport(),
            'rabies_view' => new RabiesImport(),

            'ROTA' => new RotaImport(),
            'rotavirus_view.csv' => new RotaImport(),
            
            'TYPHOID' => new TyphoidImport(),
            'typhoid_view' => new TyphoidImport(),

            'LABORATORY' => new LaboratoryImport(),
            'LAB' => new LaboratoryImport(),
            'laboratory_view' => new LaboratoryImport(),

            'sari_view' => new SevereAcuteRespiratoryInfectionImport(),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
    }
}

/*
Pano yung late report ng Tuesday 11AM onwards?
MW should be the same and not modified to advance to next week because pasok pa din naman sa MW reporting period for the particular week
*/

class AbdImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['last_modified_date']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //CHECK FOR DUPLICATES
            /*
            $check1 = Abd::where('FamilyName', $row['last_name'])
            ->where('FirstName', $row['first_name'])
            ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
            ->where('Year', $row['year'])
            ->where('MorbidityWeek', $row['morbidity_week'])
            ->first();
            */

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => NULL,
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'StoolCulture' => NULL,
                'Organism' => NULL,
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'DateOfEntry' => date('Y-m-d'), //NO $row['timestamp']
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(date('Y-m-d'))->format('n'), //NO $row['timestamp']
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Abd::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'Year' => $row['year'],
                'NameOfDru' => $row['facilityname'],
                'District' => 6,
                'InterLocal' => NULL,
                
                'CASECLASS' => substr($row['case_classi'],0,1),
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Abd::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Abd::create($table_params);
            }

            return $model;

            /*
            if(!(Abd::where('EPIID', $row['epi_id'])->first())) {
                
            }
            
            if(!($check1)) {
                return new Abd([
                    
                ]);
            }
            */
        }
    }
}

class AfpImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row)
    {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            //Check Case Definition


            $table_params = [
                'Icd10Code' => NULL,
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted']),
                'DateOfReport' => EdcsImport::tDate($row['date_of_report']),
                'DateOfInvestigation' => EdcsImport::tDate($row['date_of_investigation']),
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Afp::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'Fever' => ($row['fever'] == 'Yes') ? 'Y' : 'N',
                'DONSETP' => EdcsImport::tDate($row['date_onset_paralysis']),
                'RArm' => ($row['right_arm'] == 'Yes') ? 'Y' : 'N',
                'Cough' => ($row['cough'] == 'Yes') ? 'Y' : 'N',
                'ParalysisAtBirth' => ($row['present_at_birth'] == 'Yes') ? 'Y' : 'N',
                'LArm' => ($row['left_arm'] == 'Yes') ? 'Y' : 'N',
                'DiarrheaVomiting' => ($row['diarrheavomiting'] == 'Yes') ? 'Y' : 'N',
                'Asymm' => ($row['asymmetric'] == 'Yes') ? 'Y' : 'N',
                'RLeg' => ($row['right_leg'] == 'Yes') ? 'Y' : 'N',
                'MusclePain' => ($row['muscle_pain'] == 'Yes') ? 'Y' : 'N',
                'LLeg' => ($row['left_leg'] == 'Yes') ? 'Y' : 'N',
                'Mening' => ($row['meningeal_signs'] == 'Yes') ? 'Y' : 'N',
                'BrthMusc' => ($row['breathing_muscles'] == 'Yes') ? 'Y' : 'N',
                'NeckMusc' => ($row['neck_muscles'] == 'Yes') ? 'Y' : 'N',
                'Paradev' => ($row['paralysis_fully_developed_within_3_to_14_days_from_onset_of_illness'] == 'Yes') ? 'Y' : 'N',
                'Paradir' => $row['direction_of_paralysis'],
                'FacialMusc' => ($row['facial_muscles'] == 'Yes') ? 'Y' : 'N',
                'WorkingDiagnosis' => $row['working_diagnosis'],
                'RASens' => $row['sensory_status'],
                'LASens' => isset($row['sensory_status_1']) ? $row['sensory_status_1'] : $row['sensory_status2'],
                'RLSens' => isset($row['sensory_status_4']) ? $row['sensory_status_4'] : $row['sensory_status5'],
                'LLSens' => isset($row['sensory_status_7']) ? $row['sensory_status_7'] : $row['sensory_status8'],
                'RARef' => $row['deep_tendon_reflexes'],
                'LARef' => isset($row['deep_tendon_reflexes_2']) ? $row['deep_tendon_reflexes_2'] : $row['deep_tendon_reflexes3'],
                'RLRef' => isset($row['deep_tendon_reflexes_5']) ? $row['deep_tendon_reflexes_5'] : $row['deep_tendon_reflexes6'],
                'LLRef' => isset($row['deep_tendon_reflexes_8']) ? $row['deep_tendon_reflexes_8'] : $row['deep_tendon_reflexes9'],
                'RAMotor' => $row['motor_status'],
                'LAMotor' => isset($row['motor_status_3']) ? $row['motor_status_3'] : $row['motor_status4'],
                'RLMotor' => isset($row['motor_status_6']) ? $row['motor_status_6'] : $row['motor_status7'],
                'LLMotor' => isset($row['motor_status_9']) ? $row['motor_status_9'] : $row['motor_status10'],
                'HxDisorder' => ($row['history_of_neurologic_disorder'] == 'Yes') ? 'Y' : 'N',
                'Disorder' => ($row['history_of_neurologic_disorder'] == 'Yes') ? $row['if_yes_specify_disorder'] : NULL,
                'TravelPrior2Illness' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? 'Y' : 'N',
                'PlaceOfTravel' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? $row['if_yes_specify_place'] : NULL,
                'FrmTrvlDate' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? EdcsImport::tDate($row['date_traveled_from']) : NULL,
                'OtherCases' => ($row['other_afp_cases_in_patients_community_within_60_days_of_patients_paralysis'] == 'Yes') ? 'Y' : 'N',
                'InjTrauAnibite' => ($row['does_the_patient_had_any_history_of_injection_trauma_and_or_animal_bite'] == 'Yes') ? 'Y' : 'N',
                'SpecifyInjTrauAnibite' => ($row['does_the_patient_had_any_history_of_injection_trauma_and_or_animal_bite'] == 'Yes') ? $row['if_yes_specify_type'] : 0,
                'Investigator' => $row['name_of_investigator'],
                'ContactNum' => $row['contact_no'],
                'OPVDoses' => $row['total_opvipv_doses_received'],
                'DateLastDose' => ($row['date_last_dose_of_opvipv'] != "" && !is_null($row['date_last_dose_of_opvipv'])) ? EdcsImport::tDate($row['date_last_dose_of_opvipv']) : NULL,
                'HotCase' => ($row['is_this_a_hot_case'] == 'Yes') ? 'Y' : 'N',

                'FirstStoolSpec' => NULL,
                'DStool1Taken' => NULL,
                'DStool2Taken' => NULL,
                'DStool1Sent' => NULL,
                'DStool2Sent' => NULL,
                'Stool1Result' => NULL,
                'Stool2Result' => NULL,

                'ExpDffup' => ($row['expected_date_of_follow_up'] != "" && !is_null($row['expected_date_of_follow_up'])) ? EdcsImport::tDate($row['expected_date_of_follow_up']) : NULL,
                'ActDffp' => ($row['if_yes_actual_date_of_follow_up_conducted'] != "" && !is_null($row['if_yes_actual_date_of_follow_up_conducted'])) ? EdcsImport::tDate($row['if_yes_actual_date_of_follow_up_conducted']) : NULL,
                'PhyExam' => ($row['pe_done'] == 'Yes') ? 1 : 0,
                'ReasonND' => ($row['pe_done'] == 'No') ? $row['if_no_reason_for_no_pe'] : NULL,
                'DateDied' => ($row['date_died'] != "" && !is_null($row['date_died'])) ? EdcsImport::tDate($row['date_died']) : NULL,
                'OtherReasonND' => $row['others_specify'],
                'ResPara' => $row['residual_paralysis_at_60_days'],
                'ResParaType' => $row['if_yes_specify'],
                'Atrophy' => ($row['presence_of_atrophy'] == 'Yes') ? 'Y' : 'N',
                'RAatrophy' => ($row['right_arm1'] == 'Yes') ? 'Y' : 'N',
                'LAatrophy' => ($row['left_arm1'] == 'Yes') ? 'Y' : 'N',
                'RLatrophy' => ($row['right_leg1'] == 'Yes') ? 'Y' : 'N',
                'LLatrophy' => ($row['left_leg1'] == 'Yes') ? 'Y' : 'N',
                'OthObs' => $row['note_other_observations'],
                'FClass' => $row['final_classification'],
                'DateClass' => ($row['date_classified'] != "" && !is_null($row['date_classified'])) ? EdcsImport::tDate($row['date_classified']) : NULL,
                'VAPP' => NULL,
                'CCriteria' => $row['classification_criteria'],
                'FinalDx' => $row['final_diagnosis'],
                'OtherDiagnosis' => NULL,
                'ReportToInvestigation' => NULL,
                'Stool1CollectSend' => NULL,
                'Stool2CollectSend' => NULL,
                'Stool1SentResult' => NULL,
                'Stool2SentResult' => NULL,
                'Followupindicator' => NULL,
                'Stool1OnsetCollect' => NULL,
                'Stool2OnsetCollect' => NULL,
                'LabResultToClassification' => NULL,
                'Stool1ResultToClassify' => NULL,
                'Stool2ResultToClassify' => NULL,
                'ActDffup' => ($row['follow_up_done'] == 'Yes') ? EdcsImport::tDate($row['if_yes_actual_date_of_follow_up_conducted']) : NULL,
                'DStool1Received' => NULL,
                'DStool2Received' => NULL,
                'Stool1RecResult' => NULL,
                'Stool2RecResult' => NULL,
                'SecndStoolSpec' => NULL,
                'DateRep' => ($row['date_of_report'] != "" && !is_null($row['date_of_report'])) ? EdcsImport::tDate($row['date_of_report']) : NULL,
                'DateInv' => ($row['date_of_investigation'] != "" && !is_null($row['date_of_investigation'])) ? EdcsImport::tDate($row['date_of_investigation']) : NULL,

                'Year' => $row['year'],
                'SentinelSite' => NULL,
                'ClinicalSummary' => NULL,
                'DeleteRecord' => NULL,
                'NameOfDru' => $row['facilityname'],
                'ToTrvldate' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? EdcsImport::tDate($row['date_traveled_to']) : NULL,
                'ILHZ' => 'GENTAMAR',
                'District' => 6,
                
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'OCCUPATION' => NULL,
                'SENT' => 'Y',
                'ip' => 'N', 
                'ipgroup' => NULL,
                'Outcome' => 'A',
                'DateOutcomeDied' => ($row['date_died'] != "" && !is_null($row['date_died'])) ? EdcsImport::tDate($row['date_died']) : NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Afp::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Afp::create($table_params);
            }

            return $model;
        }        
    }
}

class AmesImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row)
    {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }
            
            if(is_null($row['health_facility_code'])) {
                $getDruRegionText = NULL;
                $getDruProvinceText = NULL;
                $getDruMuncityText = NULL;

                $getDruFacilityTypeText = NULL;
            }
            else {
                $getDruRegionText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1;
                $getDruProvinceText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province;
                $getDruMuncityText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity;

                $getDruFacilityTypeText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type;
            }

            $match_casedef = 1;

            //AMES Case Definition
            if($row['fever'] == 'Yes') {
                if($row['change_in_mental_status'] == 'Yes' || $row['neck_stiffness'] == 'Yes' || $row['meningeal_signs'] == 'Yes') {
                    $match_casedef = 1;
                }
                else {
                    $match_casedef = 0;
                }
            }

            $table_params = [
                'Icd10Code' => NULL,
                'RegionOFDrU' => $getDruRegionText,
                'ProvOfDRU' => $getDruProvinceText,
                'MuncityOfDRU' => $getDruMuncityText,
                'DRU' => $getDruFacilityTypeText,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'NHTS' => NULL,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_font_stylecolorred_font']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'DateRep' => EdcsImport::tDate($row['timestamp']),
                'DateInv' => EdcsImport::tDate($row['date_of_investigation']),
                'Investigator' => $row['name_of_investigator'],
                'ContactNum' => $row['contact_no'],
                'InvDesig' => $row['designation'],
                'Fever' => ($row['fever'] == 'Yes') ? 'Y' : 'N',
                'BehaviorChng' => ($row['change_in_mental_status'] == 'Yes') ? 'Y' : 'N',
                'Seizure' => ($row['new_onset_seizures'] == 'Yes') ? 'Y' : 'N',
                'Stiffneck' => ($row['neck_stiffness'] == 'Yes') ? 'Y' : 'N',
                'bulgefontanel' => NULL,
                'MenSign' => ($row['meningeal_signs'] == 'Yes') ? 'Y' : 'N',
                'ClinDiag' => $row['cnsinfection'],
                'OtherDiag' => $row['cns_others_specify'],
                'JE' => ($row['je'] == 'Yes') ? 'Y' : 'N',
                'VacJeDate' => EdcsImport::tDate($row['je_date_last_dose']),
                'JEDose' => $row['je_no_of_doses'],
                'Hib' => ($row['penta_hib'] == 'Yes') ? 'Y' : 'N',
                'VacHibDate' => EdcsImport::tDate($row['penta_hib_date_last_dose']),
                'HibDose' => $row['penta_hib_no_of_doses'],
                'PCV10' => ($row['pcv10'] == 'Yes') ? 'Y' : 'N',
                'VacPCV10Date' => EdcsImport::tDate($row['pcv10_date_last_dose']),
                'PCV10Dose' => $row['pcv_10_no_of_doses'],
                'PCV13' => ($row['pcv13'] == 'Yes') ? 'Y' : 'N',
                'VacPCV13Date' => EdcsImport::tDate($row['pcv13_date_last_dose']),
                'PCV13Dose' => $row['pcv13_no_of_doses'],
                'MeningoVacc' => ($row['meningococcal'] == 'Yes') ? 'Y' : 'N',
                'VacMeningoDate' => EdcsImport::tDate($row['meningococcal_date_last_dose']),
                'MeningoVaccDose' => $row['meningococcal_no_of_doses'],
                'MeasVacc' => ($row['measles'] == 'Yes') ? 'Y' : 'N',
                'VacMeasDate' => EdcsImport::tDate($row['measles_date_last_dose']),
                'MeasVaccDose' => $row['measles_no_of_doses'],
                'MMR' => NULL,
                'VacMMRDate' => NULL,
                'MMRDose' => NULL,
                'plcDaycare' => ($row['day_care'] == 'Yes') ? 'Y' : 'N',
                'plcBrgy' => ($row['barangay'] == 'Yes') ? 'Y' : 'N',
                'plcHome' => ($row['home'] == 'Yes') ? 'Y' : 'N',
                'plcSchool' => ($row['school'] == 'Yes') ? 'Y' : 'N',
                'plcdormitory' => ($row['dormitory'] == 'Yes') ? 'Y' : 'N',
                'plcHC' => ($row['health_care_facility'] == 'Yes') ? 'Y' : 'N',
                'plcWorkplace' => ($row['workplace'] == 'Yes') ? 'Y' : 'N',
                'plcOther' => ($row['other_exposure'] == 'Yes') ? 'Y' : 'N',
                'Travel' => ($row['if_yes_specify_place'] == 'Yes') ? 'Y' : 'N',
                'PlaceTravelled' => $row['if_yes_specify_place'],
                'FrmTrvlDate' => EdcsImport::tDate($row['date_traveled_from']),
                'ToTrvlDate' => EdcsImport::tDate($row['date_traveled_to']),
                'CSFColl' => ($row['were_bloodcsf_extracted_before_the_first_dose_of_antibiotics_was_given_to_the_patient'] == 'Yes') ? 'Y' : 'N',
                'D8CSFTaken' => NULL,
                'TymCSFTaken' => NULL,
                'D8CSFHospLab' => NULL,
                'TymCSFHospLab' => NULL,
                'CSFAppearance' => NULL,
                'GramStain' => NULL,
                'GramStainResult' => NULL,
                'culture' => NULL,
                'CultureResult' => NULL,
                'OtherTest' => NULL,
                'OtherTestResult' => NULL,
                'D8CSFSentRITM' => NULL,
                'D8CSFReceivedRITM' => NULL,
                'CSFSampVol' => NULL,
                'D8CSFTesting' => NULL,
                'CSFResult' => NULL,
                'Serum1Col' => NULL,
                'D8Serum1Taken' => NULL,
                'D8Serum1HospLab' => NULL,
                'D8Serum1Sent' => NULL,
                'D8Seruml1Received' => NULL,
                'Serum1SampVol' => NULL,
                'D8Serum1Testing' => NULL,
                'Serum1Result' => NULL,
                'Serum2Col' => NULL,
                'D8Serum2Taken' => NULL,
                'D8Serum2HospLab' => NULL,
                'D8Serum2Sent' => NULL,
                'D8Serum2Received' => NULL,
                'Serum2SampVol' => NULL,
                'D8Serum2testing' => NULL,
                'Serum2Result' => NULL,
                'AESCaseClass' => $row['caseclassification_aes'],
                'BmCaseClass' => $row['caseclassification_bm'],
                'AESOtherAgent' => $row['aes_other_agent'],
                'ConfirmBMTest' => $row['if_confirmed_case_please_state_confirmatory_test'],
                'FinalDiagnosis' => $row['final_diagnosis'],
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'DateDisch' => NULL,
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'RecoverSequelae' => NULL,
                'SequelaeSpecs' => NULL,
                'TransTo' => NULL,
                'HAMA' => NULL,
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Ames::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'Year' => $row['year'],
                'NameOfDru' => $row['facilityname'],
                'ILHZ' => 'GENTAMAR',
                'District' => 6,
                
                'CASECLASS' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                'match_casedef' => $match_casedef,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Ames::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Ames::create($table_params);
            }

            return $model;
        }
    }
}

class HepaImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }
            
            $table_params = [
                'Icd10Code' => 'B15-17',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'Type' => NULL,
                'LabResult' => NULL,
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'TypeOfHepatitis' => NULL,
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Hepatitis::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'Year' => $row['year'],
                'NameOfDru' => $row['facilityname'],
                'ILHZ' => 'GENTAMAR',
                'District' => 6,
                
                'CASECLASS' => $row['caseclassification'],
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Hepatitis::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Hepatitis::create($table_params);
            }

            return $model;
        }
    }
}

class HfmdImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {     
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }
            
            //CHECK CASE DEF
            if($row['sore_throat'] == 'Yes') {
                if($row['fever'] == 'Yes' && $row['rash'] == 'Yes') {
                    if($row['rash'] == 'Yes' || $row['maculopapular'] == 'Yes' || $row['papulovesicular'] == 'Yes') {
                        $match_casedef = 1;
                    }
                    else {
                        $match_casedef = 0;
                    }
                }
                else {
                    $match_casedef = 0;
                }
            }
            else {
                $match_casedef = 0;
            }

            //CUSTOM CASE CLASS
            if($row['case_classification'] == 'Suspected case of Hand, Foot and Mouth Disease') {
                $get_class = 'SUSPECTED CASE OF HFMD';
            }
            else if($row['case_classification'] == 'Probable case of Hand, Foot and Mouth Disease') {
                $get_class = 'PROBABLE CASE OF HFMD';
            }
            else if($row['case_classification'] == 'Confirmed case of Hand, Foot and Mouth Disease') {
                $get_class = 'CONFIRMED CASE OF HFMD';
            }
            else {
                $get_class = mb_strtoupper($row['case_classification']);
            }

            $table_params = [
                'Icd10Code' => NULL,
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consult']),
                'DONSET' => EdcsImport::tDate($row['date_onset']),
                'Fever' => ($row['fever'] == 'Yes') ? 'Y' : 'N',
                'FeverOnset' => EdcsImport::tDate($row['date_onset_of_fever']),
                'RashChar' => NULL,
                'RashSores' => ($row['rash'] == 'Yes') ? 'Y' : 'N',
                'Maculopapular' => ($row['maculopapular'] == 'Yes') ? 'Y' : 'N',
                'Papulovesicular' => ($row['papulovesicular'] == 'Yes') ? 'Y' : 'N',
                'SoreOnset' => EdcsImport::tDate($row['date_onset_of_rash']),
                'Palms' => ($row['palms'] == 'Yes') ? 'Y' : 'N',
                'Fingers' => ($row['fingers'] == 'Yes') ? 'Y' : 'N',
                'FootSoles' => ($row['soles_of_feet'] == 'Yes') ? 'Y' : 'N',
                'Buttocks' => ($row['buttocks'] == 'Yes') ? 'Y' : 'N',
                'MouthUlcers' => ($row['mouth_ulcers'] == 'Yes') ? 'Y' : 'N',
                'Pain' => ($row['painful'] == 'Yes') ? 'Y' : 'N',
                'Anorexia' => ($row['loss_of_appetite'] == 'Yes') ? 'Y' : 'N',
                'BM' => ($row['body_malaise'] == 'Yes') ? 'Y' : 'N',
                'SoreThroat' => ($row['sore_throat'] == 'Yes') ? 'Y' : 'N',
                'NausVom' => ($row['nause_or_vomiting'] == 'Yes') ? 'Y' : 'N',
                'DiffBreath' => ($row['difficulty_of_breathing'] == 'Yes') ? 'Y' : 'N',
                'Paralysis' => ($row['acute_flaccid_paralysis'] == 'Yes') ? 'Y' : 'N',
                'MeningLes' => ($row['meningea_lirritation'] == 'Yes') ? 'Y' : 'N',
                'OthSymptoms' => ($row['others_symptoms'] == 'Yes') ? 'Y' : 'N',
                'AnyComp' => ($row['are_there_any_complications'] == 'Yes') ? 'Y' : 'N',
                'Complic8' => $row['if_yes_specify_complication'],
                'Investigator' => $row['name_of_investigators'],
                'ContactNum' => $row['contact_numbers'],
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'ReportToInvestigation' => NULL,
                'UniqueKey' => Hfmd::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'Travel' => NULL,
                'ProbExposure' => NULL,
                'OthExposure' => ($row['other_exposure'] == 'Yes') ? 'Y' : 'N',
                'OtherCase' => $row['other_exposure_specify'],
                'RectalSwabColl' => NULL,
                'VesicFluidColl' => NULL,
                'StoolColl' => NULL,
                'ThroatSwabColl' => NULL,
                'DateStooltaken' => NULL,
                'DateStoolsent' => NULL,
                'DateStoolRecvd' => NULL,
                'StoolResult' => NULL,
                'StoolOrg' => NULL,
                'StoolResultD8' => NULL,
                'VFSwabtaken' => NULL,
                'VFSwabsent' => NULL,
                'VFSwabRecvd' => NULL,
                'VesicFluidRes' => NULL,
                'VesicFluidOrg' => NULL,
                'VFSwabResultD8' => NULL,
                'ThroatSwabtaken' => NULL,
                'ThroatSwabsent' => NULL,
                'ThroatSwabRecvd' => NULL,
                'ThroatSwabResult' => NULL,
                'ThroatSwabOrg' => NULL,
                'ThroatSwabResultD8' => NULL,
                'RectalSwabtaken' => NULL,
                'RectalSwabsent' => NULL,
                'RectalSwabRecvd' => NULL,
                'RectalSwabResult' => NULL,
                'RectalSwabOrg' => NULL,
                'RectalSwabResultD8' => NULL,
                'CaseClass' => $get_class,
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'WFDiag' => $row['workingfinal_diagnosis'],
                'Death' => EdcsImport::tDate($row['date_died']),
                'DCaseRep' => NULL,
                'DCASEINV' => EdcsImport::tDate($row['date_of_investigation']),
                'SentinelSite' => NULL,
                'Year' => $row['year'],
                'DeleteRecord' => NULL,
                'NameOfDru' => $row['facilityname'],
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                'match_casedef' => $match_casedef,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Hfmd::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Hfmd::create($table_params);
            }

            return $model;
        }
    }
}

class LeptoImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            //Check Case Def
            //Can't use directly because of 

            $table_params = [
                'Icd10Code' => 'A27',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consulted']),
                'DOnset' => EdcsImport::tDate($row['date_on_set_of_illness_first_symptoms']),
                'LabRes' => NULL,
                'Serovar' => NULL,
                'CaseClassification' => $row['case_classification'],
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'Occupation' => $row['occupation'],
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Leptospirosis::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'Year' => $row['year'],
                'NameOfDru' => $row['facilityname'],
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                
                'TYPEHOSPITALCLINIC' =>NULL,
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Leptospirosis::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Leptospirosis::create($table_params);
            }

            return $model;
        }
    }
}

class MeaslesImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $match_casedef = 1;

            //Check Case Definition
            if(!is_null($row['date_onset'][0]) && !is_null($row['date_onset'][1])) {
                if($row['cough'] == 'Yes' || $row['runny_nosecoryza'] == 'Yes' || $row['red_eyes_conjunctivitis'] == 'Yes') {
                    $match_casedef = 1;
                }
                else {
                    $match_casedef = 0;
                }
            }

            $table_params = [
                'Icd10Code' => 'B05',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Address' => NULL,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'Preggy' => ($row['pregnant'] == 'Yes') ? 'Y' : 'N',
                'WkOfPreg' => $row['if_yes_weeks_of_pregnancy'],
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consulted']),
                'DONSET' => EdcsImport::tDate($row['date_onset'][0]),
                'VitaminA' => ($row['was_the_patient_given_vitamin_a_during_this_illness'] == 'Yes') ? 'Y' : 'N',
                'FeverOnset' => EdcsImport::tDate($row['date_onset'][0]),
                'RashOnset' => EdcsImport::tDate($row['date_onset'][1]),
                'MeasVacc' => ($row['patient_received_measles_containing_vaccine_mcv_br_if_yes_indicate_the_number_of_doses_whichever_is_applicable'] == 'Yes') ? 'Y' : 'N',
                'Cough' => ($row['cough'] == 'Yes') ? 'Y' : 'N',
                'KoplikSpot' => ($row['koplik_sign'] == 'Yes') ? 'Y' : 'N',
                'MVDose' => $row['mv'],
                'MRDose' => $row['mr'],
                'MMRDose' => $row['mmr'],
                'LastVacc' => EdcsImport::tDate($row['date_last_dose_received_mcv']),
                'RunnyNose' => ($row['runny_nosecoryza'] == 'Yes') ? 'Y' : 'N',
                'RedEyes' => ($row['red_eyes_conjunctivitis'] == 'Yes') ? 'Y' : 'N',
                'ArthritisArthralgia' => ($row['arthralgiaarthritis'] == 'Yes') ? 'Y' : 'N',
                'SwoLympNod' => ($row['swollen_lymphatic_nodules'] == 'Yes') ? 'Y' : 'N',
                'LympNodLoc' => $row['swollen_lymphatic_specify'],
                'OthLocation' => $row['others_specify'][0],
                'OthSymptoms' => $row['other_symptoms'],
                'AreThereAny' => ($row['are_there_any_complications'] == 'Yes') ? 'Y' : 'N',
                'Complications' => $row['if_yes_specify'],
                'Reporter' => $row['name_of_reporter'],
                'Investigator' => $row['name_of_investigators'],
                'RContactNum' => $row['contact_nos_2'] ?? $row['contact_nos'][1] ?? NULL,
                'ContactNum' => $row['contact_nos_1'] ?? $row['contact_nos'][0] ?? NULL,
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'ReportToInvestigation' => NULL,
                'UniqueKey' => Measles::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'Reasons' => NULL,
                'OtherReasons' => NULL,
                'SpecialCampaigns' => ($row['was_vaccination_received_during_special_campaigns'] == 'Yes') ? 'Y' : 'N',
                'Travel' => ($row['with_history_of_travel_within_23_days_prior_to_onset_of_rash'] == 'Yes') ? 'Y' : 'N',
                'PlaceTravelled' => $row['place_of_travel'],
                'TravTiming' => EdcsImport::tDate($row['date_of_travel']),
                'ProbExposure' => NULL,
                'OtherExposure' => $row['others_specify'][1],
                'OtherCase' => ($row['are_there_other_known_cases_with_fever_and_rash_regardless_of_presence_of_3_csbr_in_the_community'] == 'Yes') ? 'Y' : 'N',
                
                'WholeBloodColl' => NULL,
                'DriedBloodColl' => NULL,
                'OP/NPSwabColl' => NULL,
                'DateWBtaken' => NULL,
                'DateWBsent' => NULL,
                'DateDBtaken' => NULL,
                'DateDBsent' => NULL,
                'OPNPSwabtaken' => NULL,
                'OPNPSwabsent' => NULL,
                'OPSwabPCRRes' => NULL,
                'OPNpSwabResult' => NULL,
                'DateWBRecvd' => NULL,
                'DateDBRecvd' => NULL,
                'OPNPSwabRecvd' => NULL,
                'OraColColl' => NULL,
                'OraColD8taken' => NULL,
                'OraColD8sent' => NULL,
                'OraColD8Recvd' => NULL,
                'OraColPCRRes' => NULL,
                'FinalClass' => !is_null($row['final_classification']) ? mb_strtoupper($row['final_classification']) : NULL,
                'InfectionSource' => $row['source_infection'],
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'FinalDx' => $row['final_diagnosis'],
                'Death' => EdcsImport::tDate($row['date_died']),
                'DCaseRep' => EdcsImport::tDate($row['date_of_report']),
                'DCASEINV' => EdcsImport::tDate($row['date_of_investigation']),
                'SentinelSite' => NULL,
                'Year' => $row['year'],
                'DeleteRecord' => NULL,
                'WBRubellaIgM' => NULL,
                'WBMeaslesIgM' => NULL,
                'DBMeaslesIgM' => NULL,
                'DBRubellaIgM' => NULL,
                'ContactConfirmedCase' => ($row['was_there_contact_with_a_confirmed_measles_case_7_23_days_prior_to_rash_onset'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                'ContactName' => $row['if_yes_name_of_contact'],
                'ContactPlace' => $row['place_of_residence'],
                'ContactDate' => EdcsImport::tDate($row['date_of_contact']),
                'NameOfDru' => $row['facilityname'],
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'Labcode' => NULL,
                'ContactConfirmedRubella' => ($row['was_there_contact_with_a_confirmed_rubella_case_7_23_days_prior_to_rash_onset'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                'TravRegion' => NULL,
                'TravMun' => NULL,
                'TravProv' => NULL,
                'TravBgy' => NULL,
                'Travelled' => NULL,
                'DateTrav' => EdcsImport::tDate($row['date_of_travel']),
                'Report2Inv' => NULL,
                'Birth2RashOnset' => NULL,
                'OnsetToReport' => NULL,
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                'match_casedef' => $match_casedef,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Measles::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Measles::create($table_params);
            }

            return $model;
        }
    }
}

class NntImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => 'A33',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_font_stylecolorred_font']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'RecentAcuteWound' => ($row['with_recent_wound'] == 'Yes') ? 'Y' : 'N',
                'WoundSite' => $row['woundsite'],
                'WoundType' => $row['woundtype'],
                'OtherWound' => $row['wound_type_specify'],
                'TetanusToxoid' => ($row['recieved_tetanus_toxoid_vaccination'] == 'Yes') ? 'Y' : 'N',
                'TetanusAntitoxin' => ($row['recieved_aniti_tetanus_anti_toxin_or_tig'] == 'Yes') ? 'Y' : 'N',
                'SkinLesion' => ($row['with_recent_wound'] == 'Yes') ? 'Y' : 'N',
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Nnt::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'Year' => $row['year'],
                'NameOfDru' => $row['facilityname'],
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => NULL,
                'edcs_contactNo' => NULL,
                'edcs_ageGroup' => $row['age_group'],
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Nnt::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Nnt::create($table_params);
            }

            return $model;
        }
    }
}

class RabiesImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => 'A82',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'SentinelSite' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Weight' => NULL,
                'Admitted' => ($row['patient_admitted'] == 'Yes' || $row['patient_admitted'] == 'Y') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consult']),
                'DOnset' => EdcsImport::tDate($row['date_onset_of_illness']),
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'Year' => $row['year'],
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Rabies::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'PlaceOfIncidence' => $row['place_of_exposure'],
                'TypeOfExposure' => $row['type_exposure'],
                'Category' => $row['category_exposure'], //VARCHAR 25
                'BiteSite' => $row['affected_site'],
                'OtherTypeOfExposure' => $row['other_specify'],
                'DateBitten' => EdcsImport::tDate($row['date_exposure']),
                'TypeOfAnimal' => $row['type_animal'],
                'OtherTypeOfAnimal' => (isset($row['other_specify1'])) ? $row['other_specify1'] : $row['other_specify2'],
                'LabDiagnosis' => NULL,
                'LabResult' => NULL,
                'AnimalStatus' => $row['animal_status'],
                'OtherAnimalStatus' => (isset($row['other_specify2'])) ? $row['other_specify2'] : $row['other_specify3'],
                'DateVaccStarted' => EdcsImport::tDate($row['date_vaccine_started']),
                'Vaccine' => $row['brand_name_of_vaccine'],
                'AdminRoute' => $row['route_admin'],
                'PostExposureComplete' => ($row['post_exposure_completed'] == 'Yes' || $row['post_exposure_completed'] == 'Y') ? 1 : 0,
                'AnimalVaccination' => $row['animal_vacc_hist'],
                'WoundCleaned' => ($row['wound_clean'] == 'Yes' || $row['wound_clean'] == 'Y') ? 1 : 0,
                'Rabiesvaccine' => ($row['patient_given_rabies_vaccine'] == 'Yes' || $row['patient_given_rabies_vaccine'] == 'Y') ? 1 : 0,
                'DeleteRecord' => NULL,
                'Outcomeanimal' => NULL,
                'RIG' => ($row['patient_given_rabies_immunoglobulin'] == 'Yes' || $row['patient_given_rabies_immunoglobulin'] == 'Y') ? 1 : 0,
                'NameOfDru' => $row['facilityname'],
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                
                'CASECLASS' => $row['final_classification'],
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Rabies::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Rabies::create($table_params);
            }

            return $model;
        }
    }
}

class RotaImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }
            
            $table_params = [
                'Icd10Code' => NULL,
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'DRUContactNum' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'NHTS' => NULL,
                'IVTherapy' => ($row['did_patient_receive_iv_rehydration_therapy_while_at_the_er'] == 'Yes') ? 'Y' : 'N',
                'Vomiting' => ($row['vomiting'] == 'Yes') ? 'Y' : 'N',
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['if_yes_date_of_admission_font_stylecolorred_font']),
                'D_ONSET' => EdcsImport::tDate($row['date_of_onset_of_diarrhea']),
                'DateRep' => EdcsImport::tDate($row['date_of_report']),
                'DateInv' => EdcsImport::tDate($row['date_of_investigation']),
                'Investigator' => $row['name_of_investigator'],
                'ContactNum' => $row['contact_numbers'],
                'InvDesignation' => $row['positiondesignation'],
                'Fever' => ($row['fever'] == 'Yes') ? 'Y' : 'N',
                'Temp' => NULL,
                'V_ONSET' => EdcsImport::tDate($row['date_of_onset_of_vomiting_health_facility_font_stylecolorred_font']),
                'AdmDx' => $row['admitting_diagnosis'],
                'FinalDx' => $row['final_diagnosis'],
                'DegDehy' => $row['degree_of_dehydration_health_facility_font_stylecolorred_font'],
                'DiarrCases' => $row['are_there_two_or_more_diarrhea_cases'],
                'Community' => $row['if_yes_where'],
                'HHold' => NULL,
                'School' => NULL,
                'RotaVirus' => ($row['received_rotavirus_vaccine'] == 'Yes') ? 'Y' : 'N',
                'RVDose' => $row['if_yes_total_doses_received_health_facility_font_stylecolorred_font'],
                'D8RV1stDose' => EdcsImport::tDate($row['date_first_dose_received_health_facility_font_stylecolorred_font']),
                'D8RVLastDose' => EdcsImport::tDate($row['date_last_dose_received_health_facility_font_stylecolorred_font']),
                'StoolColl' => NULL,
                'D8StoolTaken' => NULL,
                'D8StoolSent' => NULL,
                'D8StoolRecvd' => NULL,
                'Amount' => NULL,
                'StoolQty' => NULL,
                'ElisaRes' => NULL,
                'D8ElisaRes' => NULL,
                'PCRRes' => NULL,
                'OthPCRRes' => NULL,
                'Genotype' => NULL,
                'D8PCRRes' => NULL,
                'SpecCond' => NULL,
                'DateDisch' => EdcsImport::tDate($row['date_of_discharge_health_facility_font_stylecolorred_font']),
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Rotavirus::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' =>  NULL,
                'SentinelSite' =>  NULL,
                'DeleteRecord' =>  NULL,
                'Year' => $row['year'],
                'NameOfDru' => $row['facilityname'],
                'ILHZ' => 'GENTAMAR',
                'District' => 6,
                
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'hospdiarrhea' => ($row['did_patient_have_previous_hospitalization_due_to_diarrhea'] == 'Yes') ? 'Y' : 'N',
                'Datehosp' => EdcsImport::tDate($row['if_yes_date_of_hospitalization_health_facility_font_stylecolorred_font']),
                'classification' => $row['case_classification'],
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => NULL,
                'edcs_contactNo' => NULL,
                'edcs_ageGroup' => $row['age_group'],
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Rotavirus::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Rotavirus::create($table_params);
            }

            return $model;
        }
    }
}

class TyphoidImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => 'A01.0',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consult']),
                'DOnset' => EdcsImport::tDate($row['date_onset_of_illness']),
                'LabResult' => NULL,
                'Organism' => NULL,
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'UniqueKey' => Typhoid::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'Year' => $row['year'],
                'NameOfDru' => $row['facilityname'],
                'District' =>  NULL,
                'ILHZ' => 'GENTAMAR',
                'CASECLASS' => $row['caseclass'],
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Typhoid::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Typhoid::create($table_params);
            }

            return $model;
        }
    }
}

class DengueImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            //CLASSIFICATION FIX
            if($row['clinical_classification'] == 'Dengue Without Warning Signs') {
                $get_classi = 'NO WARNING SIGNS';
            }
            else if($row['clinical_classification'] == 'Dengue With Warning Signs' || $row['clinical_classification'] == 'Dengue With Warning ') {
                $get_classi = 'WITH WARNING SIGNS';
            }
            else {
                $get_classi = mb_strtoupper($row['clinical_classification']);
            }

            if(is_null($row['health_facility_code'])) {
                $getDruRegionText = NULL;
                $getDruProvinceText = NULL;
                $getDruMuncityText = NULL;

                $getDruFacilityTypeText = NULL;
            }
            else {
                $getDruRegionText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1;
                $getDruProvinceText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province;
                $getDruMuncityText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity;

                $getDruFacilityTypeText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type;
            }

            $table_params = [
                'Icd10Code' => 'A90',
                'RegionOFDrU' => $getDruRegionText,
                'ProvOfDRU' => $getDruProvinceText,
                'MuncityOfDRU' => $getDruMuncityText,
                'DRU' => $getDruFacilityTypeText,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && mb_strtoupper($row['current_address_sitio_purok_street_name']) != 'N/A') ? $row['current_address_sitio_purok_street_name'] : NULL,
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen']),
                'DOnset' => EdcsImport::tDate($row['date_on_set_of_illness_first_symptoms']),
                'Type' => 'DF',
                'LabTest' => NULL,
                'LabRes' => NULL,
                'ClinClass' => $get_classi,
                'CaseClassification' => mb_strtoupper(substr($row['case_classification'],0,1)),
                'Outcome' => (isset($row['outcome'])) ? mb_strtoupper(substr($row['outcome'],0,1)) : mb_strtoupper(substr($row['outcome2'],0,1)),
                'EPIID' => $row['epi_id'],
                'DateDied' => EdcsImport::tDate($row['date_died']),
                
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'AdmitToEntry' => preg_replace('/[^0-9]/', '', $row['timelapse_dateadmittodateencode']),
                'OnsetToAdmit' => preg_replace('/[^0-9]/', '', $row['timelapse_dateonsettodateencode']),
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'Year' => $row['year'],
                'Recstatus' => NULL,
                'UniqueKey' => Dengue::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                
                'ILHZ' => 'GENTAMAR',
                'District' => 6,
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => (isset($row['case_id'])) ? $row['case_id'] : $row['21'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Dengue::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Dengue::create($table_params);
            }

            return $model;
        }
    }
}

class DiphImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            //Check Case Definition

            $table_params = [
                'Icd10Code' => 'A36',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,

                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consult']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'DptDoses' => $row['number_of_total_doses_diphtheria_containing_vaccine'],
                'DateLastDose' => EdcsImport::tDate($row['date_of_last_vaccination']),
                'CaseClassification' => $row['caseclassification'],
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['datedied']),

                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,
                'UniqueKey' => Diph::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'Year' => $row['year'],

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Diph::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Diph::create($table_params);
            }

            return $model;
        }
    }
}

class ChikvImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => NULL,
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,

                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_font_stylecolorred_font']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'CaseClass' => $row['case_classification'],
                'DCaseRep' => NULL,
                'DCASEINV' => NULL,
                'DayswidSymp' => NULL,
                'Fever' => ($row['fever'] == 'Yes') ? 'Y' : 'N',
                'Arthritis' => ($row['fever'] == 'Yes') ? 'Y' : 'N',
                'Hands' => NULL,
                'Feet' => NULL,
                'Ankles' => NULL,
                'OthSite' => NULL,
                'Arthralgia' => ($row['arthralgia'] == 'Yes') ? 'Y' : 'N',
                'PeriEdema' => NULL,
                'SkinMani' => NULL,
                'SkinDesc' => NULL,
                'Myalgia' => NULL,
                'BackPain' => NULL,
                'Headache' => NULL,
                'Nausea' => NULL,
                'MucosBleed' => NULL,
                'Vomiting' => NULL,
                'Asthenia' => NULL,
                'MeningoEncep' => NULL,
                'OthSymptom' =>NULL,
                'ClinDx' => NULL,
                'DCollected' => NULL,
                'DSpecSent' => NULL,
                'SerIgM' => NULL,
                'IgM_Res' => NULL,
                'DIgMRes' => NULL,
                'SerIgG' => NULL,
                'IgG_Res' => NULL,
                'DIgGRes' => NULL,
                'RT_PCR' => NULL,
                'RT_PCRRes' => NULL,
                'DRtPCRRes' => NULL,
                'VirIso' => NULL,
                'VirIsoRes' => NULL,
                'DVirIsoRes' => NULL,
                'TravHist' => ($row['is_there_history_of_travel_within_15_days'] == 'Yes') ? 'Y' : 'N',
                'PlaceofTravel' => $row['history_of_travel_specify'],
                'Residence' => NULL,
                'BldTransHist' => NULL,
                'Reporter' => $row['user_id'],
                'ReporterContNum' => NULL,
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),

                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,
                'UniqueKey' => Chikv::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'Recstatus' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'Year' => $row['year'],

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,

                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Chikv::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Chikv::create($table_params);
            }

            return $model;
        }
    }
}

class MeningoImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => 'A39',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,

                'Occupation' => $row['occupation'],
                'Workplace' => $row['name_of_workplace'],
                'WrkplcAddr' => $row['address_of_workplace'],
                'School' => $row['name_of_school'],
                'SchlAddr' => $row['address_of_school'],

                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admittedseenconsult']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'Fever' => ($row['fever'] == 'Yes') ? 1 : 0,
                'Seizure' => ($row['seizure'] == 'Yes') ? 1 : 0,
                'Malaise' => ($row['malaise'] == 'Yes') ? 1 : 0,
                'Headache' => ($row['headache'] == 'Yes') ? 1 : 0,
                'StiffNeck' => ($row['stiff_neck'] == 'Yes') ? 1 : 0,
                'Cough' => ($row['cough'] == 'Yes') ? 1 : 0,
                'Rash' => ($row['maculopapular_rash'] == 'Yes') ? 1 : 0,
                'Vomiting' => ($row['vomiting'] == 'Yes') ? 1 : 0,
                'SoreThroat' => ($row['sore_throat'] == 'Yes') ? 1 : 0,
                'Petechia' => ($row['petechia'] == 'Yes') ? 1 : 0,
                'SensoriumCh' => ($row['change_of_sensorium'] == 'Yes') ? 1 : 0,
                'RunnyNose' => ($row['runny_nose'] == 'Yes') ? 1 : 0,
                'Purpura' => ($row['purpura'] == 'Yes') ? 1 : 0,
                'Drowsiness' => ($row['drowsiness'] == 'Yes') ? 1 : 0,
                'Dyspnea' => ($row['dyspnea'] == 'Yes') ? 1 : 0,
                
                'Othlesions' => $row['other_lesions'],
                'OtherSS' => $row['other_signs_symptoms'],
                'ClinicalPres' => $row['clinical_presentation'],
                'CaseClassification' => $row['case_classification'],
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'Bld_CSF' => ($row['were_bloodcsf_extracted_before_the_first_dose_of_antibiotics_was_given_to_the_patient'] == 'Yes') ? 1 : 0,
                'Antibiotics' => ($row['administered_antibiotic_therapy'] == 'Yes') ? 1 : 0,
                'CSFSpecimen' => NULL,
                'CultureDone' => NULL,
                'DateCSFTakenCulture' => NULL,
                'CSFCultureResult' => NULL,
                'DateCSFCultureResult' => NULL,
                'CSFCultureOrganism' => NULL,
                'LatexAggluDone' => NULL,
                'DateCSFTakenLatex' => NULL,
                'CSFLatexResult' => NULL,
                'DateCSFLatexResult' => NULL,
                'CSFLatexOrganism' => NULL,
                'GramStainDone' => NULL,
                'CSFGramStainResult' => NULL,
                'DateCSFTakenGramstain' => NULL,
                'GramStainOrganism' => NULL,
                'BloodSpecimen' => NULL,
                'BloodCultureDone' => NULL,
                'BloodCultureResult' => NULL,
                'DateBloodCultureResult' => NULL,
                'DateBloodTakenCulture' => NULL,
                'BloodCultureOrganism' => NULL,
                'DateCSFGramResult' => NULL,
                'Interact' => ($row['did_the_patient_or_close_contacts_interact_with_a_suspected_or_confirmed_meningococcal_case'] == 'Yes') ? 1 : 0,
                'ContactName' => $row['close_contacts_names'],
                'SuspName' => $row['if_yes_what_was_the_name_of_the_suspected_or_confirmed_meningococcal_case'],
                'SuspAddress' => $row['what_is_the_address_of_the_suspected_or_confirmed_meningococcal_case'],
                'PlaceInteract' => $row['where_did_the_patient_or_close_contacts_interact_with_the_meningococcal_case'],
                'DateInteract' => EdcsImport::tDate($row['when']),
                'DaysNum' => $row['number_of_days'],
                'PtTravel' => ($row['did_the_patient_travel_within_2_weeks_prior_to_illness'] == 'Yes') ? 1 : 0,
                'PlacePtTravel' => $row['if_yes_where'][0],
                'ContactTravel' => ($row['did_the_patient_attend_any_social_gathering_within_2_weeks_prior_to_illness'] == 'Yes') ? 1 : 0,
                'PlaceContactTravel' => $row['if_yes_who_and_where'],
                'AttendSocicalGather' => ($row['did_the_patient_attend_any_social_gathering_within_2_weeks_prior_to_illness'] == 'Yes') ? 1 : 0,
                'PlaceSocialGather' => $row['if_yes_where'][1],
                'PatientURTI' => ($row['did_the_patient_have_upper_respiratory_tract_infection_within_2_weeks_prior_to_illness'] == 'Yes') ? 1 : 0,
                'ContactURTI' => ($row['did_a_close_contacts_have_upper_respiratory_tract_infection_within_2_weeks_prior_to_the_patients_illness'] == 'Yes') ? 1 : 0,
                
                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'SentinelSite' => NULL,
                'DELETERECORD' => NULL,
                'District' => 6,
                'InterLocal' => NULL,
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,
                'UniqueKey' => Meningo::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'Year' => $row['year'],
                
                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Meningo::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = Meningo::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Meningo::create($table_params);
            }

            return $model;
        }   
    }
}

class NtImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => 'A35',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,

                'Address' => NULL,
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_font_stylecolorred_font']),
                'DONSET' => EdcsImport::tDate($row['date_onse_of_illness']),
                'DateOfReport' => EdcsImport::tDate($row['timestamp']),
                'DateOfInvestigation' => EdcsImport::tDate($row['timestamp']),
                'Investigator' => $row['user_id'],
                'ContactNum' => NULL,
                'First2days' => NULL,
                'After2days' => NULL,
                'FinalDx' => NULL,
                'Trismus' => $row['from_3_to_28_days_of_life_does_the_baby_have_convulsions_or_muscles_stiffness_or_fits_trismus'],
                'ClenFis' => NULL,
                'Opistho' => NULL,
                'StumpInf' => $row['was_the_umbilical_stump_infected_bad_smell_pus'],
                'ReportToInvestigation' => NULL,
                'TotPreg' => $row['no_of_total_pregnancies'],
                'Livebirths' => $row['live_births'],
                'TTDose' => $row['how_many_doses_of_tetanus_containing_vaccine'],
                'LivingKids' => $row['living_children'],
                'LastDoseGiven' => EdcsImport::tDate($row['date_last_dose_given']),
                'DosesGiven' => $row['how_many_doses_of_tetanus_containing_vaccine'],
                'PreVisits' => $row['if_she_received_2_doses_were_they_given_during'],
                'ImmunStatRep' => $row['immunizationreported'],
                'FirstPV' => EdcsImport::tDate($row['td1']),
                'ChldProt' => ($row['is_the_child_protected_at_birth'] == 'Yes') ? 'Y' : 'N',
                'PNCHist' => $row['prenatalcarehistory'],
                'Reason' => $row['state_reason_for_no_or_late_prenatal'],
                'PlaceDel' => $row['placedelivery'],
                'OtherPlaceDelivery' => $row['place_of_delivery_others_font_stylecolorred_font'],
                'NameAddressHospital' => $row['if_born_in_a_hospitallying_inclinic_give_name_and_address_of_the_hospitallying_inclinic_font_stylecolorred_font'],
                'OtherInstrument' => $row['cord_was_cut_using_others_specify_font_stylecolorred_font'],
                'DelAttnd' => $row['attendedelivery'],
                'OtherAttendant' => $row['who_attended_the_delivery_others_specify_font_stylecolorred_font'],
                'CordCut' => ($row['cordcut'] == 'Yes') ? 'Y' : 'N',
                'StumpTreat' => ($row['stump'] == 'Yes') ? 'Y' : 'N',
                'OtherMaterials' => $row['cord_was_cut_using_others_specify_font_stylecolorred_font'],
                'FinalClass' => $row['caseclassification'],
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                
                'Mother' => NULL,
                'DOBtoOnset' => NULL,

                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,
                'UniqueKey' => Nt::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'Year' => $row['year'],

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Nt::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Nt::create($table_params);
            }

            return $model;
        }
    }
}

class PertImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            dd($row);
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $match_casedef = 1;

            //Case Def Check
            if($row['coughing_lasting_at_least_2_weeks'] == 'Yes') {
                if($row['paroxysms_of_coughing'] == 'Yes' || $row['inspiratory_whooping'] == 'Yes' || $row['post_tussive_vomiting'] == 'Yes' || $row['others'] == 'Yes') {
                    $match_casedef = 1;
                }
                else {
                    $match_casedef = 0;
                }
            }
            else {
                $match_casedef = 0;
            }

            $table_params = [
                'Icd10Code' => 'A37',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,
                
                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'DptDoses' => ($row['pertussis_containing_vaccine_doses'] == 'Yes') ? 'Y' : 'N',
                'DateLastDose' => EdcsImport::tDate($row['if_yes_number_of_total_doses_health_facility_font_stylecolorred_font']),
                'CaseClassification' => substr($row['caseclassification'],0,1),
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),

                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,
                'UniqueKey' => Pert::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'Year' => $row['year'],

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                'match_casedef' => $match_casedef,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Pert::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Pert::create($table_params);
            }

            return $model;
        }
    }
}

class CholeraImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => 'A00',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,

                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_font_stylecolorred_font']),
                'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                'StoolCulture' => NULL,
                'Organism' => NULL,
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'CASECLASS' => mb_strtoupper(substr($row['caseclassification'],0,1)),

                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,
                'UniqueKey' => Pert::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'Year' => $row['year'],

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];

            $exist_check = Cholera::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Cholera::create($table_params);
            }

            return $model;
        }
    }
}

class InfluenzaImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'Icd10Code' => 'J10, J11',
                'RegionOFDrU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1,
                'ProvOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province,
                'MuncityOfDRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity,
                'DRU' => EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type,
                'NameOfDru' => $row['facilityname'],
                'AddressOfDRU' => NULL,
                'PatientNumber' => $row['patient_no'],
                'FirstName' => $row['first_name'],
                'FamilyName' => $row['last_name'],
                'FullName' => $getFullName,
                'Region' => '04A',
                'Province' => 'CAVITE',
                'Muncity' => 'GENERAL TRIAS',
                'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'Sex' => strtoupper(substr($row['sex'],0,1)),
                'DOB' => EdcsImport::tDate($row['date_of_birth']),
                'AgeYears' => $row['age_in_years'],
                'AgeMons' => $getAgeMonths,
                'AgeDays' => $getAgeDays,

                'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consulted']),
                'DOnset' => EdcsImport::tDate($row['date_on_set_of_illness']),
                'LabResult' => NULL,
                'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                'DateDied' => EdcsImport::tDate($row['date_died']),
                'CASECLASS' => $row['case_classification'],
                'SARI' => NULL,
                'Organism' => NULL,

                'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                'SentinelSite' => NULL,
                'DeleteRecord' => NULL,
                'District' => 6,
                'ILHZ' => 'GENTAMAR',
                'SENT' => 'Y',
                'ip' => 'N',
                'ipgroup' => NULL,
                'UniqueKey' => Pert::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                'RECSTATUS' => NULL,
                'TYPEHOSPITALCLINIC' => $row['verification_level'],
                'MorbidityMonth' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'MorbidityWeek' => $row['morbidity_week'],
                'EPIID' => $row['epi_id'],
                'Year' => $row['year'],

                'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                'edcs_caseid' => $row['case_id'],
                'edcs_healthFacilityCode' => $row['health_facility_code'],
                'edcs_verificationLevel' => $row['verification_level'],
                'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                'from_edcs' => 1,
                
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),
            ];
            
            $exist_check = Influenza::where('EPIID', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];

                $model = Influenza::create($table_params);
            }

            return $model;
        }
    }
}

class LaboratoryImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            //Search for duplicate to avoid duplicate
            $lab_search = EdcsLaboratoryData::where('user_id', $row['user_id'])
            ->where('specimen_collected_date', EdcsImport::tDate($row['date_specimen_collected']))
            ->where('specimen_type', $row['specimen_type'])
            ->where('test_type', $row['type_of_test_conducted'])
            ->first();

            if(!$lab_search) {
                $table_params = [
                    'lab_id' => $row['id'],
                    'case_id' => $row['case_id'],
                    'case_code' => $row['case_code'],
                    'epi_id' => $row['epi_id'],
                    'sent_to_ritm' => $row['specimen_sent_to_ritmsnl'],
                    'specimen_collected_date' => EdcsImport::tDate($row['date_specimen_collected']),
                    'specimen_type' => $row['specimen_type'],
                    'date_sent' => EdcsImport::tDate($row['date_sent']),
                    'date_received' => EdcsImport::tDate($row['date_received']),
                    'result' => $row['laboratory_result'],
                    'test_type' => $row['type_of_test_conducted'],
                    'interpretation' => $row['interpretation'],
                    'user_id' => $row['user_id'],
                    'timestamp' => EdcsImport::tDate($row['timestamp']),
                    'last_modified_by' => $row['last_modified_by'],
                    'last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                    'user_regcode' => $row['user_regcode'],
                    'user_provcode' => $row['user_provcode'],
                    'user_citycode' => $row['user_citycode'],
                    'hfhudcode' => $row['hfhudcode'],
                ];
    
                $exist_check = EdcsLaboratoryData::where('lab_id', $row['id'])->first();
                
                if($exist_check) {
                    $old_modified_date = $exist_check->last_modified_date;
                    $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                    if(is_null($exist_check->last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                        $model = $exist_check->update($table_params);
                    }
    
                    $model = $exist_check;
                }
                else {
                    $table_params = $table_params + [
                        'created_by' => auth()->user()->id,
                    ];

                    $model = EdcsLaboratoryData::create($table_params);
                }
    
                return $model;
            }
        }
    }
}

class SevereAcuteRespiratoryInfectionImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow {
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {

            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            $table_params = [
                'edcs_caseid' => $row['case_id'],
                'epi_id' => $row['epi_id'],
                'patient_number' => $row['patient_no'],
                'lname' => $row['last_name'],
                'fname' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'suffix' => $row['suffix_name'],
                'sex' => mb_strtoupper(substr($row['sex'],0,1)),
                'birthdate' => EdcsImport::tDate($row['date_of_birth']),
                'age_years' => $row['age_in_years'],
                'age_months' => $getAgeMonths,
                'age_days' => $getAgeDays,
                'region' => '04A',
                'province' => 'CAVITE',
                'muncity' => 'GENERAL TRIAS',
                'barangay' => (!is_null($row['current_address_barangay'])) ? EdcsImport::brgySetter($row['current_address_barangay']) : NULL,
                'streetpurok' => (!is_null($row['current_address_sitio_purok_street_name'])) ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                'perm_region' => mb_strtoupper($row['permanent_address_region']),
                'perm_province' => mb_strtoupper($row['permanent_address_province']),
                'perm_muncity' => mb_strtoupper($row['permanent_address_city_municipality']),
                'perm_barangay' => (!is_null($row['permanent_address_barangay'])) ? EdcsImport::brgySetter($row['permanent_address_barangay']) : NULL,
                'perm_streetpurok' => (!is_null($row['permanent_address_sitio_purok_street_name'])) ? mb_strtoupper($row['permanent_address_sitio_purok_street_name']) : NULL,
                'facility_name' => $fac_name,
                'edcs_healthFacilityCode' => $hfcode,
                'admitted' => $row['patient_admitted'],
                'date_admitted' => EdcsImport::tDate($row['date_admittedseenconsult']),
                'date_onset' => EdcsImport::tDate($row['date_onset_of_ill_ness']),
                'ranitidine' => $row['ranitidine'],
                'zanamivir' => $row['zanamivir'],
                'amantidine' => $row['amantidine'],
                'oseltamivir' => $row['oseltamivir'],
                'others_medicine' => $row['others_please_specify'][0],
                'arethereinfluenzaduringtheweek' => $row['are_there_any_influenza_like_illness_during_the_week_in_yourbrhousehold'],
                'school_daycare_workplace' => $row['schooldaycareworkplace'],
                'receiveinfluenzavaccinepastyear' => $row['did_you_receive_an_anti_influenza_vaccine_in_the_past_year'],
                'date_vaccinated' => EdcsImport::tDate($row['if_yes_date_of_vaccination']),
                'bats' => $row['history_of_exposure_to_any_of_the_ffbrbats'],
                'camels' => $row['camels'],
                'horses' => $row['horses'],
                'poultry_birds' => $row['poultrymigratory_birds'],
                'pigs' => $row['pigs'],
                'other_animal' => $row['others'],
                'history_of_travel' => $row['history_of_travel'],
                'date_of_travel' => $row['if_yes_date_of_travel'],
                'specify_countries' => $row['specify_countryies'],
                'chestxray_done' => $row['chest_x_ray_done'],
                'chestxray_result' => $row['chest_x_ray_result'],
                'temperature_at_consultation' => $row['temperature_at_consultation'],
                'fever' => $row['fever_feverish'],
                'fever_duration' => $row['fever_duration_daysweeks'],
                'headache' => $row['headache'],
                'cough' => $row['cough'],
                'sorethroat' => $row['sore_throat'],
                'difficultyofbreathing' => $row['difficulty_of_breathing'],
                'requires_hospital_admission' => $row['requires_hospital_admission'][0],
                'others' => $row['others_please_specify'][1],
                'any_twomonthstofiveyears_age_withcoughordob' => $row['any_2_months_to_5_years_of_age_with_cough_or_difficult_breathing'],
                'bftsixtybreaths_infants' => $row['breathing_faster_than_60_breathsmin_infants_2_months'],
                'bftfiftybreaths_twototwelvemonths' => $row['breathing_faster_than_50_breathsmin_2_12_months'],
                'bftfortybreaths_onetofiveyo' => $row['breathing_faster_than_40_breathsmin_1_5_years_old'],
                'requires_hospital_admission2' => $row['requires_hospital_admission'][1],
                'any_twomonthstofiveyears_age_withcoughordob2' => $row['any_child_2_months_to_5_years_of_age_with_cough_or_difficult_breathing'],
                'unabletodrinkorbreastfeed' => $row['unable_to_drink_or_breastfeed'],
                'vomitseverything' => $row['vomits_everything'],
                'convulsions' => $row['convulsions'],
                'lethargic_unconscious' => $row['lethargic_or_unconscious'],
                'stridor' => $row['chest_indrawing_or_stridor_in_a_calm_child'],
                'requires_hospital_admission3' => $row['requires_hospital_admission'][2],
                'asthma' => $row['asthma'],
                'chroniccardiacdisease' => $row['chronic_cardiac_disease'],
                'chronicliverdisesae' => $row['chronic_liver_disease'],
                'chronicneurological' => $row['chronic_neurological_or_neuromuscular_disease'],
                'chronicrenal' => $row['chronic_renal_disease'],
                'diabetes' => $row['diabetes'],
                'haematologicdisorders' => $row['haematologic_disorders'],
                'immunodeficiencydiseases' => $row['immunodeficiency_diseases'],
                'pregnancy' => $row['prenancy'],
                'antibiotics' => $row['antibiotics'],
                'specify_antibiotics' => $row['specify_antibiotics'],
                'antivirals' => $row['antivirals'],
                'specify_antivirals' => $row['specify_antivirals'],
                'fluid_theraphy' => $row['fluid_theraphy'],
                'specify_fluidtherapy' => $row['specify_fluid_theraphy'],
                'oxygen' => $row['oxygen'],
                'specify_oxygen' => $row['specify_oxygen'],
                'intubation' => $row['intubation'],
                'specify_intubation' => $row['specify_intubation'],
                'bacterialtesting' => $row['bacterial_testing'],
                'specify_bacterialtesting' => $row['specify_bacterial_testing'],
                'othertherapeutic' => $row['other_therapeutic_procedures'],
                'specify_othertherapeutic' => $row['specify_other_therapeutic_procedures'],
                'final_diagnosis' => $row['final_diagnosis'],
                'outcome' => $row['outcome'],
                'date_discharged' => EdcsImport::tDate($row['date_of_discharge']),
                'date_died' => EdcsImport::tDate($row['date_died']),
                'case_classification' => $row['case_classification'],

                'year' => $row['year'],
                'morbidity_month' => Carbon::parse(EdcsImport::tDate($row['timestamp']))->format('n'),
                'morbidity_week' => $row['morbidity_week'],
                'admit_to_entry' => $row['timelapse_dateadmittodateencode'],
                'onset_to_admit' => $row['timelapse_dateonsettodateencode'],
                
                'systemsent' => 0,
                'enabled' => 1,
                //'edcs_investigatorName' => $row['edcs_caseid'],
                //'edcs_contactNo' => $row['edcs_caseid'],
                //'edcs_ageGroup' => $row['edcs_caseid'],
                'edcs_verificationLevel' => $row['verification_level'],
                'from_edcs' => 1,
                //'encoded_mw' => $row['edcs_caseid'],
                'match_casedef' => 1,
                'system_notified' => 0,
                'edcs_userid' => $row['user_id'],
                'edcs_last_modifiedby' => $row['last_modified_by'],
                'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                'notify_email_sent' => 0,
                //'notify_email_sent_datetime' => $row['edcs_caseid'],
                //'edcs_patientcontactnum' => $row['edcs_caseid'],
                //'system_remarks' => $row['edcs_caseid'],
                //'system_subdivision_id' => $row['edcs_caseid'],
            ];

            $exist_check = SevereAcuteRespiratoryInfection::where('epi_id', $row['epi_id'])->first();

            if($exist_check) {
                $old_modified_date = $exist_check->edcs_last_modified_date;
                $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                    $model = $exist_check->update($table_params);
                }

                $model = $exist_check;
            }
            else {
                $table_params = $table_params + [
                    'created_by' => auth()->user()->id,
                ];
                
                $model = SevereAcuteRespiratoryInfection::create($table_params);
            }

            return $model;
        }
    }
}