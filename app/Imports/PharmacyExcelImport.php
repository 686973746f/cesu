<?php

namespace App\Imports;

use App\Models\PharmacySupplyMaster;
use Carbon\Carbon;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PharmacyExcelImport implements OnEachRow, WithHeadingRow
{
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
    
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        $check = PharmacySupplyMaster::where('name', $row['name'])
        ->orWhere('sku_code', $row['sku_code'])
        ->first();

        if(!($check)) {
            $new1
        }
        else {

        }
    }
}
