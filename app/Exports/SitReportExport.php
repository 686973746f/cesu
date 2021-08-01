<?php

namespace App\Exports;

use DateTime;
use DateInterval;
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

        $sheets[] = new ActiveConfirmedDistributionSheet();
        $sheets[] = new ConfirmedVsNewCasesDistribution();
        $sheets[] = new SexDistributionSheet();
        $sheets[] = new AgeDistributionSheet();
        $sheets[] = new NatureOfWorkDistributionSheet();

        return $sheets;
    }
}

class ActiveConfirmedDistributionSheet implements FromArray, WithMapping, WithHeadings, WithTitle, ShouldAutoSize {
    public function __construct()
    {
        $this->totalActiveCount = 0;
        $this->dateArrays = array();

        $startDate = new DateTime(date('Ymd', strtotime(Forms::with('records')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->orderBy('testDateCollected1', 'ASC')
        ->pluck('testDateCollected1')
        ->first())));
        
        $iterateDate = $startDate;
        
        $endDate = new DateTime(date('Ymd'));

        while ($iterateDate <= $endDate) {
            array_push($this->dateArrays, $iterateDate->format('Y-m-d'));

            $iterateDate->modify('+1 Day');
        }
    }

    public function array(): array
    {
        return $this->dateArrays;
    }

    public function title(): string
    {
        return 'Active Cases Distribution';
    }

    public function map($form): array {
        $this->totalActiveCount += Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where(function ($query) use ($form) {
            $query->where('testDateCollected1', $form)
            ->orWhere('testDateCollected2', $form);
        })->count();

        $recoveredCount = Forms::where('outcomeCondition', 'Recovered')
        ->where(function ($query) use ($form) {
            $query->where('testDateCollected1', $form)
            ->orWhere('testDateCollected2', $form);
        })->count();
        
        $totalCounts = $this->totalActiveCount - $recoveredCount;

        return [
            $form,
            ($totalCounts > 0) ? $totalCounts : '0',
        ];
    }

    public function headings(): array {
        return [
            '',
            '',
        ];
    }
}

class ConfirmedVsNewCasesDistribution implements FromArray, WithMapping, WithHeadings, WithTitle, ShouldAutoSize {
    public function __construct()
    {
        $this->totalActiveCount = 0;
        $this->dateArrays = array();

        $startDate = new DateTime(date('Ymd', strtotime(Forms::with('records')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->orderBy('testDateCollected1', 'ASC')
        ->pluck('testDateCollected1')
        ->first())));
        
        $iterateDate = $startDate;
        
        $endDate = new DateTime(date('Ymd'));

        while ($iterateDate <= $endDate) {
            array_push($this->dateArrays, $iterateDate->format('Y-m-d'));

            $iterateDate->modify('+1 Day');
        }
    }

    public function array(): array
    {
        return $this->dateArrays;
    }

    public function title(): string
    {
        return 'Confirmed vs. New Cases Distribution';
    }

    public function map($form): array {
        $this->totalActiveCount += Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where(function ($query) use ($form) {
            $query->where('testDateCollected1', $form)
            ->orWhere('testDateCollected2', $form);
        })->count();

        $recoveredCount = Forms::where('outcomeCondition', 'Recovered')
        ->where(function ($query) use ($form) {
            $query->where('testDateCollected1', $form)
            ->orWhere('testDateCollected2', $form);
        })->count();

        $newCasesCount = Forms::where('outcomeCondition', 'Active')
        ->where(function ($query) use ($form) {
            $query->where('testDateCollected1', $form)
            ->orWhere('testDateCollected2', $form);
        })->count();

        $totalCounts = $this->totalActiveCount - $recoveredCount;

        return [
            $form,
            ($totalCounts > 0) ? $totalCounts : '0',
            ($newCasesCount > 0) ? $newCasesCount : '0',
        ];
    }

    public function headings(): array {
        return [
            '',
            '',
        ];
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
        $formsctr = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->count();

        return [
            '',
            'Sex Distribution of Active Cases N = '.$formsctr,
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

class NatureOfWorkDistributionSheet implements FromArray, WithMapping, WithHeadings, WithTitle, ShouldAutoSize {
    public function __construct()
    {
        $forms = Forms::with('records')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->get();

        $this->recs = $forms->unique('records.natureOfWork')->pluck('records.natureOfWork')->toArray();
        $this->activeCount = $forms->count();
    }

    public function array(): array
    {
        return $this->recs;
    }

    public function title(): string
    {
        return 'Nature of Work';
    }

    public function map($form): array {
        return [
            (!is_null($form)) ? $form : 'NON-WORKING (SENIORS, HOUSEWIVES, ETC.)',
            Forms::with('records')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->whereHas('records', function ($query) use ($form) {
                $query->where('records.natureOfWork', $form);
            })->count(),
        ];
    }

    public function headings(): array {
        return [
            '',
            'Distribution of '.$this->activeCount.' Active Cases by Nature of Work'
        ];
    }
}

/*
class NatureOfWorkDistributionSheet implements FromArray, WithMapping, WithHeadings, WithTitle, ShouldAutoSize {
    public function array(): array
    {
        $forms = Forms::with('records')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->get();

        $recs = $forms->unique('records.natureOfWork');

        return [
            NULL,
            $recs,
        ];
    }

    public function title(): string
    {
        return 'Nature of Work';
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