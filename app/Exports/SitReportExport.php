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
        $sheets[] = new AgeDistributionSheet();

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

class AgeDistributionSheet implements FromArray, WithMapping, WithHeadings, WithTitle, ShouldAutoSize {
    public function array(): array
    {
        return [
            NULL,
            '0 YRS - 17 YRS',
            '18 YRS - 25 YRS',
            '26 YRS - 35 YRS',
            '36 YRS - 45 YRS',
            '46 YRS - 59 YRS',
            '60 YRS - UP',
        ];
    }

    public function title(): string
    {
        return 'Age Distribution';
    }

    public function map($array): array {
        $forms = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->get();

        $counter = 0;

        foreach($forms as $item) {
            if($array == '0 YRS - 17 YRS') {
                if($item->records->getAge() <= 17) {
                    $counter++;
                }
            }
            else if ($array == '18 YRS - 25 YRS') {
                if($item->records->getAge() >= 18 && $item->records->getAge() <= 25) {
                    $counter++;
                }
            }
            else if ($array == '26 YRS - 35 YRS') {
                if($item->records->getAge() >= 26 && $item->records->getAge() <= 35) {
                    $counter++;
                }
            }
            else if ($array == '36 YRS - 45 YRS') {
                if($item->records->getAge() >= 36 && $item->records->getAge() <= 45) {
                    $counter++;
                }
            }
            else if ($array == '46 YRS - 59 YRS') {
                if($item->records->getAge() >= 46 && $item->records->getAge() <= 59) {
                    $counter++;
                }
            }
            else if ($array == '60 YRS - UP') {
                if($item->records->getAge() >= 60) {
                    $counter++;
                }
            }
        }

        return [
            $array,
            $counter,
        ];
    }

    public function headings(): array {
        $formsctr = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->count();

        return [
            '',
            'Age Distribution of Active Cases N = '.$formsctr,
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