<?php

namespace App\Http\Controllers;

use App\Models\Forms;

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
}
