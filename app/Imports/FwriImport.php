<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\FwInjury;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;

class FwriImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    private function tDate($value) {
        $cdate = Carbon::parse(Date::excelToDateTimeObject($value))->format('Y-m-d');

        if(!is_null($value) && !empty($value)) {
            //return date('Y-m-d', strtotime(Date::excelToDateTimeObject($value)));
            return $cdate;
        }
        else {
            return NULL;
        }
    }

    public function startRow(): int {
        return 2;
    }
    
    public function model(array $r) {
        if($r['pat_current_address_city'] == 'GENERAL TRIAS') {
            dd($r);
            $lname = $r['pat_last_name'];
            $fname = $r['pat_first_name'];
            $bdate = $this->tDate($r['pat_date_of_birth']);
            $date_report = $this->tDate($r['date_report']);

            //if Carbon month is January
            if(Carbon::now()->format('m') == '12') {
                $check = FwInjury::whereBetween('date_report', [Carbon::createFromDate(date('Y'), 12, 21)->format('Y-m-d'), Carbon::createFromDate(date('Y')+1, 1, 06)->format('Y-m-d')])
                ->where('lname', $lname)
                ->where('fname', $fname)
                ->where('bdate', $bdate)
                ->first();
            }
            else if(Carbon::now()->format('m') == '01') {
                $check = FwInjury::whereBetween('date_report', [Carbon::createFromDate(date('Y')-1, 12, 21)->format('Y-m-d'), Carbon::createFromDate(date('Y'), 1, 06)->format('Y-m-d')])
                ->where('lname', $lname)
                ->where('fname', $fname)
                ->where('bdate', $bdate)
                ->first();
            }

            if(!$check) {
                $birthdate = Carbon::parse($bdate);
                $currentDate = Carbon::parse($date_report);

                $get_ageyears = $birthdate->diffInYears($currentDate);
                $get_agemonths = $birthdate->diffInMonths($currentDate);
                $get_agedays = $birthdate->diffInDays($currentDate);

                $table_params = [
                    'oneiss_pno' => $r['pno'],
                    'oneiss_status' => $r['status'],
                    'oneiss_dataentrystatus' => $r['data_entrystatus'],
                    'oneiss_regno'  => $r['reg_no'],
                    'oneiss_tempregno'  => $r['tempreg_no'],
                    'oneiss_patfacilityno' => $r['pat_facility_no'],
                    
                    'reported_by' => $r['pat_facility_no'].' REPORTER',
                    'report_date' => $this->tDate($r['date_report']),
                    //'facility_code',
                    //'account_type',
                    'hospital_name' => $r['pat_facility_no'],
                    'lname' => $r['pat_last_name'],
                    'fname' => $r['pat_first_name'],
                    'mname' => $r['pat_middle_name'],
                    //'suffix',
                    'bdate' => $bdate,
                    'gender' => mb_strtoupper($r['pat_sex']),
                    'is_4ps' => 'N',
                    'contact_number' => $r['telephone_no'],
                    //'contact_number2',
                    'address_region_code',
                    'address_region_text',
                    'address_province_code',
                    'address_province_text',
                    'address_muncity_code',
                    'address_muncity_text',
                    'address_brgy_code',
                    'address_brgy_text',
                    'address_street' => $r['pat_current_address_streetname'],
                    'address_houseno',
                    'injury_date',
                    'consultation_date',
                    'reffered_anotherhospital',
                    'nameof_hospital',
                    'place_of_occurrence',
                    'place_of_occurrence_others',
                    'injury_sameadd' => substr($r['poi_sameadd'], 0, 1),
                    'injury_address_region_code',
                    'injury_address_region_text',
                    'injury_address_province_code',
                    'injury_address_province_text',
                    'injury_address_muncity_code',
                    'injury_address_muncity_text',
                    'injury_address_brgy_code',
                    'injury_address_brgy_text',
                    'injury_address_street',
                    'injury_address_houseno',
                    'involvement_type',
                    'nature_injury',
                    'iffw_typeofinjury',
                    'complete_diagnosis',
                    'anatomical_location',
                    'firework_name',
                    'firework_illegal',
                    'liquor_intoxication',
                    'treatment_given',
                    'disposition_after_consultation',
                    'disposition_after_consultation_transferred_hospital',
        
                    'disposition_after_admission',
                    'disposition_after_admission_transferred_hospital',
        
                    'date_died',
                    'aware_healtheducation_list',
                    
                    'age_years' => $get_ageyears,
                    'age_months' => $get_agemonths,
                    'age_days' => $get_agedays,
        
                    'status',
                    'remarks',
                    'sent',
                ];
            }

            $c = FwInjury::create([

            ]);
        }

        
    }
}
