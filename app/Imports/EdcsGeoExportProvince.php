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
        $s = Regions::where('edcs_code', $r['regioncode'])->first();

        $c = EdcsProvince::create([
            'region_id' => $s->id,
            'edcs_code' => $r['provincecode'],
            'name' => $r['provincename'],
        ]);
    }
}
