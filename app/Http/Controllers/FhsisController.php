<?php

namespace App\Http\Controllers;

use PDO;
use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\FhsisM2;
use App\Models\Icd10Code;
use App\Models\LiveBirth;
use App\Models\FhsisDental;
use App\Models\FhsisMortBhs;
use App\Models\FhsisNonComm;
use Illuminate\Http\Request;
use App\Models\FhsisBarangay;
use App\Models\FhsisChildCare;
use App\Models\FhsisPopulation;
use App\Models\DeathCertificate;
use App\Models\FhsisDemographic;
use App\Models\AbtcBakunaRecords;
use App\Imports\FhsisTbdotsImport;
use Illuminate\Support\Facades\DB;
use App\Models\FhsisFamilyPlanning1;
use App\Models\FhsisFamilyPlanning2;
use App\Models\FhsisFamilyPlanning3;
use App\Models\FhsisTbdotsMorbidity;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\FhsisMortalityNatality;
use Illuminate\Support\Facades\Storage;
use App\Models\FhsisEnvironmentalHealth;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\FhsisSystemDemographicProfile;

class FhsisController extends Controller
{
    public function home() {
        $brgylist = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $bgy_list_fhsisformat = FhsisBarangay::where('MUN_CODE', 'GENERAL TRIAS')
        ->orderBy('BGY_DESC', 'ASC')->get();

        return view('efhsis.home', [
            'brgylist' => $brgylist,
            'bgy_list_fhsisformat' => $bgy_list_fhsisformat,
        ]);
    }

    public function report() {
        if(request()->input('type') && request()->input('year')) {
            $bgy_list = FhsisBarangay::where('MUN_CODE', 'GENERAL TRIAS')
            ->orderBy('BGY_DESC', 'ASC')->get();

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
                $count_male = 0;
                $count_female = 0;

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

                    $count_male += $t['1_4_M'] +
                    $t['5_9_M'] +
                    $t['10_14_M'] +
                    $t['15_19_M'] +
                    $t['20_24_M'] +
                    $t['25_29_M'] +
                    $t['30_34_M'] +
                    $t['35_39_M'] +
                    $t['40_44_M'] +
                    $t['45_49_M'] +
                    $t['50_54_M'] +
                    $t['55_59_M'] +
                    $t['60_64_M'] +
                    $t['65_69_M'] +
                    $t['70ABOVE_M'] +
                    $t['0_6DAYS_M'] +
                    $t['7_28DAYS_M'] +
                    $t['29DAYS_11MOS_M'];

                    $count_female += $t['1_4_F'] +
                    $t['5_9_F'] +
                    $t['10_14_F'] +
                    $t['15_19_F'] +
                    $t['20_24_F'] +
                    $t['25_29_F'] +
                    $t['30_34_F'] +
                    $t['35_39_F'] +
                    $t['40_44_F'] +
                    $t['45_49_F'] +
                    $t['50_54_F'] +
                    $t['55_59_F'] +
                    $t['60_64_F'] +
                    $t['65_69_F'] +
                    $t['70ABOVE_F'] +
                    $t['0_6DAYS_F'] +
                    $t['7_28DAYS_F'] +
                    $t['29DAYS_11MOS_F'];
                }

                array_push($mort_final_list, [
                    'disease' => $s,
                    'count_male' => $count_male,
                    'count_female' => $count_female,
                    'count' => $count,
                ]);
            }

            //FETCHING MORBIDITY
            foreach($morb_query as $s) {
                $count = 0;
                $count_male = 0;
                $count_female = 0;

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

                    $count_male += $t['1_4_M'] +
                    $t['5_9_M'] +
                    $t['10_14_M'] +
                    $t['15_19_M'] +
                    $t['20_24_M'] +
                    $t['25_29_M'] +
                    $t['30_34_M'] +
                    $t['35_39_M'] +
                    $t['40_44_M'] +
                    $t['45_49_M'] +
                    $t['50_54_M'] +
                    $t['55_59_M'] +
                    $t['60_64_M'] +
                    $t['65_69_M'] +
                    $t['70ABOVE_M'] +
                    $t['0_6DAYS_M'] +
                    $t['7_28DAYS_M'] +
                    $t['29DAYS_11MOS_M'];

                    $count_female += $t['1_4_F'] +
                    $t['5_9_F'] +
                    $t['10_14_F'] +
                    $t['15_19_F'] +
                    $t['20_24_F'] +
                    $t['25_29_F'] +
                    $t['30_34_F'] +
                    $t['35_39_F'] +
                    $t['40_44_F'] +
                    $t['45_49_F'] +
                    $t['50_54_F'] +
                    $t['55_59_F'] +
                    $t['60_64_F'] +
                    $t['65_69_F'] +
                    $t['70ABOVE_F'] +
                    $t['0_6DAYS_F'] +
                    $t['7_28DAYS_F'] +
                    $t['29DAYS_11MOS_F'];
                }

                array_push($morb_final_list, [
                    'disease' => $s,
                    'count' => $count,
                    'count_male' => $count_male,
                    'count_female' => $count_female,
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

    public function pquery() {
        ini_set('max_execution_time', 9999999999);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $filePath = 'C:\laragon\www\cesu\storage\app\efhsis\output.sql';
        } else {
            $filePath = storage_path('app/efhsis/output.sql');
        }

        if (!file_exists($filePath)) {
            return redirect()->back()
            ->with('msg', 'Error: BE File output.sql not found.')
            ->with('msgtype', 'warning');
        }

        $sql = file_get_contents($filePath);

        DB::unprepared($sql);

        //Check if Demographic Profile Exists and Create if not existing
        $demographics = FhsisDemographic::where('MUN_CODE', 'GENERAL TRIAS')
        ->orderBy('DATE', 'ASC')->get();

        if($demographics->count() != 0) {
            foreach($demographics as $d) {
                $search_barangay = Brgy::where('city_id', 1)->where('brgyNameFhsis', $d->BGY_CODE)->first();

                //Check if Demographic Data Exists
                $create_year = Carbon::createFromDate($d->YEAR_DEMO, 12, 01);
                $demographic_date = Carbon::parse($d->DATE);

                if($demographic_date->isSameDay($create_year)) {
                    $search_demographics = FhsisSystemDemographicProfile::where('city_id', 1)
                    ->where('brgy_id', $search_barangay->id)
                    ->whereDate('encode_date', $create_year->format('Y-m-d'))
                    ->where('for_year', $d->YEAR_DEMO)
                    ->first();

                    if(!$search_demographics) {
                        $create = FhsisSystemDemographicProfile::create([
                            'encode_date' => Carbon::parse($d->DATE)->format('Y-m-d'),
                            'city_id' => 1,
                            'brgy_id' => $search_barangay->id,
                            'for_year' => $d->YEAR_DEMO,
                            'total_brgy' => $d->TOT_BGY,
                            'total_bhs' => $d->TOT_BHS,
                            'total_mainhc' => $d->TOT_HC,
                            'total_cityhc' => $d->TOT_HCC,
                            'total_ruralhc' => $d->TOT_HCR,
                            'doctors_lgu' => $d->MD_M,
                            'doctors_doh' => $d->MD_F,
                            'dentists_lgu' => $d->DENT_M,
                            'dentists_doh' => $d->DENT_F,
                            'nurses_lgu' => $d->PHN_M,
                            'nurses_doh' => $d->PHN_F,
                            'midwifes_lgu' => $d->MIDW_M,
                            'midwifes_doh' => $d->MIDW_F,
                            'nutritionists_lgu' => $d->NUTR_M,
                            'nutritionists_doh' => $d->NUTR_F,
                            'medtechs_lgu' => $d->MEDT_M,
                            'medtechs_doh' => $d->MEDT_F,
                            'sanitary_eng_lgu' => $d->SE_M,
                            'sanitary_eng_doh' => $d->SE_F,
                            'sanitary_ins_lgu' => $d->SI_M,
                            'sanitary_ins_doh' => $d->SI_F,
                            'bhws_lgu' => $d->BHW_M,
                            'bhws_doh' => $d->BHW_F,

                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }
        }

        return redirect()->back()
        ->with('msg', 'Import Successful.')
        ->with('msgtype', 'success');
    }

    public function morbreport() {

    }

    public function liveBirthsEncode() {
        if(request()->input('month') && request()->input('year')) {
            $month = request()->input('month');
            $year = request()->input('year');

            $current = Carbon::create(date('Y'), date('m'), 1, 0, 0, 0);
            $selected = Carbon::create($year, $month, 1, 0, 0, 0);

            if($selected->gt($current)) {
                return redirect()->route('fhsis_home')
                ->with('msg', 'Error: Advance ka pa sa present date mag-encode ah. Bawal yun! hihi')
                ->with('msgtype', 'warning');
            }

            return view('efhsis.livebirth_encode', [
                'month' => $month,
                'year' => $year,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function liveBirthsStore(Request $r) {
        $current_check = Carbon::create($r->year, $r->month, 1, 0, 0, 0);
        $dob_check = Carbon::create($r->input_year, $r->input_month, 1, 0, 0, 0);
        $dob_final = Carbon::create($r->input_year, $r->input_month, $r->input_day, 0, 0, 0);

        $checkreg = LiveBirth::where('registryno', $r->registryno)->first();

        if($checkreg) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Livebirth data already encoded. Double check and try again.')
            ->with('msgtype', 'warning');
        }

        if ($r->input_day > $dob_check->format('t')) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'ERROR: Invalid Birthdate of Newborn. Double check the fields and then try again.')
            ->with('msgtype', 'warning');
        }
        else if($dob_check->gt($current_check)) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'ERROR: Newborn Birthdate is greater than the Selected Encoding Period. Double check or change the encoding period and then try again.')
            ->with('msgtype', 'warning');
        }

        $c = $r->user()->livebirth()->create([
            'registryno' => $r->registryno,
            'year' => $r->year,
            'month' => $r->month,
            'sex' => $r->sex,
            'dob' => $dob_final->format('Y-m-d'),

            'address_region_code' => $r->address_region_code,
            'address_region_text' => $r->address_region_text,
            'address_province_code' => $r->address_province_code,
            'address_province_text' => $r->address_province_text,
            'address_muncity_code' => $r->address_muncity_code,
            'address_muncity_text' => $r->address_muncity_text,
            'address_brgy_code' => $r->address_brgy_text,
            'address_brgy_text' => $r->address_brgy_text,
            'address_street' => ($r->filled('address_street')) ? mb_strtoupper($r->address_street) : NULL,
            'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
            
            'hospital_lyingin' => ($r->filled('hospital_lyingin')) ? mb_strtoupper($r->hospital_lyingin) : NULL,
            'mother_age' => $r->mother_age,
            'mode_delivery' => $r->mode_delivery,
            'multiple_delivery' => $r->multiple_delivery,
        ]);

        return redirect()->back()
        ->with('msg', 'Birth Certificate Registry No.: '.$r->registryno.' was successfully added.')
        ->with('msgtype', 'success');
    }

    public function liveBirthsReport() {
        if(request()->input('month') && request()->input('year') && request()->input('brgy')) {
            $brgylist = Brgy::where('displayInList', 1)
            ->where('city_id', 1)
            ->orderBy('brgyName', 'asc')
            ->get();

            $month = request()->input('month');
            $year = request()->input('year');
            $brgy = request()->input('brgy');

            if($brgy == 'ALL BARANGAYS IN GENERAL TRIAS') {
                $brgy_array = collect();
                foreach($brgylist as $b) {
                    $total_livebirths_m = LiveBirth::where('year', $year)
                    ->where('sex', 'M')
                    ->where('month', $month)
                    ->where('address_brgy_text', $b->brgyName)
                    ->count();

                    $total_livebirths_f = LiveBirth::where('year', $year)
                    ->where('sex', 'F')
                    ->where('month', $month)
                    ->where('address_brgy_text', $b->brgyName)
                    ->count();

                    $livebirth1014_m = LiveBirth::where('year', $year)
                    ->where('month', $month)
                    ->where('sex', 'M')
                    ->whereBetween('mother_age', [10,14])
                    ->where('address_brgy_text', $b->brgyName)
                    ->count();

                    $livebirth1519_m = LiveBirth::where('year', $year)
                    ->where('month', $month)
                    ->where('sex', 'M')
                    ->whereBetween('mother_age', [15,19])
                    ->where('address_brgy_text', $b->brgyName)
                    ->count();

                    $livebirth1014_f = LiveBirth::where('year', $year)
                    ->where('month', $month)
                    ->where('sex', 'F')
                    ->whereBetween('mother_age', [10,14])
                    ->where('address_brgy_text', $b->brgyName)
                    ->count();

                    $livebirth1519_f = LiveBirth::where('year', $year)
                    ->where('month', $month)
                    ->where('sex', 'F')
                    ->whereBetween('mother_age', [15,19])
                    ->where('address_brgy_text', $b->brgyName)
                    ->count();

                    $brgy_array->push([
                        'name' => $b->brgyName,
                        'total_livebirths_m' => $total_livebirths_m,
                        'total_livebirths_f' => $total_livebirths_f,
                        'livebirth1014_m' => $livebirth1014_m,
                        'livebirth1014_f' => $livebirth1014_f,
                        'livebirth1519_m' => $livebirth1519_m,
                        'livebirth1519_f' => $livebirth1519_f,
                    ]);
                }

                $livebirth_othercities_total_m = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('sex', 'M')
                ->where('address_muncity_text', '!=', 'GENERAL TRIAS')
                ->count();

                $livebirth_othercities_total_f = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('sex', 'F')
                ->where('address_muncity_text', '!=', 'GENERAL TRIAS')
                ->count();

                $livebirth_othercities_1014_m = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('sex', 'M')
                ->whereBetween('mother_age', [10,14])
                ->where('address_muncity_text', '!=', 'GENERAL TRIAS')
                ->count();

                $livebirth_othercities_1519_m = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('sex', 'M')
                ->whereBetween('mother_age', [15,19])
                ->where('address_muncity_text', '!=', 'GENERAL TRIAS')
                ->count();

                $livebirth_othercities_1014_f = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('sex', 'F')
                ->whereBetween('mother_age', [10,14])
                ->where('address_muncity_text', '!=', 'GENERAL TRIAS')
                ->count();

                $livebirth_othercities_1519_f = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('sex', 'F')
                ->whereBetween('mother_age', [15,19])
                ->where('address_muncity_text', '!=', 'GENERAL TRIAS')
                ->count();

                return view('efhsis.livebirth_report', [
                    'month' => $month,
                    'year' => $year,
                    'brgy' => $brgy,

                    'brgy_array' => $brgy_array,

                    'brgylist' => $brgylist,

                    'livebirth_othercities_total_m' => $livebirth_othercities_total_m,
                    'livebirth_othercities_1014_m' => $livebirth_othercities_1014_m,
                    'livebirth_othercities_1519_m' => $livebirth_othercities_1519_m,

                    'livebirth_othercities_total_f' => $livebirth_othercities_total_f,
                    'livebirth_othercities_1014_f' => $livebirth_othercities_1014_f,
                    'livebirth_othercities_1519_f' => $livebirth_othercities_1519_f,
                ]);
            }
            else {
                $total_livebirths_m = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('address_brgy_text', $brgy)
                ->where('sex', 'M')
                ->count();

                $total_livebirths_f = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->where('address_brgy_text', $brgy)
                ->where('sex', 'F')
                ->count();

                $livebirth1014_m = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->whereBetween('mother_age', [10,14])
                ->where('address_brgy_text', $brgy)
                ->where('sex', 'M')
                ->count();

                $livebirth1014_f = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->whereBetween('mother_age', [10,14])
                ->where('address_brgy_text', $brgy)
                ->where('sex', 'F')
                ->count();

                $livebirth1519_m = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->whereBetween('mother_age', [15,19])
                ->where('address_brgy_text', $brgy)
                ->where('sex', 'M')
                ->count();

                $livebirth1519_f = LiveBirth::where('year', $year)
                ->where('month', $month)
                ->whereBetween('mother_age', [15,19])
                ->where('address_brgy_text', $brgy)
                ->where('sex', 'F')
                ->count();
                
                return view('efhsis.livebirth_report', [
                    'month' => $month,
                    'year' => $year,
                    'brgy' => $brgy,

                    'total_livebirths_m' => $total_livebirths_m,
                    'livebirth1014_m' => $livebirth1014_m,
                    'livebirth1519_m' => $livebirth1519_m,

                    'total_livebirths_f' => $total_livebirths_f,
                    'livebirth1014_f' => $livebirth1014_f,
                    'livebirth1519_f' => $livebirth1519_f,
                    
                    'brgylist' => $brgylist,
                ]);
            }
        }
        else {
            return abort(401);
        }
    }
    
    public function tbdotsHome() {
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();
        
        return view('efhsis.tbdots.home', [
            'brgy_list' => $brgy_list,
        ]);
    }

    public function tbdotsImport(Request $r) {
        Excel::import(new FhsisTbdotsImport(), $r->itis_file);

        return redirect()->route('fhsis_tbdots_home')
        ->with('msg', 'Excel file was uploaded successfully.')
        ->with('msgtype', 'success');
    }

    public function tbdotsDashboard() {
        $tb_array = [
            'A15.0 Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
            'A16.1 Tuberculosis of lung, bacteriological and histological examination not done',
            'A16.0 Tuberculosis of lung, bacteriologically and histologically negative',
            'A18 Tuberculosis of other organs',
        ];

        $final_arr = [];

        $brgy = request()->input('brgy');
        $month = request()->input('month');

        if(!($brgy) && !($month)) {
            return redirect()->back()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }

        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        foreach($tb_array as $tb) {
            $age1_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [1,4])
            ->where('sex', 'M');

            $age1_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [1,4])
            ->where('sex', 'F');
            
            $age2_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [5,9])
            ->where('sex', 'M');

            $age2_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [5,9])
            ->where('sex', 'F');

            $age3_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [10,14])
            ->where('sex', 'M');

            $age3_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [10,14])
            ->where('sex', 'F');

            $age4_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [15,19])
            ->where('sex', 'M');

            $age4_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [15,19])
            ->where('sex', 'F');

            $age5_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [20,24])
            ->where('sex', 'M');

            $age5_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [20,24])
            ->where('sex', 'F');

            $age6_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [25,29])
            ->where('sex', 'M');

            $age6_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [25,29])
            ->where('sex', 'F');

            $age7_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [30,34])
            ->where('sex', 'M');

            $age7_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [30,34])
            ->where('sex', 'F');

            $age8_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [35,39])
            ->where('sex', 'M');

            $age8_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [35,39])
            ->where('sex', 'F');

            $age9_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [40,44])
            ->where('sex', 'M');

            $age9_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [40,44])
            ->where('sex', 'F');

            $age10_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [45,49])
            ->where('sex', 'M');

            $age10_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [45,49])
            ->where('sex', 'F');

            $age11_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [50,54])
            ->where('sex', 'M');

            $age11_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [50,54])
            ->where('sex', 'F');

            $age12_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [55,59])
            ->where('sex', 'M');

            $age12_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [55,59])
            ->where('sex', 'F');

            $age13_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [60,64])
            ->where('sex', 'M');

            $age13_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [60,64])
            ->where('sex', 'F');

            $age14_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [65,69])
            ->where('sex', 'M');

            $age14_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->whereBetween('age', [65,69])
            ->where('sex', 'F');

            $age15_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->where('age', '>=', 70)
            ->where('sex', 'M');

            $age15_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereMonth('date_started_tx', $month)
            ->where('age', '>=', 70)
            ->where('sex', 'F');

            if($tb == 'A15.0 Tuberculosis of lung, confirmed by sputum microscopy with or without culture') {
                $age1_male = $age1_male->where('xpert_result', 'MTB Detected')->count();
                $age2_male = $age2_male->where('xpert_result', 'MTB Detected')->count();
                $age3_male = $age3_male->where('xpert_result', 'MTB Detected')->count();
                $age4_male = $age4_male->where('xpert_result', 'MTB Detected')->count();
                $age5_male = $age5_male->where('xpert_result', 'MTB Detected')->count();
                $age6_male = $age6_male->where('xpert_result', 'MTB Detected')->count();
                $age7_male = $age7_male->where('xpert_result', 'MTB Detected')->count();
                $age8_male = $age8_male->where('xpert_result', 'MTB Detected')->count();
                $age9_male = $age9_male->where('xpert_result', 'MTB Detected')->count();
                $age10_male = $age10_male->where('xpert_result', 'MTB Detected')->count();
                $age11_male = $age11_male->where('xpert_result', 'MTB Detected')->count();
                $age12_male = $age12_male->where('xpert_result', 'MTB Detected')->count();
                $age13_male = $age13_male->where('xpert_result', 'MTB Detected')->count();
                $age14_male = $age14_male->where('xpert_result', 'MTB Detected')->count();
                $age15_male = $age15_male->where('xpert_result', 'MTB Detected')->count();

                $age1_female = $age1_female->where('xpert_result', 'MTB Detected')->count();
                $age2_female = $age2_female->where('xpert_result', 'MTB Detected')->count();
                $age3_female = $age3_female->where('xpert_result', 'MTB Detected')->count();
                $age4_female = $age4_female->where('xpert_result', 'MTB Detected')->count();
                $age5_female = $age5_female->where('xpert_result', 'MTB Detected')->count();
                $age6_female = $age6_female->where('xpert_result', 'MTB Detected')->count();
                $age7_female = $age7_female->where('xpert_result', 'MTB Detected')->count();
                $age8_female = $age8_female->where('xpert_result', 'MTB Detected')->count();
                $age9_female = $age9_female->where('xpert_result', 'MTB Detected')->count();
                $age10_female = $age10_female->where('xpert_result', 'MTB Detected')->count();
                $age11_female = $age11_female->where('xpert_result', 'MTB Detected')->count();
                $age12_female = $age12_female->where('xpert_result', 'MTB Detected')->count();
                $age13_female = $age13_female->where('xpert_result', 'MTB Detected')->count();
                $age14_female = $age14_female->where('xpert_result', 'MTB Detected')->count();
                $age15_female = $age15_female->where('xpert_result', 'MTB Detected')->count();
            }
            else if($tb == 'A16.1 Tuberculosis of lung, bacteriological and histological examination not done') {
                $age1_male = $age1_male->where('xpert_result', 'Not Done')->count();
                $age2_male = $age2_male->where('xpert_result', 'Not Done')->count();
                $age3_male = $age3_male->where('xpert_result', 'Not Done')->count();
                $age4_male = $age4_male->where('xpert_result', 'Not Done')->count();
                $age5_male = $age5_male->where('xpert_result', 'Not Done')->count();
                $age6_male = $age6_male->where('xpert_result', 'Not Done')->count();
                $age7_male = $age7_male->where('xpert_result', 'Not Done')->count();
                $age8_male = $age8_male->where('xpert_result', 'Not Done')->count();
                $age9_male = $age9_male->where('xpert_result', 'Not Done')->count();
                $age10_male = $age10_male->where('xpert_result', 'Not Done')->count();
                $age11_male = $age11_male->where('xpert_result', 'Not Done')->count();
                $age12_male = $age12_male->where('xpert_result', 'Not Done')->count();
                $age13_male = $age13_male->where('xpert_result', 'Not Done')->count();
                $age14_male = $age14_male->where('xpert_result', 'Not Done')->count();
                $age15_male = $age15_male->where('xpert_result', 'Not Done')->count();

                $age1_female = $age1_female->where('xpert_result', 'Not Done')->count();
                $age2_female = $age2_female->where('xpert_result', 'Not Done')->count();
                $age3_female = $age3_female->where('xpert_result', 'Not Done')->count();
                $age4_female = $age4_female->where('xpert_result', 'Not Done')->count();
                $age5_female = $age5_female->where('xpert_result', 'Not Done')->count();
                $age6_female = $age6_female->where('xpert_result', 'Not Done')->count();
                $age7_female = $age7_female->where('xpert_result', 'Not Done')->count();
                $age8_female = $age8_female->where('xpert_result', 'Not Done')->count();
                $age9_female = $age9_female->where('xpert_result', 'Not Done')->count();
                $age10_female = $age10_female->where('xpert_result', 'Not Done')->count();
                $age11_female = $age11_female->where('xpert_result', 'Not Done')->count();
                $age12_female = $age12_female->where('xpert_result', 'Not Done')->count();
                $age13_female = $age13_female->where('xpert_result', 'Not Done')->count();
                $age14_female = $age14_female->where('xpert_result', 'Not Done')->count();
                $age15_female = $age15_female->where('xpert_result', 'Not Done')->count();
            }
            else if($tb == 'A16.0 Tuberculosis of lung, bacteriologically and histologically negative') {
                $age1_male = $age1_male->where('xpert_result', 'MTB Not Detected')->count();
                $age2_male = $age2_male->where('xpert_result', 'MTB Not Detected')->count();
                $age3_male = $age3_male->where('xpert_result', 'MTB Not Detected')->count();
                $age4_male = $age4_male->where('xpert_result', 'MTB Not Detected')->count();
                $age5_male = $age5_male->where('xpert_result', 'MTB Not Detected')->count();
                $age6_male = $age6_male->where('xpert_result', 'MTB Not Detected')->count();
                $age7_male = $age7_male->where('xpert_result', 'MTB Not Detected')->count();
                $age8_male = $age8_male->where('xpert_result', 'MTB Not Detected')->count();
                $age9_male = $age9_male->where('xpert_result', 'MTB Not Detected')->count();
                $age10_male = $age10_male->where('xpert_result', 'MTB Not Detected')->count();
                $age11_male = $age11_male->where('xpert_result', 'MTB Not Detected')->count();
                $age12_male = $age12_male->where('xpert_result', 'MTB Not Detected')->count();
                $age13_male = $age13_male->where('xpert_result', 'MTB Not Detected')->count();
                $age14_male = $age14_male->where('xpert_result', 'MTB Not Detected')->count();
                $age15_male = $age15_male->where('xpert_result', 'MTB Not Detected')->count();

                $age1_female = $age1_female->where('xpert_result', 'MTB Not Detected')->count();
                $age2_female = $age2_female->where('xpert_result', 'MTB Not Detected')->count();
                $age3_female = $age3_female->where('xpert_result', 'MTB Not Detected')->count();
                $age4_female = $age4_female->where('xpert_result', 'MTB Not Detected')->count();
                $age5_female = $age5_female->where('xpert_result', 'MTB Not Detected')->count();
                $age6_female = $age6_female->where('xpert_result', 'MTB Not Detected')->count();
                $age7_female = $age7_female->where('xpert_result', 'MTB Not Detected')->count();
                $age8_female = $age8_female->where('xpert_result', 'MTB Not Detected')->count();
                $age9_female = $age9_female->where('xpert_result', 'MTB Not Detected')->count();
                $age10_female = $age10_female->where('xpert_result', 'MTB Not Detected')->count();
                $age11_female = $age11_female->where('xpert_result', 'MTB Not Detected')->count();
                $age12_female = $age12_female->where('xpert_result', 'MTB Not Detected')->count();
                $age13_female = $age13_female->where('xpert_result', 'MTB Not Detected')->count();
                $age14_female = $age14_female->where('xpert_result', 'MTB Not Detected')->count();
                $age15_female = $age15_female->where('xpert_result', 'MTB Not Detected')->count();
            }
            else if($tb == 'A18 Tuberculosis of other organs') {
                $age1_male = $age1_male->where('ana_site', 'EP')->count();
                $age2_male = $age2_male->where('ana_site', 'EP')->count();
                $age3_male = $age3_male->where('ana_site', 'EP')->count();
                $age4_male = $age4_male->where('ana_site', 'EP')->count();
                $age5_male = $age5_male->where('ana_site', 'EP')->count();
                $age6_male = $age6_male->where('ana_site', 'EP')->count();
                $age7_male = $age7_male->where('ana_site', 'EP')->count();
                $age8_male = $age8_male->where('ana_site', 'EP')->count();
                $age9_male = $age9_male->where('ana_site', 'EP')->count();
                $age10_male = $age10_male->where('ana_site', 'EP')->count();
                $age11_male = $age11_male->where('ana_site', 'EP')->count();
                $age12_male = $age12_male->where('ana_site', 'EP')->count();
                $age13_male = $age13_male->where('ana_site', 'EP')->count();
                $age14_male = $age14_male->where('ana_site', 'EP')->count();
                $age15_male = $age15_male->where('ana_site', 'EP')->count();

                $age1_female = $age1_female->where('ana_site', 'EP')->count();
                $age2_female = $age2_female->where('ana_site', 'EP')->count();
                $age3_female = $age3_female->where('ana_site', 'EP')->count();
                $age4_female = $age4_female->where('ana_site', 'EP')->count();
                $age5_female = $age5_female->where('ana_site', 'EP')->count();
                $age6_female = $age6_female->where('ana_site', 'EP')->count();
                $age7_female = $age7_female->where('ana_site', 'EP')->count();
                $age8_female = $age8_female->where('ana_site', 'EP')->count();
                $age9_female = $age9_female->where('ana_site', 'EP')->count();
                $age10_female = $age10_female->where('ana_site', 'EP')->count();
                $age11_female = $age11_female->where('ana_site', 'EP')->count();
                $age12_female = $age12_female->where('ana_site', 'EP')->count();
                $age13_female = $age13_female->where('ana_site', 'EP')->count();
                $age14_female = $age14_female->where('ana_site', 'EP')->count();
                $age15_female = $age15_female->where('ana_site', 'EP')->count();
            }

            $agetotal_male = $age1_male + $age2_male + $age3_male + $age4_male + $age5_male + $age6_male + $age7_male + $age8_male + $age9_male + $age10_male + $age11_male + $age12_male + $age13_male + $age14_male + $age15_male;
            $agetotal_female = $age1_female + $age2_female + $age3_female + $age4_female + $age5_female + $age6_female + $age7_female + $age8_female + $age9_female + $age10_female + $age11_female + $age12_female + $age13_female + $age14_female + $age15_female;

            $final_arr[] = [
                'disease' => $tb,
                'age1_male' => $age1_male,
                'age2_male' => $age2_male,
                'age3_male' => $age3_male,
                'age4_male' => $age4_male,
                'age5_male' => $age5_male,
                'age6_male' => $age6_male,
                'age7_male' => $age7_male,
                'age8_male' => $age8_male,
                'age9_male' => $age9_male,
                'age10_male' => $age10_male,
                'age11_male' => $age11_male,
                'age12_male' => $age12_male,
                'age13_male' => $age13_male,
                'age14_male' => $age14_male,
                'age15_male' => $age15_male,
                'agetotal_male' => $agetotal_male,

                'age1_female' => $age1_female,
                'age2_female' => $age2_female,
                'age3_female' => $age3_female,
                'age4_female' => $age4_female,
                'age5_female' => $age5_female,
                'age6_female' => $age6_female,
                'age7_female' => $age7_female,
                'age8_female' => $age8_female,
                'age9_female' => $age9_female,
                'age10_female' => $age10_female,
                'age11_female' => $age11_female,
                'age12_female' => $age12_female,
                'age13_female' => $age13_female,
                'age14_female' => $age14_female,
                'age15_female' => $age15_female,
                'agetotal_female' => $agetotal_female,
            ];
        }

        return view('efhsis.tbdots.dashboard', [
            'final_array' => $final_arr,
            'brgy_list' => $brgy_list,
        ]);
    }

    public function morbMortReportMain() { //Report V2
        if(request()->input('startDate') && request()->input('endDate') && request()->input('brgy')) {
            $startDate = request()->input('startDate');
            $endDate = request()->input('endDate');
            $brgy = request()->input('brgy');

            $getYear = Carbon::parse($startDate)->startOfMonth()->format('Y');
            $getMonth = Carbon::parse($startDate)->startOfMonth()->format('m');
            
            $getYear_end = Carbon::parse($endDate)->startOfMonth()->format('Y');
            $getMonth_end = Carbon::parse($endDate)->startOfMonth()->format('m');

            $data_demographic = FhsisSystemDemographicProfile::where('city_id', 1)
            ->where('for_year', $getYear);

            if($brgy == 'ALL') {
                $data_demographic = $data_demographic->whereNull('brgy_id')
                ->where('remarks', 'YEARLY AUTOMATED')
                ->first();
            }
            else {
                $srBrgy = Brgy::where('city_id', 1)
                ->where('brgyNameFhsis', $brgy)
                ->first();

                if($srBrgy) {
                    $data_demographic = $data_demographic->where('brgy_id', $srBrgy->id)->first();
                }
            }

            if(!$data_demographic) {
                if($brgy == 'ALL') {
                    $all_brgy_count = FhsisBarangay::where('MUN_CODE', 'GENERAL TRIAS')->count();

                    //Create Demographic Data
                    $create_demographic = FhsisSystemDemographicProfile::create([
                        'encode_date' => date('Y-m-d'),
                        'city_id' => 1,
                        'brgy_id' => NULL,
                        'for_year' => $getYear,
                        'total_brgy' => $all_brgy_count,
                        'total_bhs' => 49,
                        'total_mainhc' => 1,
                        'total_cityhc' => 1,
                        'total_ruralhc' => 0,

                        'remarks' => 'YEARLY AUTOMATED',

                        'created_by' => Auth::id(),
                    ]);

                    $data_demographic = $create_demographic;
                }
                
                /*
                return redirect()->back()
                ->with('msg', 'Error: No Demographic Data found for Year '.$getYear.'. Please initialize first in the settings then try again.')
                ->with('msgtype', 'warning');
                */
            }

            //Calculate and Update Total Population if not existing
            if(is_null($data_demographic->total_population) && is_null($data_demographic->total_household)) {
                $pop_query = FhsisPopulation::where('MUN_CODE', 'GENERAL TRIAS')
                ->where('POP_YEAR', $getYear);

                if($brgy != 'ALL') {
                    $pop_query = $pop_query->where('BGY_CODE', $srBrgy->fhsis_bgycode);
                }

                $total_population = $pop_query->sum('POP_BGY');
                $total_household = $pop_query->sum('NO_HH');

                $data_demographic->total_population = $total_population;
                $data_demographic->total_household = $total_household;
            }

            //LB Query Always Update
            $lb_query = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereYear('DATE', $getYear);

            if($brgy != 'ALL') {
                $lb_query = $lb_query->where('BGY_CODE', $srBrgy->brgyNameFhsis);
            }

            $total_lb = $lb_query->sum('LB_M') + $lb_query->sum('LB_F');

            $data_demographic->total_livebirths = $total_lb;

            if($data_demographic->isDirty()) {
                $data_demographic->save();
            }

            $search_startDate = Carbon::parse($startDate)->startOfMonth()->format('Y-m-d');
            $search_endDate = Carbon::parse($endDate)->startOfMonth()->format('Y-m-d');

            $tot_deaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            $tot_deaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_infdeaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            $tot_infdeaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_matdeaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            $tot_matdeaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_und5deaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            $tot_und5deaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_fetaldeaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            $tot_fetaldeaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_neonataldeaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            $tot_neonataldeaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_earlyneonataldeaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            $tot_earlyneonataldeaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_perinataldeaths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            
            $tot_perinataldeaths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $tot_livebirths_m = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);
            
            $tot_livebirths_f = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $gtot_matorigdeaths = FhsisMortalityNatality::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            if($brgy == 'ALL') {
                $tot_deaths_m = $tot_deaths_m->sum('TOTDEATH_M');
                $tot_deaths_f = $tot_deaths_f->sum('TOTDEATH_F');

                $tot_infdeaths_m = $tot_infdeaths_m->sum('INFDEATH_M');
                $tot_infdeaths_f = $tot_infdeaths_f->sum('INFDEATH_F');

                $tot_matdeaths_m = $tot_matdeaths_m->sum('MATDEATH_M');
                $tot_matdeaths_f = $tot_matdeaths_f->sum('MATDEATH_F');

                $tot_und5deaths_m = $tot_und5deaths_m->sum('DEATHUND5_M');
                $tot_und5deaths_f = $tot_und5deaths_f->sum('DEATHUND5_F');

                $tot_fetaldeaths_m = $tot_fetaldeaths_m->sum('FD_M');
                $tot_fetaldeaths_f = $tot_fetaldeaths_f->sum('FD_F');

                $tot_neonataldeaths_m = $tot_neonataldeaths_m->sum('NEON_M');
                $tot_neonataldeaths_f = $tot_neonataldeaths_f->sum('NEON_F');

                $tot_earlyneonataldeaths_m = $tot_earlyneonataldeaths_m->sum('NEOTET_M');
                $tot_earlyneonataldeaths_f = $tot_earlyneonataldeaths_f->sum('NEOTET_F');

                $tot_perinataldeaths_m = $tot_perinataldeaths_m->sum('PRENATDEATH_M');
                $tot_perinataldeaths_f = $tot_perinataldeaths_f->sum('PRENATDEATH_F');

                $tot_livebirths_m = $tot_livebirths_m->sum('LB_M');
                $tot_livebirths_f = $tot_livebirths_f->sum('LB_F');

                $gtot_matorigdeaths = $gtot_matorigdeaths->sum('MATDEATHORIG_F');
            }
            else {
                $tot_deaths_m = $tot_deaths_m->where('BGY_CODE', $brgy)->sum('TOTDEATH_M');
                $tot_deaths_f = $tot_deaths_f->where('BGY_CODE', $brgy)->sum('TOTDEATH_F');
                
                $tot_infdeaths_m = $tot_infdeaths_m->where('BGY_CODE', $brgy)->sum('INFDEATH_M');
                $tot_infdeaths_f = $tot_infdeaths_f->where('BGY_CODE', $brgy)->sum('INFDEATH_F');

                $tot_matdeaths_m = $tot_matdeaths_m->where('BGY_CODE', $brgy)->sum('MATDEATH_M');
                $tot_matdeaths_f = $tot_matdeaths_f->where('BGY_CODE', $brgy)->sum('MATDEATH_F');

                $tot_und5deaths_m = $tot_und5deaths_m->where('BGY_CODE', $brgy)->sum('DEATHUND5_M');
                $tot_und5deaths_f = $tot_und5deaths_f->where('BGY_CODE', $brgy)->sum('DEATHUND5_F');

                $tot_fetaldeaths_m = $tot_fetaldeaths_m->where('BGY_CODE', $brgy)->sum('FD_M');
                $tot_fetaldeaths_f = $tot_fetaldeaths_f->where('BGY_CODE', $brgy)->sum('FD_F');

                $tot_neonataldeaths_m = $tot_neonataldeaths_m->where('BGY_CODE', $brgy)->sum('NEON_M');
                $tot_neonataldeaths_f = $tot_neonataldeaths_f->where('BGY_CODE', $brgy)->sum('NEON_F');

                $tot_earlyneonataldeaths_m = $tot_earlyneonataldeaths_m->where('BGY_CODE', $brgy)->sum('NEOTET_M');
                $tot_earlyneonataldeaths_f = $tot_earlyneonataldeaths_f->where('BGY_CODE', $brgy)->sum('NEOTET_F');

                $tot_perinataldeaths_m = $tot_perinataldeaths_m->where('BGY_CODE', $brgy)->sum('PRENATDEATH_M');
                $tot_perinataldeaths_f = $tot_perinataldeaths_f->where('BGY_CODE', $brgy)->sum('PRENATDEATH_F');

                $tot_livebirths_m = $tot_livebirths_m->where('BGY_CODE', $brgy)->sum('LB_M');
                $tot_livebirths_f = $tot_livebirths_f->where('BGY_CODE', $brgy)->sum('LB_F');

                $gtot_matorigdeaths = $gtot_matorigdeaths->where('BGY_CODE', $brgy)->sum('MATDEATHORIG_F');
            }

            $gtot_deaths = $tot_deaths_m + $tot_deaths_f;
            $gtot_infdeaths = $tot_infdeaths_m + $tot_infdeaths_f;
            $gtot_matdeaths = $tot_matdeaths_m + $tot_matdeaths_f;
            $gtot_und5deaths = $tot_und5deaths_m + $tot_und5deaths_f;
            $gtot_fetaldeaths = $tot_fetaldeaths_m + $tot_fetaldeaths_f;
            $gtot_neonataldeaths = $tot_neonataldeaths_m + $tot_neonataldeaths_f;
            $gtot_earlyneonataldeaths = $tot_earlyneonataldeaths_m + $tot_earlyneonataldeaths_f;
            $gtot_perinataldeaths = $tot_perinataldeaths_m + $tot_perinataldeaths_f;
            $gtot_livebirths = $tot_livebirths_m + $tot_livebirths_f;

            $mortality_rate = round($gtot_deaths / $data_demographic->total_population * 1000, 2);
            $imr = round($gtot_infdeaths / $gtot_livebirths * 1000, 2);
            $mmr = round($gtot_matdeaths / $gtot_livebirths * 100000, 2);
            $ufmr = round($gtot_und5deaths / $gtot_livebirths * 1000, 2);

            $neomort_rate = round($gtot_neonataldeaths / $gtot_livebirths * 1000, 2);
            $perimort_rate = round($gtot_perinataldeaths / ($gtot_fetaldeaths + $gtot_livebirths) * 1000, 2);

            //Leading cause of Mortality and Morbidity
            $mort_final_list = [];
            $morb_final_list = [];

            $mort_query = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            $morb_query = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
            ->whereBetween('DATE', [$search_startDate, $search_endDate]);

            if($brgy == 'ALL') {
                $mort_query = $mort_query->distinct()
                ->pluck('DISEASE');

                $morb_query = $morb_query->distinct()
                ->pluck('DISEASE');
            }
            else {
                $mort_query = $mort_query
                ->where('BGY_CODE', $brgy)
                ->distinct()
                ->pluck('DISEASE');

                $morb_query = $morb_query
                ->where('BGY_CODE', $brgy)
                ->distinct()
                ->pluck('DISEASE');
            }

            //FETCHING MORTALITY
            foreach($mort_query as $s) {
                $count = 0;
                $count_male = 0;
                $count_female = 0;

                $mort_query2 = FhsisMortBhs::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereBetween('DATE', [$search_startDate, $search_endDate])
                ->where('DISEASE', $s);

                if($brgy == 'ALL') {
                    $mort_query2 = $mort_query2->get();
                }
                else {
                    $mort_query2 = $mort_query2->where('BGY_CODE', $brgy)
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

                    $count_male += $t['1_4_M'] +
                    $t['5_9_M'] +
                    $t['10_14_M'] +
                    $t['15_19_M'] +
                    $t['20_24_M'] +
                    $t['25_29_M'] +
                    $t['30_34_M'] +
                    $t['35_39_M'] +
                    $t['40_44_M'] +
                    $t['45_49_M'] +
                    $t['50_54_M'] +
                    $t['55_59_M'] +
                    $t['60_64_M'] +
                    $t['65_69_M'] +
                    $t['70ABOVE_M'] +
                    $t['0_6DAYS_M'] +
                    $t['7_28DAYS_M'] +
                    $t['29DAYS_11MOS_M'];

                    $count_female += $t['1_4_F'] +
                    $t['5_9_F'] +
                    $t['10_14_F'] +
                    $t['15_19_F'] +
                    $t['20_24_F'] +
                    $t['25_29_F'] +
                    $t['30_34_F'] +
                    $t['35_39_F'] +
                    $t['40_44_F'] +
                    $t['45_49_F'] +
                    $t['50_54_F'] +
                    $t['55_59_F'] +
                    $t['60_64_F'] +
                    $t['65_69_F'] +
                    $t['70ABOVE_F'] +
                    $t['0_6DAYS_F'] +
                    $t['7_28DAYS_F'] +
                    $t['29DAYS_11MOS_F'];
                }

                array_push($mort_final_list, [
                    'disease' => $s,
                    'count_male' => $count_male,
                    'count_female' => $count_female,
                    'count' => $count,
                ]);
            }

            //FETCHING MORBIDITY
            foreach($morb_query as $s) {
                $count = 0;
                $count_male = 0;
                $count_female = 0;

                $morb_query2 = FhsisM2::where('MUN_CODE', 'GENERAL TRIAS')
                ->whereBetween('DATE', [$search_startDate, $search_endDate])
                ->where('DISEASE', $s);

                if($brgy == 'ALL') {
                    $morb_query2 = $morb_query2->get();
                }
                else {
                    $morb_query2 = $morb_query2->where('BGY_CODE', $brgy)
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

                    $count_male += $t['1_4_M'] +
                    $t['5_9_M'] +
                    $t['10_14_M'] +
                    $t['15_19_M'] +
                    $t['20_24_M'] +
                    $t['25_29_M'] +
                    $t['30_34_M'] +
                    $t['35_39_M'] +
                    $t['40_44_M'] +
                    $t['45_49_M'] +
                    $t['50_54_M'] +
                    $t['55_59_M'] +
                    $t['60_64_M'] +
                    $t['65_69_M'] +
                    $t['70ABOVE_M'] +
                    $t['0_6DAYS_M'] +
                    $t['7_28DAYS_M'] +
                    $t['29DAYS_11MOS_M'];

                    $count_female += $t['1_4_F'] +
                    $t['5_9_F'] +
                    $t['10_14_F'] +
                    $t['15_19_F'] +
                    $t['20_24_F'] +
                    $t['25_29_F'] +
                    $t['30_34_F'] +
                    $t['35_39_F'] +
                    $t['40_44_F'] +
                    $t['45_49_F'] +
                    $t['50_54_F'] +
                    $t['55_59_F'] +
                    $t['60_64_F'] +
                    $t['65_69_F'] +
                    $t['70ABOVE_F'] +
                    $t['0_6DAYS_F'] +
                    $t['7_28DAYS_F'] +
                    $t['29DAYS_11MOS_F'];
                }

                array_push($morb_final_list, [
                    'disease' => $s,
                    'count' => $count,
                    'count_male' => $count_male,
                    'count_female' => $count_female,
                ]);
            }

            //Donut1
            $donut1_titles = ['Under Five Deaths', 'Infant Deaths'];
            $donut1_values = [$gtot_und5deaths, $gtot_infdeaths];

            $donut2_titles = ['Infant Deaths', 'ND, END, FD'];
            $donut2_values = [$gtot_infdeaths, ($gtot_neonataldeaths + $gtot_earlyneonataldeaths + $gtot_fetaldeaths)];

            //Livebirths outside
            $gtot_livebirths_outside = LiveBirth::whereBetween('year', [$getYear, $getYear_end])
            ->whereBetween('month', [$getMonth, $getMonth_end])
            ->where('address_muncity_text', '!=', 'GENERAL TRIAS')
            ->count();

            return view('efhsis.reportv2', [
                'brgy' => $brgy,
                'data_demographic' => $data_demographic,

                'gtot_deaths' => $gtot_deaths,
                'gtot_infdeaths' => $gtot_infdeaths,
                'gtot_matdeaths' => $gtot_matdeaths,
                'gtot_und5deaths' => $gtot_und5deaths,
                'gtot_fetaldeaths' => $gtot_fetaldeaths,
                'gtot_neonataldeaths' => $gtot_neonataldeaths,
                'gtot_earlyneonataldeaths' => $gtot_earlyneonataldeaths,
                'gtot_matorigdeaths' => $gtot_matorigdeaths,
                'gtot_perinataldeaths' => $gtot_perinataldeaths,
                'gtot_livebirths' => $gtot_livebirths,

                'mortality_rate' => $mortality_rate,
                'imr' => $imr,
                'mmr' => $mmr,
                'ufmr' => $ufmr,
                'neomort_rate' => $neomort_rate,
                'perimort_rate' => $perimort_rate,

                'morb_final_list' => $morb_final_list,
                'mort_final_list' => $mort_final_list,

                'donut1_titles' => $donut1_titles,
                'donut1_values' => $donut1_values,

                'donut2_titles' => $donut2_titles,
                'donut2_values' => $donut2_values,

                'gtot_livebirths_outside' => $gtot_livebirths_outside,
            ]);
        }
    }

    public function icdSearcher() {
        if(request()->input('q')) {
            $q = request()->input('q');

            $list = Icd10Code::where('ICD10_CODE', 'LIKE', '%'.$q.'%')
            ->orWhere('ICD10_DESC', 'LIKE', '%'.$q.'%')
            ->orWhere('ICD10_CAT', 'LIKE', '%'.$q.'%')
            ->paginate(10);
        }
        else {
            $list = NULL;
        }

        return view('efhsis.icd10_searcher', [
            'list' => $list,
        ]);
    }

    public function deathCertEncode() {
        if(!request()->input('year') && !request()->input('month')) {
            return abort(401);
        }

        if(request()->input('year') == date('Y')) {
            if(request()->input('month') > date('n')) {
                return redirect()->route('fhsis_home')
                ->with('msg', 'Error: Current Month is just '.date('F').' '.date('Y'))
                ->with('msgtype', 'warning');
            }
        }
        
        return view('efhsis.deathcert_encode');
    }

    public function deathCertStore(Request $r) {
        if($r->if_fetaldeath == 'Y') {
            $lname = (!is_null($r->lname)) ? mb_strtoupper($r->lname) : NULL;
            $fname = (!is_null($r->fname)) ? mb_strtoupper($r->fname) : NULL;
            $mname = (!is_null($r->mname)) ? mb_strtoupper($r->mname) : NULL;
            $bdate = NULL;
            //$fetald_dateofdelivery = Carbon::create($r->input_year2, $r->input_month2, $r->input_day2, 0, 0, 0)->format('Y-m-d');
            $fetald_dateofdelivery = Carbon::parse($r->bdate)->format('Y-m-d');
            $date_died = NULL;
            $age_death_years = NULL;
            $age_death_months = NULL;
            $age_death_days = NULL;

            if(!is_null($lname) && !is_null($fname)) {
                $exist_check = DeathCertificate::where('lname', $lname)
                ->where('fname', $fname)
                ->whereDate('fetald_dateofdelivery', $fetald_dateofdelivery);
                
                if(!is_null($mname) && $mname != 'N/A') {
                    $exist_check = $exist_check->where('mname', $mname)->first();
                }
                else {
                    $exist_check = $exist_check->first();
                }

                if($exist_check) {
                    return redirect()->back()
                    ->with('msg', 'Error: Fetal Death data already exists in the server. Please double check the name of Fetus, and Date of Delivery.')
                    ->with('msgtype', 'warning');
                }
            }

            $fetald_mother_lname = mb_strtoupper($r->fetald_mother_lname);
            $fetald_mother_fname = mb_strtoupper($r->fetald_mother_fname);
            $fetald_mother_mname = (!is_null($r->fetald_mother_mname)) ? mb_strtoupper($r->fetald_mother_mname) : NULL;

            $exist_check = DeathCertificate::where('fetald_mother_lname', $fetald_mother_lname)
            ->where('fetald_mother_fname', $fetald_mother_fname)
            ->whereDate('fetald_dateofdelivery', $fetald_dateofdelivery)
            ->where('fetald_birthorder', $r->fetald_birthorder);
            
            if(!is_null($fetald_mother_mname)) {
                $exist_check = $exist_check->where('fetald_mother_mname', $fetald_mother_mname)->first();
            }
            else {
                $exist_check = $exist_check->first();
            }

            if($exist_check) {
                return redirect()->back()
                ->with('msg', 'Error: Fetal Death data already exists in the server. Please double check the Mother Name, Date of Delivery, and Birth Order.')
                ->with('msgtype', 'warning');
            }

            $maternal_condition = 'N/A';
        }
        else {
            $lname = mb_strtoupper($r->lname);
            $fname = mb_strtoupper($r->fname);
            $mname = (!is_null($r->mname)) ? mb_strtoupper($r->mname) : NULL;
            //$suffix = (!is_null($r->suffix)) ? mb_strtoupper($r->suffix) : NULL;
            //$bdate = Carbon::create($r->input_year2, $r->input_month2, $r->input_day2, 0, 0, 0)->format('Y-m-d');
            $bdate = Carbon::parse($r->bdate)->format('Y-m-d');
            $fetald_dateofdelivery = NULL;
            $date_died = Carbon::create($r->input_year, $r->input_month, $r->input_day, 0, 0, 0)->format('Y-m-d');

            //get age in years, month, days
            $birthdate = Carbon::parse($bdate);
            $currentDate = Carbon::parse($date_died);

            $age_death_years = $birthdate->diffInYears($currentDate);
            $age_death_months = $birthdate->diffInMonths($currentDate);
            $age_death_days = $birthdate->diffInDays($currentDate);

            $fetald_mother_lname = NULL;
            $fetald_mother_fname = NULL;
            $fetald_mother_mname = NULL;

            $exist_check = DeathCertificate::where('lname', $lname)
            ->where('fname', $fname)
            ->whereDate('bdate', $bdate);

            if(!is_null($mname) && $mname != 'N/A') {
                $exist_check = $exist_check->where('mname', $mname)->first();
            }
            else {
                $exist_check = $exist_check->first();
            }

            if($age_death_years >= 15 && $age_death_years <= 49) {
                if($r->gender == 'FEMALE') {
                    $maternal_condition = $r->maternal_condition;
                }
                else {
                    $maternal_condition = 'N/A';
                }
            }
            else {
                $maternal_condition = 'N/A';
            }
        }

        if($mname == 'N/A') {
            $mname = NULL;
        }

        if(!$exist_check) {
            $c = DeathCertificate::create([
                'if_fetaldeath' => ($r->if_fetaldeath == 'Y') ? 1 : 0,
                'lname' => $lname,
                'fname' => $fname,
                'mname' => $mname,
                //'suffix',
                'bdate' => $bdate,
                'gender' => $r->gender,
                'date_died' => $date_died,
                'age_death_years' => $age_death_years,
                'age_death_months' => $age_death_months,
                'age_death_days' => $age_death_days,
                'fetald_dateofdelivery' => $fetald_dateofdelivery,
                'fetald_typeofdelivery' => ($r->if_fetaldeath == 'Y') ? $r->fetald_typeofdelivery : NULL,
                'fetald_ifmultipledeliveries_fetuswas' => ($r->if_fetaldeath == 'Y' && $r->fetald_typeofdelivery != 'SINGLE') ? $r->fetald_ifmultipledeliveries_fetuswas : NULL,
                'fetald_methodofdelivery' => ($r->if_fetaldeath == 'Y') ? $r->fetald_methodofdelivery : NULL,
                //'fetald_methodofdelivery_others'  => ($r->if_fetaldeath == 'Y') ? $r->fetald_typeofdelivery : NULL,
                'fetald_birthorder' => ($r->if_fetaldeath == 'Y') ? $r->fetald_birthorder : NULL,
                //'fetald_fetusweight',
                //'fetald_fetusdiedwhen',
                //'fetald_lenghthpregnancyweeks',
                'fetald_mother_lname' => $fetald_mother_lname,
                'fetald_mother_fname' => $fetald_mother_fname,
                'fetald_mother_mname' => $fetald_mother_mname,
                'name_placeofdeath' => mb_strtoupper($r->name_placeofdeath),
                'pod_address_region_code' => $r->pod_address_region_code,
                'pod_address_region_text' => $r->pod_address_region_text,
                'pod_address_province_code' => $r->pod_address_province_code,
                'pod_address_province_text' => $r->pod_address_province_text,
                'pod_address_muncity_code' => $r->pod_address_muncity_code,
                'pod_address_muncity_text' => $r->pod_address_muncity_text,
                'pod_address_brgy_code' => $r->pod_address_brgy_text,
                'pod_address_brgy_text' => $r->pod_address_brgy_text,
                //'pod_address_street',
                //'pod_address_houseno',
                'address_region_code' => $r->address_region_code,
                'address_region_text' => $r->address_region_text,
                'address_province_code' => $r->address_province_code,
                'address_province_text' => $r->address_province_text,
                'address_muncity_code' => $r->address_muncity_code,
                'address_muncity_text' => $r->address_muncity_text,
                'address_brgy_code' => $r->address_brgy_text,
                'address_brgy_text' => $r->address_brgy_text,
                //'address_street',
                //'address_houseno',
                'maternal_condition' => $maternal_condition,
                'immediate_cause' => $r->immediate_cause,
                //'antecedent_cause',
                //'underlying_cause',
                'created_by' => Auth::id(),
            ]);

            return redirect()->back()
            ->with('msg', 'Death Certificate was successfully encoded: ID #'.$c->id.'.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Death Certificate data already exists in the server. Please double check the Name and Birthdate.')
            ->with('msgtype', 'warning');
        }
    }

    public static function generateMortBhs($start, $end, $brgy, $brgyNameFhsis, $submit) {
        //Mortality Total
        $early_neonatal_deaths_finaltotal_m = 0;
        $early_neonatal_deaths_finaltotal_f = 0;

        $fetal_deaths_finaltotal_m = 0;
        $fetal_deaths_finaltotal_f = 0;

        $neonatal_deaths_finaltotal_m = 0;
        $neonatal_deaths_finaltotal_f = 0;

        $infant_deaths_finaltotal_m = 0;
        $infant_deaths_finaltotal_f = 0;

        $uf_deaths_finaltotal_m = 0;
        $uf_deaths_finaltotal_f = 0;

        $mat_deaths_finaltotal = 0;
        $ormat_deaths_finaltotal = 0;

        $total_deaths_m = 0;
        $total_deaths_f = 0;

        //Group ICD10 Codes

        //Check if exists
        $gcheck = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
        ->where('pod_address_brgy_text', $brgy)
        ->first();

        $final_arr = [];
        
        if($gcheck) {
            $list_group = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->where('pod_address_brgy_text', $brgy)
            ->groupBy('immediate_cause')
            ->pluck('immediate_cause')
            ->toArray();

            $mat_deaths_finaltotal += DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->where('pod_address_brgy_text', $brgy)
            ->where('gender', 'FEMALE')
            ->whereIn('maternal_condition', ['PREGNANT, IN LABOUR', 'LESS THAN 42 DAYS AFTER DELIVERY'])
            ->whereBetween('age_death_years', [15,49])
            ->count();

            foreach($list_group as $l) {
                //0-6 Days
                $age1_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('if_fetaldeath', 0)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_days', [0,6])
                ->count();
    
                $early_neonatal_deaths_finaltotal_m += $age1_m;
                $infant_deaths_finaltotal_m += $age1_m;
                $uf_deaths_finaltotal_m += $age1_m;
    
                $age1_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('if_fetaldeath', 0)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_days', [0,6])
                ->count();
    
                $early_neonatal_deaths_finaltotal_f += $age1_f;
                $infant_deaths_finaltotal_f += $age1_f;
                $uf_deaths_finaltotal_f += $age1_f;
    
                //Search Fetal Deaths
                $fet_death_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('if_fetaldeath', 1)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->count();
    
                $fet_death_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('if_fetaldeath', 1)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->count();
    
                $fetal_deaths_finaltotal_m += $fet_death_m;
                $fetal_deaths_finaltotal_f += $fet_death_f;
    
                //7-28 Days
                $age2_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_days', [7,28])
                ->count();
    
                $neonatal_deaths_finaltotal_m += $age2_m;
                $infant_deaths_finaltotal_m += $age2_m;
                $uf_deaths_finaltotal_m += $age2_m;
    
                $age2_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_days', [7,28])
                ->count();
    
                $neonatal_deaths_finaltotal_f += $age2_f;
                $infant_deaths_finaltotal_f += $age2_f;
                $uf_deaths_finaltotal_f += $age2_f;
    
                //29 Days - 11 Months
                $age3_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->where('age_death_days', '>=', 29)
                ->where('age_death_months', '<=', 11)
                ->count();
    
                $age3_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->where('age_death_days', '>=', 29)
                ->where('age_death_months', '<=', 11)
                ->count();
                
                //1-4
                $age4_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [1, 4])
                ->count();
    
                $age4_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [1, 4])
                ->count();
    
                //5-9
                $age5_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [5, 9])
                ->count();
    
                $age5_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [5, 9])
                ->count();
    
                //10-14
                $age6_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [10,14])
                ->count();
    
                $age6_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [10,14])
                ->count();
    
                //15-19
                $age7_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [15,19])
                ->count();
    
                $age7_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [15,19])
                ->count();
                //ss
    
                //20-24
                $age8_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [20,24])
                ->count();
    
                $age8_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [20,24])
                ->count();
                
                //25-29
                $age9_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [25,29])
                ->count();
    
                $age9_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [25,29])
                ->count();
    
                //30-34
                $age10_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [30,34])
                ->count();
    
                $age10_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [30,34])
                ->count();
    
                //35-39
                $age11_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [35,39])
                ->count();
    
                $age11_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [35,39])
                ->count();
    
                //40-44
                $age12_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [40,44])
                ->count();
    
                $age12_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [40,44])
                ->count();
    
                //45-49
                $age13_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [45,49])
                ->count();
    
                $age13_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [45,49])
                ->count();
    
                //50-54
                $age14_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [50,54])
                ->count();
    
                $age14_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [50,54])
                ->count();
    
                //55-59
                $age15_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [55,59])
                ->count();
    
                $age15_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [55,59])
                ->count();
    
                //60-64
                $age16_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [60,64])
                ->count();
    
                $age16_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [60,64])
                ->count();
    
                //65-69
                $age17_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [65,69])
                ->count();
    
                $age17_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->whereBetween('age_death_years', [65,69])
                ->count();
    
                //70 and above
                $age18_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'MALE')
                ->where('immediate_cause', $l)
                ->where('age_death_years', '>=', 70)
                ->count();
    
                $age18_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where('pod_address_brgy_text', $brgy)
                ->where('gender', 'FEMALE')
                ->where('immediate_cause', $l)
                ->where('age_death_years', '>=', 70)
                ->count();

                if($submit == 'download') {
                    /*
                    //Under 1
                    $under1_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->where('pod_address_brgy_text', $brgy)
                    ->where('gender', 'MALE')
                    ->where('immediate_cause', $l)
                    ->where('age_death_years', '<', 1)
                    ->count();
        
                    $under1_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->where('pod_address_brgy_text', $brgy)
                    ->where('gender', 'FEMALE')
                    ->where('immediate_cause', $l)
                    ->where('age_death_years', '<', 1)
                    ->count();


                    //65 Above
                    $above65_m = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->where('pod_address_brgy_text', $brgy)
                    ->where('gender', 'MALE')
                    ->where('immediate_cause', $l)
                    ->where('age_death_years', '>=', 65)
                    ->count();
        
                    $above65_f = DeathCertificate::whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->where('pod_address_brgy_text', $brgy)
                    ->where('gender', 'FEMALE')
                    ->where('immediate_cause', $l)
                    ->where('age_death_years', '>=', 65)
                    ->count();
                    */

                    //Under 1
                    $under1_m = 0;
                    $under1_f = 0;

                    //65 Above
                    $above65_m = 0;
                    $above65_f = 0;
                }
    
                $total_m = $age1_m +
                    $age2_m +
                    $age3_m +
                    $age4_m +
                    $age5_m +
                    $age6_m +
                    $age7_m +
                    $age8_m +
                    $age9_m +
                    $age10_m +
                    $age11_m +
                    $age12_m +
                    $age13_m +
                    $age14_m +
                    $age15_m +
                    $age16_m +
                    $age17_m +
                    $age18_m;
    
                $total_f = $age1_f +
                    $age2_f +
                    $age3_f +
                    $age4_f +
                    $age5_f +
                    $age6_f +
                    $age7_f +
                    $age8_f +
                    $age9_f +
                    $age10_f +
                    $age11_f +
                    $age12_f +
                    $age13_f +
                    $age14_f +
                    $age15_f +
                    $age16_f +
                    $age17_f +
                    $age18_f;
    
                $total_deaths_m += $total_m;
                $total_deaths_f += $total_f;
                
                if($submit == 'download') {
                    $final_arr[] = [
                        'REG_CODE' => 'REGION IV-A (CALABARZON)',
                        'PROV_CODE' => 'CAVITE',
                        'MUN_CODE' => 'GENERAL TRIAS',
                        'BGY_CODE' => $brgyNameFhsis,
                        'DATE' => $start->format('m/d/y'),
                        'DISEASE' => $l,
                        'UNDER1_M' => $under1_m,
                        'UNDER1_F' => $under1_f,
                        '1_4_M' => $age4_m,
                        '1_4_F' => $age4_f,
                        '5_9_M' => $age5_m,
                        '5_9_F' => $age5_f,
                        '10_14_M' => $age6_m,
                        '10_14_F' => $age6_f,
                        '15_19_M' => $age7_m,
                        '15_19_F' => $age7_f,
                        '20_24_M' => $age8_m,
                        '20_24_F' => $age8_f,
                        '25_29_M' => $age9_m,
                        '25_29_F' => $age9_f,
                        '30_34_M' => $age10_m,
                        '30_34_F' => $age10_f,
                        '35_39_M' => $age11_m,
                        '35_39_F' => $age11_f,
                        '40_44_M' => $age12_m,
                        '40_44_F' => $age12_f,
                        '45_49_M' => $age13_m,
                        '45_49_F' => $age13_f,
                        '50_54_M' => $age14_m,
                        '50_54_F' => $age14_f,
                        '55_59_M' => $age15_m,
                        '55_59_F' => $age15_f,
                        '60_64_M' => $age16_m,
                        '60_64_F' => $age16_f,
                        '65ABOVE_M' => $above65_m,
                        '65ABOVE_F' => $above65_f,
                        '65_69_M' => $age17_m,
                        '65_69_F' => $age17_f,
                        '70ABOVE_M' => $age18_m,
                        '70ABOVE_F' => $age18_f,
                        '0_6DAYS_M' => $age1_m,
                        '0_6DAYS_F' => $age1_f,
                        '7_28DAYS_M' => $age2_m,
                        '7_28DAYS_F' => $age2_f,
                        '29DAYS_11MOS_M' => $age3_m,
                        '29DAYS_11MOS_F' => $age3_f,
                    ];
                }
                else {
                    $final_arr[] = [
                        'disease' => $l,
                        'age1_m' => $age1_m,
                        'age1_f' => $age1_f,
                        'age2_m' => $age2_m,
                        'age2_f' => $age2_f,
                        'age3_m' => $age3_m,
                        'age3_f' => $age3_f,
                        'age4_m' => $age4_m,
                        'age4_f' => $age4_f,
                        'age5_m' => $age5_m,
                        'age5_f' => $age5_f,
                        'age6_m' => $age6_m,
                        'age6_f' => $age6_f,
                        'age7_m' => $age7_m,
                        'age7_f' => $age7_f,
                        'age8_m' => $age8_m,
                        'age8_f' => $age8_f,
                        'age9_m' => $age9_m,
                        'age9_f' => $age9_f,
                        'age10_m' => $age10_m,
                        'age10_f' => $age10_f,
                        'age11_m' => $age11_m,
                        'age11_f' => $age11_f,
                        'age12_m' => $age12_m,
                        'age12_f' => $age12_f,
                        'age13_m' => $age13_m,
                        'age13_f' => $age13_f,
                        'age14_m' => $age14_m,
                        'age14_f' => $age14_f,
                        'age15_m' => $age15_m,
                        'age15_f' => $age15_f,
                        'age16_m' => $age16_m,
                        'age16_f' => $age16_f,
                        'age17_m' => $age17_m,
                        'age17_f' => $age17_f,
                        'age18_m' => $age18_m,
                        'age18_f' => $age18_f,
                        'total_m' => $total_m,
                        'total_f' => $total_f,
                    ];
                }
            }

            //return $final_arr
        }
        else {
            if($submit == 'download') {
                $final_arr[] = [
                    'REG_CODE' => 'REGION IV-A (CALABARZON)',
                    'PROV_CODE' => 'CAVITE',
                    'MUN_CODE' => 'GENERAL TRIAS',
                    'BGY_CODE' => $brgyNameFhsis,
                    'DATE' => $start->format('m/d/y'),
                    'DISEASE' => '',
                    'UNDER1_M' => 0,
                    'UNDER1_F' => 0,
                    '1_4_M' => 0,
                    '1_4_F' => 0,
                    '5_9_M' => 0,
                    '5_9_F' => 0,
                    '10_14_M' => 0,
                    '10_14_F' => 0,
                    '15_19_M' => 0,
                    '15_19_F' => 0,
                    '20_24_M' => 0,
                    '20_24_F' => 0,
                    '25_29_M' => 0,
                    '25_29_F' => 0,
                    '30_34_M' => 0,
                    '30_34_F' => 0,
                    '35_39_M' => 0,
                    '35_39_F' => 0,
                    '40_44_M' => 0,
                    '40_44_F' => 0,
                    '45_49_M' => 0,
                    '45_49_F' => 0,
                    '50_54_M' => 0,
                    '50_54_F' => 0,
                    '55_59_M' => 0,
                    '55_59_F' => 0,
                    '60_64_M' => 0,
                    '60_64_F' => 0,
                    '65ABOVE_M' => 0,
                    '65ABOVE_F' => 0,
                    '65_69_M' => 0,
                    '65_69_F' => 0,
                    '70ABOVE_M' => 0,
                    '70ABOVE_F' => 0,
                    '0_6DAYS_M' => 0,
                    '0_6DAYS_F' => 0,
                    '7_28DAYS_M' => 0,
                    '7_28DAYS_F' => 0,
                    '29DAYS_11MOS_M' => 0,
                    '29DAYS_11MOS_F' => 0,
                ];
            }
            else {
                //return NULL;
            }
        }

        //Get Livebirths
        $total_livebirths_m = LiveBirth::where('year', $start->format('Y'))
        ->where('month', $start->format('n'))
        ->where('address_brgy_text', $brgy)
        ->where('sex', 'M')
        ->count();

        $total_livebirths_f = LiveBirth::where('year', $start->format('Y'))
        ->where('month', $start->format('n'))
        ->where('address_brgy_text', $brgy)
        ->where('sex', 'F')
        ->count();

        $livebirth1014_m = LiveBirth::where('year', $start->format('Y'))
        ->where('month', $start->format('n'))
        ->whereBetween('mother_age', [10,14])
        ->where('address_brgy_text', $brgy)
        ->where('sex', 'M')
        ->count();

        $livebirth1014_f = LiveBirth::where('year', $start->format('Y'))
        ->where('month', $start->format('n'))
        ->whereBetween('mother_age', [10,14])
        ->where('address_brgy_text', $brgy)
        ->where('sex', 'F')
        ->count();

        $livebirth1519_m = LiveBirth::where('year', $start->format('Y'))
        ->where('month', $start->format('n'))
        ->whereBetween('mother_age', [15,19])
        ->where('address_brgy_text', $brgy)
        ->where('sex', 'M')
        ->count();

        $livebirth1519_f = LiveBirth::where('year', $start->format('Y'))
        ->where('month', $start->format('n'))
        ->whereBetween('mother_age', [15,19])
        ->where('address_brgy_text', $brgy)
        ->where('sex', 'F')
        ->count();

        //Return Result Array
        if($submit == 'download') {
            return [
                'total' => [
                    'REG_CODE' => 'REGION IV-A (CALABARZON)',
                    'PROV_CODE' => 'CAVITE',
                    'MUN_CODE' => 'GENERAL TRIAS',
                    'BGY_CODE' => $brgyNameFhsis,
                    'DATE' => $start->format('m/d/y'),
                    'TOTDEATH_M' => $total_deaths_m,
                    'TOTDEATH_F' => $total_deaths_f,
                    'INFDEATH_M' => $infant_deaths_finaltotal_m,
                    'INFDEATH_F' => $infant_deaths_finaltotal_m,
                    'MATDEATH_M' => 0,
                    'MATDEATH_F' => $mat_deaths_finaltotal,
                    'NEOTET_M' => $early_neonatal_deaths_finaltotal_m,
                    'NEOTET_F' => $early_neonatal_deaths_finaltotal_f,
                    'PRENATDEATH_M' => 0,
                    'PRENATDEATH_F' => 0,
                    'DEATHUND5_M' => $uf_deaths_finaltotal_m,
                    'DEATHUND5_F' => $uf_deaths_finaltotal_f,
                    'FD_M' => $fetal_deaths_finaltotal_m,
                    'FD_F' => $fetal_deaths_finaltotal_f,
                    'NEON_M' => $neonatal_deaths_finaltotal_m,
                    'NEON_F' => $neonatal_deaths_finaltotal_f,
                    'LB_M' => $total_livebirths_m,
                    'LB_F' => $total_livebirths_f,
                    'LB_1519_M' => $livebirth1519_m,
                    'LB_1519_F' => $livebirth1519_f,
                    'MATDEATHORIG_F' => $ormat_deaths_finaltotal,
                    'LB_1014_M' => $livebirth1014_m,
                    'LB_1014_F' => $livebirth1014_f,
                ],
                'diseases' => $final_arr,
            ];
        }
        else {
            return [
                'total' => [
                    'early_neonatal_deaths_finaltotal_m' => $early_neonatal_deaths_finaltotal_m,
                    'early_neonatal_deaths_finaltotal_f' => $early_neonatal_deaths_finaltotal_f,
                    'fetal_deaths_finaltotal_m' => $fetal_deaths_finaltotal_m,
                    'fetal_deaths_finaltotal_f' => $fetal_deaths_finaltotal_f,
                    'neonatal_deaths_finaltotal_m' => $neonatal_deaths_finaltotal_m,
                    'neonatal_deaths_finaltotal_f' => $neonatal_deaths_finaltotal_f,
                    'infant_deaths_finaltotal_m' => $infant_deaths_finaltotal_m,
                    'infant_deaths_finaltotal_f' => $infant_deaths_finaltotal_f,
                    'uf_deaths_finaltotal_m' => $uf_deaths_finaltotal_m,
                    'uf_deaths_finaltotal_f' => $uf_deaths_finaltotal_f,
                    'mat_deaths_finaltotal' => $mat_deaths_finaltotal,
                    'ormat_deaths_finaltotal' => $ormat_deaths_finaltotal,
                    'total_deaths_m' => $total_deaths_m,
                    'total_deaths_f' => $total_deaths_f,
                ],
                'diseases' => $final_arr,
            ];
        }
    }

    public function deathCertReport() {
        if(!request()->input('year') && !request()->input('month') && !request()->input('brgy') && !request()->input('submit')) {
            return abort(401);
        }

        $year = request()->input('year');
        $brgy = request()->input('brgy');
        $month = request()->input('month');
        $submit = request()->input('submit');

        $start = Carbon::createFromDate($year, $month, 01)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 01)->endOfMonth();

        if($submit == 'download' && $brgy == 'ALL') {
            $brgy_list = Brgy::where('city_id', 1)
            ->where('displayInList', 1)
            ->orderBy('brgyName', 'ASC')
            ->get();

            $farr_final = [];

            foreach($brgy_list as $b) {
                $farr = FhsisController::generateMortBhs($start, $end, $b->brgyName, $b->brgyNameFhsis, $submit);

                if(!is_null($farr)) {
                    $farr_final[] = [
                        'brgy' => $b->brgyName,
                        'total' => $farr['total'],
                        'diseases_list' => $farr['diseases'],
                    ];
                }
            }

            //dd($farr_final);
            /*
            
            $totalData = [
                ['Column1' => 'Data11', 'Column2' => 'Data12'],
                ['Column1' => 'Data21', 'Column2' => 'Data22'],
            ];
    
            // Data for the 'List' sheet
            $listData = [
                ['ColumnA' => 'DataA1', 'ColumnB' => 'DataB1', 'ColumnC' => 'DataC1'],
                ['ColumnA' => 'DataA2', 'ColumnB' => 'DataB2', 'ColumnC' => 'DataC2'],
            ];
            */

            $sheets = new SheetCollection([
                'MORT BHS' => array_column($farr_final, 'total'),
                'MORTALITY' => array_merge(...array_column($farr_final, 'diseases_list')),
            ]);

            $header_style = (new Style())->setFontBold();
            $rows_style = (new Style())->setShouldWrapText();

            return $exp = (new FastExcel($sheets))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->download('FHSIS_IMPORT_MORTALITY_'.$start->format('M_Y').'.xlsx');
        }
        else {
            $b = Brgy::where('city_id', 1)
            ->where('brgyName', $brgy)
            ->first();
            
            $farr = FhsisController::generateMortBhs($start, $end, $b->brgyName, $b->brgyNameFhsis, $submit);

            return view('efhsis.deathcert_report', [
                'final_arr' => $farr,
            ]);
        }
    }
}
