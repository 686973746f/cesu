<?php

namespace App\Imports;

use App\Models\Subdivision;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubdivisionImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row) {
        return new Subdivision([
            'subdName' => mb_strtoupper($row['name_of_subdivisioncondominium']),
            'type' => mb_strtoupper($row['type']),
            'brgy_id' => $row['barangayid'],
            'user_id' => 1,
            'total_projectarea' => $row['total_project_area_ha'],
            'total_lotsunits' => $row['total_no_of_lotsunits'],
        ]);
    }
}
