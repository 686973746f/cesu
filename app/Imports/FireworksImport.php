<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Regions;
use App\Models\EdcsBrgy;
use App\Models\EdcsCity;
use App\Models\FwInjury;
use Illuminate\Support\Str;
use App\Models\EdcsProvince;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FireworksImport implements ToModel, WithHeadingRow
{
    protected $facility;

    public function __construct($facility)
    {
        $this->facility = $facility;
    }

    private function getBrgyCode($reg_name, $pro_name, $mun_name, $brgy_name) {
        $rq = Regions::where('regionName', $reg_name)->first();

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
        if($r['pat_current_address_city'] == 'GENERAL TRIAS' || $r['poi_citycode'] == 'GENERAL TRIAS') {
            $s = FwInjury::where('oneiss_regno', $r['reg_no'])
            ->orWhere('oneiss_pno', $r['pno'])
            ->first();

            if(!$s) {
                $b = $this->getBrgyCode($r['pat_current_address_region'], $r['pat_current_address_province'], $r['pat_current_address_city'], $r['pat_current_address_barangay']);
                $inj_b = $this->getBrgyCode($r['poi_regcode'], $r['poi_provcode'], $r['poi_citycode'], $r['poi_bgycode']);

                $birthdate = Carbon::parse($r['pat_date_of_birth']);
                $currentDate = Carbon::parse($r['date_report'].' '.$r['time_report']);

                $get_ageyears = $birthdate->diffInYears($currentDate);
                $get_agemonths = $birthdate->diffInMonths($currentDate);
                $get_agedays = $birthdate->diffInDays($currentDate);

                $tr_arr = [];
                if($r['treatment_code'] == 'Yes') {
                    $tr_arr[] = 'ATS/TIG';
                }

                if($r['treatment_code2'] == 'Yes') {
                    $tr_arr[] = 'TOXOID';
                }

                if($r['treatment_code3'] == 'Yes') {
                    $tr_arr[] = 'OTHER';
                }

                $type_arr = [];

                if(isset($r['typeof_injurycode'])) {
                    $type_arr[] = $r['typeof_injurycode'];
                }
                else {
                    if($r['if_fireworks_related'] == 'Yes') {
                        $type_arr[] = 'FIREWORKS INJURY';
                    }

                    if($r['if_fireworks_related_2'] == 'Yes') {
                        $type_arr[] = 'FIREWORKS INGESTION';
                    }

                    if($r['if_fireworks_related_3'] == 'Yes') {
                        $type_arr[] = 'STRAY BULLET INJURY';
                    }
                    
                    if($r['if_fireworks_related_4'] == 'Yes') {
                        $type_arr[] = 'TETANUS';
                    }
                }

                $aloc_arr = [];
                if($r['analoc_eye'] == 'Yes') {
                    $aloc_arr[] = 'EYES';
                }
                if($r['analoc_head'] == 'Yes') {
                    $aloc_arr[] = 'HEAD';
                }
                if($r['analoc_neck'] == 'Yes') {
                    $aloc_arr[] = 'NECK';
                }
                if($r['analoc_chest'] == 'Yes') {
                    $aloc_arr[] = 'CHEST';
                }
                if($r['analoc_back'] == 'Yes') {
                    $aloc_arr[] = 'BACK';
                }
                if($r['analoc_abdomen'] == 'Yes') {
                    $aloc_arr[] = 'ABDOMEN';
                }
                if($r['analoc_buttocks'] == 'Yes') {
                    $aloc_arr[] = 'BUTTOCKS';
                }
                if($r['analoc_hand'] == 'Yes') {
                    $aloc_arr[] = 'HAND';
                }
                if($r['analoc_forearmarm'] == 'Yes') {
                    $aloc_arr[] = 'FOREARM/ARM';
                }
                if($r['analoc_pelvic'] == 'Yes') {
                    $aloc_arr[] = 'PELVIS';
                }
                if($r['analoc_thigh'] == 'Yes') {
                    $aloc_arr[] = 'THIGH';
                }
                if($r['analoc_knee'] == 'Yes') {
                    $aloc_arr[] = 'KNEE';
                }
                if($r['analoc_legs'] == 'Yes') {
                    $aloc_arr[] = 'LEGS';
                }
                if($r['analoc_foot'] == 'Yes') {
                    $aloc_arr[] = 'FOOT';
                }
                if($r['analoc_oth'] == 'Yes') {
                    $aloc_arr[] = 'OTHERS';
                }

                $aware_arr = [];
                if(isset($r['educ_material'])) {
                    $educ_material = explode(', ', $r['educ_material']);

                    if(in_array('TV', $educ_material)) {
                        $aware_arr[] = 'TV';
                    }
                    if(in_array('Newspaper/ Print', $educ_material)) {
                        $aware_arr[] = 'NEWSPAPER/PRINT';
                    }
                    if(in_array('Radio', $educ_material)) {
                        $aware_arr[] = 'RADIO';
                    }
                    if(in_array('Internet/ Social Media', $educ_material)) {
                        $aware_arr[] = 'INTERNET/SOCIAL MEDIA';
                    }
                    if(in_array('Poster/ Tarpaulin', $educ_material)) {
                        $aware_arr[] = 'POSTER/TARPAULIN';
                    }
                    if(in_array('Health Worker', $educ_material)) {
                        $aware_arr[] = 'HEALTH WORKER';
                    }

                    if($r['aware'] == 'No') {
                        $aware_arr[] = 'NOT AWARE';
                    }
                }
                else {
                    $aware_arr[] = 'NOT AWARE';
                }

                $foundunique = false;

                while(!$foundunique) {
                    $for_qr = Str::random(10);
                    
                    $search = FwInjury::where('qr', $for_qr)->first();
                    if(!$search) {
                        $foundunique = true;
                    }
                }

                if(isset($r['pat_suffix'])) {
                    $pat_suffix = ($r['pat_suffix'] != 'N/A') ? mb_strtoupper($r['pat_suffix']) : NULL;
                }
                else {
                    $pat_suffix = NULL;
                }

                if($r['disposition_code'] == 'TRASH') {
                    $disp_code_consultation = 'TREATED AND SENT HOME';
                    $disp_code_admission = 'DISCHARGED';
                }
                else {
                    $disp_code_consultation = $r['disposition_code'];
                    $disp_code_admission = $r['disposition_code'];
                }

                $c = FwInjury::create([
                    'oneiss_pno' => $r['pno'],
                    'oneiss_status' => mb_strtoupper($r['status']),
                    'oneiss_dataentrystatus' => $r['data_entrystatus'],
                    'oneiss_regno' => $r['reg_no'],
                    'oneiss_tempregno' => $r['tempreg_no'],
                    'oneiss_patfacilityno' => $r['pat_facility_no'],
                    
                    'reported_by' => $this->facility->edcs_defaultreporter_name,
                    'report_date' => $currentDate->format('Y-m-d H:i:s'),
                    'facility_code' => $this->facility->sys_code1,
                    'account_type' => $this->facility->facility_type,
                    'hospital_name' => $r['pat_facility_no'],
                    'lname' => mb_strtoupper($r['pat_last_name']),
                    'fname' => mb_strtoupper($r['pat_first_name']),
                    'mname' => ($r['pat_middle_name'] != 'N/A') ? mb_strtoupper($r['pat_middle_name']) : NULL,
                    'suffix' => $pat_suffix,
                    'bdate' => Carbon::parse($r['pat_date_of_birth'])->format('Y-m-d'),
                    'gender' => mb_strtoupper($r['pat_sex']),
                    'is_4ps' => (isset($r['four_ps_member'])) ? substr($r['four_ps_member'],0,1) : NULL,
                    'contact_number' => $r['telephone_no'],
                    //'contact_number2',
                    //'address_region_code',
                    //'address_region_text',
                    //'address_province_code',
                    //'address_province_text',
                    //'address_muncity_code',
                    //'address_muncity_text',
                    //'address_brgy_code',
                    //'address_brgy_text',
                    'brgy_id' => $b->id,
                    'address_street' => (!is_null($r['pat_current_address_streetname'])) ? mb_strtoupper($r['pat_current_address_streetname']) : NULL,
                    //'address_houseno',
                    'injury_date' => Carbon::parse($r['inj_date'].' '.$r['inj_time'])->format('Y-m-d H:i:s'),
                    'consultation_date' => Carbon::parse($r['encounter_date'].' '.$r['encounter_time'])->format('Y-m-d H:i:s'),
                    'reffered_anotherhospital' => (isset($r['referral'])) ? substr($r['referral'],0,1) : 'N',
                    'nameof_hospital' => (isset($r['reffered_from'])) ? $r['reffered_from'] : NULL,
                    'place_of_occurrence' => mb_strtoupper($r['place_of_occurence']),
                    'place_of_occurrence_others' => $r['place_of_occurence_others'],
                    'injury_sameadd' => substr($r['poi_sameadd'],0,1),
                    //'injury_address_region_code',
                    //'injury_address_region_text',
                    //'injury_address_province_code',
                    //'injury_address_province_text',
                    //'injury_address_muncity_code',
                    //'injury_address_muncity_text',
                    //'injury_address_brgy_code',
                    //'injury_address_brgy_text',
                    'injury_address_street' => (substr($r['poi_sameadd'],0,1) == 'Y') ? mb_strtoupper($r['pat_current_address_streetname']) : mb_strtoupper($r['plc_pat_str']),
                    'inj_brgy_id' => (substr($r['poi_sameadd'],0,1) == 'Y') ? $b->id : $inj_b->id,
                    //'injury_address_houseno',
                    'involvement_type' => mb_strtoupper($r['involve_code']),
                    'nature_injury' => $r['typeof_injurycode'],
                    'iffw_typeofinjury' => (!empty($type_arr)) ? implode(",", $type_arr) : NULL,
                    'complete_diagnosis' => $r['diagnosis'],
                    'anatomical_location' => (!empty($aloc_arr)) ? implode(',', $aloc_arr) : NULL,
                    'firework_name' => $r['firecracker_code'],
                    'firework_illegal' => ($r['firecracker_legality'] == 'Legal') ? 'N' : 'Y',
                    'liquor_intoxication' => substr($r['liquor'],0,1),
                    'treatment_given' => (!empty($tr_arr)) ? implode(",", $tr_arr) : 'NO TREATMENT',
                    'given_others' => $r['given_others'],
                    'treatment_code7' => $r['treatment_code7'],
                    'disposition_after_consultation' => $disp_code_consultation,
                    //'disposition_after_consultation_transferred_hospital',

                    'disposition_after_admission' => $disp_code_admission,
                    'disposition_after_admission_transferred_hospital' => $r['transferred_to'],
                    'transferred_to_sp' => $r['transferred_to_sp'],
                    'follow_disp' => (isset($r['follow_disp'])) ? $r['follow_disp'] : NULL,

                    'date_died' => (!is_null($r['date_died'])) ? Carbon::parse($r['date_died'])->format('Y-m-d') : NULL,
                    'aware_healtheducation_list' => implode(",", $aware_arr),
                    
                    'age_years' => $get_ageyears,
                    'age_months' => $get_agemonths,
                    'age_days' => $get_agedays,

                    'status' => 'ENABLED',
                    'sent' => 'N',

                    'plc_injury' => (isset($r['plc_injury'])) ? $r['plc_injury'] : NULL,
                    'fac_regno' => (isset($r['fac_regno'])) ? $r['fac_regno'] : NULL,
                    'trandate' => (isset($r['trandate'])) ? $r['trandate'] : NULL,
                    'sentinel' => (isset($r['sentinel'])) ? $r['sentinel'] : NULL,

                    'facility_reg' => $r['facility_reg'],
                    'facility_prov' => $r['facility_prov'],
                    'facility_citymun' => $r['facility_citymun'],
                    'report_year' => (Carbon::now()->month === Carbon::DECEMBER) ? date('Y') + 1 : date('Y') + 1 - 1,

                    'qr' => $for_qr,
                ]);
            }
        }
    }
}
