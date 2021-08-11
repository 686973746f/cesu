<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Records;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ExcelImport implements ToCollection, WithStartRow
{
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            
            $records = Records::create([

            ]);

            $forms = Forms::create([
                
            ]);
        }
    }
}
