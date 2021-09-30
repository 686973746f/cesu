<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\Forms;
use Illuminate\Http\Request;

class ReportV2Controller extends Controller
{
    public function viewDashboard() {
        function activeConfirmedGenerator() {
            foreach (Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        function recoveredGenerator() {
            foreach (Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        function deathGenerator() {
            foreach (Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        $activeconfirmed = activeConfirmedGenerator();
        $recovered = recoveredGenerator();
        $death = deathGenerator();

        return view('reportv2_dashboard', [
            'activeconfirmed_count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->count(),
            'activeconfirmed_list' => $activeconfirmed,
            'recovered_count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->count(),
            'recovered_list' => $recovered,
            'death_count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->count(),
            'death_list' => $death,
        ]);
    }

    public function convertFalseDate() {
        $list = Forms::whereDate('dateReported', '>', date('Y-m-d'))->get();

        foreach($list as $item) {
            $i = Forms::find($item->id);
            $switch = date('Y-d-m', strtotime($i->dateReported));
            $i->dateReported = date('Y-m-d 08:00:00', strtotime($switch));
            $i->created_at = date('Y-m-d 08:00:00', strtotime($switch));
            $i->updated_at = date('Y-m-d 08:00:00', strtotime($switch));

            if(Carbon::parse($i->testDateCollected1) > Carbon::now()) {
                $switch2 = date('Y-d-m', strtotime($i->testDateCollected1));
                $i->testDateCollected1 = date('Y-m-d', strtotime($switch2));
            }
            
            if(!is_null($i->dateOnsetOfIllness)) {
                if(Carbon::parse($i->dateOnsetOfIllness) > Carbon::now()) {
                    $switch3 = date('Y-d-m', strtotime($i->dateOnsetOfIllness));
                    $i->dateOnsetOfIllness = date('Y-m-d', strtotime($switch3));
                }
            }
            
            if(!is_null($i->dispoDate)) {
                if(Carbon::parse($i->dispoDate) > Carbon::now()) {
                    $switch4 = date('Y-d-m', strtotime($i->dispoDate));
                    $i->dispoDate = date('Y-m-d 08:00:00', strtotime($switch4));
                }
            }

            $i->save();
        }
    }

    public function convertNegativeCases() {
        $list = Forms::where('status', 'approved')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->get();

        foreach($list as $item) {
            $i = Forms::find($item->id);
            
            if(!is_null($i->testDateCollected2)) {
                $testType = $i->testResult2;
            }
            else {
                $testType = $i->testResult1;
            }

            if($testType == 'NEGATIVE') {
                $i->caseClassification = 'Non-COVID-19 Case';
                $i->save();
            }
        }
    }
}
