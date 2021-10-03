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

        function facilityGenerator() {
            foreach (Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('dispoType', 6)
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        function hqGenerator() {
            foreach (Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('dispoType', 3)
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->where('reinfected', 0)
            ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        function otherFacilityGenerator() {
            foreach (Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->whereIn('dispoType', [1,2,5])
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->where('reinfected', 0)
            ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                yield $data;
            }
        }

        $activeconfirmed = activeConfirmedGenerator();
        $recovered = recoveredGenerator();
        $death = deathGenerator();
        $facility = facilityGenerator();
        $hq = hqGenerator();
        $otherfacility = otherFacilityGenerator();

        //Counters
        $activeconfirmed_count = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->count();

        $recovered_count = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $death_count = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Died')
        ->count();

        return view('reportv2_dashboard', [
            'activeconfirmed_list' => $activeconfirmed,
            'activeconfirmed_count' => $activeconfirmed_count,
            'recovered_list' => $recovered,
            'recovered_count' => $recovered_count,
            'death_list' => $death,
            'death_count' => $death_count,
            'facility_list' => $facility,
            'hq_list' => $hq,
            'otherfacility_list' => $otherfacility,
        ]);
    }
}
