<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Dengue;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;


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
        if($this->sd == 'DENGUE') {
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
                            'DateOfEntry' => date('Y-m-d', strtotime($row[4])),
                            'DRU' => $row[5],
                            'PatientNumber' => $row[6],
                            'FirstName' => $row[7],
                            'FamilyName' => $row[8],
                            'FullName' => $row[8].', '.$row[7],
                            'AgeYears' => $row[10],
                            'AgeMons' => $row[11],
                            'AgeDays' => $row[12],
                            'Sex' => $row[13],
                            'AddressOfDRU' => $row[14],
                            'ProvOfDRU' => $row[15],
                            'MuncityOfDRU' => $row[16],
                            'DOB' => date('Y-m-d', strtotime($row[17])),
                            'Admitted' => $row[18],
                            'DAdmit' => date('Y-m-d', strtotime($row[19])),
                            'DOnset' => date('Y-m-d', strtotime($row[20])),
                            'Type' => $row[21],
                            'LabTest' => $row[22],
                            'LabRes' => $row[23],
                            'ClinClass' => $row[24],
                            'CaseClassification' => $row[25],
                            'Outcome' => $row[26],
                            'RegionOfDrU' => $row[27],
                            'EPIID' => $row[28],
                            'DateDied' => $row[29],
                            'Icd10Code' => $row[30],
                            'MorbidityMonth' => $row[31],
                            'MorbidityWeek' => $row[32],
                            'AdmitToEntry' => $row[33],
                            'OnsetToAdmit' => $row[34],
                            'SentinelSite' => $row[35],
                            'DeleteRecord' => $row[36],
                            'Year' => $row[37],
                            'Recstatus' => $row[38],
                            'UniqueKey' => $row[39],
                            'NameOfDru' => $row[40],
                            'ILHZ' => $row[41],
                            'District' => $row[42],
                            'Barangay' => $row[43],
                            'TYPEHOSPITALCLINIC' => $row[44],
                            'SENT' => $row[45],
                            'ip' => ($row[46] == 'Y') ? 'Y' : 'N',
                            'ipgroup' => ($row[46] == 'Y') ? $row[47] : NULL,
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
