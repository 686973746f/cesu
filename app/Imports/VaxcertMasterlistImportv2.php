<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class VaxcertMasterlistImportv2 implements OnEachRow, WithStartRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();


        $user->save();
    }

    public function startRow(): int {
        return 2;
    }
}
