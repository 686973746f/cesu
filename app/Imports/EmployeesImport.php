<?php

namespace App\Imports;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeesImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow
{
    private function tDate($value) {
        if(!is_null($value) && !empty($value)) {
            $cdate = Carbon::parse(Date::excelToDateTimeObject($value))->format('Y-m-d');
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

    public function model(array $r)
    {
        $c = Employee::create([
            'lname' => $r['lname'],
            'fname' => $r['fname'],
            'mname' => $r['mname'],
            'gender' => substr($r['gender'],0,1),
            'type' => $r['type'],
            'job_position' => $r['job_position'],
            'office' => $r['office'],
            'sub_office' => $r['sub_office'],
            'date_hired' => EmployeesImport::tDate($r['date_hired']),
            'employment_status' => $r['employment_status'],
            'date_resigned' => EmployeesImport::tDate($r['date_resigned']),
            'is_blstrained' => $r['is_blstrained'],
            'duty_canbedeployed' => $r['duty_canbedeployed'],
            'duty_team' => $r['duty_team'],
            'duty_completedcycle' => 'N',
            'created_by' => 1,
        ]);
    }
}
