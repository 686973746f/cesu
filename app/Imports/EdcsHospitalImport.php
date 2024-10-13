<?php

namespace App\Imports;

use App\Models\DohFacility;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EdcsHospitalImport implements ToModel, WithHeadingRow
{
    public function startRow(): int {
        return 2;
    }

    public function model(array $r)
    {
        $u = DohFacility::where('healthfacility_code', $r['healthfacilitycode'])
        ->update([
            'edcs_region_code' => $r['region_psgc'],
            'edcs_province_code' => $r['province_psgc'],
            'edcs_muncity_code' => $r['citymunicipality_psgc'],
            'edcs_brgy_code' => $r['barangay_psgc'],
            'edcs_service_capability' => $r['servicecapability'],
            'edcs_region_name' => $r['regname'],
            'edcs_province_name' => $r['provname'],
        ]);
    }
}
