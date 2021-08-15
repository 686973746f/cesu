<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Records;
use App\Models\PaSwabDetails;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ExcelImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //dd($rows);

        foreach ($rows as $row) {
            //dd(Date::excelToDateTimeObject($row[2])->format('Y-m-d'));
            //double entry checking
            $check1 = Records::where('lname', mb_strtoupper($row[6]))
            ->where('fname', mb_strtoupper($row[7]))
            ->where(function ($query) use ($row) {
                $query->where('mname', mb_strtoupper($row[8]))
                ->orWhereNull('mname');
            })
            ->where('bdate', Date::excelToDateTimeObject($row[9])->format('Y-m-d'))
            ->where('gender', strtoupper($row[11]))
            ->first();

            if($check1) {
                $param1 = 1;
                $where = '(Existing in the Records Page)';
            }
            else {
                $param1 = 0;
            }

            $check2 = PaSwabDetails::where('lname', mb_strtoupper($row[6]))
            ->where('fname', mb_strtoupper($row[7]))
            ->where(function ($query) use ($row) {
                $query->where('mname', mb_strtoupper($row[8]))
                ->orWhereNull('mname');
            })
            ->where('bdate', Date::excelToDateTimeObject($row[9])->format('Y-m-d'))
            ->where('gender', strtoupper($row[11]))
            ->where('status', 'pending')
            ->first();

            if($check2) {
                $param2 = 1;
                $where = '(Existing in Pa-Swab Page, waiting for Approval)';
            }
            else {
                $param2 = 0;
            }

            if($param1 == 1 || $param2 == 1) {
            
            }
            else {
                if($row[11] == 'MALE') {
                    $isPregnant = 0;
                }
                else {
                    $isPregnant = ($row[23] == 'Y') ? 1 : 0;
                }
                
                $records = auth()->user()->records()->create([
                    'status' => 'approved',
                    'lname' => mb_strtoupper($row[6]),
                    'fname' => mb_strtoupper($row[7]),
                    'mname' => (!is_null($row[8])) ? mb_strtoupper($row[8]) : null,
                    'gender' => strtoupper($row[11]),
                    'isPregnant' => $isPregnant,
                    'cs' => 'SINGLE', //walang civil status column sa NEW DOH Excel
                    'nationality' => strtoupper($row[12]),
                    'bdate' => Date::excelToDateTimeObject($row[9])->format('Y-m-d'),
                    'mobile' => $row[18],
                    'phoneno' => NULL,
                    'email' => NULL,
                    'philhealth' => NULL, //walang philhealth column sa NEW DOH Excel
                    'address_houseno' => mb_strtoupper($row[17]),
                    'address_street' => 'NEAR BRGY. HALL',
                    'address_brgy' => mb_strtoupper($row[16]),
                    'address_city' => mb_strtoupper($row[15]),
                    'address_cityjson' => '042108', //default for general trias
                    'address_province' => mb_strtoupper($row[14]),
                    'address_provincejson' => '0421', //default for cavite
        
                    'permaaddressDifferent' => 0,
                    'permaaddress_houseno' => mb_strtoupper($row[17]),
                    'permaaddress_street' => 'NEAR BRGY. HALL',
                    'permaaddress_brgy' => mb_strtoupper($row[16]),
                    'permaaddress_city' => mb_strtoupper($row[15]),
                    'permaaddress_cityjson' => '042108', //default for general trias
                    'permaaddress_province' => mb_strtoupper($row[14]),
                    'permaaddress_provincejson' => '0421', //default for cavite
                    'permamobile' => $row[18],
                    'permaphoneno' => NULL,
                    'permaemail' => NULL,
        
                    'hasOccupation' => (!is_null($row[19])) ? 1 : 0,
                    'occupation' => (!is_null($row[19])) ? mb_strtoupper($row[19]) : NULL,
                    'worksInClosedSetting' => 'UNKNOWN',
                    'occupation_lotbldg' => NULL,
                    'occupation_street' => NULL,
                    'occupation_brgy' => NULL,
                    'occupation_city' => NULL,
                    'occupation_cityjson' => NULL,
                    'occupation_province' => NULL,
                    'occupation_provincejson' => NULL,
                    'occupation_name' => (!is_null($row[21])) ? mb_strtoupper($row[21]) : NULL,
                    'occupation_mobile' => NULL,
                    'occupation_email' => NULL,
    
                    'natureOfWork' => NULL,
                    'natureOfWorkIfOthers' => NULL,
                ]);

                $forms = auth()->forms()->create([
                    'majikCode' => NULL,
                    'status' => 'approved',
                    'isPresentOnSwabDay' => 1,
                    'records_id' => $records->id,
                    'drunit' => mb_strtoupper($row[3]),
                    'drregion' => mb_strtoupper($row[4])." ".mb_strtoupper($row[5]),
                    'interviewerName' => 'BROAS, LUIS',
                    'interviewerMobile' => '09190664324',
                    'interviewDate' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
                    'informantName' => NULL,
                    'informantRelationship' => NULL,
                    'informantMobile' => NULL,
                    'existingCaseList' => '1',
                    'ecOthersRemarks' => NULL,
                    'pType' => 'PROBABLE',
                    
                ]);
            }
        }
    }

    public function startRow(): int {
        return 2;
     }
}
