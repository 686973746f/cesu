<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\Brgy;
use Illuminate\Http\Request;

class FhsisController extends Controller
{
    public function home() {

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
        

        if(request()->input('type') && request()->input('year')) {
            $year = request()->input('year');

            $type = request()->input('type');

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

            return view('efhsis.report', [
                'mort_final_list' => $mort_final_list,
                'morb_final_list' => $morb_final_list,
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
}
