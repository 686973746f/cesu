<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Forms;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class SituationalAgeDistributionChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $db = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->get();

        $age1 = 0;
        $age2 = 0;
        $age3 = 0;
        $age4 = 0;
        $age5 = 0;
        $age6 = 0;


        foreach($db as $it) {
            if($it->records->getAge() <= 17) {
                $age1++;
            }
            else if($it->records->getAge() >= 18 && ($it->records->getAge() <= 25)) {
                $age2++;
            }
            else if($it->records->getAge() >= 26 && ($it->records->getAge() <= 35)) {
                $age3++;
            }
            else if($it->records->getAge() >= 36 && ($it->records->getAge() <= 45)) {
                $age4++;
            }
            else if($it->records->getAge() >= 46 && ($it->records->getAge() <= 59)) {
                $age5++;
            }
            else if($it->records->getAge() >= 60) {
                $age6++;
            }
        }

        return Chartisan::build()
            ->labels(['0 YRS - 17 YRS', '18 YRS - 25 YRS', '26 YRS - 35 YRSS', '36 YRS - 45 YRS', '46 YRS - 59 YRS', '60 YRS - UP'])
            ->dataset('1', [$age1, $age2, $age3, $age4, $age5, $age6]);
    }
}