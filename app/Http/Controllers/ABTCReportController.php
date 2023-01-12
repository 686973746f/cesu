<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BakunaRecords;
use App\Models\VaccinationSite;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function linelist_index() {
        if(request()->input('fyear')) {
            $get = BakunaRecords::whereYear('case_date', request()->input('fyear'))->orderBy('case_date', 'ASC')->get();

            $alt = 'Showing Records Encoded for Year - '.request()->input('fyear');
        }
        else {
            //$get = BakunaRecords::whereYear('case_date', date('Y'))->get();
            
            $get = BakunaRecords::whereDate('created_at', date('Y-m-d'))->orderBy('case_date', 'ASC')->get();
            $alt = 'Showing Records Encoded for Today - '.date('m/d/Y');
        }
        
        return view('report_linelist', [
            'list' => $get,
            'alt' => $alt,
        ]);
    }

    public function linelist2() {
        $get = BakunaRecords::whereYear('case_date', date('Y'))->get();

        return view('report_linelist2', [
            'list' => $get,
        ]);
    }

    public function choreport1() {
        $m1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '01')->count();

        $m2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '02')->count();

        $m3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '03')->count();

        $m4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '04')->count();

        $m5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '05')->count();

        $m6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '06')->count();

        $m7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '07')->count();

        $m8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '08')->count();

        $m9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '09')->count();

        $m10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '10')->count();

        $m11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '11')->count();

        $m12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'MALE');
        })->whereMonth('case_date', '12')->count();

        $f1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '01')->count();

        $f2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '02')->count();

        $f3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '03')->count();

        $f4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '04')->count();

        $f5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '05')->count();

        $f6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '06')->count();

        $f7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '07')->count();

        $f8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '08')->count();

        $f9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '09')->count();

        $f10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '10')->count();

        $f11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '11')->count();

        $f12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('gender', 'FEMALE');
        })->whereMonth('case_date', '12')->count();

        $co1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->where('category_level', '1')
        ->count();

        $co2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->where('category_level', '1')
        ->count();

        $co3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->where('category_level', '1')
        ->count();

        $co4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->where('category_level', '1')
        ->count();

        $co5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->where('category_level', '1')
        ->count();

        $co6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->where('category_level', '1')
        ->count();

        $co7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->where('category_level', '1')
        ->count();

        $co8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->where('category_level', '1')
        ->count();

        $co9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->where('category_level', '1')
        ->count();

        $co10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->where('category_level', '1')
        ->count();

        $co11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->where('category_level', '1')
        ->count();

        $co12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->where('category_level', '1')
        ->count();

        $ct1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->where('category_level', '2')
        ->count();

        $ct2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->where('category_level', '2')
        ->count();

        $ct3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->where('category_level', '2')
        ->count();

        $ct4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->where('category_level', '2')
        ->count();

        $ct5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->where('category_level', '2')
        ->count();

        $ct6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->where('category_level', '2')
        ->count();

        $ct7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->where('category_level', '2')
        ->count();

        $ct8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->where('category_level', '2')
        ->count();

        $ct9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->where('category_level', '2')
        ->count();

        $ct10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->where('category_level', '2')
        ->count();

        $ct11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->where('category_level', '2')
        ->count();

        $ct12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->where('category_level', '2')
        ->count();

        $ch1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->where('category_level', '3')
        ->count();

        $ch2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->where('category_level', '3')
        ->count();

        $ch3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->where('category_level', '3')
        ->count();

        $ch4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->where('category_level', '3')
        ->count();

        $ch5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->where('category_level', '3')
        ->count();

        $ch6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->where('category_level', '3')
        ->count();

        $ch7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->where('category_level', '3')
        ->count();

        $ch8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->where('category_level', '3')
        ->count();

        $ch9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->where('category_level', '3')
        ->count();

        $ch10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->where('category_level', '3')
        ->count();

        $ch11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->where('category_level', '3')
        ->count();

        $ch12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->where('category_level', '3')
        ->count();
        
        $oe1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '1')->count();

        $oe2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '2')->count();

        $oe3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '3')->count();

        $oe4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '4')->count();

        $oe5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '5')->count();

        $oe6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '6')->count();

        $oe7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '7')->count();

        $oe8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '8')->count();

        $oe9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '9')->count();

        $oe10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '10')->count();

        $oe11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '11')->count();

        $oe12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '>=', 18);
        })->whereMonth('case_date', '12')->count();

        $ue1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '1')->count();

        $ue2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '2')->count();

        $ue3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '3')->count();

        $ue4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '4')->count();

        $ue5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '5')->count();

        $ue6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '6')->count();

        $ue7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '7')->count();

        $ue8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '8')->count();

        $ue9= BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '9')->count();

        $ue10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '10')->count();

        $ue11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '11')->count();

        $ue12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereHas('patients', function ($q) {
            $q->where('age', '<', 18);
        })->whereMonth('case_date', '12')->count();

        $er1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->whereNotNull('rig_date_given')
        ->count();

        $er2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->whereNotNull('rig_date_given')
        ->count();

        $er3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->whereNotNull('rig_date_given')
        ->count();

        $er4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->whereNotNull('rig_date_given')
        ->count();

        $er5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->whereNotNull('rig_date_given')
        ->count();

        $er6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->whereNotNull('rig_date_given')
        ->count();

        $er7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->whereNotNull('rig_date_given')
        ->count();

        $er8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->whereNotNull('rig_date_given')
        ->count();

        $er9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->whereNotNull('rig_date_given')
        ->count();

        $er10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->whereNotNull('rig_date_given')
        ->count();

        $er11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->whereNotNull('rig_date_given')
        ->count();

        $er12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->whereNotNull('rig_date_given')
        ->count();

        $oc1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->where('outcome', 'C')
        ->count();

        $oc2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->where('outcome', 'C')
        ->count();

        $oc3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->where('outcome', 'C')
        ->count();

        $oc4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->where('outcome', 'C')
        ->count();

        $oc5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->where('outcome', 'C')
        ->count();

        $oc6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->where('outcome', 'C')
        ->count();

        $oc7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->where('outcome', 'C')
        ->count();

        $oc8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->where('outcome', 'C')
        ->count();

        $oc9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->where('outcome', 'C')
        ->count();

        $oc10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->where('outcome', 'C')
        ->count();

        $oc11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->where('outcome', 'C')
        ->count();

        $oc12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->where('outcome', 'C')
        ->count();
        
        $oi1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->where('outcome', 'INC')
        ->count();

        $oi2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->where('outcome', 'INC')
        ->count();

        $oi3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->where('outcome', 'INC')
        ->count();

        $oi4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->where('outcome', 'INC')
        ->count();

        $oi5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->where('outcome', 'INC')
        ->count();

        $oi6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->where('outcome', 'INC')
        ->count();

        $oi7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->where('outcome', 'INC')
        ->count();

        $oi8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->where('outcome', 'INC')
        ->count();

        $oi9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->where('outcome', 'INC')
        ->count();

        $oi10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->where('outcome', 'INC')
        ->count();

        $oi11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->where('outcome', 'INC')
        ->count();

        $oi12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->where('outcome', 'INC')
        ->count();

        $bo1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->where('is_booster', 1)
        ->count();

        $bo2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->where('is_booster', 1)
        ->count();

        $bo3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->where('is_booster', 1)
        ->count();

        $bo4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->where('is_booster', 1)
        ->count();

        $bo5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->where('is_booster', 1)
        ->count();

        $bo6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->where('is_booster', 1)
        ->count();

        $bo7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->where('is_booster', 1)
        ->count();

        $bo8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->where('is_booster', 1)
        ->count();

        $bo9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->where('is_booster', 1)
        ->count();

        $bo10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->where('is_booster', 1)
        ->count();

        $bo11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->where('is_booster', 1)
        ->count();

        $bo12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->where('is_booster', 1)
        ->count();

        $dog1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();
        
        $dog2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();
        
        $dog3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();
        
        $dog4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $dog12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->whereIn('animal_type', ['PD', 'SD'])
        ->count();

        $cat1 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '1')
        ->where('animal_type', 'C')
        ->count();

        $cat2 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '2')
        ->where('animal_type', 'C')
        ->count();

        $cat3 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '3')
        ->where('animal_type', 'C')
        ->count();

        $cat4 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '4')
        ->where('animal_type', 'C')
        ->count();

        $cat5 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '5')
        ->where('animal_type', 'C')
        ->count();

        $cat6 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '6')
        ->where('animal_type', 'C')
        ->count();

        $cat7 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '7')
        ->where('animal_type', 'C')
        ->count();

        $cat8 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '8')
        ->where('animal_type', 'C')
        ->count();

        $cat9 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '9')
        ->where('animal_type', 'C')
        ->count();

        $cat10 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '10')
        ->where('animal_type', 'C')
        ->count();

        $cat11 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '11')
        ->where('animal_type', 'C')
        ->count();

        $cat12 = BakunaRecords::whereYear('case_date', date('Y'))
        ->whereMonth('case_date', '12')
        ->where('animal_type', 'C')
        ->count();

        return view('report_cho', [
            'm1' => $m1,
            'm2' => $m2,
            'm3' => $m3,
            'm4' => $m4,
            'm5' => $m5,
            'm6' => $m6,
            'm7' => $m7,
            'm8' => $m8,
            'm9' => $m9,
            'm10' => $m10,
            'm11' => $m11,
            'm12' => $m12,
            'f1' => $f1,
            'f2' => $f2,
            'f3' => $f3,
            'f4' => $f4,
            'f5' => $f5,
            'f6' => $f6,
            'f7' => $f7,
            'f8' => $f8,
            'f9' => $f9,
            'f10' => $f10,
            'f11' => $f11,
            'f12' => $f12,
            'co1' => $co1,
            'co2' => $co2,
            'co3' => $co3,
            'co4' => $co4,
            'co5' => $co5,
            'co6' => $co6,
            'co7' => $co7,
            'co8' => $co8,
            'co9' => $co9,
            'co10' => $co10,
            'co11' => $co11,
            'co12' => $co12,
            'ct1' => $ct1,
            'ct2' => $ct2,
            'ct3' => $ct3,
            'ct4' => $ct4,
            'ct5' => $ct5,
            'ct6' => $ct6,
            'ct7' => $ct7,
            'ct8' => $ct8,
            'ct9' => $ct9,
            'ct10' => $ct10,
            'ct11' => $ct11,
            'ct12' => $ct12,
            'ch1' => $ch1,
            'ch2' => $ch2,
            'ch3' => $ch3,
            'ch4' => $ch4,
            'ch5' => $ch5,
            'ch6' => $ch6,
            'ch7' => $ch7,
            'ch8' => $ch8,
            'ch9' => $ch9,
            'ch10' => $ch10,
            'ch11' => $ch11,
            'ch12' => $ch12,
            'oe1' => $oe1,
            'oe2' => $oe2,
            'oe3' => $oe3,
            'oe4' => $oe4,
            'oe5' => $oe5,
            'oe6' => $oe6,
            'oe7' => $oe7,
            'oe8' => $oe8,
            'oe9' => $oe9,
            'oe10' => $oe10,
            'oe11' => $oe11,
            'oe12' => $oe12,
            'ue1' => $ue1,
            'ue2' => $ue2,
            'ue3' => $ue3,
            'ue4' => $ue4,
            'ue5' => $ue5,
            'ue6' => $ue6,
            'ue7' => $ue7,
            'ue8' => $ue8,
            'ue9' => $ue9,
            'ue10' => $ue10,
            'ue11' => $ue11,
            'ue12' => $ue12,
            'er1' => $er1,
            'er2' => $er2,
            'er3' => $er3,
            'er4' => $er4,
            'er5' => $er5,
            'er6' => $er6,
            'er7' => $er7,
            'er8' => $er8,
            'er9' => $er9,
            'er10' => $er10,
            'er11' => $er11,
            'er12' => $er12,
            'oc1' => $oc1,
            'oc2' => $oc2,
            'oc3' => $oc3,
            'oc4' => $oc4,
            'oc5' => $oc5,
            'oc6' => $oc6,
            'oc7' => $oc7,
            'oc8' => $oc8,
            'oc9' => $oc9,
            'oc10' => $oc10,
            'oc11' => $oc11,
            'oc12' => $oc12,
            'oi1' => $oi1,
            'oi2' => $oi2,
            'oi3' => $oi3,
            'oi4' => $oi4,
            'oi5' => $oi5,
            'oi6' => $oi6,
            'oi7' => $oi7,
            'oi8' => $oi8,
            'oi9' => $oi9,
            'oi10' => $oi10,
            'oi11' => $oi11,
            'oi12' => $oi12,
            'bo1' => $bo1,
            'bo2' => $bo2,
            'bo3' => $bo3,
            'bo4' => $bo4,
            'bo5' => $bo5,
            'bo6' => $bo6,
            'bo7' => $bo7,
            'bo8' => $bo8,
            'bo9' => $bo9,
            'bo10' => $bo10,
            'bo11' => $bo11,
            'bo12' => $bo12,
            'dog1' => $dog1,
            'dog2' => $dog2,
            'dog3' => $dog3,
            'dog4' => $dog4,
            'dog5' => $dog5,
            'dog6' => $dog6,
            'dog7' => $dog7,
            'dog8' => $dog8,
            'dog9' => $dog9,
            'dog10' => $dog10,
            'dog11' => $dog11,
            'dog12' => $dog12,
            'cat1' => $cat1,
            'cat2' => $cat2,
            'cat3' => $cat3,
            'cat4' => $cat4,
            'cat5' => $cat5,
            'cat6' => $cat6,
            'cat7' => $cat7,
            'cat8' => $cat8,
            'cat9' => $cat9,
            'cat10' => $cat10,
            'cat11' => $cat11,
            'cat12' => $cat12,
        ]);
    }

    public function export1(Request $request) {
        $sd = $request->start_date;
        $ed = $request->end_date;
        
        if($request->submit == 'AR') {
            $spreadsheet = IOFactory::load(storage_path('AR_TEMPLATE.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A6', date('Y').' Quarter Accomplishment Reports from '.date('M d, Y', strtotime($request->start_date)).' to '.date('M d, Y', strtotime($request->end_date)));
            
            $vslist = VaccinationSite::get();

            foreach($vslist as $i => $v) {
                $i = $i + 11; //Row 11 Start ng pag-fill ng Values

                $male_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('gender', 'MALE')
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $female_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('gender', 'FEMALE')
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $less15 = BakunaRecords::whereHas('patient', function($q) {
                    $q->where(function ($r) {
                        $r->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 15')
                        ->orWhere('age', '<', 15);
                    })
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $great15 = BakunaRecords::whereHas('patient', function($q) {
                    $q->where(function ($r) {
                        $r->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 15')
                        ->orWhere('age', '>=', 15);
                    })
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat1_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 1)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $dog_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->whereIn('animal_type', ['PD', 'SD'])
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('animal_type', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $others_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('animal_type', 'O')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $tcv_count = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('outcome', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $hrig = 0;

                $erig = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('outcome', 'C')
                ->where('category_level', 3)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $booster_count = BakunaRecords::where('vaccination_site_id', $v->id)
                ->where('is_booster', 1)
                ->where('outcome', 'C')
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $ir1 = 0;
                $ir2 = 0;
                $ir3 = 0;

                $sheet->setCellValue('A'.$i, $v->site_name);

                $sheet->setCellValue('C'.$i, $male_count);
                $sheet->setCellValue('D'.$i, $female_count);

                $sheet->setCellValue('F'.$i, $less15);
                $sheet->setCellValue('G'.$i, $great15);

                $sheet->setCellValue('I'.$i, $cat1_count);
                $sheet->setCellValue('J'.$i, $cat2_count);
                $sheet->setCellValue('K'.$i, $cat3_count);

                $sheet->setCellValue('P'.$i, $ir1);
                $sheet->setCellValue('Q'.$i, $ir2);
                $sheet->setCellValue('R'.$i, $ir3);

                $sheet->setCellValue('S'.$i, $tcv_count);
                $sheet->setCellValue('T'.$i, $hrig);
                $sheet->setCellValue('T'.$i, $erig); //ERIG

                $sheet->setCellValue('V'.$i, $dog_count);
                $sheet->setCellValue('W'.$i, $cat_count);
                $sheet->setCellValue('X'.$i, $others_count);

                $sheet->setCellValue('Z'.$i, $booster_count);
                $sheet->setCellValue('AA'.$i, 0); //Pre-exposure Count
            }

            $i = $i+1;

            $fileName = 'ABTC_ACCOMPLISHMENT_'.date('m_d_Y').'.xlsx';
            ob_clean();
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
            //$writer->save(public_path('TEST.xlsx'));
        }
        else if($request->submit == 'RO4A') {
            $spreadsheet = IOFactory::load(storage_path('RO4A_TEMPLATE.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'CY: '.date('M d, Y', strtotime($sd)).' - '.date('M d, Y', strtotime($ed)));
            
            $vslist = VaccinationSite::get();

            foreach($vslist as $i => $v) {
                $i = $i + 6; //Row 6 Start ng pag-fill ng Values

                $cat2_total = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_rig = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_complete = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_incomplete = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'INC')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();
                
                $cat2_none = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'N')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_died = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'D')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_total = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_rig = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_complete = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('outcome', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_incomplete = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('outcome', 'INC')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();
                
                $cat3_none = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('outcome', 'N')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_died = BakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('outcome', 'D')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $sheet->setCellValue('A'.$i, $v->site_name);

                $sheet->setCellValue('B'.$i, $cat2_total);
                $sheet->setCellValue('C'.$i, $cat2_rig);
                $sheet->setCellValue('D'.$i, $cat2_complete);
                $sheet->setCellValue('E'.$i, $cat2_incomplete);
                $sheet->setCellValue('F'.$i, $cat2_none);
                $sheet->setCellValue('G'.$i, $cat2_died);

                $sheet->setCellValue('H'.$i, $cat3_total);
                $sheet->setCellValue('I'.$i, $cat3_rig);
                $sheet->setCellValue('J'.$i, $cat3_complete);
                $sheet->setCellValue('K'.$i, $cat3_incomplete);
                $sheet->setCellValue('L'.$i, $cat3_none);
                $sheet->setCellValue('M'.$i, $cat3_died);
            }

            $fileName = 'ABTC_COHORT_REPORT.xlsx';
            ob_clean();
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
            //$writer->save(public_path('TEST.xlsx'));
        }
        else {
            return abort(401);
        }
    }
}