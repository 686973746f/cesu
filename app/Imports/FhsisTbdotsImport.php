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
    
    public function startRow(): int {
        return 2;
    }

    /**
    * @param Collection $collection
    */
    public function model(array $r)
    {

        dd($r);
        $lname = $r['last_name'];
        $fname = $r['first_name'];
        $birthdate = $this->tDate($r['birthdate']);
        $date_started_tx = $this->tDate($r['date_started_tx']);

        $search_existing = FhsisTbdotsMorbidity::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $birthdate)
        ->whereDate('date_started_tx', $date_started_tx)
        ->exists();

        if(!$search_existing) {
            $c = FhsisTbdotsMorbidity::create([
                'lname' => mb_strtoupper($lname),
                'fname' => mb_strtoupper($fname),
                'mname' => mb_strtoupper($r['middle_name']),
                'suffix' => NULL,
                'bdate' => $birthdate,
                'age' => $r['age'],
                'sex' => $r['sex'],
                'brgy' => mb_strtoupper($r['brgy']),
                'source_of_patient' => $r['source_of_patient'],
                'ana_site' => $r['anatomical_site'],
                'reg_group' => $r['registration_group'],
                'bac_status' => $r['bacteriologic_status'],
                'xpert_result' => substr($r['xpert_result'], 0, strlen($r['xpert_result']) - 2),
                'date_started_tx' => $date_started_tx,
                'outcome' => isset($r['outcome']) ? $r['outcome'] : NULL,
            ]);

            return $c;
        }
    }
}
