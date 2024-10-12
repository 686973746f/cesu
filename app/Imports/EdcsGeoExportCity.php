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
        $s = EdcsProvince::where('edcs_code', $r['provincecode'])->first();

        $c = EdcsCity::create([
            'province_id' => $s->id,
            'edcs_code' => $r['citycode'],
            'name' => $r['cityname'],
        ]);
    }
}
