<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;


class PidsrImport implements ToCollection, WithStartRow
{

    public function __construct($sd) 
    {
        $this->sd = $sd;
    }

    private function transformDateTime($value, string $format = 'Y-m-d')
    {
        if(!is_null($value) && $value != 'N/A') {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
            } catch (\ErrorException $e) {
                if(strtotime($value)) {
                    return Carbon::parse($value)->format('Y-m-d');
                }
            }
        }
    }

    /**
    * @param Collection $collection
    */
    /*
    public function collection(Collection $collection)
    {
        dd($sd);

        foreach ($rows as $row) {
        
        }
    }
    */

    public function collection(Collection $rows)
    {
        if($this->sd == 'Dengue') {
            foreach ($rows as $row) 
            {
                if($row[1] == 'CAVITE' && $row[2] == 'GENERAL TRIAS') {
                    $sf = Dengue::where('EPIID', $row[28])
                    ->first();

                    if(!($sf)) {
                        $c = Dengue::create([
                            'Region' => $row[0],
                            'Province' => $row[1],
                            'Muncity' => $row[2],
                            'Streetpurok' => $row[3],
                            'DateOfEntry' => $this->transformDateTime($row[4]),
                            'DRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[9],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'AddressOfDRU' => $row[14],
                            'ProvOfDRU' => $row[15],
                            'MuncityOfDRU' => $row[16],
                            'DOB' => $row[16],
                            'Admitted' => $row[16],
                            'DAdmit' => $row[16],
                            'DOnset' => $row[16],
                            'Type' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
                            'MuncityOfDRU' => $row[16],
        
                        ]);
                    }
                }
            }
        }
        else {

        }

        
    }

    public function startRow(): int {
        return 2;
    }
}
