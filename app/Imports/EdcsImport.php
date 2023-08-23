<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Afp;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EdcsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'ABD' => new AbdImport(),
            'AFP' => new AfpImport(),
            'AMES' => new AmesImport(),
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

    }
}

class AfpImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite') {
            if(!(Afp::where('EPIID', $row['epi_id'])->first())) {
                $birthdate = Carbon::parse($row['date_of_birth']);
                $currentDate = Carbon::parse($row['date_of_report']);

                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);

                //CHECK FOR DUPLICATES
                $check1 = Afp::where('FamilyName', $row['last_name'])
                ->where('FirstName', $row['first_name'])
                ->whereDate('DOB', $row['date_of_birth'])
                ->where('Year', $row['year'])
                ->where('MorbidityWeek', $row['morbidity_week'])
                ->first();

                if(!($check1)) {
                    return new Afp([
                        'Icd10Code' => NULL,
                        'RegionOfDrU' => NULL,
                        'ProvOfDRU' => NULL,
                        'MuncityOfDRU' => NULL,
                        'DRU' => $row['facilityname'],
                        'AddressOfDRU' => NULL,
                        'PatientNumber' => $row['patient_no'],
                        'FirstName' => $row['first_name'],
                        'FamilyName' => $row['last_name'],
                        'FullName' => $row['last_name'].', '.$row['first_name'].' '.$row['middle_name'].' '.$row['suffix_name'],
                        'Region' => $row['current_address_region'],
                        'Province' => 'CAVITE',
                        'Muncity' => 'GENERAL TRIAS',
                        'Streetpurok' => $row['current_address_sitio_purok_street_name'],
                        'Sex' => strtoupper($row['sex']),
                        'DOB' => date('Y-m-d', strtotime($row['date_of_birth'])),
                        'AgeYears' => $row['age_in_years'],
                        'AgeMons' => $getAgeMonths,
                        'AgeDays' => $getAgeDays,
                        'Admitted' => ($row['patient_admitted'] == 'Yes') ? 1 : 0,
                        'DAdmit' => date('Y-m-d', strtotime($row['date_admitted'])),
                        'DateOfReport' => date('Y-m-d', strtotime($row['date_of_report'])),
                        'DateOfInvestigation' => date('Y-m-d', strtotime($row['date_of_investigation'])),
                        'DateOfEntry' => date('Y-m-d', strtotime($row['timestamp'])),
                        'AdmitToEntry' => NULL,
                        'OnsetToAdmit' => NULL,
                        'MorbidityMonth' => $row['morbiditymonth'],
                        'MorbidityWeek' => $row['morbidity_week'],
                        'EPIID' => $row['epi_id'],
                        'UniqueKey' => NULL,
                        'RECSTATUS' => NULL,
                        'Fever' => ($row['fever'] == 'Yes') ? 1 : 0,
                        'DONSETP' => date('Y-m-d', strtotime($row['date_onset_paralysis'])),
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
                        'FrmTrvlDate' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? date('Y-m-d', strtotime($row['date_traveled_from'])) : NULL,
                        'OtherCases' => ($row['other_afp_cases_in_patients_community_within_60_days_of_patients_paralysis'] == 'Yes') ? 1 : 0,
                        'InjTrauAnibite' => ($row['does_the_patient_had_any_history_of_injection_trauma_and_or_animal_bite'] == 'Yes') ? 1 : 0,
                        'SpecifyInjTrauAnibite' => ($row['does_the_patient_had_any_history_of_injection_trauma_and_or_animal_bite'] == 'Yes') ? $row['if_yes_specify_type'] : 0,
                        'Investigator' => $row['name_of_investigator'],
                        'ContactNum' => $row['contact_no'],
                        'OPVDoses' => $row['total_opvipv_doses_received'],
                        'DateLastDose' => ($row['date_last_dose_of_opvipv'] != "" && !is_null($row['date_last_dose_of_opvipv'])) ? date('Y-m-d', strtotime($row['date_last_dose_of_opvipv'])) : NULL,
                        'HotCase' => ($row['is_this_a_hot_case'] == 'Yes') ? 1 : 0,
    
                        'FirstStoolSpec' => NULL,
                        'DStool1Taken' => NULL,
                        'DStool2Taken' => NULL,
                        'DStool1Sent' => NULL,
                        'DStool2Sent' => NULL,
                        'Stool1Result' => NULL,
                        'Stool2Result' => NULL,
    
                        'ExpDffup' => ($row['expected_date_of_follow_up'] != "" && !is_null($row['expected_date_of_follow_up'])) ? date('Y-m-d', strtotime($row['expected_date_of_follow_up'])) : NULL,
                        'ActDffp' => ($row['if_yes_actual_date_of_follow_up_conducted'] != "" && !is_null($row['if_yes_actual_date_of_follow_up_conducted'])) ? date('Y-m-d', strtotime($row['if_yes_actual_date_of_follow_up_conducted'])) : NULL,
                        'PhyExam' => ($row['pe_done'] == 'Yes') ? 1 : 0,
                        'ReasonND' => ($row['pe_done'] == 'No') ? $row['if_no_reason_for_no_pe'] : NULL,
                        'DateDied' => ($row['date_died'] != "" && !is_null($row['date_died'])) ? date('Y-m-d', strtotime($row['date_died'])) : NULL,
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
                        'DateClass' => ($row['date_classified'] != "" && !is_null($row['date_classified'])) ? date('Y-m-d', strtotime($row['date_classified'])) : NULL,
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
                        'ActDffup' => ($row['follow_up_done'] == 'Yes') ? date('Y-m-d', strtotime($row['if_yes_actual_date_of_follow_up_conducted'])) : NULL,
                        'DStool1Received' => NULL,
                        'DStool2Received' => NULL,
                        'Stool1RecResult' => NULL,
                        'Stool2RecResult' => NULL,
                        'SecndStoolSpec' => NULL,
                        'DateRep' => ($row['date_of_report'] != "" && !is_null($row['date_of_report'])) ? date('Y-m-d', strtotime($row['date_of_report'])) : NULL,
                        'DateInv' => ($row['date_of_investigation'] != "" && !is_null($row['date_of_investigation'])) ? date('Y-m-d', strtotime($row['date_of_investigation'])) : NULL,
    
                        'Year' => $row['year'],
                        'SentinelSite' => NULL,
                        'ClinicalSummary' => NULL,
                        'DeleteRecord' => NULL,
                        'NameOfDru' => $row['facilityname'],
                        'ToTrvldate' => ($row['did_the_patient_travel_10_km_from_house_one_month_prior_to_illness'] == 'Yes') ? date('Y-m-d', strtotime($row['date_traveled_to'])) : NULL,
                        'ILHZ' => NULL,
                        'District' => NULL,
                        'Barangay' => mb_strtoupper($row['current_address_barangay']),
                        'TYPEHOSPITALCLINIC' => NULL,
                        'OCCUPATION' => NULL,
                        'SENT' => 'Y',
                        'ip' => 'N', 
                        'ipgroup' => NULL,
                        'Outcome' => NULL,
                        'DateOutcomeDied' => ($row['date_died'] != "" && !is_null($row['date_died'])) ? date('Y-m-d', strtotime($row['date_died'])) : NULL,
    
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
        return new Afp([
            'name' => $row[0],
        ]);
    }
}

class HepaImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class HfmdImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class LeptoImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class MeaslesImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class NntImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class RabiesImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class RotaImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class TyphoidImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}

class DengueImport implements ToModel, WithHeadingRow
{
    public function model(array $row) {
        
    }
}