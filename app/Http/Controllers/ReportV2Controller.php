<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Http\Request;

class ReportV2Controller extends Controller
{
    public function viewDashboard() {
        function activeConfirmedGenerator() {
            foreach (Forms::where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->orderby('created_at', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        function recoveredGenerator() {
            foreach (Forms::where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->orderby('created_at', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        function deathGenerator() {
            foreach (Forms::where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->orderby('created_at', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        $activeconfirmed = activeConfirmedGenerator();
        $recovered = recoveredGenerator();
        $death = deathGenerator();

        return view('reportv2_dashboard', [
            'activeconfirmed_count' => Forms::where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->count(),
            'activeconfirmed_list' => $activeconfirmed,
            'recovered_count' => Forms::where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->count(),
            'recovered_list' => $recovered,
            'death_count' => Forms::where('status', 'approved')
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
}
