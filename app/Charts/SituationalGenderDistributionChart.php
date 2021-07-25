<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Forms;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class SituationalGenderDistributionChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $male = Forms::with('records')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->whereHas('records', function($query) {
            $query->where('gender', 'MALE');
        })->count();

        $female = Forms::with('records')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->whereHas('records', function($query) {
            $query->where('gender', 'FEMALE');
        })->count();

        $total = $male + $female;

        //$malepercent = ($male / 100) * $total;
        //$femalepercent = ($female / 100) * $total;

        $malepercent = round(($male / $total) * 100, 2);
        $femalepercent = round(($female / $total) * 100, 2);
        
        return Chartisan::build()
            ->labels(['Male', 'Female'])
            ->dataset('1', [$malepercent, $femalepercent]);
    }
}