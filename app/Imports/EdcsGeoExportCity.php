<?php

namespace App\Imports;

use App\Models\EdcsCity;
use App\Models\EdcsProvince;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EdcsGeoExportCity implements ToModel, WithHeadingRow
{
    public function startRow(): int {
        return 2;
    }

    public function model(array $r)
    {
        $s = EdcsProvince::where('edcs_code', $r['province_id'])->first();

        if($s) {
            $c = EdcsCity::create([
                'province_id' => $s->id,
                'edcs_code' => $r['id'],
                'name' => mb_strtoupper($r['city_name']),
                'geographic_level' => $r['geographic_level'],
                'city_class' => $r['city_class'],
                'psgc_9digit' => $r['9digitpsgc_city'],
                'psgc_10digit' => $r['10digitpsgc_city'],
            ]);
        }
    }
}
