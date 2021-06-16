<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Forms;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class SituationalDailyConfirmedActiveChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $arrayDate = array();
        $dateCounter = array();

        $lastdayinMonth = Carbon::parse('2021-06')->daysInMonth;

        for($i=1;$i<=$lastdayinMonth;$i++) {
            if($i < 10) {
                $i_display = '0'.$i;
            }
            else {
                $i_display = $i;
            }

            array_push($arrayDate, date('m/d/Y', strtotime('2021-06-'.$i_display)));

            $count = Forms::where('testDateCollected1', '2021-06-'.$i_display)
            ->orWhere('testDateCollected2', '2021-06-'.$i_display)
            ->count();

            array_push($dateCounter, $count);
        }

        /*
        $forms = Forms::where(function ($query) {
            $query->whereBetween('testDateCollected1', ['2021-06-01', '2021-06-15'])
            ->orWhereBetween('testDateCollected1', ['2021-06-01', '2021-06-15']);
        })->orderBy('testDateCollected1', 'asc');

        $datelist = $forms->pluck('testDateCollected1');
        */

        return Chartisan::build()
            ->labels($arrayDate)
            ->dataset('Sample', $dateCounter);
    }
}