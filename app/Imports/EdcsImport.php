<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Abd;
use App\Models\Afp;
use App\Models\Nnt;
use App\Models\Ames;
use App\Models\Hfmd;
use App\Models\Dengue;
use App\Models\Rabies;
use App\Models\Measles;
use App\Models\Hepatitis;
use App\Models\Leptospirosis;
use App\Models\Rotavirus;
use App\Models\Typhoid;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EdcsImport implements WithMultipleSheets
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

    public function sheets(): array
    {
        return [
            
            'ABD' => new AbdImport(),
            'AFP' => new AfpImport(),
            'AMES' => new AmesImport(),
            'DENGUE' => new DengueImport(),
            'HEPA' => new HepaImport(),
            'HFMD' => new HfmdImport(),
            'LEPTO' => new LeptoImport(),
            'MEASLES' => new MeaslesImport(),
            'NNT' => new NntImport(),
            'RABIES' => new RabiesImport(),
            'ROTA' => new RotaImport(),
            'TYPHOID' => new TyphoidImport(),
        ];
    }
}

class AbdImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Abd::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Abd::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Abd([
                        'Icd10Code' => NULL,
                        'RegionOFDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
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
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                        'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                        'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                        'DAdmit' => EdcsImport::tDate($row['date_admitted']),
                        'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                        'StoolCulture' => NULL,
                        'Organism' => NULL,
                        'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                        'DateDied' => EdcsImport::tDate($row['date_died']),
                        'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'SentinelSite' => NULL,
                        'DeleteRecord' => NULL,
                        'Year' => $row['year'],
                        'NameOfDru' => $row['facilityname'],
                        'District' => NULL,
                        'InterLocal' => NULL,
                        
                        'CASECLASS' => $row['case_classi'],
                        'TYPEHOSPITALCLINIC' => $row['verification_level'],
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => NULL,
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class AfpImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Afp::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Afp::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Afp([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
                        'AddressOfDRU' => NULL,
                        'PatientNumber' => $row['patient_no'],
                        'FirstName' => $row['first_name'],
                        'FamilyName' => $row['last_name'],
                        'FullName' => $getFullName,
                        'Region' => '04A',
                        'Province' => 'CAVITE',
                        'Muncity' => 'GENERAL TRIAS',
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
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
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'Fever' => ($row['fever'] == 'Yes') ? 1 : 0,
                        'DONSETP' => EdcsImport::tDate($row['date_onset_paralysis']),
                        'RArm' => ($row['right_arm'] == 'Yes') ? 1 : 0,
                        'Cough' => ($row['cough'] == 'Yes') ? 1 : 0,
                        'ParalysisAtBirth' => ($row['present_at_birth'] == 'Yes') ? 1 : 0,
                        'LArm' => ($row['left_arm'] == 'Yes') ? 1 : 0,
                        'DiarrheaVomiting' => ($row['diarrheavomiting'] == 'Yes') ? 1 : 0,
                        'Asymm' => ($row['asymmetric'] == 'Yes') ? 1 : 0,
                        'RLeg' => ($row['right_leg'] == 'Yes') ? 1 : 0,
                        'MusclePain' => ($row['muscle_pain'] == 'Yes') ? 1 : 0,
                        'LLeg' => ($row['left_leg'] == 'Yes') ? 1 : 0,
                        'Mening' => ($row['meningeal_signs'] == 'Yes') ? 1 : 0,
                        'BrthMusc' => ($row['breathing_muscles'] == 'Yes') ? 1 : 0,
                        'NeckMusc' => ($row['neck_muscles'] == 'Yes') ? 1 : 0,
                        'Paradev' => ($row['paralysis_fully_developed_within_3_to_14_days_from_onset_of_illness'] == 'Yes') ? 1 : 0,
                        'Paradir' => $row['direction_of_paralysis'],
                        'FacialMusc' => ($row['facial_muscles'] == 'Yes') ? 1 : 0,
                        'WorkingDiagnosis' => $row['working_diagnosis'],
                        'RASens' => $row['sensory_status'],
                        'LASens' => $row['sensory_status_1'],
                        'RLSens' => $row['sensory_status_4'],
                        'LLSens' => $row['sensory_status_7'],
                        'RARef' => $row['deep_tendon_reflexes'],
                        'LARef' => $row['deep_tendon_reflexes_2'],
                        'RLRef' => $row['deep_tendon_reflexes_5'],
                        'LLRef' => $row['deep_tendon_reflexes_8'],
                        'RAMotor' => $row['motor_status'],
                        'LAMotor' => $row['motor_status_3'],
                        'RLMotor' => $row['motor_status_6'],
                        'LLMotor' => $row['motor_status_9'],
                        'HxDisorder' => ($row['history_of_neurologic_disorder'] == 'Yes') ? 1 : 0,
                        'Disorder' => ($row['history_of_neurologic_disorder'] == 'Yes') ? $row['if_yes_specify_disorder'] : NULL,
                        'TravelPrior2Illness' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? 1 : 0,
                        'PlaceOfTravel' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? $row['if_yes_specify_place'] : NULL,
                        'FrmTrvlDate' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? EdcsImport::tDate($row['date_traveled_from']) : NULL,
                        'OtherCases' => ($row['other_afp_cases_in_patients_community_within_60_days_of_patients_paralysis'] == 'Yes') ? 1 : 0,
                        'InjTrauAnibite' => ($row['does_the_patient_had_any_history_of_injection_trauma_and_or_animal_bite'] == 'Yes') ? 1 : 0,
                        'SpecifyInjTrauAnibite' => ($row['does_the_patient_had_any_history_of_injection_trauma_and_or_animal_bite'] == 'Yes') ? $row['if_yes_specify_type'] : 0,
                        'Investigator' => $row['name_of_investigator'],
                        'ContactNum' => $row['contact_no'],
                        'OPVDoses' => $row['total_opvipv_doses_received'],
                        'DateLastDose' => ($row['date_last_dose_of_opvipv'] != "" && !is_null($row['date_last_dose_of_opvipv'])) ? EdcsImport::tDate($row['date_last_dose_of_opvipv']) : NULL,
                        'HotCase' => ($row['is_this_a_hot_case'] == 'Yes') ? 1 : 0,
    
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
                        'Atrophy' => ($row['presence_of_atrophy'] == 'Yes') ? 1 : 0,
                        'RAatrophy' => ($row['right_arm1'] == 'Yes') ? 1 : 0,
                        'LAatrophy' => ($row['left_arm1'] == 'Yes') ? 1 : 0,
                        'RLatrophy' => ($row['right_leg1'] == 'Yes') ? 1 : 0,
                        'LLatrophy' => ($row['left_leg1'] == 'Yes') ? 1 : 0,
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
                        'ILHZ' => NULL,
                        'District' => NULL,
                        
                        'TYPEHOSPITALCLINIC' => NULL,
                        'OCCUPATION' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N', 
                        'ipgroup' => NULL,
                        'Outcome' => NULL,
                        'DateOutcomeDied' => ($row['date_died'] != "" && !is_null($row['date_died'])) ? EdcsImport::tDate($row['date_died']) : NULL,
    
                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => $row['name_of_investigator'],
                        'edcs_contactNo' => $row['contact_no'],
                        'edcs_ageGroup' => $row['age_group'],
                        'from_edcs' => 1,
                    ]);
                }
            }
        }        
    }
}

class AmesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Ames::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Ames::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Ames([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
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
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
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
                        'Fever' => ($row['fever'] == 'Yes') ? 1 : 0,
                        'BehaviorChng' => ($row['change_in_mental_status'] == 'Yes') ? 1 : 0,
                        'Seizure' => ($row['new_onset_seizures'] == 'Yes') ? 1 : 0,
                        'Stiffneck' => ($row['neck_stiffness'] == 'Yes') ? 1 : 0,
                        'bulgefontanel' => NULL,
                        'MenSign' => ($row['meningeal_signs'] == 'Yes') ? 1 : 0,
                        'ClinDiag' => $row['cnsinfection'],
                        'OtherDiag' => $row['cns_others_specify'],
                        'JE' => ($row['je'] == 'Yes') ? 1 : 0,
                        'VacJeDate' => EdcsImport::tDate($row['je_date_last_dose']),
                        'JEDose' => $row['je_no_of_doses'],
                        'Hib' => ($row['penta_hib'] == 'Yes') ? 1 : 0,
                        'VacHibDate' => EdcsImport::tDate($row['penta_hib_date_last_dose']),
                        'HibDose' => $row['penta_hib_no_of_doses'],
                        'PCV10' => ($row['pcv10'] == 'Yes') ? 1 : 0,
                        'VacPCV10Date' => EdcsImport::tDate($row['pcv10_date_last_dose']),
                        'PCV10Dose' => $row['pcv_10_no_of_doses'],
                        'PCV13' => ($row['pcv13'] == 'Yes') ? 1 : 0,
                        'VacPCV13Date' => EdcsImport::tDate($row['pcv13_date_last_dose']),
                        'PCV13Dose' => $row['pcv13_no_of_doses'],
                        'MeningoVacc' => ($row['meningococcal'] == 'Yes') ? 1 : 0,
                        'VacMeningoDate' => EdcsImport::tDate($row['meningococcal_date_last_dose']),
                        'MeningoVaccDose' => $row['meningococcal_no_of_doses'],
                        'MeasVacc' => ($row['measles'] == 'Yes') ? 1 : 0,
                        'VacMeasDate' => EdcsImport::tDate($row['measles_date_last_dose']),
                        'MeasVaccDose' => $row['measles_no_of_doses'],
                        'MMR' => NULL,
                        'VacMMRDate' => NULL,
                        'MMRDose' => NULL,
                        'plcDaycare' => ($row['day_care'] == 'Yes') ? 1 : 0,
                        'plcBrgy' => ($row['barangay'] == 'Yes') ? 1 : 0,
                        'plcHome' => ($row['home'] == 'Yes') ? 1 : 0,
                        'plcSchool' => ($row['school'] == 'Yes') ? 1 : 0,
                        'plcdormitory' => ($row['dormitory'] == 'Yes') ? 1 : 0,
                        'plcHC' => ($row['health_care_facility'] == 'Yes') ? 1 : 0,
                        'plcWorkplace' => ($row['workplace'] == 'Yes') ? 1 : 0,
                        'plcOther' => ($row['other_exposure'] == 'Yes') ? 1 : 0,
                        'Travel' => ($row['if_yes_specify_place'] == 'Yes') ? 1 : 0,
                        'PlaceTravelled' => $row['if_yes_specify_place'],
                        'FrmTrvlDate' => EdcsImport::tDate($row['date_traveled_from']),
                        'ToTrvlDate' => EdcsImport::tDate($row['date_traveled_to']),
                        'CSFColl' => ($row['were_bloodcsf_extracted_before_the_first_dose_of_antibiotics_was_given_to_the_patient'] == 'Yes') ? 1 : 0,
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
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'SentinelSite' => NULL,
                        'DeleteRecord' => NULL,
                        'Year' => $row['year'],
                        'NameOfDru' => $row['facilityname'],
                        'ILHZ' => NULL,
                        'District' => NULL,
                        
                        'CASECLASS' => NULL,
                        'TYPEHOSPITALCLINIC' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => $row['name_of_investigator'],
                        'edcs_contactNo' => $row['contact_no'],
                        'edcs_ageGroup' => $row['age_group'],
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class HepaImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Hepatitis::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Hepatitis::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Hepatitis([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
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
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
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
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'SentinelSite' => NULL,
                        'DeleteRecord' => NULL,
                        'Year' => $row['year'],
                        'NameOfDru' => $row['facilityname'],
                        'ILHZ' => NULL,
                        'District' => NULL,
                        
                        'CASECLASS' => $row['caseclassification'],
                        'TYPEHOSPITALCLINIC' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => NULL,
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class HfmdImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Hfmd::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Hfmd::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Hfmd([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
                        'AddressOfDRU' => NULL,
                        'PatientNumber' => $row['patient_no'],
                        'FirstName' => $row['first_name'],
                        'FamilyName' => $row['last_name'],
                        'FullName' => $getFullName,
                        'Region' => '04A',
                        'Province' => 'CAVITE',
                        'Muncity' => 'GENERAL TRIAS',
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                        'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                        'Sex' => strtoupper(substr($row['sex'],0,1)),
                        'DOB' => EdcsImport::tDate($row['date_of_birth']),
                        'AgeYears' => $row['age_in_years'],
                        'AgeMons' => $getAgeMonths,
                        'AgeDays' => $getAgeDays,
                        'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                        'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consult']),
                        'DONSET' => EdcsImport::tDate($row['date_onset']),
                        'Fever' => ($row['fever'] == 'Yes') ? 1 : 0,
                        'FeverOnset' => EdcsImport::tDate($row['date_onset_of_fever']),
                        'RashChar' => ($row['rash'] == 'Yes') ? 1 : 0,
                        'RashSores' => NULL,
                        'SoreOnset' => EdcsImport::tDate($row['date_onset_of_rash']),
                        'Palms' => ($row['palms'] == 'Yes') ? 1 : 0,
                        'Fingers' => ($row['fingers'] == 'Yes') ? 1 : 0,
                        'FootSoles' => ($row['soles_of_feet'] == 'Yes') ? 1 : 0,
                        'Buttocks' => ($row['buttocks'] == 'Yes') ? 1 : 0,
                        'MouthUlcers' => ($row['mouth_ulcers'] == 'Yes') ? 1 : 0,
                        'Pain' => ($row['painful'] == 'Yes') ? 1 : 0,
                        'Anorexia' => ($row['loss_of_appetite'] == 'Yes') ? 1 : 0,
                        'BM' => ($row['body_malaise'] == 'Yes') ? 1 : 0,
                        'SoreThroat' => ($row['sore_throat'] == 'Yes') ? 1 : 0,
                        'NausVom' => ($row['nause_or_vomiting'] == 'Yes') ? 1 : 0,
                        'DiffBreath' => ($row['difficulty_of_breathing'] == 'Yes') ? 1 : 0,
                        'Paralysis' => ($row['acute_flaccid_paralysis'] == 'Yes') ? 1 : 0,
                        'MeningLes' => ($row['meningea_lirritation'] == 'Yes') ? 1 : 0,
                        'OthSymptoms' => ($row['others_symptoms'] == 'Yes') ? 1 : 0,
                        'AnyComp' => ($row['are_there_any_complications'] == 'Yes') ? 1 : 0,
                        'Complic8' => $row['if_yes_specify_complication'],
                        'Investigator' => $row['name_of_investigators'],
                        'ContactNum' => $row['contact_numbers'],
                        'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'ReportToInvestigation' => NULL,
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'Travel' => NULL,
                        'ProbExposure' => NULL,
                        'OthExposure' => ($row['other_exposure'] == 'Yes') ? 1 : 0,
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
                        'CaseClass' => $row['case_classification'],
                        'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                        'WFDiag' => $row['workingfinal_diagnosis'],
                        'Death' => EdcsImport::tDate($row['date_died']),
                        'DCaseRep' => NULL,
                        'DCASEINV' => EdcsImport::tDate($row['date_of_investigation']),
                        'SentinelSite' => NULL,
                        'Year' => $row['year'],
                        'DeleteRecord' => NULL,
                        'NameOfDru' => $row['facilityname'],
                        'District' => NULL,
                        'ILHZ' => NULL,
                        
                        'TYPEHOSPITALCLINIC' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => NULL,
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class LeptoImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Leptospirosis::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Leptospirosis::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Leptospirosis([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
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
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
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
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'SentinelSite' => NULL,
                        'DeleteRecord' => NULL,
                        'Year' => $row['year'],
                        'NameOfDru' => $row['facilityname'],
                        'District' => NULL,
                        'ILHZ' => NULL,
                        
                        'TYPEHOSPITALCLINIC' =>NULL,
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => $row['name_of_investigator'],
                        'edcs_contactNo' => $row['contact_no'],
                        'edcs_ageGroup' => $row['age_group'],
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class MeaslesImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Measles::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Measles::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Measles([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
                        'AddressOfDRU' => NULL,
                        'PatientNumber' => $row['patient_no'],
                        'FirstName' => $row['first_name'],
                        'FamilyName' => $row['last_name'],
                        'FullName' => $getFullName,
                        'Address' => NULL,
                        'Region' => '04A',
                        'Province' => 'CAVITE',
                        'Muncity' => 'GENERAL TRIAS',
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                        'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                        'Sex' => strtoupper(substr($row['sex'],0,1)),
                        'Preggy' => ($row['pregnant'] == 'Yes') ? 1 : 0,
                        'WkOfPreg' => $row['if_yes_weeks_of_pregnancy'],
                        'DOB' => EdcsImport::tDate($row['date_of_birth']),
                        'AgeYears' => $row['age_in_years'],
                        'AgeMons' => $getAgeMonths,
                        'AgeDays' => $getAgeDays,
                        'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                        'DAdmit' => EdcsImport::tDate($row['date_admitted_seen_consulted']),
                        'DONSET' => EdcsImport::tDate($row['date_onset']),
                        'VitaminA' => ($row['was_the_patient_given_vitamin_a_during_this_illness'] == 'Yes') ? 1 : 0,
                        'FeverOnset' => EdcsImport::tDate($row['date_onset']),
                        'MeasVacc' => ($row['patient_received_measles_containing_vaccine_mcv_br_if_yes_indicate_the_number_of_doses_whichever_is_applicable'] == 'Yes') ? 1 : 0,
                        'Cough' => ($row['cough'] == 'Yes') ? 1 : 0,
                        'KoplikSpot' => ($row['koplik_sign'] == 'Yes') ? 1 : 0,
                        'MVDose' => $row['mv'],
                        'MRDose' => $row['mr'],
                        'MMRDose' => $row['mmr'],
                        'LastVacc' => EdcsImport::tDate($row['date_last_dose_received_mcv']),
                        'RunnyNose' => ($row['runny_nosecoryza'] == 'Yes') ? 1 : 0,
                        'RedEyes' => ($row['red_eyes_conjunctivitis'] == 'Yes') ? 1 : 0,
                        'ArthritisArthralgia' => ($row['arthralgiaarthritis'] == 'Yes') ? 1 : 0,
                        'SwoLympNod' => ($row['swollen_lymphatic_nodules'] == 'Yes') ? 1 : 0,
                        'LympNodLoc' => $row['swollen_lymphatic_specify'],
                        'OthLocation' => $row['others_specify'],
                        'OthSymptoms' => $row['other_symptoms'],
                        'AreThereAny' => ($row['are_there_any_complications'] == 'Yes') ? 1 : 0,
                        'Complications' => $row['if_yes_specify'],
                        'Reporter' => $row['name_of_reporter'],
                        'Investigator' => $row['name_of_investigators'],
                        'RContactNum' => $row['contact_nos_1'],
                        'ContactNum' => $row['contact_nos_2'],
                        'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                        'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                        'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'ReportToInvestigation' => NULL,
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'Reasons' => NULL,
                        'OtherReasons' => NULL,
                        'SpecialCampaigns' => ($row['was_vaccination_received_during_special_campaigns'] == 'Yes') ? 1 : 0,
                        'Travel' => ($row['with_history_of_travel_within_23_days_prior_to_onset_of_rash'] == 'Yes') ? 1 : 0,
                        'PlaceTravelled' => $row['place_of_travel'],
                        'TravTiming' => EdcsImport::tDate($row['date_of_travel']),
                        'ProbExposure' => NULL,
                        'OtherExposure' => NULL,
                        'OtherCase' => ($row['are_there_other_known_cases_with_fever_and_rash_regardless_of_presence_of_3_csbr_in_the_community'] == 'Yes') ? 1 : 0,
                        'RashOnset' => EdcsImport::tDate($row['date_onset_3']),
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
                        'FinalClass' => $row['final_classification'],
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
                        'District' => NULL,
                        'ILHZ' => NULL,
                        
                        'TYPEHOSPITALCLINIC' => NULL,
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

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => $row['name_of_investigator'],
                        'edcs_contactNo' => $row['contact_no'],
                        'edcs_ageGroup' => $row['age_group'],
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class NntImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Nnt::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Nnt::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Nnt([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
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
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                        'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                        'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                        'DAdmit' => EdcsImport::tDate($row['date_admitted_font_stylecolorred_font']),
                        'DOnset' => EdcsImport::tDate($row['date_onse_of_illness']),
                        'RecentAcuteWound' => ($row['with_recent_wound'] == 'Yes') ? 1 : 0,
                        'WoundSite' => $row['woundsite'],
                        'WoundType' => $row['woundtype'],
                        'OtherWound' => $row['wound_type_specify'],
                        'TetanusToxoid' => ($row['recieved_tetanus_toxoid_vaccination'] == 'Yes') ? 1 : 0,
                        'TetanusAntitoxin' => ($row['recieved_aniti_tetanus_anti_toxin_or_tig'] == 'Yes') ? 1 : 0,
                        'SkinLesion' => ($row['with_recent_wound'] == 'Yes') ? 1 : 0,
                        'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                        'DateDied' => EdcsImport::tDate($row['date_died']),
                        'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                        'AdmitToEntry' => $row['timelapse_dateadmittodateencode'],
                        'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'SentinelSite' => NULL,
                        'DeleteRecord' => NULL,
                        'Year' => $row['year'],
                        'NameOfDru' => $row['facilityname'],
                        'District' => NULL,
                        'ILHZ' => NULL,
                        'TYPEHOSPITALCLINIC' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => $row['age_group'],
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class RabiesImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Rabies::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Rabies::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Rabies([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
                        'AddressOfDRU' => NULL,
                        'SentinelSite' => NULL,
                        'PatientNumber' => $row['patient_no'],
                        'FirstName' => $row['first_name'],
                        'FamilyName' => $row['last_name'],
                        'FullName' => $getFullName,
                        'Region' => '04A',
                        'Province' => 'CAVITE',
                        'Muncity' => 'GENERAL TRIAS',
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
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
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'PlaceOfIncidence' => $row['place_of_exposure'],
                        'TypeOfExposure' => $row['type_exposure'],
                        'Category' => $row['category_exposure'],
                        'BiteSite' => $row['affected_site'],
                        'OtherTypeOfExposure' => $row['other_specify'],
                        'DateBitten' => EdcsImport::tDate($row['date_exposure']),
                        'TypeOfAnimal' => $row['type_animal'],
                        'OtherTypeOfAnimal' => $row['other_specify1'],
                        'LabDiagnosis' => NULL,
                        'LabResult' => NULL,
                        'AnimalStatus' => $row['animal_status'],
                        'OtherAnimalStatus' => $row['other_specify_1'],
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
                        'District' => NULL,
                        'ILHZ' => NULL,
                        
                        'CASECLASS' => $row['final_classification'],
                        'TYPEHOSPITALCLINIC' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => NULL,
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class RotaImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Rotavirus::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Rotavirus::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Rotavirus([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
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
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                        'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && $row['current_address_sitio_purok_street_name'] != 'N/A') ? mb_strtoupper($row['current_address_sitio_purok_street_name']) : NULL,
                        'NHTS' => NULL,
                        'IVTherapy' => ($row['did_patient_receive_iv_rehydration_therapy_while_at_the_er'] == 'Yes') ? 1 : 0,
                        'Vomiting' => ($row['vomiting'] == 'Yes') ? 1 : 0,
                        'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                        'DAdmit' => EdcsImport::tDate($row['if_yes_date_of_admission_font_stylecolorred_font']),
                        'D_ONSET' => EdcsImport::tDate($row['date_of_onset_of_diarrhea']),
                        'DateRep' => EdcsImport::tDate($row['date_of_report']),
                        'DateInv' => EdcsImport::tDate($row['date_of_investigation']),
                        'Investigator' => $row['name_of_investigator'],
                        'ContactNum' => $row['contact_numbers'],
                        'InvDesignation' => $row['positiondesignation'],
                        'Fever' => ($row['fever'] == 'Yes') ? 1 : 0,
                        'Temp' => NULL,
                        'V_ONSET' => EdcsImport::tDate($row['date_of_onset_of_vomiting_health_facility_font_stylecolorred_font']),
                        'AdmDx' => $row['admitting_diagnosis'],
                        'FinalDx' => $row['final_diagnosis'],
                        'DegDehy' => $row['degree_of_dehydration_health_facility_font_stylecolorred_font'],
                        'DiarrCases' => $row['are_there_two_or_more_diarrhea_cases'],
                        'Community' => $row['if_yes_where'],
                        'HHold' => NULL,
                        'School' => NULL,
                        'RotaVirus' => ($row['received_rotavirus_vaccine'] == 'Yes') ? 1 : 0,
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
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' =>  NULL,
                        'RECSTATUS' =>  NULL,
                        'SentinelSite' =>  NULL,
                        'DeleteRecord' =>  NULL,
                        'Year' => $row['year'],
                        'NameOfDru' => $row['facilityname'],
                        'ILHZ' => NULL,
                        'District' => NULL,
                        
                        'TYPEHOSPITALCLINIC' => NULL,
                        'SENT' => 'Y',
                        'hospdiarrhea' => ($row['did_patient_have_previous_hospitalization_due_to_diarrhea'] == 'Yes') ? 1 : 0,
                        'Datehosp' => EdcsImport::tDate($row['if_yes_date_of_hospitalization_health_facility_font_stylecolorred_font']),
                        'classification' => $row['case_classification'],
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => $row['age_group'],
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class TyphoidImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Typhoid::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Typhoid::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Typhoid([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => NULL,
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
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
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
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'SentinelSite' => NULL,
                        'DeleteRecord' => NULL,
                        'Year' => $row['year'],
                        'NameOfDru' => $row['facilityname'],
                        'District' =>  NULL,
                        'ILHZ' =>  NULL,
                        'CASECLASS' => $row['caseclass'],
                        'TYPEHOSPITALCLINIC' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => NULL,
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}

class DengueImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            //EdcsImport::tDate($row['date_of_birth'])

            if(!(Dengue::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
                $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Dengue::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', EdcsImport::tDate($row['date_of_birth']))
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                //GET FULL NAME
                $getFullName = $row['last_name'].', '.$row['first_name'];

                if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['middle_name'];
                }

                if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A') {
                    $getFullName = $getFullName.' '.$row['suffix_name'];
                }

                if(!($check1)) {
                    return new Dengue([
                        'Region' => '04A',
                        'Province' => 'CAVITE',
                        'Muncity' => 'GENERAL TRIAS',
                        'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && mb_strtoupper($row['current_address_sitio_purok_street_name']) != 'N/A') ? $row['current_address_sitio_purok_street_name'] : NULL,
                        'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                        'DRU' => NULL,
                        'PatientNumber' => $row['patient_no'],
                        'FirstName' => $row['first_name'],
                        'FamilyName' => $row['last_name'],
                        'FullName' => $getFullName,
                        'AgeYears' => $row['age_in_years'],
                        'AgeMons' => $getAgeMonths,
                        'AgeDays' => $getAgeDays,
                        'Sex' => strtoupper(substr($row['sex'],0,1)),
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'AddressOfDRU' => NULL,
                        'DOB' => EdcsImport::tDate($row['date_of_birth']),
                        'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                        'DAdmit' => EdcsImport::tDate($row['date_admitted_seen']),
                        'DOnset' => EdcsImport::tDate($row['date_on_set_of_illness_first_symptoms']),
                        'Type' => NULL,
                        'LabTest' => NULL,
                        'LabRes' => NULL,
                        'ClinClass' => $row['clinical_classification'],
                        'CaseClassification' => $row['case_classification'],
                        'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                        'EPIID' => $row['epi_id'],
                        'DateDied' => EdcsImport::tDate($row['date_died']),
                        'Icd10Code' => NULL,
                        'MorbidityMonth' => date('m', strtotime(EdcsImport::tDate($row['timestamp']))),
                        'MorbidityWeek' => $row['morbidity_week'],
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'SentinelSite' => NULL,
                        'DeleteRecord' => NULL,
                        'Year' => $row['year'],
                        'Recstatus' => NULL,
                        'UniqueKey' => NULL,
                        'NameOfDru' => $row['facilityname'],
                        'ILHZ' => NULL,
                        'District' => NULL,
                        'Barangay' => ($row['current_address_barangay'] != '' && !is_null($row['current_address_barangay']) && $row['current_address_barangay'] != 'N/A') ? mb_strtoupper($row['current_address_barangay']) : NULL,
                        'TYPEHOSPITALCLINIC' => $row['verification_level'],
                        'SENT' => 'Y',
                        'ip' => 'N',
                        'ipgroup' => NULL,

                        'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name']) && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                        'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A') ? $row['suffix_name'] : NULL,
                        'edcs_caseid' => $row['case_id'],
                        'edcs_healthFacilityCode' => $row['health_facility_code'],
                        'edcs_verificationLevel' => $row['verification_level'],
                        'edcs_investigatorName' => NULL,
                        'edcs_contactNo' => NULL,
                        'edcs_ageGroup' => NULL,
                        'from_edcs' => 1,
                    ]);
                }
            }
        }
    }
}