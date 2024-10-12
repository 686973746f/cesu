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
        $s = EdcsCity::where('edcs_code', $r['citycode'])->first();

        $c = EdcsBrgy::create([
            'city_id' => $s->id,
            'edcs_code' => $r['brgycode'],
            'name' => $r['barangayname'],
        ]);
    }
}
