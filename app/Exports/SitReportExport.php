<?php

namespace App\Exports;

use App\Models\Forms;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SitReportExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array 
    {
        $sheets = [];

        $sheets[] = new SexDistributionSheet();

        return $sheets;
    }
}

class SexDistributionSheet implements FromArray, WithMapping, WithHeadings, WithTitle, ShouldAutoSize {
    public function array(): array
    {
        return [
            NULL,
            'MALE',
            'FEMALE',
        ];
    }

    public function title(): string
    {
        return 'Sex Distribution';
    }

    public function map($array): array {
        $gcounter = Forms::with('records')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->whereHas('records', function($query) use ($array) {
            $query->where('gender', $array);
        })->count();

        return [
            $array,
            $gcounter,
        ];
    }

    public function headings(): array {
        return [
            '',
            'Sex Distribution of Active Cases',
        ];
    }
}

/*
class SexDistributionSheet implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize, WithStyles {
    public function collection()
    {
        return Forms::whereIn('id', $this->id)
        ->where('caseClassification', 'Suspect')
        ->orderby('testDateCollected1', 'asc')
        ->orderby('testDateCollected2', 'asc')
        ->get();
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }

    public function title(): string
    {
        return 'Sex Distribution';
    }

    public function map($form): array {
        return [

        ];
    }

    public function headings(): array {
        return [

        ];
    }
}
*/