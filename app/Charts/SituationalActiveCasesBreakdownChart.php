<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Forms;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class SituationalActiveCasesBreakdownChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $list1 = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where('healthStatus', 'Asymptomatic')->count();
        $list2 = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where('healthStatus', 'Mild')->count();
        $list3 = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where('healthStatus', 'Severe')->count();
        $list4 = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where('healthStatus', 'Critical')->count();

        return Chartisan::build()
            ->labels(['Asymptomatic', 'Mild', 'Severe', 'Critical'])
            ->dataset('1', [$list1, $list2, $list3, $list4]);
    }
}