<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\Rabies;
use Illuminate\Http\Request;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccinationSite;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ABTCReportController extends Controller
{
    public function linelist_index() {
        $vslist = AbtcVaccinationSite::get();

        if(request()->input('fyear') && request()->input('vid')) {
            $get = AbtcBakunaRecords::whereYear('case_date', request()->input('fyear'))
            ->where('vaccination_site_id', request()->input('vid'))
            ->orderBy('created_at', 'ASC')
            ->get();

            $alt = 'Showing Records Encoded for Year - '.request()->input('fyear');
        }
        else {
            //$get = AbtcBakunaRecords::whereYear('case_date', date('Y'))->get();
            
            $get = AbtcBakunaRecords::whereDate('created_at', date('Y-m-d'))
            ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
            ->orderBy('created_at', 'ASC')
            ->get();

            $alt = 'Showing Records Encoded for Today - '.date('m/d/Y');
        }
        
        return view('abtc.report_linelist', [
            'list' => $get,
            'alt' => $alt,
            'vslist' => $vslist,
        ]);
    }

    public function linelist2() {
        $get = AbtcBakunaRecords::whereYear('case_date', date('Y'))->get();

        return view('abtc.report_linelist2', [
            'list' => $get,
        ]);
    }

    public function choreport1() {
        if(request()->input('sy')) {
            $sy = request()->input('sy');
        }
        else {
            $sy = date('Y');
        }

        for($i = 1; $i <= 12; $i++) {
            if($i <= date('n') || date('Y') != $sy) {
                ${'m'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('gender', 'MALE');
                })->whereMonth('case_date', $i);

                ${'f'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('gender', 'FEMALE');
                })->whereMonth('case_date', $i);

                ${'co_legit'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->where('category_level', '1');

                ${'ct'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->where('category_level', '2');

                ${'ch'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->where('category_level', '3');

                ${'oe'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('age', '>=', 18);
                })->whereMonth('case_date', $i);

                ${'ue'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('age', '<', 18);
                })->whereMonth('case_date', $i);

                ${'uf_male'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('age', '<', 15)
                    ->where('gender', 'MALE');
                })->whereMonth('case_date', $i);

                ${'uf_female'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('age', '<', 15)
                    ->where('gender', 'FEMALE');
                })->whereMonth('case_date', $i);

                ${'er'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->whereNotNull('rig_date_given');

                ${'oc'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->where('outcome', 'C');

                ${'oi'. $i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->where('outcome', 'INC');

                ${'bo'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->where('is_booster', 1);

                ${'dog'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->whereIn('animal_type', ['PD', 'SD']);

                ${'cat'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereMonth('case_date', $i)
                ->whereIn('animal_type', ['C', 'PC', 'SC']);

                if(!(request()->input('vid')) || request()->input('vid') == 'ALL') {
                    ${'m'.$i} = ${'m'.$i}->count();
    
                    ${'f'.$i} = ${'f'.$i}->count();
    
                    ${'co'.$i} = 0;
    
                    ${'ct'.$i} = (${'co_legit'.$i}->count() + ${'ct'.$i}->count());
    
                    ${'ch'.$i} = ${'ch'.$i}->count();
    
                    ${'oe'.$i} = ${'oe'.$i}->count();
    
                    ${'ue'.$i} = ${'ue'.$i}->count();

                    ${'uf_male'.$i} = ${'uf_male'.$i}->count();
                    
                    ${'uf_female'.$i} = ${'uf_female'.$i}->count();
    
                    ${'er'.$i} = ${'er'.$i}->count();
    
                    ${'oc'.$i} = ${'oc'.$i}->count();
    
                    ${'oi'. $i} = ${'oi'. $i}->count();
    
                    ${'bo'.$i} = ${'bo'.$i}->count();
    
                    ${'dog'.$i} = ${'dog'.$i}->count();
    
                    ${'cat'.$i} = ${'cat'.$i}->count();
                }
                else {
                    $vid = request()->input('vid');
    
                    ${'m'.$i} = ${'m'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'f'.$i} = ${'f'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'co'.$i} = 0;
    
                    ${'ct'.$i} = (${'co_legit'.$i}->where('vaccination_site_id', $vid)->count() + ${'ct'.$i}->where('vaccination_site_id', $vid)->count());
    
                    ${'ch'.$i} = ${'ch'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'oe'.$i} = ${'oe'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'ue'.$i} = ${'ue'.$i}->where('vaccination_site_id', $vid)->count();

                    ${'uf_male'.$i} = ${'uf_male'.$i}->where('vaccination_site_id', $vid)->count();
                    
                    ${'uf_female'.$i} = ${'uf_female'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'er'.$i} = ${'er'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'oc'.$i} = ${'oc'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'oi'. $i} = ${'oi'. $i}->where('vaccination_site_id', $vid)->count();
    
                    ${'bo'.$i} = ${'bo'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'dog'.$i} = ${'dog'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'cat'.$i} = ${'cat'.$i}->where('vaccination_site_id', $vid)->count();
                }
            }
            else {
                ${'m'.$i} = 0;
    
                ${'f'.$i} = 0;

                ${'co'.$i} = 0;

                ${'ct'.$i} = 0;

                ${'ch'.$i} = 0;

                ${'oe'.$i} = 0;

                ${'ue'.$i} = 0;

                ${'uf_male'.$i} = 0;

                ${'uf_female'.$i} = 0;

                ${'er'.$i} = 0;

                ${'oc'.$i} = 0;

                ${'oi'. $i} = 0;

                ${'bo'.$i} = 0;

                ${'dog'.$i} = 0;

                ${'cat'.$i} = 0;
            }
        }

        $vslist = AbtcVaccinationSite::get();

        return view('abtc.report_cho', [
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
            'sy' => $sy,
            'vslist' => $vslist,
            'uf_male1' => $uf_male1,
            'uf_male2' => $uf_male2,
            'uf_male3' => $uf_male3,
            'uf_male4' => $uf_male4,
            'uf_male5' => $uf_male5,
            'uf_male6' => $uf_male6,
            'uf_male7' => $uf_male7,
            'uf_male8' => $uf_male8,
            'uf_male9' => $uf_male9,
            'uf_male10' => $uf_male10,
            'uf_male11' => $uf_male11,
            'uf_male12' => $uf_male12,
            'uf_female1' => $uf_female1,
            'uf_female2' => $uf_female2,
            'uf_female3' => $uf_female3,
            'uf_female4' => $uf_female4,
            'uf_female5' => $uf_female5,
            'uf_female6' => $uf_female6,
            'uf_female7' => $uf_female7,
            'uf_female8' => $uf_female8,
            'uf_female9' => $uf_female9,
            'uf_female10' => $uf_female10,
            'uf_female11' => $uf_female11,
            'uf_female12' => $uf_female12,
        ]);
    }

    public function export1(Request $request) {
        $sd = $request->start_date;
        $ed = $request->end_date;

        $vslist = AbtcVaccinationSite::where('enabled', 1)->get();
        
        if($request->submit == 'AR') {
            $spreadsheet = IOFactory::load(storage_path('AR_TEMPLATE.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A6', date('Y').' Quarter Accomplishment Reports from '.date('M d, Y', strtotime($request->start_date)).' to '.date('M d, Y', strtotime($request->end_date)));

            foreach($vslist as $i => $v) {
                $i = $i + 11; //Row 11 Start ng pag-fill ng Values

                $male_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('gender', 'MALE')
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $female_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('gender', 'FEMALE')
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $less15 = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('age', '<', 15)
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $great15 = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('age', '>=', 15)
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                /*
                $cat1_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 1)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();
                */

                $cat1_count = 0;

                $cat1_count_be = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 1)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $dog_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->whereIn('animal_type', ['PD', 'SD'])
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->whereIn('animal_type', ['C', 'PC', 'SC'])
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $others_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('animal_type', 'O')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                /*

                Old TCV Count

                $tcv_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('outcome', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                */

                //$tcv_count = $male_count + $female_count;

                $tcv_count = $cat2_count + $cat3_count;

                //$tcv_count = $cat2_count + $cat3_count;

                $hrig = 0;

                /*

                $erig = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('outcome', 'C')
                ->where('category_level', 3)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();
                */

                //$erig = $cat3_count;

                $erig = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $booster_count = AbtcBakunaRecords::where('vaccination_site_id', $v->id)
                ->where('is_booster', 1)
                ->where('outcome', 'C')
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $preexp_count = AbtcBakunaRecords::where('vaccination_site_id', $v->id)
                ->where('is_preexp', 1)
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
                $sheet->setCellValue('U'.$i, $erig); //ERIG

                $sheet->setCellValue('V'.$i, $dog_count);
                $sheet->setCellValue('W'.$i, $cat_count);
                $sheet->setCellValue('X'.$i, $others_count);

                $sheet->setCellValue('Z'.$i, $booster_count);
                $sheet->setCellValue('AA'.$i, $preexp_count); //Pre-exposure Count
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

            foreach($vslist as $i => $v) {
                $i = $i + 6; //Row 6 Start ng pag-fill ng Values

                $cat1_be = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 1)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_total = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count() + $cat1_be;

                /*
                $cat2_rig = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();
                */

                $cat2_rig = 0;

                $cat2_complete = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_incomplete = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'INC')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();
                
                $cat2_none = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'N')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_died = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('outcome', 'D')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_total = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_rig = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_complete = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('outcome', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_incomplete = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('outcome', 'INC')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();
                
                $cat3_none = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 3)
                ->where('outcome', 'N')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat3_died = AbtcBakunaRecords::whereHas('patient', function($q) {
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

    public function dashboard() {
        if(request()->input('sy')) {
            $sy = request()->input('sy');
        }
        else {
            $sy = date('Y');
        }

        //brgy list (total, male/female, categories, dog/cat)
        $brgyArray = collect();

        $brgyList = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        foreach($brgyList as $brgy) {
            $tt = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->count();

            $bmale = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName)
                ->where('gender', 'MALE');
            })->whereYear('case_date', $sy)
            ->count();

            $bfemale = $tt - $bmale;

            $bcat3 = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->where('category_level', 3)
            ->count();

            $bcat2 = $tt - $bcat3;

            $bdogs = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->whereIn('animal_type', ['PD', 'SD'])
            ->count();

            $bcats = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->whereIn('animal_type', ['PC', 'SC', 'C'])
            ->count();

            $bothers = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->where('animal_type', 'O')
            ->count();

            $bbite = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->where('bite_type', 'B')
            ->count();
            
            $bscratch = $tt - $bbite;

            $bdogv = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->where('if_animal_vaccinated', 1)
            ->count();

            $bdognv = $tt - $bdogv;

            $bcomp = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->where('outcome', 'C')
            ->count();

            $bdied = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('case_date', $sy)
            ->where('outcome', 'D')
            ->count();

            $binc = $tt - $bcomp - $bdied;

            $brgyArray->push([
                'name' => $brgy->brgyName,
                'tt' => $tt,
                'bmale' => $bmale,
                'bfemale' => $bfemale,
                'bcat2' => $bcat2,
                'bcat3' => $bcat3,
                'bdogs' => $bdogs,
                'bcats' => $bcats,
                'bothers' => $bothers,
                'bcomp' => $bcomp,
                'binc' => $binc,
                'bdied' => $bdied,
                'bbite' => $bbite,
                'bscratch' => $bscratch,
                'bdogv' => $bdogv,
                'bdognv' => $bdognv,
            ]);

            //top 10 last 7 days (total, male/female, categories, dog/cat)
            $topBrgyArray = collect();

            foreach($brgyList as $brgy) {
                $tt = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->count();

                if($tt != 0) {
                    $bmale = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName)
                        ->where('gender', 'MALE');
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->count();
        
                    $bfemale = $tt - $bmale;
        
                    $bcat3 = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->where('category_level', 3)
                    ->count();

                    $bcat2 = $tt - $bcat3;
                        
                    $bdogs = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->whereIn('animal_type', ['PD', 'SD'])
                    ->count();
        
                    $bcats = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->whereIn('animal_type', ['PC', 'SC', 'C'])
                    ->count();
        
                    $bothers = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->where('animal_type', 'O')
                    ->count();

                    $bcomp = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->where('outcome', 'C')
                    ->count();
        
                    $bdied = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->where('outcome', 'D')
                    ->count();
        
                    $binc = $tt - $bcomp - $bdied;
                    
                    $bbite = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->where('bite_type', 'B')
                    ->count();
                    
                    $bscratch = $tt - $bbite;
        
                    $bdogv = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                        $q->where('address_province_text', 'CAVITE')
                        ->where('address_muncity_text', 'GENERAL TRIAS')
                        ->where('address_brgy_text', $brgy->brgyName);
                    })->whereDate('case_date', '>=', date('Y-m-d', strtotime('-7 Days')))
                    ->where('if_animal_vaccinated', 1)
                    ->count();
        
                    $bdognv = $tt - $bdogv;
    
                    $topBrgyArray->push([
                        'name' => $brgy->brgyName,
                        'tt' => $tt,
                        'bmale' => $bmale,
                        'bfemale' => $bfemale,
                        'bcat2' => $bcat2,
                        'bcat3' => $bcat3,
                        'bdogs' => $bdogs,
                        'bcats' => $bcats,
                        'bothers' => $bothers,
                        'bcomp' => $bcomp,
                        'binc' => $binc,
                        'bdied' => $bdied,
                        'bbite' => $bbite,
                        'bscratch' => $bscratch,
                        'bdogv' => $bdogv,
                        'bdognv' => $bdognv,
                    ]);
                }
            }
        }

        return view('abtc.report_dashboard', [
            'sy' => $sy,
            'brgyarray' => $brgyArray,
            'topbrgyarray' => $topBrgyArray,
        ]);
    }

    public function mainreport() {
        /*
        $spreadsheet = IOFactory::load(storage_path('ABTCMAINREPORT.xlsx'));
        $sheet = $spreadsheet->setActiveSheetIndexByName('COUNTER');
        $sheet->setCellValue('C4', 20);

        $fileName = 'TEST.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
        */
        /*
        AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('address_brgy_text', $brgy->brgyName)
            ->where('gender', 'MALE');
        })->whereYear('case_date', $sy)
        ->count();
        */

        $bgy = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $templateProcessor  = new TemplateProcessor(storage_path('ABTCMAINREPORT.docx'));

        $sy = request()->input('year');
        
        $tcases = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        });

        $tcasesc = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('outcome', 'C');

        $tcasesi = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('outcome', 'INC');

        $tcasesd = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('outcome', 'D');

        $rabiesdeath = Rabies::where('Muncity', 'GENERAL TRIAS')
        ->where('Province', 'CAVITE')
        ->where('enabled', 1);

        $ag1m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '<', 1)
            ->where('gender', 'MALE');
        });

        $ag2m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [1,10])
            ->where('gender', 'MALE');
        });

        $ag3m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [11,20])
            ->where('gender', 'MALE');
        });

        $ag4m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [21,30])
            ->where('gender', 'MALE');
        });

        $ag5m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [31,40])
            ->where('gender', 'MALE');
        });

        $ag6m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [41,50])
            ->where('gender', 'MALE');
        });

        $ag7m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [51,60])
            ->where('gender', 'MALE');
        });

        $ag8m = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '>', 60)
            ->where('gender', 'MALE');
        });

        $ag1f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '<', 1)
            ->where('gender', 'FEMALE');
        });

        $ag2f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [1,10])
            ->where('gender', 'FEMALE');
        });

        $ag3f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [11,20])
            ->where('gender', 'FEMALE');
        });

        $ag4f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [21,30])
            ->where('gender', 'FEMALE');
        });

        $ag5f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [31,40])
            ->where('gender', 'FEMALE');
        });

        $ag6f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [41,50])
            ->where('gender', 'FEMALE');
        });

        $ag7f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->whereBetween('age', [51,60])
            ->where('gender', 'FEMALE');
        });

        $ag8f = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '>', 60)
            ->where('gender', 'FEMALE');
        });

        $ct1 = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('category_level', 1);

        $ct2 = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('category_level', 2);

        $ct3 = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('category_level', 3);

        $age1 = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '<=', 15);
        });
        
        $age2 = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '>', 15);
        });
        
        $age3 = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '<=', 18);
        });

        $age4 = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS')
            ->where('age', '>', 18);
        });

        $bs = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('bite_type', 'NB');

        $bb = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('bite_type', 'B');

        $cc = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('bite_type', 'CC');

        $pd = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('animal_type', 'PD');

        $sd = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('animal_type', 'SD');

        $pc = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->whereIn('animal_type', ['PC', 'C']);

        $sc = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('animal_type', 'SC');

        $oth = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('animal_type', 'O');

        $avc = AbtcBakunaRecords::whereHas('patients', function ($q) {
            $q->where('address_province_text', 'CAVITE')
            ->where('address_muncity_text', 'GENERAL TRIAS');
        })->where('if_animal_vaccinated', 1);

        $bctr = 1;

        foreach($bgy as $b) {
            ${'bgt'. $bctr} = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $b->brgyName);
            });

            ${'bgm'. $bctr} = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $b->brgyName)
                ->where('gender', 'MALE');
            });

            ${'bgrd'. $bctr} = Rabies::where('enabled', 1)
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('Province', 'CAVITE')
            ->where('Barangay', $b->brgyName);

            //${'bgf'. $bctr} = ${'bgt' . $bctr} - ${'bgm' . $bctr};

            $bctr++;
        }



        if(request()->input('type') == 'YEARLY') {
            $txt1 = 'YEAR '.request()->input('year');

            $paylname = 'ABTC_REPORT_Y'.request()->input('year').'.docx';

            $tcases = $tcases->whereYear('case_date', $sy);
            
            $tcasesc = $tcasesc->whereYear('case_date', $sy);

            $tcasesi = $tcasesi->whereYear('case_date', $sy);

            $tcasesd = $tcasesd->whereYear('case_date', $sy);

            $rabiesdeath = $rabiesdeath->where('Year', $sy);

            $ag1m = $ag1m->whereYear('case_date', $sy);
            
            $ag2m = $ag2m->whereYear('case_date', $sy);
            
            $ag3m = $ag3m->whereYear('case_date', $sy);

            $ag4m = $ag4m->whereYear('case_date', $sy);

            $ag5m = $ag5m->whereYear('case_date', $sy);

            $ag6m = $ag6m->whereYear('case_date', $sy);

            $ag7m = $ag7m->whereYear('case_date', $sy);

            $ag8m = $ag8m->whereYear('case_date', $sy);

            $ag1f = $ag1f->whereYear('case_date', $sy);
            
            $ag2f = $ag2f->whereYear('case_date', $sy);
            
            $ag3f = $ag3f->whereYear('case_date', $sy);

            $ag4f = $ag4f->whereYear('case_date', $sy);

            $ag5f = $ag5f->whereYear('case_date', $sy);

            $ag6f = $ag6f->whereYear('case_date', $sy);

            $ag7f = $ag7f->whereYear('case_date', $sy);

            $ag8f = $ag8f->whereYear('case_date', $sy);

            $ct1 = $ct1->whereYear('case_date', $sy);

            $ct2 = $ct2->whereYear('case_date', $sy);

            $ct3 = $ct3->whereYear('case_date', $sy);

            $bs = $bs->whereYear('case_date', $sy);

            $bb = $bb->whereYear('case_date', $sy);

            $cc = $cc->whereYear('case_date', $sy);

            $age1 = $age1->whereYear('case_date', $sy);

            $age2 = $age2->whereYear('case_date', $sy);

            $age3 = $age3->whereYear('case_date', $sy);

            $age4 = $age4->whereYear('case_date', $sy);

            $pd = $pd->whereYear('case_date', $sy);
            
            $sd = $sd->whereYear('case_date', $sy);
            
            $pc = $pc->whereYear('case_date', $sy);

            $sc = $sc->whereYear('case_date', $sy);

            $oth = $oth->whereYear('case_date', $sy);

            $avc = $avc->whereYear('case_date', $sy);

            $bctr = 1;

            foreach($bgy as $b) {
                ${'bgt'. $bctr} = ${'bgt'. $bctr}->whereYear('case_date', $sy);

                ${'bgm'. $bctr} = ${'bgm'. $bctr}->whereYear('case_date', $sy);

                ${'bgrd'. $bctr} = ${'bgrd'. $bctr}->where('Year', $sy);

                //${'bgf'. $bctr} = ${'bgt'. $bctr} - ${'bgm'. $bctr};

                $bctr++;
            }
        }
        else if(request()->input('type') == 'QUARTERLY') {
            $qtr = request()->input('quarter');
            $paylname = 'ABTC_REPORT_'.request()->input('quarter').'Q_Y'.$sy.'.docx';

            if(request()->input('quarter') == '1') {
                $txt2 = '1ST';

                $date = Carbon::parse($sy.'-01-01');
            }
            else if(request()->input('quarter') == '2') {
                $txt2 = '2ND';

                $date = Carbon::parse($sy.'-04-01');
            }
            else if(request()->input('quarter') == '3') {
                $txt2 = '3RD';

                $date = Carbon::parse($sy.'-07-01');
            }
            else if(request()->input('quarter') == '4') {
                $txt2 = '4TH';
                
                $date = Carbon::parse($sy.'-10-01');
            }

            $txt1 = $txt2.' QUARTER, YEAR '.request()->input('year');

            $tcases = $tcases->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $tcasesc = $tcasesc->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $tcasesi = $tcasesi->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $tcasesd = $tcasesd->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $rabiesdeath = $rabiesdeath->where('Year', $sy)
            ->whereBetween('DateOfEntry', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag1m = $ag1m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
            
            $ag2m = $ag2m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
            
            $ag3m = $ag3m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag4m = $ag4m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag5m = $ag5m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag6m = $ag6m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag7m = $ag7m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag8m = $ag8m->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag1f = $ag1f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
            
            $ag2f = $ag2f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
            
            $ag3f = $ag3f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag4f = $ag4f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag5f = $ag5f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag6f = $ag6f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag7f = $ag7f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ag8f = $ag8f->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ct1 = $ct1->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ct2 = $ct2->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $ct3 = $ct3->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $bs = $bs->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $bb = $bb->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $cc = $cc->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $age1 = $age1->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $age2 = $age2->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $age3 = $age3->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $age4 = $age4->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $pd = $pd->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
            
            $sd = $sd->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
            
            $pc = $pc->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $sc = $sc->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $oth = $oth->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $avc = $avc->whereYear('case_date', $sy)
            ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

            $bctr = 1;

            foreach($bgy as $b) {
                ${'bgt'. $bctr} = ${'bgt'. $bctr}->whereYear('case_date', $sy)
                ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

                ${'bgm'. $bctr} = ${'bgm'. $bctr}->whereYear('case_date', $sy)
                ->whereBetween('case_date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

                ${'bgrd'. $bctr} = ${'bgrd'. $bctr}->where('Year', $sy)
                ->whereBetween('DateOfEntry', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);

                //${'bgf'. $bctr} = ${'bgt'. $bctr} - ${'bgm'. $bctr};

                $bctr++;
            }
        }
        else if(request()->input('type') == 'MONTHLY') {
            if(request()->input('month') == '1') {
                $txt2 = 'January';
            }
            else if(request()->input('month') == '2') {
                $txt2 = 'February';
            }
            else if(request()->input('month') == '3') {
                $txt2 = 'March';
            }
            else if(request()->input('month') == '4') {
                $txt2 = 'April';
            }
            else if(request()->input('month') == '5') {
                $txt2 = 'May';
            }
            else if(request()->input('month') == '6') {
                $txt2 = 'June';
            }
            else if(request()->input('month') == '7') {
                $txt2 = 'July';
            }
            else if(request()->input('month') == '8') {
                $txt2 = 'August';
            }
            else if(request()->input('month') == '9') {
                $txt2 = 'September';
            }
            else if(request()->input('month') == '10') {
                $txt2 = 'October';
            }
            else if(request()->input('month') == '11') {
                $txt2 = 'November';
            }
            else if(request()->input('month') == '12') {
                $txt2 = 'December';
            }

            $txt1 = 'MONTH OF '.strtoupper($txt2).', YEAR '.request()->input('year');
            $month = request()->input('month');
            $paylname = 'ABTC_REPORT_'.$txt2.' '.$sy.'.docx';

            $tcases = $tcases->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $tcasesc = $tcasesc->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $tcasesi = $tcasesi->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $tcasesd = $tcasesd->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $rabiesdeath = $rabiesdeath->where('Year', $sy)
            ->where('MorbidityMonth', $month);

            $ag1m = $ag1m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);
            
            $ag2m = $ag2m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);
            
            $ag3m = $ag3m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag4m = $ag4m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag5m = $ag5m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag6m = $ag6m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag7m = $ag7m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag8m = $ag8m->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag1f = $ag1f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);
            
            $ag2f = $ag2f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag3f = $ag3f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag4f = $ag4f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag5f = $ag5f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag6f = $ag6f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag7f = $ag7f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ag8f = $ag8f->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ct1 = $ct1->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ct2 = $ct2->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $ct3 = $ct3->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $bs = $bs->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $bb = $bb->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);
            
            $cc = $cc->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $age1 = $age1->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $age2 = $age2->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $age3 = $age3->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $age4 = $age4->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $pd = $pd->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);
            
            $sd = $sd->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);
            
            $pc = $pc->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $sc = $sc->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $oth = $oth->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $avc = $avc->whereYear('case_date', $sy)
            ->whereMonth('case_date', $month);

            $bctr = 1;

            foreach($bgy as $b) {
                ${'bgt'. $bctr} = ${'bgt'. $bctr}->whereYear('case_date', $sy)
                ->whereMonth('case_date', $month);

                ${'bgm'. $bctr} = ${'bgm'. $bctr}->whereYear('case_date', $sy)
                ->whereMonth('case_date', $month);

                ${'bgrd'. $bctr} = ${'bgrd'. $bctr}->where('Year', $sy)
                ->where('MorbidityMonth', $month);

                //${'bgf'. $bctr} = ${'bgt'. $bctr} - ${'bgm'. $bctr};

                $bctr++;
            }
        }
        else if(request()->input('type') == 'WEEKLY') {
            $txt1 = 'WEEK '.request()->input('week').', YEAR '.request()->input('year');
            $week = request()->input('week');
            $paylname = 'ABTC_REPORT_W'.$week.' Y'.$sy.'.docx';

            $tcases = $tcases->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $tcasesc = $tcasesc->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $tcasesi = $tcasesi->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $tcasesd = $tcasesd->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $rabiesdeath = $rabiesdeath->where('Year', $sy)
            ->where('MorbidityWeek', $week);

            $ag1m = $ag1m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);
            
            $ag2m = $ag2m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);
            
            $ag3m = $ag3m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag4m = $ag4m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag5m = $ag5m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag6m = $ag6m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag7m = $ag7m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag8m = $ag8m->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag1f = $ag1f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);
            
            $ag2f = $ag2f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);
            
            $ag3f = $ag3f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag4f = $ag4f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag5f = $ag5f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag6f = $ag6f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag7f = $ag7f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ag8f = $ag8f->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ct1 = $ct1->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ct2 = $ct2->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $ct3 = $ct3->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $bs = $bs->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $bb = $bb->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $cc = $cc->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $age1 = $age1->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $age2 = $age2->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $age3 = $age3->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $age4 = $age4->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $pd = $pd->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);
            
            $sd = $sd->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);
            
            $pc = $pc->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $sc = $sc->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $oth = $oth->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $avc = $avc->whereYear('case_date', $sy)
            ->whereRaw('WEEK(case_date) = ?', [$week]);

            $bctr = 1;

            foreach($bgy as $b) {
                ${'bgt'. $bctr} = ${'bgt'. $bctr}->whereYear('case_date', $sy)
                ->whereRaw('WEEK(case_date) = ?', [$week]);

                ${'bgm'. $bctr} = ${'bgm'. $bctr}->whereYear('case_date', $sy)
                ->whereRaw('WEEK(case_date) = ?', [$week]);

                ${'bgrd'. $bctr} = ${'bgrd'. $bctr}->where('Year', $sy)
                ->where('MorbidityWeek', $week);

                //${'bgf'. $bctr} = ${'bgt'. $bctr} - ${'bgm'. $bctr};

                $bctr++;
            }
        }

        if(request()->input('branch') == 'ALL') {
            $dbranch = 'ALL';

            $tcases = $tcases->count();

            $tcasesc = $tcasesc->count();

            $tcasesi = $tcasesi->count();

            $tcasesd = $tcasesd->count();

            $rabiesdeath = $rabiesdeath->count();

            $ag1m = $ag1m->count();
            
            $ag2m = $ag2m->count();
            
            $ag3m = $ag3m->count();

            $ag4m = $ag4m->count();

            $ag5m = $ag5m->count();

            $ag6m = $ag6m->count();

            $ag7m = $ag7m->count();

            $ag8m = $ag8m->count();

            $ag1f = $ag1f->count();
            
            $ag2f = $ag2f->count();
            
            $ag3f = $ag3f->count();

            $ag4f = $ag4f->count();

            $ag5f = $ag5f->count();

            $ag6f = $ag6f->count();

            $ag7f = $ag7f->count();

            $ag8f = $ag8f->count();

            $ct1 = $ct1->count();

            $ct2 = $ct2->count();

            $ct3 = $ct3->count();

            $bs = $bs->count();

            $bb = $bb->count();

            $cc = $cc->count();

            $age1 = $age1->count();

            $age2 = $age2->count();

            $age3 = $age3->count();

            $age4 = $age4->count();

            $pd = $pd->count();
            
            $sd = $sd->count();
            
            $pc = $pc->count();

            $sc = $sc->count();

            $oth = $oth->count();

            $avc = $avc->count();

            $bctr = 1;

            foreach($bgy as $b) {
                ${'bgrd'. $bctr} = ${'bgrd'. $bctr}->count();

                ${'bgt'. $bctr} = ${'bgt'. $bctr}->count();

                ${'bgm'. $bctr} = ${'bgm'. $bctr}->count(); 

                ${'bgf'. $bctr} = ${'bgt'. $bctr} - ${'bgm'. $bctr};

                $bctr++;
            }
        }
        else {
            $vid = request()->input('branch');
            $dbranch = AbtcVaccinationSite::findOrFail($vid)->site_name;

            $tcases = $tcases->where('vaccination_site_id', $vid)->count();

            $tcasesc = $tcasesc->where('vaccination_site_id', $vid)->count();

            $tcasesi = $tcasesi->where('vaccination_site_id', $vid)->count();

            $tcasesd = $tcasesd->where('vaccination_site_id', $vid)->count();

            $rabiesdeath = $rabiesdeath->count();

            $ag1m = $ag1m->where('vaccination_site_id', $vid)->count();
            
            $ag2m = $ag2m->where('vaccination_site_id', $vid)->count();
            
            $ag3m = $ag3m->where('vaccination_site_id', $vid)->count();

            $ag4m = $ag4m->where('vaccination_site_id', $vid)->count();

            $ag5m = $ag5m->where('vaccination_site_id', $vid)->count();

            $ag6m = $ag6m->where('vaccination_site_id', $vid)->count();

            $ag7m = $ag7m->where('vaccination_site_id', $vid)->count();

            $ag8m = $ag8m->where('vaccination_site_id', $vid)->count();

            $ag1f = $ag1f->where('vaccination_site_id', $vid)->count();
            
            $ag2f = $ag2f->where('vaccination_site_id', $vid)->count();
            
            $ag3f = $ag3f->where('vaccination_site_id', $vid)->count();

            $ag4f = $ag4f->where('vaccination_site_id', $vid)->count();

            $ag5f = $ag5f->where('vaccination_site_id', $vid)->count();

            $ag6f = $ag6f->where('vaccination_site_id', $vid)->count();

            $ag7f = $ag7f->where('vaccination_site_id', $vid)->count();

            $ag8f = $ag8f->where('vaccination_site_id', $vid)->count();

            $ct1 = $ct1->where('vaccination_site_id', $vid)->count();

            $ct2 = $ct2->where('vaccination_site_id', $vid)->count();

            $ct3 = $ct3->where('vaccination_site_id', $vid)->count();

            $bs = $bs->where('vaccination_site_id', $vid)->count();

            $bb = $bb->where('vaccination_site_id', $vid)->count();
            
            $cc = $cc->where('vaccination_site_id', $vid)->count();

            $age1 = $age1->where('vaccination_site_id', $vid)->count();

            $age2 = $age2->where('vaccination_site_id', $vid)->count();

            $age3 = $age3->where('vaccination_site_id', $vid)->count();

            $age4 = $age4->where('vaccination_site_id', $vid)->count();

            $pd = $pd->where('vaccination_site_id', $vid)->count();
            
            $sd = $sd->where('vaccination_site_id', $vid)->count();
            
            $pc = $pc->where('vaccination_site_id', $vid)->count();

            $sc = $sc->where('vaccination_site_id', $vid)->count();

            $oth = $oth->where('vaccination_site_id', $vid)->count();

            $avc = $avc->where('vaccination_site_id', $vid)->count();

            $bctr = 1;

            foreach($bgy as $b) {
                ${'bgrd'. $bctr} = ${'bgrd'. $bctr}->count();

                ${'bgt'. $bctr} = ${'bgt'. $bctr}->where('vaccination_site_id', $vid)->count();

                ${'bgm'. $bctr} = ${'bgm'. $bctr}->where('vaccination_site_id', $vid)->count();

                ${'bgf'. $bctr} = ${'bgt'. $bctr} - ${'bgm'. $bctr};
                
                $bctr++;
            }
        }

        $ctt = $ct1 + $ct2 + $ct3;
        $bt = $bs + $bb + $cc;
        $anv = $tcases - $avc;

        $templateProcessor->setValue('duration', $txt1);
        $templateProcessor->setValue('branch', $dbranch);
        
        $templateProcessor->setValue('tcases', number_format($tcases + $rabiesdeath));
        $templateProcessor->setValue('tcasesc', number_format($tcasesc));
        $templateProcessor->setValue('tcasesi', number_format($tcasesi));
        $templateProcessor->setValue('tcasesd', number_format($tcasesd + $rabiesdeath));

        $templateProcessor->setValue('ag1m', number_format($ag1m));
        $templateProcessor->setValue('ag2m', number_format($ag2m));
        $templateProcessor->setValue('ag3m', number_format($ag3m));
        $templateProcessor->setValue('ag4m', number_format($ag4m));
        $templateProcessor->setValue('ag5m', number_format($ag5m));
        $templateProcessor->setValue('ag6m', number_format($ag6m));
        $templateProcessor->setValue('ag7m', number_format($ag7m));
        $templateProcessor->setValue('ag8m', number_format($ag8m));

        $templateProcessor->setValue('ag1f', number_format($ag1f));
        $templateProcessor->setValue('ag2f', number_format($ag2f));
        $templateProcessor->setValue('ag3f', number_format($ag3f));
        $templateProcessor->setValue('ag4f', number_format($ag4f));
        $templateProcessor->setValue('ag5f', number_format($ag5f));
        $templateProcessor->setValue('ag6f', number_format($ag6f));
        $templateProcessor->setValue('ag7f', number_format($ag7f));
        $templateProcessor->setValue('ag8f', number_format($ag8f));

        $templateProcessor->setValue('ct1', number_format(0));
        $templateProcessor->setValue('ct2', number_format($ct2 + $ct1)); //Category 1 flagged as Cat2 for Counting purposes
        $templateProcessor->setValue('ct3', number_format($ct3));
        $templateProcessor->setValue('ctt', number_format($ct1 + $ct2 + $ct3));

        $templateProcessor->setValue('bs', number_format($bs + $ct1)); //Added Null Exposure Type (Pre-Exposure)
        $templateProcessor->setValue('bb', number_format($bb));
        $templateProcessor->setValue('cc', number_format($cc));
        $templateProcessor->setValue('bt', number_format($bs + $ct1 + $bb + $cc));

        $templateProcessor->setValue('age1', number_format($age1));
        $templateProcessor->setValue('age2', number_format($age2));
        //$templateProcessor->setValue('age3', $age3);
        //$templateProcessor->setValue('age4', $age4);

        $templateProcessor->setValue('pd', number_format($pd));
        $templateProcessor->setValue('sd', number_format($sd));
        $templateProcessor->setValue('pc', number_format($pc));
        $templateProcessor->setValue('sc', number_format($sc));
        $templateProcessor->setValue('oth', number_format($oth));

        $templateProcessor->setValue('avc', number_format($avc));
        $templateProcessor->setValue('anv', number_format($anv));

        $bctr = 1;
        $mgt = 0;
        $fgt = 0;
        $bgrdt = 0;
        $tgt = 0;

        foreach($bgy as $b) {
            $templateProcessor->setValue('bgm'.$bctr, ${'bgm'.$bctr});
            $mgt += ${'bgm'.$bctr};
            
            $templateProcessor->setValue('bgf'.$bctr, ${'bgf'.$bctr});
            $fgt += ${'bgf'.$bctr};

            if(${'bgrd'.$bctr} == 0) {
                $templateProcessor->setValue('bgrd'.$bctr, '');
            }
            else {
                $templateProcessor->setValue('bgrd'.$bctr, ${'bgrd'.$bctr});
            }
            
            $bgrdt += ${'bgrd'.$bctr};
            
            if(${'bgrd'.$bctr} == '') {
                $bgrdt_final = 0;
            }
            else {
                $bgrdt_final = ${'bgrd'.$bctr};
            }

            $templateProcessor->setValue('bgt'.$bctr, ${'bgt'.$bctr} + $bgrdt_final);
            $tgt += ${'bgt'.$bctr} + ${'bgrd'.$bctr};

            $bctr++;
        }

        $templateProcessor->setValue('bgmg', number_format($mgt));
        $templateProcessor->setValue('bgfg', number_format($fgt));
        $templateProcessor->setValue('bgrdg', number_format($bgrdt));
        $templateProcessor->setValue('bgtt', number_format($tgt));

        ob_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="'. urlencode($paylname).'"');
        $templateProcessor->saveAs('php://output');
        //$templateProcessor->saveAs(storage_path($paylname));
    }
}