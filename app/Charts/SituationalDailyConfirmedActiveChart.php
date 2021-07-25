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

        //$lastdayinMonth = Carbon::parse(date('Y-m', strtotime($request->eDate)))->daysInMonth;
        $lastdayinMonth = date('d');

        $count = 0;

        $count += Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where(function ($query) {
            $query->where('testDateCollected1', '<=', date('Y-m-31', strtotime("-1 month")))
            ->orWhere('testDateCollected2', '<=', date('Y-m-31', strtotime("-1 month")));
        })->count();
        
        for($i=1;$i<=$lastdayinMonth;$i++) {
            if($i < 10) {
                $i_display = '0'.$i;
            }
            else {
                $i_display = $i;
            }

            array_push($arrayDate, date('m/d/Y', strtotime(date('Y-m-', strtotime($request->sDate)).$i_display)));

            $count += Forms::where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->where(function ($query) use ($request, $i_display) {
                $query->where('testDateCollected1', date('Y-m-', strtotime($request->sDate)).$i_display)
                ->orWhere('testDateCollected2', date('Y-m-', strtotime($request->sDate)).$i_display);
            })->count();
            
            $negativeCount = Forms::whereIn('outcomeCondition', ['Active','Recovered'])
            ->where('caseClassification', 'Non-COVID-19 Case')
            ->where(function ($query) use ($request, $i_display) {
                $query->where('testDateCollected1', date('Y-m-', strtotime($request->sDate)).$i_display)
                ->orWhere('testDateCollected2', date('Y-m-', strtotime($request->sDate)).$i_display);
            })->count();

            array_push($dateCounter, ($count - $negativeCount));
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