<?php

namespace App\Http\Controllers;

use App\Models\AbtcBakunaRecords;
use PDO;
use Carbon\Carbon;
use App\Models\Brgy;
use Illuminate\Http\Request;

class FhsisController extends Controller
{
    public function home() {
        return view('efhsis.home');
    }

    public function report() {
        // Set up a new PDO connection using the ODBC driver
        $mdb_location = storage_path('app/efhsis/eFHSIS_be.mdb');
        
        /*
        $uname = explode(" ",php_uname());
        print_r($uname);
        $os = $uname[0];
        echo "<br>";
        echo $os;
        switch ($os){
        case 'Windows':
            
            break;
        case 'Linux':
            $driver = 'MDBTools';
            break;
        default:
            exit("Don't know about this OS");
        }
        */

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

        // Query the database
        $mort_final_list = [];
        $morb_final_list = [];

        $bgy_nm_list = [];
        $bgy_mone_list = [];

        $bgy_list = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        if(request()->input('type') && request()->input('year')) {
            $year = request()->input('year');
            $base_year = request()->input('year');
            $type = request()->input('type');

            if($type == 'quarterly') {
                $q = request()->input('quarter');

                if($q == 1) {
                    $from = '#'.date('m/d/y', strtotime('01/01/'.request()->input('year'))).'#';
                    $to = '#'.date('m/d/y', strtotime('03/01/'.request()->input('year'))).'#';
                }
                else if($q == 2) {
                    $from = '#'.date('m/d/y', strtotime('04/01/'.request()->input('year'))).'#';
                    $to = '#'.date('m/d/y', strtotime('06/01/'.request()->input('year'))).'#';
                }
                else if($q == 3) {
                    $from = '#'.date('m/d/y', strtotime('07/01/'.request()->input('year'))).'#';
                    $to = '#'.date('m/d/y', strtotime('09/01/'.request()->input('year'))).'#';
                }
                else if($q == 4) {
                    $from = '#'.date('m/d/y', strtotime('10/01/'.request()->input('year'))).'#';
                    $to = '#'.date('m/d/y', strtotime('12/01/'.request()->input('year'))).'#';
                }
            }
            
            //TOP 10 MORB AND MORT
            if($type == 'yearly') {
                $mort_distinct_query = "SELECT DISTINCT(DISEASE)
                FROM [MORT BHS]
                WHERE FORMAT([DATE], 'yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE <> ''";

                $mort_row_query = "SELECT * FROM [MORT BHS]
                WHERE FORMAT([DATE], 'yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE = :disease";

                $morb_distinct_query = "SELECT DISTINCT(DISEASE)
                FROM [M2 BHS]
                WHERE FORMAT([DATE], 'yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE <> ''";

                $morb_row_query = "SELECT * FROM [M2 BHS]
                WHERE FORMAT([DATE], 'yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE = :disease";
            }
            else if($type == 'quarterly') {
                $mort_distinct_query = "SELECT DISTINCT(DISEASE)
                FROM [MORT BHS]
                WHERE [DATE] BETWEEN $from AND $to
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE <> ''";

                $mort_row_query = "SELECT * FROM [MORT BHS]
                WHERE [DATE] BETWEEN $from AND $to
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE = :disease";

                $morb_distinct_query = "SELECT DISTINCT(DISEASE)
                FROM [M2 BHS]
                WHERE [DATE] BETWEEN $from AND $to
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE <> ''";

                $morb_row_query = "SELECT * FROM [M2 BHS]
                WHERE [DATE] BETWEEN $from AND $to
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE = :disease";
            }
            else if($type == 'monthly') {
                $mort_distinct_query = "SELECT DISTINCT(DISEASE)
                FROM [MORT BHS]
                WHERE FORMAT([DATE], 'mm/yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE <> ''";

                $mort_row_query = "SELECT * FROM [MORT BHS]
                WHERE FORMAT([DATE], 'mm/yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE = :disease";

                $morb_distinct_query = "SELECT DISTINCT(DISEASE)
                FROM [M2 BHS]
                WHERE FORMAT([DATE], 'mm/yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE <> ''";

                $morb_row_query = "SELECT * FROM [M2 BHS]
                WHERE FORMAT([DATE], 'mm/yyyy') = :year
                AND MUN_CODE = 'GENERAL TRIAS'
                AND DISEASE = :disease";

                $year = date('m/Y', strtotime(request()->input('year').'-'.request()->input('month').'-01'));
            }

            //MORTALITY DISTINCT
            $mort_stmt = $pdo->prepare($mort_distinct_query);

            if($type != 'quarterly') {
                $mort_stmt->bindParam(':year', $year, PDO::PARAM_STR);
            }
            
            $mort_stmt->execute();

            while ($row = $mort_stmt->fetch()) {
                $row_disease = $row['DISEASE'];
    
                $stmt2 = $pdo->prepare($mort_row_query);
    
                if($type != 'quarterly') {
                    $stmt2->bindParam(':year', $year, PDO::PARAM_STR);
                }

                $stmt2->bindParam(':disease', $row_disease, PDO::PARAM_STR);
    
                $stmt2->execute();
    
                $count = 0;
    
                while ($row2 = $stmt2->fetch()) {
                    $count +=
                    $row2['1_4_M'] + $row2['1_4_F'] +
                    $row2['5_9_M'] + $row2['5_9_F'] +
                    $row2['10_14_M'] + $row2['10_14_F'] + 
                    $row2['15_19_M'] + $row2['15_19_F'] +
                    $row2['20_24_M'] + $row2['20_24_F'] +
                    $row2['25_29_M'] + $row2['25_29_F'] + 
                    $row2['30_34_M'] + $row2['30_34_F'] +
                    $row2['35_39_M'] + $row2['35_39_F'] +
                    $row2['40_44_M'] + $row2['40_44_F'] +
                    $row2['45_49_M'] + $row2['45_49_F'] +
                    $row2['50_54_M'] + $row2['50_54_F'] +
                    $row2['55_59_M'] + $row2['55_59_F'] +
                    $row2['60_64_M'] + $row2['60_64_F'] +
                    $row2['65_69_M'] + $row2['65_69_F'] +
                    $row2['70ABOVE_M'] + $row2['70ABOVE_F'] +
                    $row2['0_6DAYS_M'] + $row2['0_6DAYS_F'] +
                    $row2['7_28DAYS_M'] + $row2['7_28DAYS_F'] +
                    $row2['29DAYS_11MOS_M'] + $row2['29DAYS_11MOS_F']
                    ;
                }
    
                array_push($mort_final_list, [
                    'disease' => $row_disease,
                    'count' => $count,
                ]);
            }

            //MORBIDITY
            $morb_stmt = $pdo->prepare($morb_distinct_query);

            if($type != 'quarterly') {
                $morb_stmt->bindParam(':year', $year, PDO::PARAM_STR);
            }

            $morb_stmt->execute();

            while ($row = $morb_stmt->fetch()) {
                $row_disease = $row['DISEASE'];

                $stmt2 = $pdo->prepare($morb_row_query);

                if($type != 'quarterly') {
                    $stmt2->bindParam(':year', $year, PDO::PARAM_STR);
                }

                $stmt2->bindParam(':disease', $row_disease, PDO::PARAM_STR);

                $stmt2->execute();

                $count = 0;

                while ($row2 = $stmt2->fetch()) {
                    $count +=
                    $row2['1_4_M'] + $row2['1_4_F'] +
                    $row2['5_9_M'] + $row2['5_9_F'] +
                    $row2['10_14_M'] + $row2['10_14_F'] + 
                    $row2['15_19_M'] + $row2['15_19_F'] +
                    $row2['20_24_M'] + $row2['20_24_F'] +
                    $row2['25_29_M'] + $row2['25_29_F'] + 
                    $row2['30_34_M'] + $row2['30_34_F'] +
                    $row2['35_39_M'] + $row2['35_39_F'] +
                    $row2['40_44_M'] + $row2['40_44_F'] +
                    $row2['45_49_M'] + $row2['45_49_F'] +
                    $row2['50_54_M'] + $row2['50_54_F'] +
                    $row2['55_59_M'] + $row2['55_59_F'] +
                    $row2['60_64_M'] + $row2['60_64_F'] +
                    $row2['65_69_M'] + $row2['65_69_F'] +
                    $row2['70ABOVE_M'] + $row2['70ABOVE_F'] +
                    $row2['0_6DAYS_M'] + $row2['0_6DAYS_F'] +
                    $row2['7_28DAYS_M'] + $row2['7_28DAYS_F'] +
                    $row2['29DAYS_11MOS_M'] + $row2['29DAYS_11MOS_F']
                    ;
                }

                array_push($morb_final_list, [
                    'disease' => $row_disease,
                    'count' => $count,
                ]);
            }

            //MORT AND NATALITY
            foreach($bgy_list as $b) {
                $bstring = $b->brgyName;

                //get BGY_CODE
                $bgy_query = "SELECT * FROM [BARANGAY]
                WHERE UCASE(BGY_DESC) = :bgy";

                $bgy_stmt = $pdo->prepare($bgy_query);

                $bgy_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);

                $bgy_stmt->execute();

                $bgy_row = $bgy_stmt->fetch();
                $bgy_desc = $bgy_row['BGY_CODE'];

                //get population first
                $pop_query = "SELECT * FROM [POPULATION]
                WHERE [MUN_CODE] = 'GENERAL TRIAS'
                AND UCASE(BGY_CODE) = :bgy
                AND [POP_YEAR] = :year";

                $pop_stmt = $pdo->prepare($pop_query);

                $pop_stmt->bindParam(':year', $base_year, PDO::PARAM_STR);
                $pop_stmt->bindParam(':bgy', $bgy_desc, PDO::PARAM_STR);
                
                $pop_stmt->execute();

                $pop_row = $pop_stmt->fetch();

                $livebirth = 0;
                $tot_death = 0;
                $mat_death = 0;
                $inf_death = 0;
                $unf_death = 0;

                if($type == 'yearly') {
                    $mn_query = "SELECT * FROM [MORTALITY]
                    WHERE FORMAT([DATE], 'yyyy') = :year
                    AND MUN_CODE = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy";
                }
                else if($type == 'quarterly') {
                    $mn_query = "SELECT * FROM [MORTALITY]
                    WHERE [DATE] BETWEEN $from AND $to
                    AND MUN_CODE = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy";
                }
                else if($type == 'monthly') {
                    $mn_query = "SELECT * FROM [MORTALITY]
                    WHERE FORMAT([DATE], 'mm/yyyy') = :year
                    AND MUN_CODE = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy";

                    $year = date('m/Y', strtotime(request()->input('year').'-'.request()->input('month').'-01'));
                }

                $mn_stmt = $pdo->prepare($mn_query);
                if($type != 'quarterly') {
                    $mn_stmt->bindParam(':year', $year, PDO::PARAM_STR);
                }
                $mn_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);

                $mn_stmt->execute();
                
                while ($row = $mn_stmt->fetch()) {
                    $livebirth += $row['LB_M'] + $row['LB_F'];
                    $tot_death += $row['TOTDEATH_M'] + $row['TOTDEATH_F'];
                    $mat_death += $row['MATDEATH_M'] + $row['MATDEATH_F'];
                    $inf_death += $row['INFDEATH_M'] + $row['INFDEATH_F'];
                    $unf_death += $row['DEATHUND5_M'] + $row['DEATHUND5_F'];
                }

                array_push($bgy_nm_list, [
                    'barangay' => $bstring,
                    'population' => $pop_row['POP_BGY'],
                    'livebirth' => $livebirth,
                    'tot_death' => $tot_death,
                    'mat_death' => $mat_death,
                    'inf_death' => $inf_death,
                    'unf_death' => $unf_death,
                ]);
            }

            //M1
            foreach($bgy_list as $b) {
                $bstring = $b->brgyName;

                if($type == 'yearly') {
                    $ccare_query = "SELECT * FROM [CHILD CARE]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy
                    AND FORMAT([DATE], 'yyyy') = :year";

                    $ncom_query = "SELECT * FROM [OTHER INDICATORS]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy
                    AND FORMAT([DATE], 'yyyy') = :year";

                    $fp1_query = "SELECT * FROM [FAMILY PLANNING]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy
                    AND FORMAT([DATE], 'yyyy') = :year";

                    $fp2_query = "SELECT * FROM [FAMILY PLANNING1]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy
                    AND FORMAT([DATE], 'yyyy') = :year";

                    $fp3_query = "SELECT * FROM [FAMILY PLANNING2]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND UCASE(BGY_CODE) = :bgy
                    AND FORMAT([DATE], 'yyyy') = :year";

                    //Environmental
                    if($base_year != date('Y')) {
                        $edate = date('m/d/y', strtotime($base_year.'-12-01'));
                    }
                    else {
                        if(date('n') >= 2 && date('n') <= 4) {
                            $edate = date('m/d/y', strtotime($base_year.'-03-01'));
                        }
                        else if(date('n') >= 5 && date('n') <= 7) {
                            $edate = date('m/d/y', strtotime($base_year.'-06-01'));
                        }
                        else if(date('n') >= 8 && date('n') <= 10) {
                            $edate = date('m/d/y', strtotime($base_year.'-09-01'));
                        }
                        else if(date('n') >= 11 && date('n') <= 12) {
                            $edate = date('m/d/y', strtotime($base_year.'-12-01'));
                        }
                    }

                    $env_query = "SELECT * FROM [ENVIRONMENTAL HEALTH]
                    WHERE [YEAR_ENV] = $base_year
                    AND [DATE] = :edate
                    AND UCASE(BGY_CODE) = :bgy";
                }
                else if($type == 'quarterly') {
                    $ccare_query = "SELECT * FROM [CHILD CARE]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND [DATE] BETWEEN $from AND $to
                    AND UCASE(BGY_CODE) = :bgy";

                    $ncom_query = "SELECT * FROM [OTHER INDICATORS]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND [DATE] BETWEEN $from AND $to
                    AND UCASE(BGY_CODE) = :bgy";
                }
                else if($type == 'monthly') {
                    $ccare_query = "SELECT * FROM [CHILD CARE]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND FORMAT([DATE], 'mm/yyyy') = :year
                    AND UCASE(BGY_CODE) = :bgy";

                    $ncom_query = "SELECT * FROM [OTHER INDICATORS]
                    WHERE [MUN_CODE] = 'GENERAL TRIAS'
                    AND FORMAT([DATE], 'mm/yyyy') = :year
                    AND UCASE(BGY_CODE) = :bgy";

                    $year = date('m/Y', strtotime(request()->input('year').'-'.request()->input('month').'-01'));
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

                $ccare_stmt = $pdo->prepare($ccare_query);
                if($type != 'quarterly') {
                    $ccare_stmt->bindParam(':year', $year, PDO::PARAM_STR);
                }
                $ccare_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);
                $ccare_stmt->execute();

                $ncom_stmt = $pdo->prepare($ncom_query);
                if($type != 'quarterly') {
                    $ncom_stmt->bindParam(':year', $year, PDO::PARAM_STR);
                }
                $ncom_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);
                $ncom_stmt->execute();

                $fp1_stmt = $pdo->prepare($fp1_query);
                if($type != 'quarterly') {
                    $fp1_stmt->bindParam(':year', $year, PDO::PARAM_STR);
                }
                $fp1_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);
                $fp1_stmt->execute();

                $fp2_stmt = $pdo->prepare($fp2_query);
                if($type != 'quarterly') {
                    $fp2_stmt->bindParam(':year', $year, PDO::PARAM_STR);
                }
                $fp2_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);
                $fp2_stmt->execute();

                $fp3_stmt = $pdo->prepare($fp3_query);
                if($type != 'quarterly') {
                    $fp3_stmt->bindParam(':year', $year, PDO::PARAM_STR);
                }
                $fp3_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);
                $fp3_stmt->execute();

                $env_stmt = $pdo->prepare($env_query);
                /*
                if($type != 'quarterly') {
                    $env_stmt->bindParam(':year', $year, PDO::PARAM_STR);
                }
                */
                $env_stmt->bindParam(':edate', $edate, PDO::PARAM_STR);
                $env_stmt->bindParam(':bgy', $bstring, PDO::PARAM_STR);
                $env_stmt->execute();

                //CHILD CARE FETCH
                while ($row = $ccare_stmt->fetch()) {
                    $fic_m += $row['FIC_M'];
                    $fic_f += $row['FIC_F'];
                    $cic_m += $row['CIC_M'];
                    $cic_f += $row['CIC_F'];
                }

                //NON-COMM FETCH
                while ($row = $ncom_stmt->fetch()) {
                    $ra += $row['NONCOM_PPEN_M'] + $row['NONCOM_PPEN_F'];
                    $ppv += $row['NONCOM_PPV_M'] + $row['NONCOM_PPV_F'];
                    $flu += $row['NONCOM_IV_M'] + $row['NONCOM_PPV_F'];
                }

                //FAMILY PLANNING 1 FETCH
                while ($row = $fp1_stmt->fetch()) {
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
                while ($row = $fp2_stmt->fetch()) {
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
                while ($row = $fp3_stmt->fetch()) {
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

                if($env_stmt->rowCount() > 0) {
                    
                }

                while ($row = $env_stmt->fetch()) {
                    $env_lvl1 += $row['HHWATER_LEVEL1'];
                    $env_lvl2 += $row['HHWATER_LEVEL2'];
                    $env_lvl3 += $row['HHWATER_LEVEL3'];
                }

                array_push($bgy_mone_list, [
                    'barangay' => $b->brgyName,
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
}
