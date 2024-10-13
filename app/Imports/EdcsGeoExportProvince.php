<?php

namespace App\Imports;

use App\Models\EdcsProvince;
use App\Models\Regions;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EdcsGeoExportProvince implements ToModel, WithHeadingRow
{
    public function startRow(): int {
        return 2;
    }

    public function model(array $r)
    {
        $s = Regions::where('edcs_code', $r['region_id'])->first();

        if($s) {
            $c = EdcsProvince::create([
                'region_id' => $s->id,
                'edcs_code' => $r['id'],
                'name' => mb_strtoupper($r['province_name']),
                'geographic_level' => $r['geographic_level'],
                'psgc_9digit' => $r['9digitpsgc_province'],
                'psgc_10digit' => $r['10digitpsgc_province'],
                'region_9digit' => $r['9digit_region'],
            ]);
        }
    }
}
