<?php

namespace App\Imports;

use App\Models\EdcsBrgy;
use App\Models\EdcsCity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EdcsGeoExportBrgy implements ToModel, WithHeadingRow
{
    public function startRow(): int {
        return 2;
    }

    public function model(array $r)
    {
        $s = EdcsCity::where('edcs_code', $r['city_municipality_id'])->first();

        if($s) {
            $c = EdcsBrgy::create([
                'city_id' => $s->id,
                'edcs_code' => $r['id'],
                'name' => mb_strtoupper($r['barangay_name']),
                'psgc_9digit' => $r['9digitpsgc_barangay'],
                'psgc_10digit' => $r['10digitpsgc_barangay'],
            ]);
        }
    }
}
