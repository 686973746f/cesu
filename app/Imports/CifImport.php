<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Records;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CifImport implements ToCollection, WithStartRow
{
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
    * @param Collection $collection
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if($row[9] == 'Male') {
                $isPregnant = 0;
            }
            else {
                if($row[10] == 'No') {
                    $isPregnant = 0;
                }
                else {
                    $isPregnant = 1;
                }
            }

            $records = Records::create([
                'user_id' => auth()->user()->id,
                'status' => 'approved',
                'lname' => mb_strtoupper($row[5]),
                'fname' => mb_strtoupper($row[6]),
                'mname' => (!is_null($row[7])) ? mb_strtoupper($row[7]) : null,
                'gender' => strtoupper($row[9]),
                'isPregnant' => $isPregnant,
                'cs' => strtoupper($row[12]),
                'nationality' => strtoupper($row[13]),
                'bdate' => Carbon::parse($row[8])->format('Y-m-d'),
                'mobile' => $row[21],
                'phoneno' => null,
                'email' => $row[22],


            ]); 
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
