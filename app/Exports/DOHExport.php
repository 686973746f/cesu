<?php

namespace App\Exports;

use App\Models\Forms;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class DOHExport implements WithMultipleSheets, WithTitle
{
    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = ['Probable', 'Suspect', 'Confirmed'];

        return $sheets;
    }
    
    public function title(): string
    {
        return 'Vouchers';
    }
}
