<?php

namespace App\Http\Controllers;

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
}
