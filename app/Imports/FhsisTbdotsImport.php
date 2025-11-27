<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\FhsisTbdotsMorbidity;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;

class FhsisTbdotsImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
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

    private function tDate2($value) {
        $cdate = Carbon::parse(Date::excelToDateTimeObject($value))->format('Y-m-d H:i:s');

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

    /**
    * @param Collection $collection
    */
    public function model(array $r)
    {
        $lname = $r['last_name'];
        $fname = $r['first_name'];
        $birthdate = $this->tDate($r['birthdate']);
        $screening_date = $this->tDate($r['date_of_screening']);
        $diagnosis_date = $this->tDate($r['date_of_diagnosis']);
        $notification_date = $this->tDate($r['date_of_notification']);
        $rdt_release_date = ($r['rdt_release_date'] != 'No Data') ? $this->tDate($r['rdt_release_date']) : NULL;
        $date_started_tx = ($r['date_started_tx'] != 'No Data') ? $this->tDate($r['date_started_tx']) : NULL;
        $case_number = mb_strtoupper($r['tbtpt_case_no']);
        $date_of_outcomestatus = $this->tDate($r['date_of_outcomestatus']);
        $datetime_record_was_created = $this->tDate2($r['datetime_record_was_created']);

        /*
        $search_existing = FhsisTbdotsMorbidity::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $birthdate)
        ->whereDate('date_started_tx', $date_started_tx)
        ->exists();
        */

        $search_existing = FhsisTbdotsMorbidity::where('case_number', $case_number)
        ->exists();

        if(!$search_existing) {
            $birthdate = Carbon::parse($birthdate);
            $currentDate = Carbon::parse($date_started_tx);

            $get_agemonths = $birthdate->diffInMonths($currentDate);
            $get_agedays = $birthdate->diffInDays($currentDate);

            $c = FhsisTbdotsMorbidity::create([
                'type' => $r['type'],
                'validation_status' => $r['validation_status'],
                'screening_date' => $screening_date,
                'diagnosis_date' => $diagnosis_date,
                'notification_date' => $notification_date,
                'case_number' => $case_number,
                'lname' => mb_strtoupper($lname),
                'fname' => mb_strtoupper($fname),
                'mname' => mb_strtoupper($r['middle_name']),
                'suffix' => NULL,
                'bdate' => $birthdate,
                'age' => $r['age'],
                'age_months' => $get_agemonths,
                'age_days' => $get_agedays,
                'sex' => $r['sex'],
                'brgy' => mb_strtoupper($r['brgy']),
                'source_of_patient' => $r['source_of_patient'],
                'ana_site' => $r['anatomical_site'],
                'reg_group' => $r['registration_group'],
                'bac_status' => $r['bacteriologic_status'],
                'xpert_result' => (isset($r['xpert_result'])) ? $r['xpert_result'] : $r['rdt_result'],
                'rdt_release_date' => $rdt_release_date,
                'date_started_tx' => $date_started_tx,
                'outcome' => isset($r['outcome']) ? $r['outcome'] : $r['outcomestatus'],
                'date_of_outcomestatus' => $date_of_outcomestatus,
                'datetime_record_was_created' => $datetime_record_was_created,
            ]);

            return $c;
        }
    }
}
