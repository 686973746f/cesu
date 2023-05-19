<?php

namespace App\Http\Controllers;

use PDO;
use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\FhsisM2;
use App\Models\FhsisMortBhs;
use App\Models\FhsisNonComm;
use Illuminate\Http\Request;
use App\Models\FhsisBarangay;
use App\Models\FhsisChildCare;
use App\Models\FhsisPopulation;
use App\Models\AbtcBakunaRecords;
use App\Models\FhsisDental;
use App\Models\FhsisEnvironmentalHealth;
use App\Models\FhsisFamilyPlanning1;
use App\Models\FhsisFamilyPlanning2;
use App\Models\FhsisFamilyPlanning3;
use App\Models\FhsisMortalityNatality;

class FhsisController extends Controller
{
    public function home() {
        return view('efhsis.home');
    }

    public function report() {
        if(request()->input('type') && request()->input('year')) {
            $bgy_list = FhsisBarangay::orderBy('BGY_DESC', 'ASC')->get();

            $mort_final_list = [];
            $morb_final_list = [];

            $bgy_nm_list = [];
            $bgy_mone_list = [];

            $year = request()->input('year');
            $base_year = request()->input('year');
            $type = request()->input('type');

            if($type == 'quarterly') {
                $q = request()->input('quarter');

                if($q == 1) {
                    $from = date('Y-m-d', strtotime(request()->input('year').'-01-01'));
                    $to = date('Y-m-d', strtotime(request()->input('year').'-03-01'));
                }
                else if($q == 2) {
                    $from = date('Y-m-d', strtotime(request()->input('year').'-04-01'));
                    $to = date('Y-m-d', strtotime(request()->input('year').'-06-01'));
                }
                else if($q == 3) {
                    $from = date('Y-m-d', strtotime(request()->input('year').'-07-01'));
                    $to = date('Y-m-d', strtotime(request()->input('year').'-09-01'));
                }
                else if($q == 4) {
                    $from = date('Y-m-d', strtotime(request()->input('year').'-10-01'));
                    $to = date('Y-m-d', strtotime(request()->input('year').'-12-01'));
                }
            }

            //TOP 10 MORBIDITY AND MORTALITY
            if($type == 'yearly') {
                $mort_query = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereYear('DATE', $base_year)
                ->distinct()
                ->pluck('DISEASE');

                $morb_query = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereYear('DATE', $base_year)
                ->distinct()
                ->pluck('DISEASE');
            }
            else if($type == 'quarterly') {
                $mort_query = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereBetween('DATE', [$from, $to])
                ->distinct()
                ->pluck('DISEASE');

                $morb_query = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereBetween('DATE', [$from, $to])
                ->distinct()
                ->pluck('DISEASE');
            }
            else if($type == 'monthly') {
                $mort_query = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereYear('DATE', $base_year)
                ->whereMonth('DATE', date('m', strtotime(request()->input('year').'-'.request()->input('month').'-01')))
                ->distinct()
                ->pluck('DISEASE');

                $morb_query = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereYear('DATE', $base_year)
                ->whereMonth('DATE', date('m', strtotime(request()->input('year').'-'.request()->input('month').'-01')))
                ->distinct()
                ->pluck('DISEASE');
            }

            
            //FETCHING MORTALITY
            foreach($mort_query as $s) {
                $count = 0;

                if($type == 'yearly') {
                    $mort_query2 = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
                    ->whereYear('DATE', $base_year)
                    ->where('DISEASE', $s)
                    ->get();
                }
                else if($type == 'quarterly') {
                    $mort_query2 = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
                    ->whereBetween('DATE', [$from, $to])
                    ->where('DISEASE', $s)
                    ->get();
                }
                else if($type == 'monthly') {
                    $mort_query2 = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', date('m', strtotime(request()->input('year').'-'.request()->input('month').'-01')))
                    ->where('DISEASE', $s)
                    ->get();
                }

                foreach ($mort_query2 as $t) {
                    $count +=
                    $t['1_4_M'] + $t['1_4_F'] +
                    $t['5_9_M'] + $t['5_9_F'] +
                    $t['10_14_M'] + $t['10_14_F'] + 
                    $t['15_19_M'] + $t['15_19_F'] +
                    $t['20_24_M'] + $t['20_24_F'] +
                    $t['25_29_M'] + $t['25_29_F'] + 
                    $t['30_34_M'] + $t['30_34_F'] +
                    $t['35_39_M'] + $t['35_39_F'] +
                    $t['40_44_M'] + $t['40_44_F'] +
                    $t['45_49_M'] + $t['45_49_F'] +
                    $t['50_54_M'] + $t['50_54_F'] +
                    $t['55_59_M'] + $t['55_59_F'] +
                    $t['60_64_M'] + $t['60_64_F'] +
                    $t['65_69_M'] + $t['65_69_F'] +
                    $t['70ABOVE_M'] + $t['70ABOVE_F'] +
                    $t['0_6DAYS_M'] + $t['0_6DAYS_F'] +
                    $t['7_28DAYS_M'] + $t['7_28DAYS_F'] +
                    $t['29DAYS_11MOS_M'] + $t['29DAYS_11MOS_F']
                    ;
                }

                array_push($mort_final_list, [
                    'disease' => $s,
                    'count' => $count,
                ]);
            }

            //FETCHING MORBIDITY
            foreach($morb_query as $s) {
                $count = 0;

                if($type == 'yearly') {
                    $morb_query2 = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
                    ->whereYear('DATE', $base_year)
                    ->where('DISEASE', $s)
                    ->get();
                }
                else if($type == 'quarterly') {
                    $morb_query2 = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
                    ->whereBetween('DATE', [$from, $to])
                    ->where('DISEASE', $s)
                    ->get();
                }
                else if($type == 'monthly') {
                    $morb_query2 = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', date('m', strtotime(request()->input('year').'-'.request()->input('month').'-01')))
                    ->where('DISEASE', $s)
                    ->get();
                }

                foreach ($morb_query2 as $t) {
                    $count +=
                    $t['1_4_M'] + $t['1_4_F'] +
                    $t['5_9_M'] + $t['5_9_F'] +
                    $t['10_14_M'] + $t['10_14_F'] + 
                    $t['15_19_M'] + $t['15_19_F'] +
                    $t['20_24_M'] + $t['20_24_F'] +
                    $t['25_29_M'] + $t['25_29_F'] + 
                    $t['30_34_M'] + $t['30_34_F'] +
                    $t['35_39_M'] + $t['35_39_F'] +
                    $t['40_44_M'] + $t['40_44_F'] +
                    $t['45_49_M'] + $t['45_49_F'] +
                    $t['50_54_M'] + $t['50_54_F'] +
                    $t['55_59_M'] + $t['55_59_F'] +
                    $t['60_64_M'] + $t['60_64_F'] +
                    $t['65_69_M'] + $t['65_69_F'] +
                    $t['70ABOVE_M'] + $t['70ABOVE_F'] +
                    $t['0_6DAYS_M'] + $t['0_6DAYS_F'] +
                    $t['7_28DAYS_M'] + $t['7_28DAYS_F'] +
                    $t['29DAYS_11MOS_M'] + $t['29DAYS_11MOS_F']
                    ;
                }

                array_push($morb_final_list, [
                    'disease' => $s,
                    'count' => $count,
                ]);
            }

            //MORT AND NATALITY
            foreach($bgy_list as $b) {

                //get population count in barangay first
                $pop_count = FhsisPopulation::where('BGY_CODE', $b->BGY_CODE)
                ->where('POP_YEAR', $base_year)->first()->POP_BGY;

                $livebirth = 0;
                $tot_death = 0;
                $mat_death = 0;
                $inf_death = 0;
                $unf_death = 0;

                if($type == 'yearly') {
                    $mn_query = FhsisMortalityNatality::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->get();
                }
                else if($type == 'quarterly') {
                    $mn_query = FhsisMortalityNatality::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereBetween('DATE', [$from, $to])
                    ->get();
                }
                else if($type == 'monthly') {
                    $mn_query = FhsisMortalityNatality::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', request()->input('month'))
                    ->get();
                }

                foreach($mn_query as $row) {
                    $livebirth += $row['LB_M'] + $row['LB_F'];
                    $tot_death += $row['TOTDEATH_M'] + $row['TOTDEATH_F'];
                    $mat_death += $row['MATDEATH_M'] + $row['MATDEATH_F'];
                    $inf_death += $row['INFDEATH_M'] + $row['INFDEATH_F'];
                    $unf_death += $row['DEATHUND5_M'] + $row['DEATHUND5_F'];
                }

                array_push($bgy_nm_list, [
                    'barangay' => $b->BGY_DESC,
                    'population' => $pop_count,
                    'livebirth' => $livebirth,
                    'tot_death' => $tot_death,
                    'mat_death' => $mat_death,
                    'inf_death' => $inf_death,
                    'unf_death' => $unf_death,
                ]);
            }

            //M1
            foreach($bgy_list as $b) {
                if($type == 'yearly') {
                    $ccare_query = FhsisChildCare::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->get();

                    $ncom_query = FhsisNonComm::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->get();

                    $fp1_query = FhsisFamilyPlanning1::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->get();

                    $fp2_query = FhsisFamilyPlanning2::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->get();

                    $fp3_query = FhsisFamilyPlanning3::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->get();

                    if($base_year != date('Y')) {
                        $edate = date('Y-m-d', strtotime($base_year.'-12-01'));
                    }
                    else {
                        if(date('n') >= 2 && date('n') <= 4) {
                            $edate = date('Y-m-d', strtotime($base_year.'-03-01'));
                        }
                        else if(date('n') >= 5 && date('n') <= 7) {
                            $edate = date('Y-m-d', strtotime($base_year.'-06-01'));
                        }
                        else if(date('n') >= 8 && date('n') <= 10) {
                            $edate = date('Y-m-d', strtotime($base_year.'-09-01'));
                        }
                        else if(date('n') >= 11 && date('n') <= 12) {
                            $edate = date('Y-m-d', strtotime($base_year.'-12-01'));
                        }
                    }

                    $env_query = FhsisEnvironmentalHealth::where('YEAR_ENV', $base_year)
                    ->whereDate('DATE', $edate)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->get();

                    $dental_query = FhsisDental::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->get();
                }
                else if($type == 'quarterly') {
                    $ccare_query = FhsisChildCare::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereBetween('DATE', [$from, $to])
                    ->get();

                    $ncom_query = FhsisNonComm::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereBetween('DATE', [$from, $to])
                    ->get();

                    $fp1_query = FhsisFamilyPlanning1::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereBetween('DATE', [$from, $to])
                    ->get();

                    $fp2_query = FhsisFamilyPlanning2::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereBetween('DATE', [$from, $to])
                    ->get();

                    $fp3_query = FhsisFamilyPlanning3::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereBetween('DATE', [$from, $to])
                    ->get();

                    if($q == 1) {
                        $edate = $base_year.'-03-01';
                    }
                    else if($q == 2) {
                        $edate = $base_year.'-06-01';
                    }
                    else if($q == 3) {
                        $edate = $base_year.'-09-01';
                    }
                    else if($q == 4) {
                        $edate = $base_year.'-12-01';
                    }
                    
                    $env_query = FhsisEnvironmentalHealth::where('YEAR_ENV', $base_year)
                    ->whereDate('DATE', $edate)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->get();

                    $dental_query = FhsisDental::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereBetween('DATE', [$from, $to])
                    ->get();
                }
                else if($type == 'monthly') {
                    $im = request()->input('month');

                    $ccare_query = FhsisChildCare::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', request()->input('month'))
                    ->get();

                    $ncom_query = FhsisNonComm::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', request()->input('month'))
                    ->get();

                    $fp1_query = FhsisFamilyPlanning1::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', request()->input('month'))
                    ->get();

                    $fp2_query = FhsisFamilyPlanning2::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', request()->input('month'))
                    ->get();

                    $fp3_query = FhsisFamilyPlanning3::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', request()->input('month'))
                    ->get();

                    if($im == '01' || $im == '02' || $im == '03') {
                        $edate = $base_year.'-03-01';
                    }
                    else if($im == '04' || $im == '05' || $im == '06') {
                        $edate = $base_year.'-06-01';
                    }
                    else if($im == '07' || $im == '08' || $im == '09') {
                        $edate = $base_year.'-09-01';
                    }
                    else if($im == '10' || $im == '11' || $im == '12') {
                        $edate = $base_year.'-12-01';
                    }
                    
                    $env_query = FhsisEnvironmentalHealth::where('YEAR_ENV', $base_year)
                    ->whereDate('DATE', $edate)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->get();

                    $dental_query = FhsisDental::where('MUN_CODE', $b->MUN_CODE)
                    ->where('BGY_CODE', $b->BGY_DESC)
                    ->whereYear('DATE', $base_year)
                    ->whereMonth('DATE', request()->input('month'))
                    ->get();
                }

                $fic_m = 0;
                $fic_f = 0;
                $cic_m = 0;
                $cic_f = 0;
                
                $ppv = 0;
                $flu = 0;
                $ra = 0;

                $fp_currusers_beggining = 0;
                $fp_otheraccp_present = 0;
                $fp_dropouts_present = 0;
                $fp_currusers_end = 0;
                $fp_newaccp_present = 0;

                //CHILD CARE FETCH
                foreach($ccare_query as $row) {
                    $fic_m += $row['FIC_M'];
                    $fic_f += $row['FIC_F'];
                    $cic_m += $row['CIC_M'];
                    $cic_f += $row['CIC_F'];
                }

                //NON-COMM FETCH
                foreach($ncom_query as $row) {
                    $ra += $row['NONCOM_PPEN_M'] + $row['NONCOM_PPEN_F'];
                    $ppv += $row['NONCOM_PPV_M'] + $row['NONCOM_PPV_F'];
                    $flu += $row['NONCOM_IV_M'] + $row['NONCOM_PPV_F'];
                }

                //FAMILY PLANNING 1 FETCH
                foreach($fp1_query as $row) {
                    $fp_currusers_beggining +=
                    $row['PREV_FS'] + 
                    $row['PREV_MS'] + 
                    $row['PREV_PILLS'] + 
                    $row['PREV_IUD'] + 
                    $row['PREV_DMPA'] + 
                    $row['PREV_NFPCM'] + 
                    $row['PREV_NFPBBT'] + 
                    $row['PREV_NFPLAM'] + 
                    $row['PREV_NFPSDM'] + 
                    $row['PREV_NFPSTM'] + 
                    $row['PREV_CONDOM'] + 
                    $row['PREV_CONDOM_F'] + 
                    $row['PREV_IMPLANT'];

                    $fp_otheraccp_present +=
                    $row['TOA_FS'] + 
                    $row['TOA_MS'] + 
                    $row['TOA_PILLS'] + 
                    $row['TOA_IUD'] + 
                    $row['TOA_DMPA'] + 
                    $row['TOA_NFPCM'] + 
                    $row['TOA_NFPBBT'] + 
                    $row['TOA_NFPLAM'] + 
                    $row['TOA_NFPSDM'] + 
                    $row['TOA_NFPSTM'] + 
                    $row['TOA_CONDOM'] + 
                    $row['TOA_CONDOM_F'] + 
                    $row['TOA_IMPLANT'];

                    $fp_dropouts_present +=
                    $row['TDO_FS'] + 
                    $row['TDO_MS'] + 
                    $row['TDO_PILLS'] + 
                    $row['TDO_IUD'] + 
                    $row['TDO_DMPA'] + 
                    $row['TDO_NFPCM'] + 
                    $row['TDO_NFPBBT'] + 
                    $row['TDO_NFPLAM'] + 
                    $row['TDO_NFPSDM'] + 
                    $row['TDO_NFPSTM'] + 
                    $row['TDO_CONDOM'] + 
                    $row['TDO_CONDOM_F'] + 
                    $row['TDO_IMPLANT'];

                    //$fp_currusers_end += ($fp_currusers_beggining + $fp_otheraccp_present) - $fp_dropouts_present;
                    
                    $fp_newaccp_present +=
                    $row['TNA_FS'] + 
                    $row['TNA_MS'] + 
                    $row['TNA_PILLS'] + 
                    $row['TNA_IUD'] + 
                    $row['TNA_DMPA'] + 
                    $row['TNA_NFPCM'] + 
                    $row['TNA_NFPBBT'] + 
                    $row['TNA_NFPLAM'] + 
                    $row['TNA_NFPSDM'] + 
                    $row['TNA_NFPSTM'] + 
                    $row['TNA_CONDOM'] + 
                    $row['TNA_CONDOM_F'] + 
                    $row['TNA_IMPLANT'];
                }

                //FAMILY PLANNING 2 FETCH
                foreach($fp2_query as $row) {
                    $fp_currusers_beggining +=
                    $row['PREV_FS1519'] + 
                    $row['PREV_MS1519'] + 
                    $row['PREV_PILLS1519'] + 
                    $row['PREV_IUD1519'] + 
                    $row['PREV_DMPA1519'] + 
                    $row['PREV_NFPCM1519'] + 
                    $row['PREV_NFPBBT1519'] + 
                    $row['PREV_NFPLAM1519'] + 
                    $row['PREV_NFPSDM1519'] + 
                    $row['PREV_NFPSTM1519'] + 
                    $row['PREV_CONDOM1519'] + 
                    $row['PREV_CONDOM_F1519'] + 
                    $row['PREV_IMPLANT1519'];

                    $fp_otheraccp_present +=
                    $row['TOA_FS1519'] + 
                    $row['TOA_MS1519'] + 
                    $row['TOA_PILLS1519'] + 
                    $row['TOA_IUD1519'] + 
                    $row['TOA_DMPA1519'] + 
                    $row['TOA_NFPCM1519'] + 
                    $row['TOA_NFPBBT1519'] + 
                    $row['TOA_NFPLAM1519'] + 
                    $row['TOA_NFPSDM1519'] + 
                    $row['TOA_NFPSTM1519'] + 
                    $row['TOA_CONDOM1519'] + 
                    $row['TOA_CONDOM_F1519'] + 
                    $row['TOA_IMPLANT1519'];

                    $fp_dropouts_present +=
                    $row['TDO_FS1519'] + 
                    $row['TDO_MS1519'] + 
                    $row['TDO_PILLS1519'] + 
                    $row['TDO_IUD1519'] + 
                    $row['TDO_DMPA1519'] + 
                    $row['TDO_NFPCM1519'] + 
                    $row['TDO_NFPBBT1519'] + 
                    $row['TDO_NFPLAM1519'] + 
                    $row['TDO_NFPSDM1519'] + 
                    $row['TDO_NFPSTM1519'] + 
                    $row['TDO_CONDOM1519'] + 
                    $row['TDO_CONDOM_F1519'] + 
                    $row['TDO_IMPLANT1519'];

                    //$fp_currusers_end += ($fp_currusers_beggining + $fp_otheraccp_present) - $fp_dropouts_present;
                    
                    $fp_newaccp_present +=
                    $row['TNA_FS1519'] + 
                    $row['TNA_MS1519'] + 
                    $row['TNA_PILLS1519'] + 
                    $row['TNA_IUD1519'] + 
                    $row['TNA_DMPA1519'] + 
                    $row['TNA_NFPCM1519'] + 
                    $row['TNA_NFPBBT1519'] + 
                    $row['TNA_NFPLAM1519'] + 
                    $row['TNA_NFPSDM1519'] + 
                    $row['TNA_NFPSTM1519'] + 
                    $row['TNA_CONDOM1519'] + 
                    $row['TNA_CONDOM_F1519'] + 
                    $row['TNA_IMPLANT1519'];
                }

                //FAMILY PLANNING 3 FETCH
                foreach($fp3_query as $row) {
                    $fp_currusers_beggining +=
                    $row['PREV_FS2049'] + 
                    $row['PREV_MS2049'] + 
                    $row['PREV_PILLS2049'] + 
                    $row['PREV_IUD2049'] + 
                    $row['PREV_DMPA2049'] + 
                    $row['PREV_NFPCM2049'] + 
                    $row['PREV_NFPBBT2049'] + 
                    $row['PREV_NFPLAM2049'] + 
                    $row['PREV_NFPSDM2049'] + 
                    $row['PREV_NFPSTM2049'] + 
                    $row['PREV_CONDOM2049'] + 
                    $row['PREV_CONDOM_F2049'] + 
                    $row['PREV_IMPLANT2049'];

                    $fp_otheraccp_present +=
                    $row['TOA_FS2049'] + 
                    $row['TOA_MS2049'] + 
                    $row['TOA_PILLS2049'] + 
                    $row['TOA_IUD2049'] + 
                    $row['TOA_DMPA2049'] + 
                    $row['TOA_NFPCM2049'] + 
                    $row['TOA_NFPBBT2049'] + 
                    $row['TOA_NFPLAM2049'] + 
                    $row['TOA_NFPSDM2049'] + 
                    $row['TOA_NFPSTM2049'] + 
                    $row['TOA_CONDOM2049'] + 
                    $row['TOA_CONDOM_F2049'] + 
                    $row['TOA_IMPLANT2049'];

                    $fp_dropouts_present +=
                    $row['TDO_FS2049'] + 
                    $row['TDO_MS2049'] + 
                    $row['TDO_PILLS2049'] + 
                    $row['TDO_IUD2049'] + 
                    $row['TDO_DMPA2049'] + 
                    $row['TDO_NFPCM2049'] + 
                    $row['TDO_NFPBBT2049'] + 
                    $row['TDO_NFPLAM2049'] + 
                    $row['TDO_NFPSDM2049'] + 
                    $row['TDO_NFPSTM2049'] + 
                    $row['TDO_CONDOM2049'] + 
                    $row['TDO_CONDOM_F2049'] + 
                    $row['TDO_IMPLANT2049'];

                    //$fp_currusers_end += ($fp_currusers_beggining + $fp_otheraccp_present) - $fp_dropouts_present;
                    
                    $fp_newaccp_present +=
                    $row['TNA_FS2049'] + 
                    $row['TNA_MS2049'] + 
                    $row['TNA_PILLS2049'] + 
                    $row['TNA_IUD2049'] + 
                    $row['TNA_DMPA2049'] + 
                    $row['TNA_NFPCM2049'] + 
                    $row['TNA_NFPBBT2049'] + 
                    $row['TNA_NFPLAM2049'] + 
                    $row['TNA_NFPSDM2049'] + 
                    $row['TNA_NFPSTM2049'] + 
                    $row['TNA_CONDOM2049'] + 
                    $row['TNA_CONDOM_F2049'] + 
                    $row['TNA_IMPLANT2049'];
                }

                $env_lvl1 = 0;
                $env_lvl2 = 0;
                $env_lvl3 = 0;

                //ENVIRONMENTAL FETCH
                foreach($env_query as $row) {
                    $env_lvl1 += $row['HHWATER_LEVEL1'];
                    $env_lvl2 += $row['HHWATER_LEVEL2'];
                    $env_lvl3 += $row['HHWATER_LEVEL3'];
                }
                
                $bhoc_m = 0;
                $bhoc_f = 0;

                //DENTAL CARE FETCH
                foreach($dental_query as $row) {
                    $bhoc_m +=
                    $row['CHILD_BOHC_M'] +
                    $row['AY_BOHC_M'] +
                    $row['OLDPER_BOHC_M'] +
                    $row['INF011_BOHC_M'] +
                    $row['CHILD59_BOHC_M'] +
                    $row['AY1519_BOHC_M'] +
                    $row['BOHC2059_M'];

                    $bhoc_f +=
                    $row['CHILD_BOHC_F'] +
                    $row['AY_BOHC_F'] +
                    $row['PREG_BOHC_F'] +
                    $row['OLDPER_BOHC_F'] +
                    $row['INF011_BOHC_F'] +
                    $row['CHILD59_BOHC_F'] +
                    $row['AY1519_BOHC_F'] +
                    $row['BOHC2059_F'] +
                    $row['PREG1519_BOHC_F'] +
                    $row['PREG2049_BOHC_F'];
                }

                array_push($bgy_mone_list, [
                    'barangay' => $b->BGY_DESC,
                    'fic_m' => $fic_m,
                    'fic_f' => $fic_f,
                    'cic_m'  => $cic_m,
                    'cic_f'  => $cic_f,
                    'ppv' => $ppv,
                    'flu' => $flu,
                    'ra' => $ra,

                    'fp_currusers_beggining' => $fp_currusers_beggining,
                    'fp_otheraccp_present' => $fp_otheraccp_present,
                    'fp_dropouts_present' => $fp_dropouts_present,
                    'fp_newaccp_present' => $fp_newaccp_present,

                    'env_lvl1' => $env_lvl1,
                    'env_lvl2' => $env_lvl2,
                    'env_lvl3' => $env_lvl3,

                    'bhoc_m' => $bhoc_m,
                    'bhoc_f' => $bhoc_f,
                ]);
            }

            return view('efhsis.report', [
                'mort_final_list' => $mort_final_list,
                'morb_final_list' => $morb_final_list,
                'bgy_nm_list' => $bgy_nm_list,
                'bgy_mone_list' => $bgy_mone_list,
            ]);
        }
        else {
            return view('efhsis.report');
        }
    }

    public function fastlookup() {

    }

    public function fastlookuptwo() {
        $array_list = [];

        $mdb_location = storage_path('app/efhsis/eFHSIS_be.mdb');
        $driver = '{Microsoft Access Driver (*.mdb, *.accdb)}';

        $dsn = "odbc:Driver=$driver;Dbq=$mdb_location;charset=utf8";
        $username = ""; // leave blank if not required
        $password = ""; // leave blank if not required
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, $username, $password, $options);

        $bgy_list = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        if(!is_null(request()->input('month')) && !is_null(request()->input('year'))) {
            foreach($bgy_list as $b) {
                /*
                $mort_bhs_stmt = $pdo->query("SELECT *
                FROM [MORT BHS]
                WHERE [DATE] = ':sdate'
                AND MUN_CODE = 'GENERAL TRIAS'
                AND UCASE([BGY_CODE]) = ':bgy_code'
                AND DISEASE <> ''");
                */

                $mort_bhs_stmt = $pdo->prepare("SELECT * FROM [MORT BHS]
                WHERE MUN_CODE = 'GENERAL TRIAS'
                AND [DATE] = :sdate
                AND UCASE(BGY_CODE) = :bgy_code
                AND DISEASE <> ''");

                $sdate = date('m/01/y', strtotime(request()->input('year').'-'.request()->input('month').'-01'));
                $bgy_name = $b->brgyName;

                $mort_bhs_stmt->bindParam(':sdate', $sdate, PDO::PARAM_STR);
                $mort_bhs_stmt->bindParam(':bgy_code', $bgy_name, PDO::PARAM_STR);

                $mort_bhs_stmt->execute();

                $early_neonatal_deaths_m = 0; //OK
                $early_neonatal_deaths_f = 0; //OK
                $fetal_deaths_m = 0;
                $fetal_deaths_f = 0;
                $neonatal_deaths_m = 0; //OK
                $neonatal_deaths_f = 0; //OK
                $infant_deaths_m = 0; //OK
                $infant_deaths_f = 0; //OK
                $underfive_deaths_m = 0; //OK
                $underfive_deaths_f = 0; //OK
                $maternal_deaths_m = 0;
                $maternal_deaths_f = 0;
                $total_deaths_m = 0;
                $total_deaths_f = 0;
                $livebirths_m = 0;
                $livebirths_f = 0;
                $livebirths_among_m = 0;
                $livebirths_among_f = 0;

                while ($r = $mort_bhs_stmt->fetch()) {
                    $early_neonatal_deaths_m += $r['0_6DAYS_M'];
                    $early_neonatal_deaths_f += $r['0_6DAYS_F'];
                    $neonatal_deaths_m += $r['7_28DAYS_M'];
                    $neonatal_deaths_f += $r['7_28DAYS_F'];
                    $infant_deaths_m += $r['UNDER1_M'];
                    $infant_deaths_f += $r['UNDER1_F'];
                    $underfive_deaths_m += $r['1_4_M'];
                    $underfive_deaths_f += $r['1_4_F'];
                    $total_deaths_m +=
                        $r['1_4_M'] +
                        $r['5_9_M'] + 
                        $r['10_14_M'] +
                        $r['15_19_M'] +
                        $r['20_24_M'] +
                        $r['25_29_M'] +
                        $r['30_34_M'] +
                        $r['35_39_M'] +
                        $r['40_44_M'] +
                        $r['45_49_M'] +
                        $r['50_54_M'] +
                        $r['55_59_M'] +
                        $r['60_64_M'] +
                        $r['65_69_M'] +
                        $r['70ABOVE_M'] +
                        $r['0_6DAYS_M'] +
                        $r['7_28DAYS_M'] +
                        $r['29DAYS_11MOS_M'];
                    $total_deaths_f +=
                        $r['1_4_F'] +
                        $r['5_9_F'] + 
                        $r['10_14_F'] +
                        $r['15_19_F'] +
                        $r['20_24_F'] +
                        $r['25_29_F'] +
                        $r['30_34_F'] +
                        $r['35_39_F'] +
                        $r['40_44_F'] +
                        $r['45_49_F'] +
                        $r['50_54_F'] +
                        $r['55_59_F'] +
                        $r['60_64_F'] +
                        $r['65_69_F'] +
                        $r['70ABOVE_F'] +
                        $r['0_6DAYS_F'] +
                        $r['7_28DAYS_F'] +
                        $r['29DAYS_11MOS_F'];
                }

                array_push($array_list, [
                    'barangay' => $b->brgyName,
                    'early_neonatal_deaths_m' => $early_neonatal_deaths_m,
                    'early_neonatal_deaths_f' => $early_neonatal_deaths_f,
                    'fetal_deaths_m' => 0,
                    'fetal_deaths_f' => 0,
                    'neonatal_deaths_m' => $neonatal_deaths_m,
                    'neonatal_deaths_f' => $neonatal_deaths_f,
                    'infant_deaths_m' => $infant_deaths_m,
                    'infant_deaths_f' => $infant_deaths_f,
                    'underfive_deaths_m' => $underfive_deaths_m,
                    'underfive_deaths_f' => $underfive_deaths_f,
                    'maternal_deaths_m' => 0,
                    'maternal_deaths_f' => 0,
                    'total_deaths_m' => $total_deaths_m,
                    'total_deaths_f' => $total_deaths_f,
                    'livebirths_m' => 0,
                    'livebirths_f' => 0,
                    'livebirths_among_m' => 0,
                    'livebirths_among_f' => 0,
                ]);
            }
        }

        return view('efhsis.fastlookup2', [
            'array_list' => $array_list,
        ]);
    }

    public function fastlookuptwo_process() {
        
    }

    public function cesum2() {
        $d = request()->input('disease');
        $y = request()->input('year');
        $m = request()->input('month');

        $bgy_list = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $arr = [];

        $length = 'Month of '.date('F', strtotime($y.'-'.$m.'-01')).', Year '.$y;

        foreach($bgy_list as $b) {
            if($d == 'Covid') {

            }
            else if($d == 'Dengue') {
                
            }
            else if($d == 'AnimalBite') {
                $lcode = 'T14.1; Open wound of unspecified body region (Animal Bite (Dog & Others), GUNSHOT WOUND, LACERATED WOUND, MINOR INJURIES, STAB WOUND)';

                $item1_m = 0;
                $item1_f = 0;
                
                $item2_m = 0;
                $item2_f = 0;
    
                $item3_m = 0;
                $item3_f = 0;
                
                //1-4
                $item4_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [1,4])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item4_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [1,4])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
                
                //5-9
                $item5_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [5,9])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item5_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [5,9])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //10-14
                $item6_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [10,14])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item6_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [10,14])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //15-19
                $item7_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [15,19])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item7_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [15,19])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //20-24
                $item8_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [20,24])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item8_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [20,24])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //25-29
                $item9_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [25,29])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item9_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [25,29])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //30-34
                $item10_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [30,34])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item10_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [30,34])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //35-39
                $item11_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [35,39])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item11_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [35,39])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //40-44
                $item12_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [40,44])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item12_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [40,44])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //45-49
                $item13_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [45,49])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item13_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [45,49])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //50-54
                $item14_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [50,54])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item14_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [50,54])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //55-59
                $item15_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [55,59])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item15_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [55,59])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //60-64
                $item16_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [60,64])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item16_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [60,64])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //65-69
                $item17_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->whereBetween('age', [65,69])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item17_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->whereBetween('age', [65,69])
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                //70 and Above
                $item18_m = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'MALE')
                    ->where('age', '>=', 70)
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $item18_f = AbtcBakunaRecords::whereHas('patients', function ($q) use ($b) {
                    $q->where('gender', 'FEMALE')
                    ->where('age', '>=', 70)
                    ->where('address_muncity_text', $b->city->cityName)
                    ->where('address_brgy_text', $b->brgyName);
                })->whereMonth('created_at', $m)
                ->count();
    
                $total_m = $item1_m + $item2_m + $item3_m + $item4_m + $item5_m + $item6_m + $item7_m + $item8_m + $item9_m + $item10_m + $item11_m + $item12_m + $item13_m + $item14_m + $item15_m + $item16_m + $item17_m + $item18_m;
                $total_f = $item1_f + $item2_f + $item3_f + $item4_f + $item5_f + $item6_f + $item7_f + $item8_f + $item9_f + $item10_f + $item11_f + $item12_f + $item13_f + $item14_f + $item15_f + $item16_f + $item17_f + $item18_f;
    
                array_push($arr, [
                    'barangay' => $b->brgyName,
                    'item1_m' => $item1_m,
                    'item1_f' => $item1_f,
                    'item2_m' => $item2_m,
                    'item2_f' => $item2_f,
                    'item3_m' => $item3_m,
                    'item3_f' => $item3_f,
                    'item4_m' => $item4_m,
                    'item4_f' => $item4_f,
                    'item5_m' => $item5_m,
                    'item5_f' => $item5_f,
                    'item6_m' => $item6_m,
                    'item6_f' => $item6_f,
                    'item7_m' => $item7_m,
                    'item7_f' => $item7_f,
                    'item8_m' => $item8_m,
                    'item8_f' => $item8_f,
                    'item9_m' => $item9_m,
                    'item9_f' => $item9_f,
                    'item10_m' => $item10_m,
                    'item10_f' => $item10_f,
                    'item11_m' => $item11_m,
                    'item11_f' => $item11_f,
                    'item12_m' => $item12_m,
                    'item12_f' => $item12_f,
                    'item13_m' => $item13_m,
                    'item13_f' => $item13_f,
                    'item14_m' => $item14_m,
                    'item14_f' => $item14_f,
                    'item15_m' => $item15_m,
                    'item15_f' => $item15_f,
                    'item16_m' => $item16_m,
                    'item16_f' => $item16_f,
                    'item17_m' => $item17_m,
                    'item17_f' => $item17_f,
                    'item18_m' => $item18_m,
                    'item18_f' => $item18_f,
                    'total_m' => $total_m,
                    'total_f' => $total_f,
                ]);
            }
        }
        
        return view('efhsis.cesum2', [
            'arr' => $arr,
            'lcode' => $lcode,
            'length' => $length,
        ]);
    }

    public function oneclickexcel() {
        /*
        List of ICD10 Codes per System Diseases

        Animal Bite T14.1; Open wound of unspecified body region (Animal Bite (Dog & Others), GUNSHOT WOUND, LACERATED WOUND, MINOR INJURIES, STAB WOUND)
        Covid

        ABD - A09.0; Acute Bloody Diarrhea
        AEFI - NOT FOUND
        AES - A83; Mosquito-borne viral encephalitis
        AFP - G83.9; Paralytic syndrome, unspecified (CVA WITH PARALYSIS) (OLD OR HEALED CVA WITH PARALYSIS)
        AHF - A97.0; Dengue without warning signs (2016)
        AMES - NOT FOUND
        ANTHRAX - A22; Anthrax
        CHIKUNGUNYA - A92.0; Chikungunya virus disease
        CHOLERA - A00; Cholera
        DENGUE - A97.0; Dengue without warning signs (2016)
        DENGUE - A97.1; Dengue with warning signs (2016)
        DENGUE - A97.2; Severe Dengue (2016)
        DIPH - A36; Diphtheria
        HEPATITIS - NOT FOUND
        HFMD - NOT FOUND
        INFLUENZA - J11.1; Influenza with other respiratory manifestations, virus not identified (INFLUENZA-LIKE DISEASE/ILLNESS) (BRONCHIAL, AURI, LARYNGITIS,  PHARYNGITIS, PLEURAL EFFUSION, URI, VIRAL)
        LEPTOSPIROSIS - A27; Leptospirosis
        MALARIA - B54; Unspecified malaria (MALARIAL ANEMIA) (MALARIA, CLINICALLY DIAGNOSED WITHOUT PARASITOLOGICAL CONFIRMATION)
        MEASLES - B05; Measles
        MENINGITIS - NOT FOUND
        MENINGO - A39.4; Meningococcemia, unspecified
        NNT - NOT FOUND
        NT - NOT FOUND
        PERT - NOT FOUND
        PSP - T61.2; Other fish and shellfish (mussels) poisoning (accidental)
        RABIES - A82; Rabies
        ROTAVIRUS - A08.0; Rotavirus
        TYPHOID - A01; Typhoid and paratyphoid fevers (ENTERIC FEVER)

        */
    }

    public function timelinesscheck() {
        if(request()->input('year')) {
            $year = request()->input('year');
        }
        else {
            $year = date('Y');
        }

        $m2l = array();

        //loop through barangay
        $blist = FhsisBarangay::orderBy('BGY_DESC', 'ASC')->get();
        foreach($blist as $ind => $b) {
            //$m2l[] = $b->BGY_DESC;

            array_push($m2l, [
                'barangay' => $b->BGY_DESC,
            ]);

            if(request()->input('year') != date('Y')) {
                $l = date('n');
            }
            else {
                $l = 12;
            }
            
            //loop through months
            for($i=1;$i<=$l;$i++) {
                $s = FhsisM2::whereYear('DATE', $year)
                ->whereMonth('DATE', $i)
                ->first();

                if($s) {
                    array_push($m2l[$ind], '✔');
                    //$m2l[$b->BGY_DESC][] = '✔';
                }
                else {
                    array_push($m2l[$ind], 'X');
                    //$m2l[$b->BGY_DESC][] = 'X';
                }
            }
        }

        //dd($m2l);

        return view('efhsis.timeliness', [
            'm2l' => $m2l,
            'blist' => $blist,
            'year' => $year,
            'month' => $l,
        ]);
    }
}
