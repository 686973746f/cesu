<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use Illuminate\Http\Request;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccinationSite;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
            if($i >= date('n') || date('Y') != $sy) {
                ${'m'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('gender', 'MALE');
                })->whereMonth('case_date', $i);

                ${'f'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
                ->whereHas('patients', function ($q) {
                    $q->where('gender', 'FEMALE');
                })->whereMonth('case_date', $i);

                ${'co'.$i} = AbtcBakunaRecords::whereYear('case_date', $sy)
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
    
                    ${'co'.$i} = ${'co'.$i}->count();
    
                    ${'ct'.$i} = ${'ct'.$i}->count();
    
                    ${'ch'.$i} = ${'ch'.$i}->count();
    
                    ${'oe'.$i} = ${'oe'.$i}->count();
    
                    ${'ue'.$i} = ${'ue'.$i}->count();
    
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
    
                    ${'co'.$i} = ${'co'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'ct'.$i} = ${'ct'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'ch'.$i} = ${'ch'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'oe'.$i} = ${'oe'.$i}->where('vaccination_site_id', $vid)->count();
    
                    ${'ue'.$i} = ${'ue'.$i}->where('vaccination_site_id', $vid)->count();
    
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
        ]);
    }

    public function export1(Request $request) {
        $sd = $request->start_date;
        $ed = $request->end_date;
        
        if($request->submit == 'AR') {
            $spreadsheet = IOFactory::load(storage_path('AR_TEMPLATE.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A6', date('Y').' Quarter Accomplishment Reports from '.date('M d, Y', strtotime($request->start_date)).' to '.date('M d, Y', strtotime($request->end_date)));
            
            $vslist = AbtcVaccinationSite::get();

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
                    $q->where(function ($r) {
                        $r->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 15')
                        ->orWhere('age', '<', 15);
                    })
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $great15 = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where(function ($r) {
                        $r->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 15')
                        ->orWhere('age', '>=', 15);
                    })
                    ->where('register_status', 'VERIFIED');
                })
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat1_count = AbtcBakunaRecords::whereHas('patient', function($q) {
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

                $tcv_count = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('outcome', 'C')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $hrig = 0;

                $erig = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('outcome', 'C')
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
            
            $vslist = AbtcVaccinationSite::get();

            foreach($vslist as $i => $v) {
                $i = $i + 6; //Row 6 Start ng pag-fill ng Values

                $cat2_total = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

                $cat2_rig = AbtcBakunaRecords::whereHas('patient', function($q) {
                    $q->where('register_status', 'VERIFIED');
                })
                ->where('category_level', 2)
                ->whereNotNull('rig_date_given')
                ->where('vaccination_site_id', $v->id)
                ->whereBetween('case_date', [$sd, $ed])
                ->count();

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
            })->whereYear('created_at', $sy)
            ->count();

            $bmale = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName)
                ->where('gender', 'MALE');
            })->whereYear('created_at', $sy)
            ->count();

            $bfemale = $tt - $bmale;

            $bcat2 = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('category_level', 2)
            ->count();

            $bcat3 = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('category_level', 3)
            ->count();

            $bdogs = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->whereIn('animal_type', ['PD', 'SD'])
            ->count();

            $bcats = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->whereIn('animal_type', ['PC', 'SC', 'C'])
            ->count();

            $bothers = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('animal_type', 'O')
            ->count();

            $bbite = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('bite_type', 'B')
            ->count();
            
            $bscratch = $tt - $bbite;

            $bdogv = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('if_animal_vaccinated', 1)
            ->count();

            $bdognv = $tt - $bdogv;

            $binc = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('outcome', 'INC')
            ->count();
            
            $bcomp = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('outcome', 'C')
            ->count();

            $bdied = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                $q->where('address_province_text', 'CAVITE')
                ->where('address_muncity_text', 'GENERAL TRIAS')
                ->where('address_brgy_text', $brgy->brgyName);
            })->whereYear('created_at', $sy)
            ->where('outcome', 'D')
            ->count();

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
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->count();
    
                $bmale = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName)
                    ->where('gender', 'MALE');
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->count();
    
                $bfemale = $tt - $bmale;
    
                $bcat2 = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->where('category_level', 2)
                ->count();
    
                $bcat3 = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->where('category_level', 3)
                ->count();
    
                $bdogs = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->whereIn('animal_type', ['PD', 'SD'])
                ->count();
    
                $bcats = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->whereIn('animal_type', ['PC', 'SC', 'C'])
                ->count();
    
                $bothers = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->where('animal_type', 'O')
                ->count();
    
                $binc = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->where('outcome', 'INC')
                ->count();
                
                $bcomp = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->where('outcome', 'C')
                ->count();
    
                $bdied = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->where('outcome', 'D')
                ->count();

                $bbite = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
                ->where('bite_type', 'B')
                ->count();
                
                $bscratch = $tt - $bbite;
    
                $bdogv = AbtcBakunaRecords::whereHas('patients', function ($q) use ($brgy) {
                    $q->where('address_province_text', 'CAVITE')
                    ->where('address_muncity_text', 'GENERAL TRIAS')
                    ->where('address_brgy_text', $brgy->brgyName);
                })->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 Days')))
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

        return view('abtc.report_dashboard', [
            'sy' => $sy,
            'brgyarray' => $brgyArray,
            'topbrgyarray' => $topBrgyArray,
        ]);
    }
}