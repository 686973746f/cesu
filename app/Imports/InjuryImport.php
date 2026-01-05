<?php

namespace App\Imports;

use Throwable;
use Carbon\Carbon;
use App\Models\Injury;
use App\Models\Regions;
use App\Models\EdcsBrgy;
use App\Models\EdcsCity;
use Illuminate\Support\Str;
use App\Models\EdcsProvince;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InjuryImport implements ToModel, WithHeadingRow
{
    protected $facility;

    public function __construct($facility)
    {
        $this->facility = $facility;
    }

    private function normalizeCity($value)
    {
        if (!is_string($value)) return $value;

        $value = strtoupper(trim($value));

        // Convert "X CITY" → "CITY OF X"
        if (preg_match('/^(.*)\s+CITY$/', $value, $m)) {
            return 'CITY OF ' . $m[1];
        }

        return $value;
    }

    private function fixEncoding($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return str_replace('Ã‘', 'Ñ', $value);
    }

    private function getCityCode($reg_name, $pro_name, $mun_name) {
        $rq = Regions::where('regionName', $reg_name)
        ->orWhere('alt_name', $reg_name)
        ->first();

        $pq = EdcsProvince::where('region_id', $rq->id)
        ->where('name', $pro_name)
        ->first();

        $mun_name = $this->normalizeCity($mun_name);

        $cq = EdcsCity::where('province_id', $pq->id)
        ->where(function ($q) use ($mun_name) {
            $q->where('name', $mun_name)
            ->orWhere('alt_name', $mun_name);
        })->first();

        return $cq;
    }

    private function getBrgyCode($reg_name, $pro_name, $mun_name, $brgy_name) {
        $rq = Regions::where('regionName', $reg_name)
        ->first();

        $pq = EdcsProvince::where('region_id', $rq->id)
        ->where('name', $pro_name)
        ->first();

        $cq = EdcsCity::where('province_id', $pq->id)
        ->where(function ($q) use ($mun_name) {
            $q->where('name', $mun_name)
            ->orWhere('alt_name', $mun_name);
        })->first();

        $bq = EdcsBrgy::where('city_id', $cq->id)
        ->where(function ($q) use ($brgy_name) {
            $q->where('name', $brgy_name)
            ->orWhere('alt_name', $brgy_name);
        })->first();

        return $bq;
    }

    /**
    * @param Collection $collection
    */
    public function model(array $r)
    {
        $r = array_map([$this, 'fixEncoding'], $r);

        if($r['pat_current_address_city'] == 'GENERAL TRIAS' || $r['plc_ctycode'] == 'GENERAL TRIAS') {
            $s = Injury::where('oneiss_pno', $r['pno'])
            ->orWhere('oneiss_regno', $r['reg_no'])
            ->first();

            $pat_brgy = (isset($r['pat_current_address_barangay'])) ? $r['pat_current_address_barangay'] : NULL;
            $plc_brgy = (isset($r['plc_current_address_barangay'])) ? $r['plc_current_address_barangay'] : NULL;
            
            $b = $this->getCityCode(mb_strtoupper($r['pat_current_address_region']), mb_strtoupper($r['pat_current_address_province']), mb_strtoupper($r['pat_current_address_city']));
            //$tempb = $this->getCityCode($r['temp_regcode'], $r['temp_provcode'], $r['temp_citycode']);
            $inj_b = $this->getCityCode(mb_strtoupper($r['plc_regcode']), mb_strtoupper($r['plc_provcode']), mb_strtoupper($r['plc_ctycode']));

            $birthdate = Carbon::parse($r['pat_date_of_birth']);
            $currentDate = Carbon::parse($r['date_report'].' '.$r['time_report']);
            $injuryDate = Carbon::parse($r['inj_date'].' '.$r['inj_time']);
            $encounterDate = Carbon::parse($r['encounter_date'].' '.$r['encounter_time']);

            $get_ageyears = $birthdate->diffInYears($currentDate);
            $get_agemonths = $birthdate->diffInMonths($currentDate);
            $get_agedays = $birthdate->diffInDays($currentDate);

            $foundunique = false;

            while(!$foundunique) {
                $for_qr = Str::random(10);
                
                $search = Injury::where('qr', $for_qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            if(!$s) {
                if(($r['pat_current_address_city'] == $r['temp_citycode'])) {
                    $sameadd = 'Y';
                }
                else {
                    $sameadd = 'N';
                }

                if($sameadd = 'Y') {
                    $tempcity = $b->id;
                }
                else {
                    if(!is_null($r['temp_regcode'])) {
                        $tempcity = $this->getCityCode($r['temp_regcode'], $r['temp_provcode'], $r['temp_citycode'])->id;
                    }
                    else {
                        $tempcity = NULL;
                    }
                }

                if(isset($r['ref_drowning_cope'])) {
                    $ref_drown = $r['ref_drowning_cope'];
                }
                else if(isset($r['ref_drowning_code'])) {
                    $ref_drown = $r['ref_drowning_code'];
                }
                else {
                    $ref_drown = NULL;
                }
                
                $table_params = [
                    //'facility_id',
                    'date_report' => $currentDate->format('Y-m-d H:i:s'),
                    'lname' => (isset($r['pat_last_name'])) ? mb_strtoupper($r['pat_last_name']) : NULL,
                    'fname' => (isset($r['pat_first_name'])) ? mb_strtoupper($r['pat_first_name']) : NULL,
                    'mname' => (isset($r['pat_middle_name'])) ? mb_strtoupper($r['pat_middle_name']) : NULL,
                    'suffix' => (isset($r['pat_suffix'])) ? mb_strtoupper($r['pat_suffix']) : NULL,

                    'reported_by' => $this->facility->edcs_defaultreporter_name,
                    //'reporter_contactno',

                    'oneiss_pno' => $r['pno'],
                    'oneiss_status' => mb_strtoupper($r['status']),
                    //'oneiss_dataentrystatus',
                    'oneiss_patfacilityno' => mb_strtoupper($r['pat_facility_no']),
                    'oneiss_regno' => mb_strtoupper($r['reg_no']),
                    'oneiss_tempregno' => mb_strtoupper($r['tempreg_no']),
                    'hosp_no' => $r['hosp_no'],
                    'hosp_reg_no' => $r['hosp_reg_no'],
                    'hosp_cas_no' => $r['hosp_cas_no'],

                    //'registry_no',
                    //'case_no',
                    'patient_type' => $r['ptype_code'],
                    
                    'sex' => substr($r['pat_sex'],0,1),
                    'bdate' => $birthdate->format('Y-m-d'),
                    'age_years' => $get_ageyears,
                    'age_months' => $get_agemonths,
                    'age_days' => $get_agedays,

                    //'perm_streetpurok',
                    'perm_city_code' => $b->id,
                    //'perm_brgy_code',
                    'tempaddress_sameasperm' => $sameadd,

                    //'temp_streetpurok',
                    'temp_city_code' => $tempcity,
                    //'temp_brgy_code',
                    //'contact_no',
                    //'contact_no2',
                    'philhealth' => $r['pat_phil_health_no'],

                    'injury_city_code' => $inj_b->id,
                    //'injury_brgy_code',
                    'injury_datetime' => $injuryDate->format('Y-m-d H:i:s'),
                    'encounter_datetime' => $encounterDate->format('Y-m-d H:i:s'),

                    'injury_intent' => $r['inj_intent_code'],
                    'vawc' => ($r['vawcyn']) ? substr($r['vawcyn'],0,1) : 'N',
                    'firstaid_given' => substr($r['first_aid_code'],0,1),
                    'firstaid_type' => $r['firstaid_others'],
                    'firstaid_bywho' => $r['firstaid_others2'],
                    'multiple_injuries' => substr($r['mult_inj'],0,1),
                    'abrasion' => substr($r['noi_abrasion'],0,1),
                    'abrasion_site' => $r['noi_abradtl'],
                    'avulsion' => substr($r['noi_avulsion'],0,1),
                    'avulsion_site' => $r['noi_avuldtl'],
                    'burn' => substr($r['noi_burn_r'],0,1),
                    //'burn_degree',
                    'burn_site' => $r['noi_burndtl'],
                    'concussion' => substr($r['noi_concussion'],0,1),
                    'concussion_site' => $r['noi_concussiondtl'],
                    'contusion' => substr($r['noi_contusion'],0,1),
                    'contusion_site' => $r['noi_contudtl'],
                    'fracture' => ($r['noi_frac_clo'] == 'Yes' || $r['noi_frac_ope'] == 'Yes') ? 'Y' : 'N',
                    'fracture_open' => substr($r['noi_frac_ope'],0,1),
                    'fracture_open_site' => $r['noi_fropdtl'],
                    'fracture_closed' => substr($r['noi_frac_clo'],0,1),
                    'fracture_closed_site' => $r['noi_frcldtl'],
                    'open_wound' => substr($r['noi_owound'],0,1),
                    'open_wound_site' => $r['noi_owoudtl'],
                    'traumatic_amputation' => substr($r['noi_amp'],0,1),
                    'traumatic_amputation_site' => $r['noi_ampdtl'],
                    'others' => substr($r['noi_owound'],0,1),
                    'others_site' => $r['noi_otherinj'],
                    'bites_stings' => substr($r['ext_bite'],0,1),
                    'bites_stings_specify' => $r['ext_bite_sp'],
                    'ext_burns' => substr($r['ext_burn_r'],0,1),
                    'ext_burns_type' => $r['ref_burn_code'],
                    'ext_burns_others_specify' => $r['ext_burn_sp'],
                    'chemical_substance' => substr($r['ext_chem'],0,1),
                    'chemical_substance_specify' => $r['ext_chem_sp'],
                    'contact_sharpobject' => substr($r['ext_sharp'],0,1),
                    'contact_sharpobject_specify' => $r['ext_sharp_sp'],
                    'drowning' => substr($r['ext_drown_r'],0,1),
                    'drowning_type' => $ref_drown,
                    'drowning_other_specify' => $r['ext_drown_sp'],
                    'exposure_forcesofnature' => substr($r['ext_expo_nature_r'],0,1),
                    'ref_expnature_code' => $r['ref_expnature_code'],
                    'ext_expo_nature_sp' => $r['ext_expo_nature_sp'],
                    'fall' => substr($r['ext_fall'],0,1),
                    'fall_specify' => $r['ext_falldtl'],
                    'firecracker' => substr($r['ext_firecracker_r'],0,1),
                    'firecracker_code' => $r['firecracker_code'],
                    'firecracker_specify' => $r['ext_firecracker_sp'],
                    'sexual_assault' => substr($r['ext_sexual'],0,1),
                    'gunshot' => substr($r['ext_sexual'],0,1),
                    'gunshot_specifyweapon' => $r['ext_gun_sp'],
                    'hanging_strangulation' => substr($r['ext_hang'],0,1),
                    'mauling_assault' => substr($r['ext_maul'],0,1),
                    'transport_vehicular_accident' => substr($r['ext_transport'],0,1),
                    'ext_others' => substr($r['ext_other'],0,1),
                    'ext_others_specify' => substr($r['ext_other_sp'],0,1),
                    
                    'transfer_hospital' => substr($r['trans_ref'],0,1),
                    'referred_hospital' => substr($r['trans_ref2'],0,1),
                    'orig_hospital' => $r['ref_hosp_code'],
                    'orig_physician' => $r['ref_physician'],
                    'status_reachingfacility' => $r['status_code'],
                    'ifalive_type' => $r['stat_reachdtl'],
                    'modeof_transport' => $r['mode_transport_code'],
                    //'modeof_transport_others',
                    'initial_impression' => $r['diagnosis'],
                    'icd10_nature' => $r['icd_10_nature_er'],
                    'icd10_external' => $r['icd_10_external_er'],
                    'disposition' => mb_strtoupper($r['disposition_code']),
                    'disposition_transferred' => $r['disp_er_sp'],
                    'outcome' => mb_strtoupper($r['outcome_code']),
                    'inp_completefinal_diagnosis' => $r['complete_diagnosis'],
                    'inp_disposition' => $r['disp_inpat'],
                    'inp_disposition_others' => $r['disp_inpat_oth'],
                    'inp_disposition_transferred' => $r['disp_inpat_sp'],
                    'inp_outcome' => $r['outcome_inpat'],
                    'inp_icd10_nature' => $r['icd10_nature_inpatient'],
                    'inp_icd10_external' => $r['icd_10_ext_inpatient'],
                    'comments' => $r['comments'],
                    //'remarks',
                    'qr' => $for_qr,
                    //'created_by',
                    //'report_year',
                    //'report_month',
                    //'report_week',
                ];

                if($r['ext_transport'] == 'Yes') {
                    $risk_arr = [];
                    $safety_arr = [];

                    if($r['risk_none'] == 'Yes') {
                        $risk = NULL;
                    }
                    else {
                        if($r['risk_alcliq'] == 'Yes') {
                            $risk_arr[] = 'ALCOHOL/LIQUOR';
                        }
                        if($r['risk_sleep'] == 'Yes') {
                            $risk_arr[] = 'SLEEPY';
                        }
                        if($r['risk_smoke'] == 'Yes') {
                            $risk_arr[] = 'SMOKING';
                        }
                        if($r['risk_mobpho'] == 'Yes') {
                            $risk_arr[] = 'USING MOBILE PHONE';
                        }
                        if($r['risk_other'] == 'Yes') {
                            $risk_arr[] = 'OTHERS';
                        }

                        if(!empty($risk_arr)) {
                            $risk = implode(", ", $risk_arr);
                        }
                        else {
                            $risk = NULL;
                        }
                    }

                    if($r['safe_none'] == 'Yes') {
                        $safety = 'NONE';
                    }
                    else {
                        if($r['safe_unkn'] == 'Yes') {
                            $safety = 'UNKNOWN';
                        }
                        else {
                            if($r['safe_airbag'] == 'Yes') {
                                $safety_arr[] = 'AIRBAG';
                            }
                            if($r['safe_helmet'] == 'Yes') {
                                $safety_arr[] = 'HELMET';
                            }
                            if($r['safe_cseat'] == 'Yes') {
                                $safety_arr[] = 'CHILDSEAT';
                            }
                            if($r['safe_sbelt'] == 'Yes') {
                                $safety_arr[] = 'SEATBELT';
                            }
                            if($r['safe_drown'] == 'Yes') {
                                $safety_arr[] = 'LIFEJACKET/FLOATATION';
                            }
                            if($r['safe_other'] == 'Yes') {
                                $safety_arr[] = 'OTHERS';
                            }

                            if(!empty($safety_arr)) {
                                $safety = implode(", ", $safety_arr);
                            }
                            else {
                                $safety = NULL;
                            }
                        }
                    }

                    $vehicle_params = [
                        'vehicle_type' => ($r['vehicle_type_id']) ? mb_strtoupper($r['vehicle_type_id']) : NULL,
                        'collision_type' => ($r['ref_veh_acctype_code']) ? mb_strtoupper($r['ref_veh_acctype_code']) : NULL,
                        'patients_vehicle_involved' => ($r['vehicle_code']) ? mb_strtoupper($r['vehicle_code']) : NULL,
                        'patients_vehicle_involved_others'  => ($r['pat_veh_sp']) ? mb_strtoupper($r['pat_veh_sp']) : NULL,
                        'other_vehicle_involved' => ($r['etc_veh']) ? mb_strtoupper($r['etc_veh']) : NULL,
                        'other_vehicle_involved_others' => ($r['etc_veh_sp']) ? mb_strtoupper($r['etc_veh_sp']) : NULL,
                        'patient_position' => ($r['position_code']) ? mb_strtoupper($r['position_code']) : NULL,
                        'patient_position_others' => ($r['pos_pat_sp']) ? mb_strtoupper($r['pos_pat_sp']) : NULL,
                        'placeof_occurrence' => ($r['place_occ_code']) ? mb_strtoupper($r['place_occ_code']) : NULL,
                        'placeof_occurrence_workplace_specify' => ($r['poc_wp_spec']) ? mb_strtoupper($r['poc_wp_spec']) : NULL,
                        'placeof_occurrence_others_specify' => ($r['poc_etc_spec']) ? mb_strtoupper($r['poc_etc_spec']) : NULL,
                        'activitypatient_duringincident' => ($r['activity_code']) ? mb_strtoupper($r['activity_code']) : NULL,
                        'act_others' => ($r['act_etc_spec']) ? mb_strtoupper($r['act_etc_spec']) : NULL,
                        'otherrisk_factors' => $risk,
                        'oth_factors_specify' => ($r['risk_etc_spec']) ? mb_strtoupper($r['risk_etc_spec']) : NULL,
                        'safety' => $safety,
                        'safety_others' => ($r['safe_other_sp']) ? mb_strtoupper($r['safe_other_sp']) : NULL,
                    ];

                    $table_params = $table_params + $vehicle_params;
                }
                
                $c = Injury::create($table_params);
            }
        }
    }
}
