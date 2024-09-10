<?php

namespace App\Http\Controllers;

use App\Models\Nt;
use Carbon\Carbon;
use App\Models\Abd;
use App\Models\Aes;
use App\Models\Afp;
use App\Models\Ahf;
use App\Models\Nnt;
use App\Models\Psp;
use App\Models\Aefi;
use App\Models\Ames;
use App\Models\Brgy;
use App\Models\Diph;
use App\Models\Hfmd;
use App\Models\Pert;
use App\Models\Chikv;
use App\Models\Forms;
use App\Models\Dengue;
use App\Models\Rabies;
use App\Models\Anthrax;
use App\Models\Cholera;
use App\Models\Malaria;
use App\Models\Measles;
use App\Models\Meningo;
use App\Models\Typhoid;
use App\Models\Hepatitis;
use App\Models\Influenza;
use App\Models\MonkeyPox;
use App\Models\Rotavirus;
use App\Models\Meningitis;
use App\Imports\EdcsImport;
use App\Models\DohFacility;
use App\Models\Subdivision;
use Illuminate\Support\Str;
use App\Imports\PidsrImport;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use App\Imports\RabiesImport;
use App\Models\Leptospirosis;
use App\Models\PidsrThreshold;
use App\Models\LabResultLogBook;
use App\Models\EdcsLaboratoryData;
use App\Models\PidsrNotifications;
use Illuminate\Support\Facades\DB;
use RebaseData\Converter\Converter;
use RebaseData\InputFile\InputFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\LabResultLogBookGroup;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use OpenSpout\Common\Entity\Style\Style;
use App\Models\EdcsWeeklySubmissionChecker;
use App\Models\SevereAcuteRespiratoryInfection;

/*
ALL TABLES

ALTER TABLE abd ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE pidsr_AEFI ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE aes ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE afp ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE ahf ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE ames ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE anthrax ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE chikv ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE cholera ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE dengue ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE diph ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE hepatitis ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE hfmd ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE influenza ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE leptospirosis ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE malaria ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE measles ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE meningitis ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE meningo ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE nnt ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE nt ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE pert ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE psp ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE rabies ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE rotavirus ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE typhoid ADD from_edcs TINYINT(1) DEFAULT 0;
ALTER TABLE severe_acute_respiratory_infections ADD from_edcs TINYINT(1) DEFAULT 0;

UPDATE abd SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE pidsr_AEFI SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE aes SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE afp SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE ahf SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE ames SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE anthrax SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE chikv SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE cholera SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE dengue SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE diph SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE hepatitis SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE hfmd SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE influenza SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE leptospirosis SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE malaria SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE measles SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE meningitis SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE meningo SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE nnt SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE nt SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE pert SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE psp SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE rabies SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE rotavirus SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';
UPDATE typhoid SET encoded_mw = 42 WHERE DATE(created_at) = '2023-10-24';

DEBUGGER 1 DEBUG
$ctxt = "SHOW COLUMNS FROM afp";
$c = DB::select($ctxt);
$columns = array_map(function ($column) {
    return $column->Field;
}, $c);

foreach($columns as $c) {
    echo "'$c' => \$row->".$c.',<br>';
}
*/

class PIDSRController extends Controller
{
    public function home() {
        $id = auth()->user()->id;

        $notif_count = PidsrNotifications::whereRaw("FIND_IN_SET($id, viewedby_id) = 0")->count();

        $now = Carbon::now();

        if($now->dayOfWeek == Carbon::TUESDAY) {
            $unlockweeklyreport = true;
        }
        else {
            $unlockweeklyreport = false;
        }

        $forverification_count = count(PIDSRController::getBlankSubdivisions());

        return view('pidsr.home', [
            'notif_count' => $notif_count,
            'unlockweeklyreport' => $unlockweeklyreport,
            'forverification_count' => $forverification_count,
        ]);
    }

    public function threshold_index() {
        if(!(request()->input('sd')) && !(request()->input('year'))) {
            return abort(401);
        }

        $s = request()->input('sd');
        $y = request()->input('year');

        $arr = array();

        if($y == date('Y')) {
            $compa = date('W');
        }
        else {
            $sdate = Carbon::createFromDate($y, 12, 31);
            $compa = $sdate->startOfWeek()->format('W');

            if($compa == 01) {
                $compa = date('W', strtotime($sdate->startOfWeek()->format('Y-m-d').' -1 Day'));
            }
        }
        if($s == 'AFP') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Afp::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'AEFI') {
            //
        }
        else if($s == 'ANTHRAX') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Anthrax::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'HFMD') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Hfmd::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'MEASLES') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Measles::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'MENINGO') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Meningo::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'NT') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Nt::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'PSP') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Psp::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'RABIES') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Rabies::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'ABD') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Abd::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'AES') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Aes::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'AHF') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Ahf::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'HEPATITIS') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Hepatitis::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'AMES') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Ames::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'MENINGITIS') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Meningitis::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'ChikV') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Chikv::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'CHOLERA') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Cholera::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'DENGUE') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Dengue::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'DIPH') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Diph::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'INFLUENZA') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Influenza::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'LEPTOSPIROSIS') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Leptospirosis::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'MALARIA') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Malaria::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'NNT') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Nnt::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'PERT') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Pert::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'RotaVirus') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = RotaVirus::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'TYPHOID') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Typhoid::where('Province', 'CAVITE')
                ->where('Muncity', 'GENERAL TRIAS')
                ->where('MorbidityWeek', $i)
                ->where('Year', $y)
                ->where('enabled', 1)
                ->count();

                $arr["mw$i"] = ${'mw'.$i};
            }
        }
        else if($s == 'MONKEYPOX') {

        }

        return view('pidsr.threshold', [
            's' => $s,
            'arr' => $arr,
            'compa' => $compa,
        ]);
    }

    public function import_start() {
        if(File::exists(storage_path('app/pidsr/ABD.xlsx'))) {
            Excel::import(new PidsrImport('ABD'), storage_path('app/pidsr/ABD.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/AES.xlsx'))) {
            Excel::import(new PidsrImport('AES'), storage_path('app/pidsr/AES.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/AFP.xlsx'))) {
            Excel::import(new PidsrImport('AFP'), storage_path('app/pidsr/AFP.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/AHF.xlsx'))) {
            Excel::import(new PidsrImport('AHF'), storage_path('app/pidsr/AHF.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/AMES.xlsx'))) {
            Excel::import(new PidsrImport('AMES'), storage_path('app/pidsr/AMES.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/ANTHRAX.xlsx'))) {
            Excel::import(new PidsrImport('ANTHRAX'), storage_path('app/pidsr/ANTHRAX.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/CHIKV.xlsx'))) {
            Excel::import(new PidsrImport('CHIKV'), storage_path('app/pidsr/CHIKV.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/ChikV.xlsx'))) {
            Excel::import(new PidsrImport('CHIKV'), storage_path('app/pidsr/ChikV.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/CHOLERA.xlsx'))) {
            Excel::import(new PidsrImport('CHOLERA'), storage_path('app/pidsr/CHOLERA.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/DENGUE.xlsx'))) {
            Excel::import(new PidsrImport('DENGUE'), storage_path('app/pidsr/DENGUE.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/DIPH.xlsx'))) {
            Excel::import(new PidsrImport('DIPH'), storage_path('app/pidsr/DIPH.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/HEPATITIS.xlsx'))) {
            Excel::import(new PidsrImport('HEPATITIS'), storage_path('app/pidsr/HEPATITIS.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/HFMD.xlsx'))) {
            Excel::import(new PidsrImport('HFMD'), storage_path('app/pidsr/HFMD.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/INFLUENZA.xlsx'))) {
            Excel::import(new PidsrImport('INFLUENZA'), storage_path('app/pidsr/INFLUENZA.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/LEPTOSPIROSIS.xlsx'))) {
            Excel::import(new PidsrImport('LEPTOSPIROSIS'), storage_path('app/pidsr/LEPTOSPIROSIS.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/MALARIA.xlsx'))) {
            Excel::import(new PidsrImport('MALARIA'), storage_path('app/pidsr/MALARIA.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/MEASLES.xlsx'))) {
            Excel::import(new PidsrImport('MEASLES'), storage_path('app/pidsr/MEASLES.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/MENINGITIS.xlsx'))) {
            Excel::import(new PidsrImport('MENINGITIS'), storage_path('app/pidsr/MENINGITIS.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/MENINGO.xlsx'))) {
            Excel::import(new PidsrImport('MENINGO'), storage_path('app/pidsr/MENINGO.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/NNT.xlsx'))) {
            Excel::import(new PidsrImport('NNT'), storage_path('app/pidsr/NNT.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/NT.xlsx'))) {
            Excel::import(new PidsrImport('NT'), storage_path('app/pidsr/NT.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/PERT.xlsx'))) {
            Excel::import(new PidsrImport('PERT'), storage_path('app/pidsr/PERT.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/PSP.xlsx'))) {
            Excel::import(new PidsrImport('PSP'), storage_path('app/pidsr/PSP.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/RABIES.xlsx'))) {
            Excel::import(new PidsrImport('RABIES'), storage_path('app/pidsr/RABIES.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/ROTAVIRUS.xlsx'))) {
            Excel::import(new PidsrImport('ROTAVIRUS'), storage_path('app/pidsr/ROTAVIRUS.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/RotaVirus.xlsx'))) {
            Excel::import(new PidsrImport('ROTAVIRUS'), storage_path('app/pidsr/RotaVirus.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/TYPHOID.xlsx'))) {
            Excel::import(new PidsrImport('TYPHOID'), storage_path('app/pidsr/TYPHOID.xlsx'));
        }

        $aefi_file = storage_path('app/pidsr/aefi.sql');

        if(File::exists($aefi_file)) {
            $sql = file_get_contents($aefi_file);

            $sql = str_replace('INSERT ', 'INSERT IGNORE ', $sql);
            
            DB::unprepared($sql);
        }

        File::delete(storage_path('app/pidsr/ABD.xlsx'));
        File::delete(storage_path('app/pidsr/AES.xlsx'));
        File::delete(storage_path('app/pidsr/AFP.xlsx'));
        File::delete(storage_path('app/pidsr/AHF.xlsx'));
        File::delete(storage_path('app/pidsr/AMES.xlsx'));
        File::delete(storage_path('app/pidsr/ANTHRAX.xlsx'));

        if(File::exists(storage_path('app/pidsr/CHIKV.xlsx'))) {
            File::delete(storage_path('app/pidsr/CHIKV.xlsx'));
        }
        if(File::exists(storage_path('app/pidsr/ChikV.xlsx'))) {
            File::delete(storage_path('app/pidsr/ChikV.xlsx'));
        }
        
        File::delete(storage_path('app/pidsr/CHOLERA.xlsx'));
        File::delete(storage_path('app/pidsr/DENGUE.xlsx'));
        File::delete(storage_path('app/pidsr/DIPH.xlsx'));
        File::delete(storage_path('app/pidsr/HEPATITIS.xlsx'));
        File::delete(storage_path('app/pidsr/HFMD.xlsx'));
        File::delete(storage_path('app/pidsr/INFLUENZA.xlsx'));
        File::delete(storage_path('app/pidsr/LEPTOSPIROSIS.xlsx'));
        File::delete(storage_path('app/pidsr/MALARIA.xlsx'));
        File::delete(storage_path('app/pidsr/MEASLES.xlsx'));
        File::delete(storage_path('app/pidsr/MENINGITIS.xlsx'));
        File::delete(storage_path('app/pidsr/MENINGO.xlsx'));
        File::delete(storage_path('app/pidsr/NNT.xlsx'));
        File::delete(storage_path('app/pidsr/NT.xlsx'));
        File::delete(storage_path('app/pidsr/PERT.xlsx'));
        File::delete(storage_path('app/pidsr/PSP.xlsx'));
        File::delete(storage_path('app/pidsr/RABIES.xlsx'));

        if(File::exists(storage_path('app/pidsr/ROTAVIRUS.xlsx'))) {
            File::delete(storage_path('app/pidsr/ROTAVIRUS.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/RotaVirus.xlsx'))) {
            File::delete(storage_path('app/pidsr/RotaVirus.xlsx'));
        }
        
        File::delete(storage_path('app/pidsr/TYPHOID.xlsx'));
        File::delete(storage_path('app/pidsr/aefi.sql'));

        if(request()->input('m')) {
            return redirect()->route('pidsr.home')
            ->with('msg', 'MDB Feedback was successfully imported. You may now proceed to Step 2.')
            ->with('msgtype', 'success');
        }
        else {
            return 'Done';
        }
    }

    public function report_generate() {
        $brgy = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $arr = [];

        $rtype = request()->input('rtype');

        if(request()->input('submit') == 'report1') {
            foreach($brgy as $b) {
                $sy = request()->input('year');
    
                if($rtype == 'YEARLY') {
                    //Category 1
                    $afp = Afp::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $anthrax = Anthrax::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $hfmd = Hfmd::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $measles = Measles::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $meningo = Meningo::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $nt = Nt::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $psp = Psp::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $rabies = Rabies::where('Barangay', $b->brgyName)->where('year', $sy)->count();

                    $aefi = Aefi::where('Barangay', $b->brgyName)->whereYear('DAdmit', $sy)->count();
    
                    //Category 2
    
                    $abd = Abd::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $aes = Aes::where('Barangay', $b->brgyName)->where('year', $sy)->count();
                    
                    $ahf = Ahf::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $hepatitis = Hepatitis::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $ames = Ames::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $meningitis = Meningitis::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $chikv = Chikv::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $cholera = Cholera::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $dengue = Dengue::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $diph = Diph::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $influenza = Influenza::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $leptospirosis = Leptospirosis::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $malaria = Malaria::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $nnt = Nnt::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $pert = Pert::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $rotavirus = Rotavirus::where('Barangay', $b->brgyName)->where('year', $sy)->count();
    
                    $typhoid = Typhoid::where('Barangay', $b->brgyName)->where('year', $sy)->count();
                }
                else if($rtype == 'QUARTERLY') {
                    $qtr = request()->input('quarter');

                    if(request()->input('quarter') == '1') {
                        $txt2 = '1ST';
        
                        $date = Carbon::parse($sy.'-01-01');

                        $mm1 = 1;
                        $mm2 = 2;
                        $mm3 = 3;
                    }
                    else if(request()->input('quarter') == '2') {
                        $txt2 = '2ND';
        
                        $date = Carbon::parse($sy.'-04-01');

                        $mm1 = 4;
                        $mm2 = 5;
                        $mm3 = 6;
                    }
                    else if(request()->input('quarter') == '3') {
                        $txt2 = '3RD';
        
                        $date = Carbon::parse($sy.'-07-01');

                        $mm1 = 7;
                        $mm2 = 8;
                        $mm3 = 9;
                    }
                    else if(request()->input('quarter') == '4') {
                        $txt2 = '4TH';
                        
                        $date = Carbon::parse($sy.'-10-01');

                        $mm1 = 10;
                        $mm2 = 11;
                        $mm3 = 12;
                    }

                    $txt1 = $txt2.' QUARTER, YEAR '.request()->input('year');

                    //Category 1

                    $afp = Afp::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $anthrax = Anthrax::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $hfmd = Hfmd::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $measles = Measles::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $meningo = Meningo::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $nt = Nt::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $psp = Psp::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $rabies = Rabies::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();

                    //Category 2
    
                    $abd = Abd::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $aes = Aes::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
                    
                    $ahf = Ahf::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $hepatitis = Hepatitis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $ames = Ames::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $meningitis = Meningitis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $chikv = Chikv::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $cholera = Cholera::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $dengue = Dengue::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $diph = Diph::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $influenza = Influenza::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $leptospirosis = Leptospirosis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $malaria = Malaria::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $nnt = Nnt::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $pert = Pert::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $rotavirus = Rotavirus::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
    
                    $typhoid = Typhoid::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->whereIn('MorbidityMonth', [$mm1, $mm2, $mm3])
                    ->count();
                }
                else if($rtype == 'MONTHLY') {
                    $mm = request()->input('month');
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

                    //Category 1
                    $afp = Afp::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $anthrax = Anthrax::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $hfmd = Hfmd::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $measles = Measles::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $meningo = Meningo::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $nt = Nt::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $psp = Psp::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $rabies = Rabies::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    //Category 2
    
                    $abd = Abd::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $aes = Aes::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
                    
                    $ahf = Ahf::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $hepatitis = Hepatitis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $ames = Ames::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $meningitis = Meningitis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $chikv = Chikv::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $cholera = Cholera::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $dengue = Dengue::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $diph = Diph::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $influenza = Influenza::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $leptospirosis = Leptospirosis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $malaria = Malaria::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $nnt = Nnt::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $pert = Pert::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $rotavirus = Rotavirus::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
    
                    $typhoid = Typhoid::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityMonth', $mm)
                    ->count();
                }
                else if($rtype == 'WEEKLY') {
                    $mw = request()->input('week');

                    //Category 1
                    $afp = Afp::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $anthrax = Anthrax::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $hfmd = Hfmd::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $measles = Measles::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $meningo = Meningo::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $nt = Nt::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $psp = Psp::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $rabies = Rabies::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    //Category 2
    
                    $abd = Abd::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $aes = Aes::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
                    
                    $ahf = Ahf::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $hepatitis = Hepatitis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $ames = Ames::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $meningitis = Meningitis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $chikv = Chikv::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $cholera = Cholera::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $dengue = Dengue::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $diph = Diph::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $influenza = Influenza::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $leptospirosis = Leptospirosis::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $malaria = Malaria::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $nnt = Nnt::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $pert = Pert::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $rotavirus = Rotavirus::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
    
                    $typhoid = Typhoid::where('Barangay', $b->brgyName)->where('year', $sy)
                    ->where('MorbidityWeek', $mw)
                    ->count();
                }
    
                array_push($arr, [
                    'barangay' => $b->brgyName,
                    
                    'afp' => $afp,
                    'aefi' => 0,
                    'anthrax' => $anthrax,
                    'hfmd' => $hfmd,
                    'measles' => $measles,
                    'meningo' => $meningo,
                    'nt' => $nt,
                    'psp' => $psp,
                    'rabies' => $rabies,
    
                    'abd' => $abd,
                    'aes' => $aes,
                    'ahf' => $ahf,
                    'hepatitis' => $hepatitis,
                    'ames' => $ames,
                    'meningitis' => $meningitis,
                    'chikv' => $chikv,
                    'cholera' => $cholera,
                    'dengue' => $dengue,
                    'diph' => $diph,
                    'influenza' => $influenza,
                    'leptospirosis' => $leptospirosis,
                    'malaria' => $malaria,
                    'nnt' => $nnt,
                    'pert' => $pert,
                    'rotavirus' => $rotavirus,
                    'typhoid' => $typhoid,
                ]);
            }
    
            return view('pidsr.report1', [
                'arr' => $arr,
            ]);
        }
    }

    /*
    public function manualsend() {
        $s = SiteSettings::find(1);
        
        Artisan::call('pidsrwndr:weekly');

        $s->pidsr_early_sent = 1;
        
        if($s->isDirty()) {
            $s->save();
        }
        
        return redirect()->route('pidsr.home')
        ->with('msg', 'Weekly Notifiable Diseases Report Mail was sent successfully. Please check your email (cesu.gentrias@gmail.com).')
        ->with('msgtype', 'success');
    }
    */

    public function report_two() {
        
    }

    public function fhsis_report() {

    }

    public function casechecker() {
        if(request()->input('case')) {
            $case = request()->input('case');
            $year = request()->input('year');
            
            if($case == 'ABD') {
                $query = Abd::where('year', $year);
                //$columns = Schema::getColumnListing('abd');

                $tbl_name = 'abd';
            }
            else if($case == 'AEFI') {
                $query = Aefi::whereYear('DAdmit', $year);
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    //$columns = Schema::getColumnListing('pidsr_aefi');
                    $tbl_name = 'pidsr_aefi';
                }
                else {
                    //$columns = Schema::getColumnListing('pidsr_AEFI');
                    $tbl_name = 'pidsr_AEFI';
                }
            }
            else if($case == 'AES') {
                $query = Aes::where('year', $year);
                //$columns = Schema::getColumnListing('aes');

                $tbl_name = 'aes';
            }
            else if($case == 'AFP') {
                $query = Afp::where('year', $year);
                //$columns = Schema::getColumnListing('afp');

                $tbl_name = 'afp';
            }
            else if($case == 'AHF') {
                $query = Ahf::where('year', $year);
                //$columns = Schema::getColumnListing('ahf');

                $tbl_name = 'ahf';
            }
            else if($case == 'AMES') {
                $query = Ames::where('year', $year);
                //$columns = Schema::getColumnListing('ames');

                $tbl_name = 'ames';
            }
            else if($case == 'ANTHRAX') {
                $query = Anthrax::where('year', $year);
                //$columns = Schema::getColumnListing('anthrax');

                $tbl_name = 'anthrax';
            }
            else if($case == 'CHIKV') {
                $query = Chikv::where('year', $year);
                //$columns = Schema::getColumnListing('chikv');

                $tbl_name = 'chikv';
            }
            else if($case == 'CHOLERA') {
                $query = Cholera::where('year', $year);
                //$columns = Schema::getColumnListing('cholera');

                $tbl_name = 'cholera';
            }
            else if($case == 'DENGUE') {
                $query = Dengue::where('year', $year);
                //$columns = Schema::getColumnListing('dengue');

                $tbl_name = 'dengue';
            }
            else if($case == 'DIPH') {
                $query = Diph::where('year', $year);
                //$columns = Schema::getColumnListing('diph');

                $tbl_name = 'diph';
            }
            else if($case == 'HEPATITIS') {
                $query = Hepatitis::where('year', $year);
                //$columns = Schema::getColumnListing('hepatitis');

                $tbl_name = 'hepatitis';
            }
            else if($case == 'HFMD') {
                $query = Hfmd::where('year', $year);
                //$columns = Schema::getColumnListing('hfmd');

                $tbl_name = 'hfmd';
            }
            else if($case == 'INFLUENZA') {
                $query = Influenza::where('year', $year);
                //$columns = Schema::getColumnListing('influenza');

                $tbl_name = 'influenza';
            }
            else if($case == 'LEPTOSPIROSIS') {
                $query = Leptospirosis::where('year', $year);
                //$columns = Schema::getColumnListing('leptospirosis');

                $tbl_name = 'leptospirosis';
            }
            else if($case == 'MALARIA') {
                $query = Malaria::where('year', $year);
                //$columns = Schema::getColumnListing('malaria');

                $tbl_name = 'malaria';
            }
            else if($case == 'MEASLES') {
                $query = Measles::where('year', $year);
                //$columns = Schema::getColumnListing('measles');

                $tbl_name = 'measles';
            }
            else if($case == 'MENINGITIS') {
                $query = Meningitis::where('year', $year);
                //$columns = Schema::getColumnListing('meningitis');

                $tbl_name = 'meningitis';
            }
            else if($case == 'MENINGO') {
                $query = Meningo::where('year', $year);
                //$columns = Schema::getColumnListing('meningo');

                $tbl_name = 'meningo';
            }
            else if($case == 'NNT') {
                $query = Nnt::where('year', $year);
                //$columns = Schema::getColumnListing('nnt');

                $tbl_name = 'nnt';
            }
            else if($case == 'NT') {
                $query = Nt::where('year', $year);
                //$columns = Schema::getColumnListing('nt');

                $tbl_name = 'nt';
            }
            else if($case == 'PERT') {
                $query = Pert::where('year', $year);
                //$columns = Schema::getColumnListing('pert');

                $tbl_name = 'pert';
            }
            else if($case == 'PSP') {
                $query = Psp::where('year', $year);
                //$columns = Schema::getColumnListing('psp');

                $tbl_name = 'psp';
            }
            else if($case == 'RABIES') {
                $query = Rabies::where('year', $year);
                //$columns = Schema::getColumnListing('rabies');

                $tbl_name = 'rabies';
            }
            else if($case == 'ROTAVIRUS') {
                $query = Rotavirus::where('year', $year);
                //$columns = Schema::getColumnListing('rotavirus');

                $tbl_name = 'rotavirus';
            }
            else if($case == 'SARI') {
                $query = SevereAcuteRespiratoryInfection::where('year', $year);
                //$columns = Schema::getColumnListing('rotavirus');

                $tbl_name = 'severe_acute_respiratory_infections';
            }
            else if($case == 'TYPHOID') {
                $query = Typhoid::where('year', $year);
                //$columns = Schema::getColumnListing('typhoid');

                $tbl_name = 'typhoid';
            }

            $ctxt = "SHOW COLUMNS FROM $tbl_name";
            $c = DB::select($ctxt);
            $columns = array_map(function ($column) {
                return $column->Field;
            }, $c);

            if($case == 'SARI') {
                $query = $query->where('muncity', 'GENERAL TRIAS')
                ->where('province', 'CAVITE');
            }
            else {
                $query = $query->where('Muncity', 'GENERAL TRIAS')
                ->where('Province', 'CAVITE');
            }

            if(request()->input('showDisabled')) {
                //$query = $query->whereIn('enabled', [0,1]);
            }
            else {
                $query = $query->where('enabled', 1);
            }

            if(request()->input('showNonMatchCaseDef')) {
                //$query = $query->whereIn('enabled', [0,1]);
            }
            else {
                $query = $query->where('match_casedef', 1);
            }

            if(request()->input('mw')) {
                $query = $query->where('encoded_mw', request()->input('mw'));
            }

            $query = $query->orderBy('created_at', 'DESC')->get();

            return view('pidsr.casechecker', [
                'list' => $query,
                'columns' => $columns,
                'case_name' => $case,
            ]);
        }
        else {
            return view('pidsr.casechecker');
        }
    }

    public function casechecker_action() {
        if(request()->input('d') && request()->input('action') && request()->input('epi_id')) {
            $d = request()->input('d');
            $action = request()->input('action');
            $epi_id = request()->input('epi_id');

            if($d == 'ABD') {
                $data = Abd::where('EPIID', $epi_id)->first();
            }
            else if($d == 'AEFI') {
                $data = Aefi::where('EPIID', $epi_id)->first();
            }
            else if($d == 'AES') {
                $data = Aes::where('EPIID', $epi_id)->first();
            }
            else if($d == 'AFP') {
                $data = Afp::where('EPIID', $epi_id)->first();
            }
            else if($d == 'AHF') {
                $data = Ahf::where('EPIID', $epi_id)->first();
            }
            else if($d == 'AMES') {
                $data = Ames::where('EPIID', $epi_id)->first();
            }
            else if($d == 'ANTHRAX') {
                $data = Anthrax::where('EPIID', $epi_id)->first();
            }
            else if($d == 'CHIKV') {
                $data = Chikv::where('EPIID', $epi_id)->first();
            }
            else if($d == 'CHOLERA') {
                $data = Cholera::where('EPIID', $epi_id)->first();
            }
            else if($d == 'DENGUE') {
                $data = Dengue::where('EPIID', $epi_id)->first();
            }
            else if($d == 'DIPH') {
                $data = Diph::where('EPIID', $epi_id)->first();
            }
            else if($d == 'HEPATITIS') {
                $data = Hepatitis::where('EPIID', $epi_id)->first();
            }
            else if($d == 'HFMD') {
                $data = Hfmd::where('EPIID', $epi_id)->first();
            }
            else if($d == 'INFLUENZA') {
                $data = Influenza::where('EPIID', $epi_id)->first();
            }
            else if($d == 'LEPTOSPIROSIS') {
                $data = Leptospirosis::where('EPIID', $epi_id)->first();
            }
            else if($d == 'MALARIA') {
                $data = Malaria::where('EPIID', $epi_id)->first();
            }
            else if($d == 'MEASLES') {
                $data = Measles::where('EPIID', $epi_id)->first();
            }
            else if($d == 'MENINGITIS') {
                $data = Meningitis::where('EPIID', $epi_id)->first();
            }
            else if($d == 'MENINGO') {
                $data = Meningo::where('EPIID', $epi_id)->first();
            }
            else if($d == 'NNT') {
                $data = Nnt::where('EPIID', $epi_id)->first();
            }
            else if($d == 'NT') {
                $data = Nt::where('EPIID', $epi_id)->first();
            }
            else if($d == 'PERT') {
                $data = Pert::where('EPIID', $epi_id)->first();
            }
            else if($d == 'PSP') {
                $data = Psp::where('EPIID', $epi_id)->first();
            }
            else if($d == 'RABIES') {
                $data = Rabies::where('EPIID', $epi_id)->first();
            }
            else if($d == 'ROTAVIRUS') {
                $data = Rotavirus::where('EPIID', $epi_id)->first();
            }
            else if($d == 'TYPHOID') {
                $data = Typhoid::where('EPIID', $epi_id)->first();
            }
            else if($d == 'SARI') {
                $data = SevereAcuteRespiratoryInfection::where('epi_id', $epi_id)->first();
            }

            if($action == "DEL") {
                $data->enabled = 0;

                if($data->isDirty()) {
                    $data->save();
                }

                return redirect()->back()
                ->with('msg', $d.' Case EPI_ID ['.$data->EPIID.'] Patient ['.$data->FullName.'] has been DISABLED successfully.')
                ->with('msgtype', 'success');
            }
            else if($action == 'ENB') {
                $data->enabled = 1;

                if($data->isDirty()) {
                    $data->save();
                }

                return redirect()->back()
                ->with('msg', $d.' Case EPI_ID ['.$data->EPIID.'] Patient ['.$data->FullName.'] has been ENABLED successfully.')
                ->with('msgtype', 'success');
            }
            else if($action == 'MATCH_CASEDEF') {
                $data->match_casedef = 1;

                if($data->isDirty()) {
                    $data->save();
                }

                return redirect()->back()
                ->with('msg', $d.' Case EPI_ID ['.$data->EPIID.'] Patient ['.$data->FullName.'] has been marked as MATCHED Case Definition successfully.')
                ->with('msgtype', 'success');
            }
            else if($action == 'NOTMATCH_CASEDEF') {
                $data->match_casedef = 0;

                if($data->isDirty()) {
                    $data->save();
                }

                return redirect()->back()
                ->with('msg', $d.' Case EPI_ID ['.$data->EPIID.'] Patient ['.$data->FullName.'] has been marked as NOT MATCHED Case Definition successfully.')
                ->with('msgtype', 'success');
            }
            else if($action == 'IDETH') {
                return redirect()->route('pidsr_casechecker_edit', [
                    'disease' => $d,
                    'epi_id' => $data->EPIID,
                ]);
            }
        }
        else {
            return abort(401);
        }
    }

    public static function dbFetcher($case) {
        $case = mb_strtoupper($case);

        if($case == 'SEVEREACUTERESPIRATORYINFECTION') {
            $case = 'SARI';
        }
        
        if($case == 'ABD') {
            $d = 'Abd';
            $flavor_title = 'Acute Bloody Diarrhea';
        }
        else if($case == 'AEFI') {
            $d = 'Aefi';
        }
        else if($case == 'AES') {
            $d = 'Aes';
        }
        else if($case == 'AFP') {
            $d = 'Afp';
            $flavor_title = 'Acute Flaccid Paralysis';
        }
        else if($case == 'AHF') {
            $d = 'Ahf';
        }
        else if($case == 'AMES') {
            $d = 'Ames';

            $flavor_title = 'Acute Meningitis Encephalitis';
        }
        else if($case == 'ANTHRAX') {
            $d = 'Anthrax';
        }
        else if($case == 'CHIKV') {
            $d = 'Chikv';

            $flavor_title = 'Chikungunya Viral Disease';
        }
        else if($case == 'CHOLERA') {
            $d = 'Cholera';

            $flavor_title = 'Cholera';
        }
        else if($case == 'DENGUE') {
            $d = 'Dengue';

            $flavor_title = 'Dengue';
        }
        else if($case == 'DIPH') {
            $d = 'Diph';

            $flavor_title = 'Diphteria';
        }
        else if($case == 'HEPATITIS') {
            $d = 'Hepatitis';

            $flavor_title = 'Acute Viral Hepatitis';
        }
        else if($case == 'HFMD') {
            $d = 'Hfmd';

            $flavor_title = 'Hand, Foot & Mouth Disease';
        }
        else if($case == 'INFLUENZA') {
            $d = 'Influenza';

            $flavor_title = 'Influenza-like Illness';
        }
        else if($case == 'LEPTOSPIROSIS') {
            $d = 'Leptospirosis';

            $flavor_title = 'Leptospirosis';
        }
        else if($case == 'MALARIA') {
            $d = 'Malaria';

            $flavor_title = 'Malaria';
        }
        else if($case == 'MEASLES') {
            $d = 'Measles';

            $flavor_title = 'Measles';
        }
        else if($case == 'MENINGITIS') {
            $d = 'MENINGITIS';

            $flavor_title = 'Meningitis';
        }
        else if($case == 'MENINGO') {
            $d = 'Meningo';

            $flavor_title = 'Meningococcal Disease';
        }
        else if($case == 'NNT') {
            $d = 'Nnt';

            $flavor_title = 'Non-Neonatal Tetanus';
        }
        else if($case == 'NT') {
            $d = 'Nt';

            $flavor_title = 'Neonatal Tetanus';
        }
        else if($case == 'PERT') {
            $d = 'Pert';

            $flavor_title = 'Pertussis';
        }
        else if($case == 'PSP') {
            $d = 'Psp';

            $flavor_title = 'TEST';
        }
        else if($case == 'RABIES') {
            $d = 'Rabies';

            $flavor_title = 'Rabies';
        }
        else if($case == 'ROTAVIRUS') {
            $d = 'Rotavirus';

            $flavor_title = 'Rotavirus';
        }
        else if($case == 'TYPHOID') {
            $d = 'Typhoid';

            $flavor_title = 'Typhoid and Paratyphoid Fever';
        }
        else if($case == 'SARI') {
            $d = 'SevereAcuteRespiratoryInfection';

            $flavor_title = 'Severe Acute Respiratory Infection';
        }

        $modelClass = "App\\Models\\$d";

        return $modelClass;
    }

    public function caseCheckerEdit($disease, $epi_id) {
        $modelClass = PIDSRController::dbFetcher($disease);

        if($disease == 'SEVEREACUTERESPIRATORYINFECTION') {
            $disease = 'SARI';
        }
        
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->get();

        if($disease == 'SARI') {
            $epiCol = 'epi_id';
        }
        else {
            $epiCol = 'EPIID';
        }

        $d = $modelClass::where($epiCol, $epi_id)->first();

        if($d) {
            return view('pidsr.casechecker_edit', [
                'd' => $d,
                'disease' => $disease,
                'brgy_list' => $brgy_list,
            ]);
        }
        else {
            return abort(401);
        }
    }

    //CASE CHECKER UPDATE
    public function caseCheckerUpdate($disease, $epi_id, Request $r) {
        $modelClass = PIDSRController::dbFetcher($disease);

        if($disease == 'SARI') {
            $epiCol = 'epi_id';
        }
        else {
            $epiCol = 'EPIID';
        }

        $d = $modelClass::where($epiCol, $epi_id)->first();

        if($r->filled('sys_coordinate_x')) {
            $r->validate([
                'sys_coordinate_x' => 'required',
                'sys_coordinate_y' => 'required',
            ]);
        }

        if($d) {
            if($disease == 'SARI') {
                $d->lname = $r->FamilyName;
                $d->fname = $r->FirstName;
                $d->middle_name = $r->middle_name;
                $d->suffix = $r->suffix;

                $d->streetpurok = $r->Streetpurok;
                $d->barangay = Brgy::find($r->Barangay)->brgyName;
            }
            else {
                $d->FamilyName = $r->FamilyName;
                $d->FirstName = $r->FirstName;
                $d->middle_name = $r->middle_name;
                $d->suffix = $r->suffix;

                $d->Streetpurok = $r->Streetpurok;
                $d->Barangay = Brgy::find($r->Barangay)->brgyName;

                $getFullName = $d->FamilyName.', '.$d->FirstName;

                if($r->filled('middle_name')) {
                    $getFullName = $getFullName.' '.$r->middle_name;
                }

                if($r->filled('suffix')) {
                    $getFullName = $getFullName.' '.$r->suffix;
                }

                $d->FullName = $getFullName;
            }

            if(!request()->is('*barangayportal*')) {
                if(!is_null($r->system_subdivision_id) && $r->system_subdivision_id != 'NOT LISTED') {
                    //Get Subdivision Details
                    $d->system_subdivision_id = $r->system_subdivision_id;
                    $getSubdivision = Subdivision::findOrFail($r->system_subdivision_id);

                    $d->system_subdivision_name = $getSubdivision->subdName;
                }
                else if($r->system_subdivision_id == 'NOT LISTED') {
                    $d->system_subdivision_name = $r->system_subdivision_name;
                }
            }
            
            $d->sys_coordinate_x = $r->sys_coordinate_x;
            $d->sys_coordinate_y = $r->sys_coordinate_y;
            $d->edcs_contactNo = $r->edcs_contactNo;
            
            if(request()->is('*barangayportal*')) {
                $d->brgy_remarks = ($r->brgy_remarks) ? mb_strtoupper($r->brgy_remarks) : $d->brgy_remarks;
            }

            if(!request()->is('*barangayportal*')) {
                $d->system_remarks = ($r->system_remarks) ? mb_strtoupper($r->system_remarks) : NULL;
            }

            //FOR PERT ADDITIONAL SETTINGS
            if($disease == 'PERT' && !request()->is('*barangayportal*')) {
                $d->system_outcome = $r->system_outcome;
                $d->system_classification = $r->system_classification;

                if($d->isDirty('system_classification')) {
                    if($d->system_classification == 'WAITING FOR RESULT') {
                        $d->CaseClassification = 'S';
                    }
                    else if($d->system_classification == 'NEGATIVE') {
                        $d->CaseClassification = 'S';
                    }
                    else if($d->system_classification == 'CONFIRMED') {
                        $d->CaseClassification = 'C';
                    }
                }

                if($d->isDirty('system_classification')) {
                    if($r->system_classification == 'DIED') {
                        $d->Outcome = 'D';
                    }
                }
            }

            if($d->isDirty()) {
                $d->save();
            }

            if($r->fromVerifier == 1) {
                return redirect()->route('pidsr_forvalidation_index')
                ->with('msg', 'The record was updated successfully.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()->back()
                ->with('msg', 'The record was updated successfully.')
                ->with('msgtype', 'success');
            }
        }
        else {
            return abort(401);
        }
    }

    public function weeklycaseviewer($year, $mw) {
        if(session('bypass')) {
            $proceed = true;
        }
        else {
            $proceed = false;

            return view();
        }
    }

    public function resetSendingStatus() {
        if(request()->input('dtr')) {
            $date_to_rr = request()->input('dtr');

            $qry1 = Abd::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Aefi::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Aes::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Afp::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Ahf::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Ames::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Anthrax::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Chikv::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Cholera::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Dengue::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Diph::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Hepatitis::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Hfmd::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Influenza::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Leptospirosis::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Malaria::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Measles::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Meningitis::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Meningo::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Nnt::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Nt::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Pert::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Psp::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Rabies::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Rotavirus::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            $qry1 = Typhoid::whereDate('created_at', $date_to_rr)
            ->where('systemsent', 1)
            ->update([
                'systemsent' => 0,
            ]);

            return redirect()->back()
            ->with('msg', 'Reset Successful.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function bulkdeleteCases(Request $r) {

    }

    public function edcsImportExcelProcess() {
        $excel_file = storage_path('app/edcs_feedback/feedback.xlsx');

        if(File::exists($excel_file)) {
            Excel::import(new EdcsImport(), $excel_file);

            File::delete($excel_file);

            return redirect()->route('pidsr.home')
            ->with('msg', 'EDCS Feedback data was imported successfully. You may now proceed to Step 3.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->route('pidsr.home')
            ->with('msg', 'Error: No EDCS Feeback file found. Make sure that you uploaded it first using the Uploader Program.')
            ->with('msgtype', 'warning');
        }
    }

    public function importToFtp() {
        /*
        DEBUGGER 1
        $ctxt = "SHOW COLUMNS FROM afp";
        $c = DB::select($ctxt);
        $columns = array_map(function ($column) {
            return $column->Field;
        }, $c);

        foreach($columns as $c) {
            echo "'$c' => \$row->".$c.',<br>';
        }

        */
        /*
        DEBUGGER2
        */

        

        //Delete files in the folder first
        $folderPath = storage_path('app/pidsr/ftp');
        if (File::exists($folderPath)) {
            $files = File::allFiles($folderPath);

            foreach ($files as $file) {
                File::delete($file);
            }
        }

        /*
        TEST EXPORT

        $afp = Afp::where('Year', 2023)->get();
        $aefi = Aefi::where('Year', 2023)->get();
        $anthrax = Anthrax::where('Year', 2023)->get();
        $measles = Measles::where('Year', 2023)->get();
        $meningo = Meningo::where('Year', 2023)->get();
        $nt = Nt::where('Year', 2023)->get();
        $psp = Psp::where('Year', 2023)->get();
        $rabies = Afp::where('Year', 2023)->get();
        $afp = Rabies::where('Year', 2023)->get();
        $abd = Abd::where('Year', 2023)->get();
        $aes = Aes::where('Year', 2023)->get();
        $ahf = Ahf::where('Year', 2023)->get();
        $hepatitis = Hepatitis::where('Year', 2023)->get();
        $ames = Ames::where('Year', 2023)->get();
        $meningitis = Meningitis::where('Year', 2023)->get();
        $chikv = Chikv::where('Year', 2023)->get();
        $cholera = Cholera::where('Year', 2023)->get();
        $dengue = Dengue::where('Year', 2023)->get();
        $diph = Diph::where('Year', 2023)->get();
        $ili = Influenza::where('Year', 2023)->get();
        $lep = Leptospirosis::where('Year', 2023)->get();
        $malaria = Malaria::where('Year', 2023)->get();
        $nnt = Nnt::where('Year', 2023)->get();
        $pert = Pert::where('Year', 2023)->get();
        $rota = Rotavirus::where('Year', 2023)->get();
        $typ = Typhoid::where('Year', 2023)->get();
        $hfmd = Hfmd::where('Year', 2023)->get();
        */

        if(request()->input('toggleRebuildMdb')) {
            $year = request()->input('year');

            $afp = Afp::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $aefi = Aefi::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $anthrax = Anthrax::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $measles = Measles::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $meningo = Meningo::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $nt = Nt::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $psp = Psp::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $rabies = Afp::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $afp = Rabies::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $abd = Abd::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $aes = Aes::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $ahf = Ahf::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $hepatitis = Hepatitis::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $ames = Ames::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $meningitis = Meningitis::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $chikv = Chikv::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $cholera = Cholera::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $dengue = Dengue::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $diph = Diph::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $ili = Influenza::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $lep = Leptospirosis::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $malaria = Malaria::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $nnt = Nnt::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $pert = Pert::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $rota = Rotavirus::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $typ = Typhoid::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
            $hfmd = Hfmd::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1)->get();
        }
        else {
            $afp = Afp::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $aefi = Aefi::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $anthrax = Anthrax::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $measles = Measles::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $meningo = Meningo::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $nt = Nt::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $psp = Psp::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $rabies = Rabies::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $abd = Abd::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $aes = Aes::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $ahf = Ahf::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $hepatitis = Hepatitis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $ames = Ames::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $meningitis = Meningitis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $chikv = Chikv::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $cholera = Cholera::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $dengue = Dengue::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $diph = Diph::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $ili = Influenza::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $lep = Leptospirosis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $malaria = Malaria::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $nnt = Nnt::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $pert = Pert::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $rota = Rotavirus::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $typ = Typhoid::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();

            $hfmd = Hfmd::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            })
            ->get();
        }

        $header_style = (new Style())->setFontBold();
        $rows_style = (new Style())->setShouldWrapText();

        if($anthrax->count() != 0) {
            $exp = (new FastExcel($anthrax))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/ANTHRAX.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'SentinelSite' => $row->SentinelSite,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Barangay' => $row->Barangay,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Year' => $row->Year,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'Occupation' => $row->Occupation,
                    'Workplace' => $row->Workplace,
                    'WorkAddress' => $row->WorkAddress,
                    'DOnset' => $row->DOnset,
                    'Fever' => $row->Fever,
                    'Nausea' => $row->Nausea,
                    'Headache' => $row->Headache,
                    'DryCough' => $row->DryCough,
                    'SoreThroat' => $row->SoreThroat,
                    'TroubleSwallowing' => $row->TroubleSwallowing,
                    'TroubleBreathing' => $row->TroubleBreathing,
                    'StomachPain' => $row->StomachPain,
                    'VomitingBlood' => $row->VomitingBlood,
                    'BloodyDiarrhea' => $row->BloodyDiarrhea,
                    'SweatingExcessively' => $row->SweatingExcessively,
                    'ExtremeTiredness' => $row->ExtremeTiredness,
                    'PainOrTightChest' => $row->PainOrTightChest,
                    'SoreMuscles' => $row->SoreMuscles,
                    'NeckPain' => $row->NeckPain,
                    'ItchySkin' => $row->ItchySkin,
                    'BlackScab' => $row->BlackScab,
                    'SkinLesions' => $row->SkinLesions,
                    'DescribeLesion' => $row->DescribeLesion,
                    'OtherSS' => $row->OtherSS,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'OccupAnimalAgriculture' => $row->OccupAnimalAgriculture,
                    'ExpToAnthVaccAnimal' => $row->ExpToAnthVaccAnimal,
                    'ExpToAnimalProducts' => $row->ExpToAnimalProducts,
                    'ContactLiveDeadAnimal' => $row->ContactLiveDeadAnimal,
                    'TravelBeyondResidence' => $row->TravelBeyondResidence,
                    'WorkInLaboratory' => $row->WorkInLaboratory,
                    'HHMembersExpSimilarSymp' => $row->HHMembersExpSimilarSymp,
                    'EatenUndercookedMeat' => $row->EatenUndercookedMeat,
                    'ReceivedLettersPackage' => $row->ReceivedLettersPackage,
                    'OpenedMailsForOthers' => $row->OpenedMailsForOthers,
                    'NearOpenedEnveloped' => $row->NearOpenedEnveloped,
                    'Cutaneous' => $row->Cutaneous,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'Gastrointestinal' => $row->Gastrointestinal,
                    'Pulmonary' => $row->Pulmonary,
                    'Meningeal' => $row->Meningeal,
                    'UnknownClinicalForm' => $row->UnknownClinicalForm,
                    'Specimen1' => $row->Specimen1,
                    'DateSpecimen1Taken' => $row->DateSpecimen1Taken,
                    'ResultSpecimen1' => $row->ResultSpecimen1,
                    'DateResult1' => $row->DateResult1,
                    'SpecifyOrganism1' => $row->SpecifyOrganism1,
                    'Specimen2' => $row->Specimen2,
                    'DateSpecimen2Taken' => $row->DateSpecimen2Taken,
                    'Result2' => $row->Result2,
                    'SpecifyOrganism2' => $row->SpecifyOrganism2,
                    'ResultSpecimen2' => $row->ResultSpecimen2,
                    'DeleteRecord' => $row->DeleteRecord,
                    'DateResult2' => $row->DateResult2,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }
        
        if($measles->count() != 0) {
            $exp = (new FastExcel($measles))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/MEASLES.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Address' => $row->Address,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'Preggy' => $row->Preggy,
                    'WkOfPreg' => $row->WkOfPreg,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DONSET' => $row->DONSET,
                    'VitaminA' => $row->VitaminA,
                    'FeverOnset' => $row->FeverOnset,
                    'MeasVacc' => $row->MeasVacc,
                    'Cough' => $row->Cough,
                    'KoplikSpot' => $row->KoplikSpot,
                    'MVDose' => $row->MVDose,
                    'MRDose' => $row->MRDose,
                    'MMRDose' => $row->MMRDose,
                    'LastVacc' => $row->LastVacc,
                    'RunnyNose' => $row->RunnyNose,
                    'RedEyes' => $row->RedEyes,
                    'ArthritisArthralgia' => $row->ArthritisArthralgia,
                    'SwoLympNod' => $row->SwoLympNod,
                    'LympNodLoc' => $row->LympNodLoc,
                    'OthLocation' => $row->OthLocation,
                    'OthSymptoms' => $row->OthSymptoms,
                    'AreThereAny' => $row->AreThereAny,
                    'Complications' => $row->Complications,
                    'Reporter' => $row->Reporter,
                    'Investigator' => $row->Investigator,
                    'RContactNum' => $row->RContactNum,
                    'ContactNum' => $row->ContactNum,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'ReportToInvestigation' => $row->ReportToInvestigation,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'Reasons' => $row->Reasons,
                    'OtherReasons' => $row->OtherReasons,
                    'SpecialCampaigns' => $row->SpecialCampaigns,
                    'Travel' => $row->Travel,
                    'PlaceTravelled' => $row->PlaceTravelled,
                    'TravTiming' => $row->TravTiming,
                    'ProbExposure' => $row->ProbExposure,
                    'OtherExposure' => $row->OtherExposure,
                    'OtherCase' => $row->OtherCase,
                    'RashOnset' => $row->RashOnset,
                    'WholeBloodColl' => $row->WholeBloodColl,
                    'DriedBloodColl' => $row->DriedBloodColl,
                    'OP/NPSwabColl' => $row['OP/NPSwabColl'],
                    'DateWBtaken' => $row->DateWBtaken,
                    'DateWBsent' => $row->DateWBsent,
                    'DateDBtaken' => $row->DateDBtaken,
                    'DateDBsent' => $row->DateDBsent,
                    'OPNPSwabtaken' => $row->OPNPSwabtaken,
                    'OPNPSwabsent' => $row->OPNPSwabsent,
                    'OPSwabPCRRes' => $row->OPSwabPCRRes,
                    'OPNpSwabResult' => $row->OPNpSwabResult,
                    'DateWBRecvd' => $row->DateWBRecvd,
                    'DateDBRecvd' => $row->DateDBRecvd,
                    'OPNPSwabRecvd' => $row->OPNPSwabRecvd,
                    'OraColColl' => $row->OraColColl,
                    'OraColD8taken' => $row->OraColD8taken,
                    'OraColD8sent' => $row->OraColD8sent,
                    'OraColD8Recvd' => $row->OraColD8Recvd,
                    'OraColPCRRes' => $row->OraColPCRRes,
                    'FinalClass' => $row->FinalClass,
                    'InfectionSource' => $row->InfectionSource,
                    'Outcome' => $row->Outcome,
                    'FinalDx' => $row->FinalDx,
                    'Death' => $row->Death,
                    'DCaseRep' => $row->DCaseRep,
                    'DCASEINV' => $row->DCASEINV,
                    'SentinelSite' => $row->SentinelSite,
                    'Year' => $row->Year,
                    'DeleteRecord' => $row->DeleteRecord,
                    'WBRubellaIgM' => $row->WBRubellaIgM,
                    'WBMeaslesIgM' => $row->WBMeaslesIgM,
                    'DBMeaslesIgM' => $row->DBMeaslesIgM,
                    'DBRubellaIgM' => $row->DBRubellaIgM,
                    'ContactConfirmedCase' => $row->ContactConfirmedCase,
                    'ContactName' => $row->ContactName,
                    'ContactPlace' => $row->ContactPlace,
                    'ContactDate' => $row->ContactDate,
                    'NameOfDru' => substr($row->NameOfDru,0,30),
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'Labcode' => $row->Labcode,
                    'ContactConfirmedRubella' => $row->ContactConfirmedRubella,
                    'TravRegion' => $row->TravRegion,
                    'TravMun' => $row->TravMun,
                    'TravProv' => $row->TravProv,
                    'TravBgy' => $row->TravBgy,
                    'Travelled' => $row->Travelled,
                    'DateTrav' => $row->DateTrav,
                    'Report2Inv' => $row->Report2Inv,
                    'Birth2RashOnset' => $row->Birth2RashOnset,
                    'OnsetToReport' => $row->OnsetToReport,
                    'IP' => $row->IP,
                    'IPgroup' => $row->IPgroup,
                ];
            });
        }

        if($meningo->count() != 0) {
            $exp = (new FastExcel($meningo))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/MENINGO.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'NameOfDru' => $row->NameOfDru,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'SentinelSite' => $row->SentinelSite,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Region' => $row->Region,
                    'Muncity' => $row->Muncity,
                    'Province' => $row->Province,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Occupation' => $row->Occupation,
                    'Workplace' => $row->Workplace,
                    'WrkplcAddr' => $row->WrkplcAddr,
                    'SchlAddr' => $row->SchlAddr,
                    'School' => $row->School,
                    'Year' => $row->Year,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'Fever' => $row->Fever,
                    'Seizure' => $row->Seizure,
                    'Malaise' => $row->Malaise,
                    'Headache' => $row->Headache,
                    'StiffNeck' => $row->StiffNeck,
                    'Cough' => $row->Cough,
                    'Rash' => $row->Rash,
                    'Vomiting' => $row->Vomiting,
                    'SoreThroat' => $row->SoreThroat,
                    'Petechia' => $row->Petechia,
                    'SensoriumCh' => $row->SensoriumCh,
                    'RunnyNose' => $row->RunnyNose,
                    'Purpura' => $row->Purpura,
                    'Drowsiness' => $row->Drowsiness,
                    'Dyspnea' => $row->Dyspnea,
                    'Othlesions' => $row->Othlesions,
                    'OtherSS' => $row->OtherSS,
                    'ClinicalPres' => $row->ClinicalPres,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'Bld_CSF' => $row->Bld_CSF,
                    'Antibiotics' => $row->Antibiotics,
                    'CSFSpecimen' => $row->CSFSpecimen,
                    'CultureDone' => $row->CultureDone,
                    'DateCSFTakenCulture' => $row->DateCSFTakenCulture,
                    'CSFCultureResult' => $row->CSFCultureResult,
                    'DateCSFCultureResult' => $row->DateCSFCultureResult,
                    'CSFCultureOrganism' => $row->CSFCultureOrganism,
                    'LatexAggluDone' => $row->LatexAggluDone,
                    'DateCSFTakenLatex' => $row->DateCSFTakenLatex,
                    'CSFLatexResult' => $row->CSFLatexResult,
                    'DateCSFLatexResult' => $row->DateCSFLatexResult,
                    'CSFLatexOrganism' => $row->CSFLatexOrganism,
                    'GramStainDone' => $row->GramStainDone,
                    'CSFGramStainResult' => $row->CSFGramStainResult,
                    'DateCSFTakenGramstain' => $row->DateCSFTakenGramstain,
                    'GramStainOrganism' => $row->GramStainOrganism,
                    'BloodSpecimen' => $row->BloodSpecimen,
                    'BloodCultureDone' => $row->BloodCultureDone,
                    'BloodCultureResult' => $row->BloodCultureResult,
                    'DateBloodCultureResult' => $row->DateBloodCultureResult,
                    'DateBloodTakenCulture' => $row->DateBloodTakenCulture,
                    'BloodCultureOrganism' => $row->BloodCultureOrganism,
                    'DateCSFGramResult' => $row->DateCSFGramResult,
                    'Interact' => $row->Interact,
                    'ContactName' => $row->ContactName,
                    'SuspName' => $row->SuspName,
                    'SuspAddress' => $row->SuspAddress,
                    'PlaceInteract' => $row->PlaceInteract,
                    'DateInteract' => $row->DateInteract,
                    'DaysNum' => $row->DaysNum,
                    'PtTravel' => $row->PtTravel,
                    'PlacePtTravel' => $row->PlacePtTravel,
                    'ContactTravel' => $row->ContactTravel,
                    'PlaceContactTravel' => $row->PlaceContactTravel,
                    'AttendSocicalGather' => $row->AttendSocicalGather,
                    'PlaceSocialGather' => $row->PlaceSocialGather,
                    'PatientURTI' => $row->PatientURTI,
                    'ContactURTI' => $row->ContactURTI,
                    'District' => $row->District,
                    'InterLocal' => $row->InterLocal,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'DELETERECORD' => $row->DELETERECORD,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($nt->count() != 0) {
            $exp = (new FastExcel($nt))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/NT.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Address' => $row->Address,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DateOfReport' => $row->DateOfReport,
                    'DateOfInvestigation' => $row->DateOfInvestigation,
                    'Investigator' => $row->Investigator,
                    'ContactNum' => $row->ContactNum,
                    'First2days' => $row->First2days,
                    'After2days' => $row->After2days,
                    'FinalDx' => $row->FinalDx,
                    'Trismus' => $row->Trismus,
                    'ClenFis' => $row->ClenFis,
                    'Opistho' => $row->Opistho,
                    'StumpInf' => $row->StumpInf,
                    'Year' => $row->Year,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'ReportToInvestigation' => $row->ReportToInvestigation,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'TotPreg' => $row->TotPreg,
                    'Livebirths' => $row->Livebirths,
                    'TTDose' => $row->TTDose,
                    'LivingKids' => $row->LivingKids,
                    'LastDoseGiven' => $row->LastDoseGiven,
                    'DosesGiven' => $row->DosesGiven,
                    'PreVisits' => $row->PreVisits,
                    'ImmunStatRep' => $row->ImmunStatRep,
                    'FirstPV' => $row->FirstPV,
                    'ChldProt' => $row->ChldProt,
                    'PNCHist' => $row->PNCHist,
                    'Reason' => $row->Reason,
                    'PlaceDel' => $row->PlaceDel,
                    'OtherPlaceDelivery' => $row->OtherPlaceDelivery,
                    'NameAddressHospital' => $row->NameAddressHospital,
                    'OtherInstrument' => $row->OtherInstrument,
                    'DelAttnd' => $row->DelAttnd,
                    'OtherAttendant' => $row->OtherAttendant,
                    'CordCut' => $row->CordCut,
                    'StumpTreat' => $row->StumpTreat,
                    'OtherMaterials' => $row->OtherMaterials,
                    'FinalClass' => $row->FinalClass,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DONSET' => $row->DONSET,
                    'Mother' => $row->Mother,
                    'DOBtoOnset' => $row->DOBtoOnset,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($psp->count() != 0) {
            $exp = (new FastExcel($psp))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/PSP.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'SentinelSite' => $row->SentinelSite,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'Year' => $row->Year,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'ReportToInvestigation' => $row->ReportToInvestigation,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'DOnset' => $row->DOnset,
                    'PlaceHarvested' => $row->PlaceHarvested,
                    'HHMealShare' => $row->HHMealShare,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DeleteRecord' => $row->DeleteRecord,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($rabies->count() != 0) {
            $exp = (new FastExcel($rabies))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/RABIES.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'SentinelSite' => $row->SentinelSite,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Weight' => $row->Weight,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'Year' => $row->Year,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'PlaceOfIncidence' => $row->PlaceOfIncidence,
                    'TypeOfExposure' => $row->TypeOfExposure,
                    'Category' => substr($row->Category,0,25),
                    'BiteSite' => $row->BiteSite,
                    'OtherTypeOfExposure' => $row->OtherTypeOfExposure,
                    'DateBitten' => $row->DateBitten,
                    'TypeOfAnimal' => $row->TypeOfAnimal,
                    'OtherTypeOfAnimal' => $row->OtherTypeOfAnimal,
                    'LabDiagnosis' => $row->LabDiagnosis,
                    'LabResult' => $row->LabResult,
                    'AnimalStatus' => $row->AnimalStatus,
                    'OtherAnimalStatus' => $row->OtherAnimalStatus,
                    'DateVaccStarted' => $row->DateVaccStarted,
                    'Vaccine' => $row->Vaccine,
                    'AdminRoute' => $row->AdminRoute,
                    'PostExposureComplete' => $row->PostExposureComplete,
                    'AnimalVaccination' => $row->AnimalVaccination,
                    'WoundCleaned' => $row->WoundCleaned,
                    'Rabiesvaccine' => $row->Rabiesvaccine,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Outcomeanimal' => $row->Outcomeanimal,
                    'RIG' => $row->RIG,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($abd->count() != 0) {
            $exp = (new FastExcel($abd))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/ABD.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOFDrU' => $row->RegionOFDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Muncity' => $row->Muncity,
                    'Province' => $row->Province,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'StoolCulture' => $row->StoolCulture,
                    'Organism' => $row->Organism,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'InterLocal' => $row->InterLocal,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($aefi->count() != 0) {
            $exp = (new FastExcel($aefi))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/AEFI.xlsx'), function ($row) {
                return [
                    'Kaso' => $row->Kaso,
                    'NameOfDru' => $row->NameOfDru,
                    'RegionOFDrU' => $row->RegionOFDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'TYPEOFDRU' => $row->TYPEOFDRU,
                    'SentinelSite' => $row->SentinelSite,
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'Sex' => $row->Sex,
                    'Region' => $row->Region,
                    'Muncity' => $row->Muncity,
                    'Province' => $row->Province,
                    'Streetpurok' => $row->Streetpurok,
                    'Barangay' => $row->Barangay,
                    'EPIID' => $row->EPIID,
                    'Donset' => $row->Donset,
                    'DateInvestigation' => $row->DateInvestigation,
                    'NameReporter' => $row->NameReporter,
                    'RepContact' => $row->RepContact,
                    'NameInvestigator' => $row->NameInvestigator,
                    'InvestigatorContact' => $row->InvestigatorContact,
                    'VaccinationCenter' => $row->VaccinationCenter,
                    'Vacc_Session' => $row->Vacc_Session,
                    'Vacc_sess_Others' => $row->Vacc_sess_Others,
                    'Anaphylactoid' => $row->Anaphylactoid,
                    'Anaphylaxis' => $row->Anaphylaxis,
                    'BrachialNeuritis' => $row->BrachialNeuritis,
                    'Diss_BCG_infect' => $row->Diss_BCG_infect,
                    'Encephalopathy' => $row->Encephalopathy,
                    'HHE' => $row->HHE,
                    'InjectSiteAbcess' => $row->InjectSiteAbcess,
                    'Intussusception' => $row->Intussusception,
                    'Lymphadenitis' => $row->Lymphadenitis,
                    'Osteitis' => $row->Osteitis,
                    'Persistent' => $row->Persistent,
                    'Seizures' => $row->Seizures,
                    'Sepsis' => $row->Sepsis,
                    'Severelocal' => $row->Severelocal,
                    'Thrombocytopenia' => $row->Thrombocytopenia,
                    'Outcome' => $row->Outcome,
                    'OtherOutcome' => $row->OtherOutcome,
                    'Alivecondition' => $row->Alivecondition,
                    'Disability' => $row->Disability,
                    'DateDied' => $row->DateDied,
                    'HistoryAllergy' => $row->HistoryAllergy,
                    'Preillness' => $row->Preillness,
                    'HistHosp' => $row->HistHosp,
                    'HistTrauma' => $row->HistTrauma,
                    'CurrPreg' => $row->CurrPreg,
                    'AOG' => $row->AOG,
                    'CurrBreastfeeding' => $row->CurrBreastfeeding,
                    'Delivery' => $row->Delivery,
                    'NatalHist' => $row->NatalHist,
                    'AnyCompli' => $row->AnyCompli,
                    'PtCurrMedic' => $row->PtCurrMedic,
                    'MedicSpecify' => $row->MedicSpecify,
                    'FamHist' => $row->FamHist,
                    'VaccExp' => $row->VaccExp,
                    'Prev_vacc' => $row->Prev_vacc,
                    'PtImmunized1' => $row->PtImmunized1,
                    'PtImmunized2' => $row->PtImmunized2,
                    'RecomNotfollowed' => $row->RecomNotfollowed,
                    'VaccAdmin' => $row->VaccAdmin,
                    'VaccPhysicalCon' => $row->VaccPhysicalCon,
                    'ErrVaccRecon' => $row->ErrVaccRecon,
                    'ErrVaccHandle' => $row->ErrVaccHandle,
                    'VaccAdminIncorrect' => $row->VaccAdminIncorrect,
                    'NoImmunizedVacc' => $row->NoImmunizedVacc,
                    'NoIimmunizedVaccSameSession' => $row->NoIimmunizedVaccSameSession,
                    'NoImmunizedSameBatch' => $row->NoImmunizedSameBatch,
                    'SameBatchLoc' => $row->SameBatchLoc,
                    'CasePartCluster' => $row->CasePartCluster,
                    'NoCasesCluster' => $row->NoCasesCluster,
                    'CasesSameVial' => $row->CasesSameVial,
                    'NoVialsUsedCluster' => $row->NoVialsUsedCluster,
                    'SimilarEventComm' => $row->SimilarEventComm,
                    'YesDescribe' => $row->YesDescribe,
                    'NoEventsEpisodes' => $row->NoEventsEpisodes,
                    'NoVaccinated' => $row->NoVaccinated,
                    'NoNotVaccinated' => $row->NoNotVaccinated,
                    'Unknown' => $row->Unknown,
                    'CausalityAssess' => $row->CausalityAssess,
                    'DateClass' => $row->DateClass,
                    'A1' => $row->A1,
                    'A2' => $row->A2,
                    'A3' => $row->A3,
                    'A3Others' => $row->A3Others,
                    'A4' => $row->A4,
                    'B1' => $row->B1,
                    'B2' => $row->B2,
                    'C1' => $row->C1,
                    'D' => $row->D,
                    'TypeofAEFI' => $row->TypeofAEFI,
                    'SENT' => $row->SENT,
                    'IP' => $row->IP,
                    'IPGROUP' => $row->IPGROUP,
                    'SIgnificantfinding' => $row->SIgnificantfinding,
                    'OtherSign' => $row->OtherSign,
                    'OtherSymptoms' => $row->OtherSymptoms,
                    'ToxicshockSyndrome' => $row->ToxicshockSyndrome,
                    'Admitted' => $row->Admitted,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'DOB' => $row->DOB,
                    'HIGHLEVELNOTIFIED' => $row->HIGHLEVELNOTIFIED,
                    'DAdmit' => $row->DAdmit,
                    'DONSETTIME' => $row->DONSETTIME,
                    'MORBIDITYWEEK' => $row->MORBIDITYWEEK,
                    'MORBIDITYMONTH' => $row->MORBIDITYMONTH,
                    'YEAR' => $row->YEAR,
                ];
            });
        }

        if($aes->count() != 0) {
            $exp = (new FastExcel($aes))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/AES.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'LabResult' => $row->LabResult,
                    'Organism' => $row->Organism,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'ILHZ' => $row->ILHZ,
                    'District' => $row->District,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                    'sari' => $row->sari,
                ];
            });
        }

        if($ahf->count() != 0) {
            $exp = (new FastExcel($ahf))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/AHF.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'PCRRes' => $row->PCRRes,
                    'PCROrganism' => $row->PCROrganism,
                    'BloodCultRes' => $row->BloodCultRes,
                    'CultureOrganism' => $row->CultureOrganism,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($hepatitis->count() != 0) {
            $exp = (new FastExcel($hepatitis))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/HEPATITIS.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Muncity' => $row->Muncity,
                    'Province' => $row->Province,
                    'Streetpurok' => substr($row->Streetpurok,0,50),
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'Type' => $row->Type,
                    'LabResult' => $row->LabResult,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'TypeOfHepatitis' => $row->TypeOfHepatitis,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'ILHZ' => $row->ILHZ,
                    'District' => $row->District,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($ames->count() != 0) {
            $exp = (new FastExcel($ames))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/AMES.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'NHTS' => $row->NHTS,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'DateRep' => $row->DateRep,
                    'DateInv' => $row->DateInv,
                    'Investigator' => $row->Investigator,
                    'ContactNum' => $row->ContactNum,
                    'InvDesig' => $row->InvDesig,
                    'Fever' => $row->Fever,
                    'BehaviorChng' => $row->BehaviorChng,
                    'Seizure' => $row->Seizure,
                    'Stiffneck' => $row->Stiffneck,
                    'bulgefontanel' => $row->bulgefontanel,
                    'MenSign' => $row->MenSign,
                    'ClinDiag' => $row->ClinDiag,
                    'OtherDiag' => $row->OtherDiag,
                    'JE' => $row->JE,
                    'VacJeDate' => $row->VacJeDate,
                    'JEDose' => $row->JEDose,
                    'Hib' => $row->Hib,
                    'VacHibDate' => $row->VacHibDate,
                    'HibDose' => $row->HibDose,
                    'PCV10' => $row->PCV10,
                    'VacPCV10Date' => $row->VacPCV10Date,
                    'PCV10Dose' => $row->PCV10Dose,
                    'PCV13' => $row->PCV13,
                    'VacPCV13Date' => $row->VacPCV13Date,
                    'PCV13Dose' => $row->PCV13Dose,
                    'MeningoVacc' => $row->MeningoVacc,
                    'VacMeningoDate' => $row->VacMeningoDate,
                    'MeningoVaccDose' => $row->MeningoVaccDose,
                    'MeasVacc' => $row->MeasVacc,
                    'VacMeasDate' => $row->VacMeasDate,
                    'MeasVaccDose' => $row->MeasVaccDose,
                    'MMR' => $row->MMR,
                    'VacMMRDate' => $row->VacMMRDate,
                    'MMRDose' => $row->MMRDose,
                    'plcDaycare' => $row->plcDaycare,
                    'plcBrgy' => $row->plcBrgy,
                    'plcHome' => $row->plcHome,
                    'plcSchool' => $row->plcSchool,
                    'plcdormitory' => $row->plcdormitory,
                    'plcHC' => $row->plcHC,
                    'plcWorkplace' => $row->plcWorkplace,
                    'plcOther' => $row->plcOther,
                    'Travel' => $row->Travel,
                    'PlaceTravelled' => $row->PlaceTravelled,
                    'FrmTrvlDate' => $row->FrmTrvlDate,
                    'ToTrvlDate' => $row->ToTrvlDate,
                    'CSFColl' => $row->CSFColl,
                    'D8CSFTaken' => $row->D8CSFTaken,
                    'TymCSFTaken' => $row->TymCSFTaken,
                    'D8CSFHospLab' => $row->D8CSFHospLab,
                    'TymCSFHospLab' => $row->TymCSFHospLab,
                    'CSFAppearance' => $row->CSFAppearance,
                    'GramStain' => $row->GramStain,
                    'GramStainResult' => $row->GramStainResult,
                    'culture' => $row->culture,
                    'CultureResult' => $row->CultureResult,
                    'OtherTest' => $row->OtherTest,
                    'OtherTestResult' => $row->OtherTestResult,
                    'D8CSFSentRITM' => $row->D8CSFSentRITM,
                    'D8CSFReceivedRITM' => $row->D8CSFReceivedRITM,
                    'CSFSampVol' => $row->CSFSampVol,
                    'D8CSFTesting' => $row->D8CSFTesting,
                    'CSFResult' => $row->CSFResult,
                    'Serum1Col' => $row->Serum1Col,
                    'D8Serum1Taken' => $row->D8Serum1Taken,
                    'D8Serum1HospLab' => $row->D8Serum1HospLab,
                    'D8Serum1Sent' => $row->D8Serum1Sent,
                    'D8Seruml1Received' => $row->D8Seruml1Received,
                    'Serum1SampVol' => $row->Serum1SampVol,
                    'D8Serum1Testing' => $row->D8Serum1Testing,
                    'Serum1Result' => $row->Serum1Result,
                    'Serum2Col' => $row->Serum2Col,
                    'D8Serum2Taken' => $row->D8Serum2Taken,
                    'D8Serum2HospLab' => $row->D8Serum2HospLab,
                    'D8Serum2Sent' => $row->D8Serum2Sent,
                    'D8Serum2Received' => $row->D8Serum2Received,
                    'Serum2SampVol' => $row->Serum2SampVol,
                    'D8Serum2testing' => $row->D8Serum2testing,
                    'Serum2Result' => $row->Serum2Result,
                    'AESCaseClass' => $row->AESCaseClass,
                    'BmCaseClass' => $row->BmCaseClass,
                    'AESOtherAgent' => $row->AESOtherAgent,
                    'ConfirmBMTest' => $row->ConfirmBMTest,
                    'FinalDiagnosis' => $row->FinalDiagnosis,
                    'Outcome' => $row->Outcome,
                    'DateOfEntry' => $row->DateOfEntry,
                    'DateDisch' => $row->DateDisch,
                    'DateDied' => $row->DateDied,
                    'RecoverSequelae' => $row->RecoverSequelae,
                    'SequelaeSpecs' => $row->SequelaeSpecs,
                    'TransTo' => $row->TransTo,
                    'HAMA' => $row->HAMA,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'ILHZ' => $row->ILHZ,
                    'District' => $row->District,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($meningitis->count() != 0) {
            $exp = (new FastExcel($meningitis))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/MENINGITIS.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'Type' => $row->Type,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'LabResult' => $row->LabResult,
                    'Organism' => $row->Organism,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($chikv->count() != 0) {
            $exp = (new FastExcel($chikv))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/ChikV.xlsx'), function ($row) {
                return [
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'DateOfEntry' => $row->DateOfEntry,
                    'DRU' => substr($row->DRU,0,25),
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DOB' => $row->DOB,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'CaseClass' => mb_strtoupper($row->CaseClass),
                    'DCaseRep' => $row->DCaseRep,
                    'DCASEINV' => $row->DCASEINV,
                    'DayswidSymp' => $row->DayswidSymp,
                    'Fever' => $row->Fever,
                    'Arthritis' => $row->Arthritis,
                    'Hands' => $row->Hands,
                    'Feet' => $row->Feet,
                    'Ankles' => $row->Ankles,
                    'OthSite' => $row->OthSite,
                    'Arthralgia' => $row->Arthralgia,
                    'PeriEdema' => $row->PeriEdema,
                    'SkinMani' => $row->SkinMani,
                    'SkinDesc' => $row->SkinDesc,
                    'Myalgia' => $row->Myalgia,
                    'BackPain' => $row->BackPain,
                    'Headache' => $row->Headache,
                    'Nausea' => $row->Nausea,
                    'MucosBleed' => $row->MucosBleed,
                    'Vomiting' => $row->Vomiting,
                    'Asthenia' => $row->Asthenia,
                    'MeningoEncep' => $row->MeningoEncep,
                    'OthSymptom' => $row->OthSymptom,
                    'ClinDx' => $row->ClinDx,
                    'DCollected' => $row->DCollected,
                    'DSpecSent' => $row->DSpecSent,
                    'SerIgM' => $row->SerIgM,
                    'IgM_Res' => $row->IgM_Res,
                    'DIgMRes' => $row->DIgMRes,
                    'SerIgG' => $row->SerIgG,
                    'IgG_Res' => $row->IgG_Res,
                    'DIgGRes' => $row->DIgGRes,
                    'RT_PCR' => $row->RT_PCR,
                    'RT_PCRRes' => $row->RT_PCRRes,
                    'DRtPCRRes' => $row->DRtPCRRes,
                    'VirIso' => $row->VirIso,
                    'VirIsoRes' => $row->VirIsoRes,
                    'DVirIsoRes' => $row->DVirIsoRes,
                    'TravHist' => $row->TravHist,
                    'PlaceofTravel' => $row->PlaceofTravel,
                    'Residence' => $row->Residence,
                    'BldTransHist' => $row->BldTransHist,
                    'Reporter' => $row->Reporter,
                    'ReporterContNum' => $row->ReporterContNum,
                    'Outcome' => $row->Outcome,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'EPIID' => $row->EPIID,
                    'DateDied' => $row->DateDied,
                    'Icd10Code' => $row->Icd10Code,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'Recstatus' => $row->Recstatus,
                    'UniqueKey' => $row->UniqueKey,
                    'NameOfDru' => $row->NameOfDru,
                    'ILHZ' => $row->ILHZ,
                    'District' => $row->District,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($cholera->count() != 0) {
            $exp = (new FastExcel($cholera))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/CHOLERA.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'StoolCulture' => $row->StoolCulture,
                    'Organism' => $row->Organism,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($dengue->count() != 0) {
            $exp = (new FastExcel($dengue))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/DENGUE.xlsx'), function ($row) {
                return [
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'DateOfEntry' => $row->DateOfEntry,
                    'DRU' => substr($row->DRU,0,25),
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DOB' => $row->DOB,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'Type' => $row->Type,
                    'LabTest' => $row->LabTest,
                    'LabRes' => $row->LabRes,
                    'ClinClass' => $row->ClinClass,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'EPIID' => $row->EPIID,
                    'DateDied' => $row->DateDied,
                    'Icd10Code' => $row->Icd10Code,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'Recstatus' => $row->Recstatus,
                    'UniqueKey' => $row->UniqueKey,
                    'NameOfDru' => $row->NameOfDru,
                    'ILHZ' => $row->ILHZ,
                    'District' => $row->District,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($diph->count() != 0) {
            $exp = (new FastExcel($diph))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/DIPH.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'DptDoses' => $row->DptDoses,
                    'DateLastDose' => $row->DateLastDose,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($ili->count() != 0) {
            $exp = (new FastExcel($ili))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/INFLUENZA.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Muncity' => $row->Muncity,
                    'Province' => $row->Province,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'LabResult' => $row->LabResult,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SARI' => $row->SARI,
                    'Organism' => $row->Organism,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($lep->count() != 0) {
            $exp = (new FastExcel($lep))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/LEPTOSPIROSIS.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'LabRes' => $row->LabRes,
                    'Serovar' => $row->Serovar,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'Occupation' => $row->Occupation,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($malaria->count() != 0) {
            $exp = (new FastExcel($malaria))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/MALARIA.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Muncity' => $row->Muncity,
                    'Province' => $row->Province,
                    'Streetpurok' => $row->Streetpurok,
                    'DOnset' => $row->DOnset,
                    'Parasite' => $row->Parasite,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'TravelHist' => $row->TravelHist,
                    'Endemicarea' => $row->Endemicarea,
                    'BldTrans' => $row->BldTrans,
                    'SentinelSite' => $row->SentinelSite,
                    'PHILMISSite' => $row->PHILMISSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($nnt->count() != 0) {
            $exp = (new FastExcel($nnt))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/NNT.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'RecentAcuteWound' => $row->RecentAcuteWound,
                    'WoundSite' => $row->WoundSite,
                    'WoundType' => $row->WoundType,
                    'OtherWound' => $row->OtherWound,
                    'TetanusToxoid' => $row->TetanusToxoid,
                    'TetanusAntitoxin' => $row->TetanusAntitoxin,
                    'SkinLesion' => $row->SkinLesion,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($pert->count() != 0) {
            $exp = (new FastExcel($pert))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/PERT.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'DptDoses' => $row->DptDoses,
                    'DateLastDose' => $row->DateLastDose,
                    'CaseClassification' => $row->CaseClassification,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($rota->count() != 0) {
            $exp = (new FastExcel($rota))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/RotaVirus.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'DRUContactNum' => $row->DRUContactNum,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'MidName' => $row->MidName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'NHTS' => $row->NHTS,
                    'IVTherapy' => $row->IVTherapy,
                    'Vomiting' => $row->Vomiting,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'D_ONSET' => $row->D_ONSET,
                    'DateRep' => $row->DateRep,
                    'DateInv' => $row->DateInv,
                    'Investigator' => $row->Investigator,
                    'ContactNum' => $row->ContactNum,
                    'InvDesignation' => $row->InvDesignation,
                    'Fever' => $row->Fever,
                    'Temp' => $row->Temp,
                    'V_ONSET' => $row->V_ONSET,
                    'AdmDx' => $row->AdmDx,
                    'FinalDx' => $row->FinalDx,
                    'DegDehy' => $row->DegDehy,
                    'DiarrCases' => $row->DiarrCases,
                    'Community' => $row->Community,
                    'HHold' => $row->HHold,
                    'School' => $row->School,
                    'RotaVirus' => $row->RotaVirus,
                    'RVDose' => $row->RVDose,
                    'D8RV1stDose' => $row->D8RV1stDose,
                    'D8RVLastDose' => $row->D8RVLastDose,
                    'StoolColl' => $row->StoolColl,
                    'D8StoolTaken' => $row->D8StoolTaken,
                    'D8StoolSent' => $row->D8StoolSent,
                    'D8StoolRecvd' => $row->D8StoolRecvd,
                    'Amount' => $row->Amount,
                    'StoolQty' => $row->StoolQty,
                    'ElisaRes' => $row->ElisaRes,
                    'D8ElisaRes' => $row->D8ElisaRes,
                    'PCRRes' => $row->PCRRes,
                    'OthPCRRes' => $row->OthPCRRes,
                    'Genotype' => $row->Genotype,
                    'D8PCRRes' => $row->D8PCRRes,
                    'SpecCond' => $row->SpecCond,
                    'DateDisch' => $row->DateDisch,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'ILHZ' => $row->ILHZ,
                    'District' => $row->District,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'hospdiarrhea' => $row->hospdiarrhea,
                    'Datehosp' => $row->Datehosp,
                    'classification' => $row->classification,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($typ->count() != 0) {
            $exp = (new FastExcel($typ))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/TYPHOID.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DOnset' => $row->DOnset,
                    'LabResult' => $row->LabResult,
                    'Organism' => $row->Organism,
                    'Outcome' => $row->Outcome,
                    'DateDied' => $row->DateDied,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'SentinelSite' => $row->SentinelSite,
                    'DeleteRecord' => $row->DeleteRecord,
                    'Year' => $row->Year,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'CASECLASS' => $row->CASECLASS,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($hfmd->count() != 0) {
            $exp = (new FastExcel($hfmd))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/HFMD.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DONSET' => $row->DONSET,
                    'Fever' => $row->Fever,
                    'FeverOnset' => $row->FeverOnset,
                    'RashChar' => $row->RashChar,
                    'RashSores' => $row->RashSores,
                    'SoreOnset' => $row->SoreOnset,
                    'Palms' => $row->Palms,
                    'Fingers' => $row->Fingers,
                    'FootSoles' => $row->FootSoles,
                    'Buttocks' => $row->Buttocks,
                    'MouthUlcers' => $row->MouthUlcers,
                    'Pain' => $row->Pain,
                    'Anorexia' => $row->Anorexia,
                    'BM' => $row->BM,
                    'SoreThroat' => $row->SoreThroat,
                    'NausVom' => $row->NausVom,
                    'DiffBreath' => $row->DiffBreath,
                    'Paralysis' => $row->Paralysis,
                    'MeningLes' => $row->MeningLes,
                    'OthSymptoms' => $row->OthSymptoms,
                    'AnyComp' => $row->AnyComp,
                    'Complic8' => $row->Complic8,
                    'Investigator' => $row->Investigator,
                    'ContactNum' => $row->ContactNum,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'ReportToInvestigation' => $row->ReportToInvestigation,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'Travel' => $row->Travel,
                    'ProbExposure' => $row->ProbExposure,
                    'OthExposure' => $row->OthExposure,
                    'OtherCase' => $row->OtherCase,
                    'RectalSwabColl' => $row->RectalSwabColl,
                    'VesicFluidColl' => $row->VesicFluidColl,
                    'StoolColl' => $row->StoolColl,
                    'ThroatSwabColl' => $row->ThroatSwabColl,
                    'DateStooltaken' => $row->DateStooltaken,
                    'DateStoolsent' => $row->DateStoolsent,
                    'DateStoolRecvd' => $row->DateStoolRecvd,
                    'StoolResult' => $row->StoolResult,
                    'StoolOrg' => $row->StoolOrg,
                    'StoolResultD8' => $row->StoolResultD8,
                    'VFSwabtaken' => $row->VFSwabtaken,
                    'VFSwabsent' => $row->VFSwabsent,
                    'VFSwabRecvd' => $row->VFSwabRecvd,
                    'VesicFluidRes' => $row->VesicFluidRes,
                    'VesicFluidOrg' => $row->VesicFluidOrg,
                    'VFSwabResultD8' => $row->VFSwabResultD8,
                    'ThroatSwabtaken' => $row->ThroatSwabtaken,
                    'ThroatSwabsent' => $row->ThroatSwabsent,
                    'ThroatSwabRecvd' => $row->ThroatSwabRecvd,
                    'ThroatSwabResult' => $row->ThroatSwabResult,
                    'ThroatSwabOrg' => $row->ThroatSwabOrg,
                    'ThroatSwabResultD8' => $row->ThroatSwabResultD8,
                    'RectalSwabtaken' => $row->RectalSwabtaken,
                    'RectalSwabsent' => $row->RectalSwabsent,
                    'RectalSwabRecvd' => $row->RectalSwabRecvd,
                    'RectalSwabResult' => $row->RectalSwabResult,
                    'RectalSwabOrg' => $row->RectalSwabOrg,
                    'RectalSwabResultD8' => $row->RectalSwabResultD8,
                    'CaseClass' => mb_strtoupper($row->CaseClass),
                    'Outcome' => $row->Outcome,
                    'WFDiag' => $row->WFDiag,
                    'Death' => $row->Death,
                    'DCaseRep' => $row->DCaseRep,
                    'DCASEINV' => $row->DCASEINV,
                    'SentinelSite' => $row->SentinelSite,
                    'Year' => $row->Year,
                    'DeleteRecord' => $row->DeleteRecord,
                    'NameOfDru' => $row->NameOfDru,
                    'District' => $row->District,
                    'ILHZ' => $row->ILHZ,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                ];
            });
        }

        if($afp->count() != 0) {
            $exp = (new FastExcel($afp))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(storage_path('app/pidsr/ftp/AFP.xlsx'), function ($row) {
                return [
                    'Icd10Code' => $row->Icd10Code,
                    'RegionOfDrU' => $row->RegionOfDrU,
                    'ProvOfDRU' => substr($row->ProvOfDRU,0,25),
                    'MuncityOfDRU' => $row->MuncityOfDRU,
                    'DRU' => substr($row->DRU,0,25),
                    'AddressOfDRU' => $row->AddressOfDRU,
                    'PatientNumber' => $row->PatientNumber,
                    'FirstName' => $row->FirstName,
                    'FamilyName' => $row->FamilyName,
                    'FullName' => ($row->from_edcs == 1) ? $row->FullName : $row->FamilyName.', '.$row->FirstName,
                    'Region' => $row->Region,
                    'Province' => $row->Province,
                    'Muncity' => $row->Muncity,
                    'Streetpurok' => $row->Streetpurok,
                    'Sex' => $row->Sex,
                    'DOB' => $row->DOB,
                    'AgeYears' => $row->AgeYears,
                    'AgeMons' => $row->AgeMons,
                    'AgeDays' => $row->AgeDays,
                    'Admitted' => $row->Admitted,
                    'DAdmit' => $row->DAdmit,
                    'DateOfReport' => $row->DateOfReport,
                    'DateOfInvestigation' => $row->DateOfInvestigation,
                    'DateOfEntry' => $row->DateOfEntry,
                    'AdmitToEntry' => $row->AdmitToEntry,
                    'OnsetToAdmit' => $row->OnsetToAdmit,
                    'MorbidityMonth' => $row->MorbidityMonth,
                    'MorbidityWeek' => $row->MorbidityWeek,
                    'EPIID' => $row->EPIID,
                    'UniqueKey' => $row->UniqueKey,
                    'RECSTATUS' => $row->RECSTATUS,
                    'Fever' => $row->Fever,
                    'DONSETP' => $row->DONSETP,
                    'RArm' => $row->RArm,
                    'Cough' => $row->Cough,
                    'ParalysisAtBirth' => $row->ParalysisAtBirth,
                    'LArm' => $row->LArm,
                    'DiarrheaVomiting' => $row->DiarrheaVomiting,
                    'Asymm' => $row->Asymm,
                    'RLeg' => $row->RLeg,
                    'MusclePain' => $row->MusclePain,
                    'LLeg' => $row->LLeg,
                    'Mening' => $row->Mening,
                    'BrthMusc' => $row->BrthMusc,
                    'NeckMusc' => $row->NeckMusctext,
                    'Paradev' => $row->Paradev,
                    'Paradir' => $row->Paradir,
                    'FacialMusc' => $row->FacialMusc,
                    'WorkingDiagnosis' => substr($row->WorkingDiagnosis,0,25),
                    'RASens' => substr($row->RASens,0,13),
                    'LASens' => substr($row->LASens,0,13),
                    'RLSens' => substr($row->RLSens,0,13),
                    'LLSens' => substr($row->LLSens,0,13),
                    'RARef' => substr($row->RARef,0,13),
                    'LARef' => substr($row->LARef,0,13),
                    'RLRef' => substr($row->RLRef,0,13),
                    'LLRef' => substr($row->LLRef,0,13),
                    'RAMotor' => substr($row->RAMotor,0,13),
                    'LAMotor' => substr($row->LAMotor,0,13),
                    'RLMotor' => substr($row->RLMotor,0,13),
                    'LLMotor' => substr($row->LLMotor,0,13),
                    'HxDisorder' => $row->HxDisorder,
                    'Disorder' => $row->Disorder,
                    'TravelPrior2Illness' => $row->TravelPrior2Illness,
                    'PlaceOfTravel' => $row->PlaceOfTravel,
                    'FrmTrvlDate' => $row->FrmTrvlDate,
                    'OtherCases' => $row->OtherCases,
                    'InjTrauAnibite' => $row->InjTrauAnibite,
                    'SpecifyInjTrauAnibite' => $row->SpecifyInjTrauAnibite,
                    'Investigator' => $row->Investigator,
                    'ContactNum' => $row->ContactNum,
                    'OPVDoses' => $row->OPVDoses,
                    'DateLastDose' => $row->DateLastDose,
                    'HotCase' => $row->HotCase,
                    'FirstStoolSpec' => $row->FirstStoolSpec,
                    'DStool1Taken' => $row->DStool1Taken,
                    'DStool2Taken' => $row->DStool2Taken,
                    'DStool1Sent' => $row->DStool1Sent,
                    'DStool2Sent' => $row->DStool2Sent,
                    'Stool1Result' => $row->Stool1Result,
                    'Stool2Result' => $row->Stool2Result,
                    'ExpDffup' => $row->ExpDffup,
                    'ActDffp' => $row->ActDffp,
                    'PhyExam' => $row->PhyExam,
                    'ReasonND' => $row->ReasonND,
                    'DateDied' => $row->DateDied,
                    'OtherReasonND' => $row->OtherReasonND,
                    'ResPara' => $row->ResPara,
                    'ResParaType' => $row->ResParaType,
                    'Atrophy' => $row->Atrophy,
                    'RAatrophy' => $row->RAatrophy,
                    'LAatrophy' => $row->LAatrophy,
                    'RLatrophy' => $row->RLatrophy,
                    'LLatrophy' => $row->LLatrophy,
                    'OthObs' => $row->OthObs,
                    'FClass' => $row->FClass,
                    'DateClass' => $row->DateClass,
                    'VAPP' => $row->VAPP,
                    'CCriteria' => $row->CCriteria,
                    'FinalDx' => substr($row->FinalDx,0,25),
                    'OtherDiagnosis' => $row->OtherDiagnosis,
                    'ReportToInvestigation' => $row->ReportToInvestigation,
                    'Stool1CollectSend' => $row->Stool1CollectSend,
                    'Stool2CollectSend' =>$row->Stool2CollectSend,
                    'Stool1SentResult' => $row->Stool1SentResult,
                    'Stool2SentResult' => $row->Stool2SentResult,
                    'Followupindicator' => $row->Followupindicator,
                    'Stool1OnsetCollect' => $row->Stool1OnsetCollect,
                    'Stool2OnsetCollect' => $row->Stool2OnsetCollect,
                    'LabResultToClassification' => $row->LabResultToClassification,
                    'Stool1ResultToClassify' => $row->Stool1ResultToClassify,
                    'Stool2ResultToClassify' => $row->Stool2ResultToClassify,
                    'ActDffup' => $row->ActDffup,
                    'DStool1Received' => $row->DStool1Received,
                    'DStool2Received' => $row->DStool2Received,
                    'Stool1RecResult' => $row->Stool1RecResult,
                    'Stool2RecResult' => $row->Stool2RecResult,
                    'SecndStoolSpec' => $row->SecndStoolSpec,
                    'DateRep' => $row->DateRep,
                    'DateInv' => $row->DateInv,
                    'Year' => $row->Year,
                    'SentinelSite' => $row->SentinelSite,
                    'ClinicalSummary' => $row->ClinicalSummary,
                    'DeleteRecord' => $row->DeleteRecord,
                    'NameOfDru' => $row->NameOfDru,
                    'ToTrvldate' => $row->ToTrvldate,
                    'ILHZ' => $row->ILHZ,
                    'District' => $row->District,
                    'Barangay' => $row->Barangay,
                    'TYPEHOSPITALCLINIC' => $row->TYPEHOSPITALCLINIC,
                    'OCCUPATION' => $row->OCCUPATION,
                    'SENT' => $row->SENT,
                    'ip' => $row->ip,
                    'ipgroup' => $row->ipgroup,
                    'Outcome' => $row->Outcome,
                    'DateOutcomeDied' => $row->DateOutcomeDied,
                ];
            });
        }

        if(request()->input('toggleRebuildMdb')) {
            return redirect()->route('pidsr.home')
            ->with('msg', 'MDB Rebuild has been successfully initiated. The next step is to use the MDB Rebuilder tool located at C:\cesu_tools\ folder')
            ->with('msgtype', 'success');   
        }
        else {
            return redirect()->route('pidsr.home')
            ->with('msg', 'Successfully Exported the cases to the FTP Server. You may now use the SUBMITTER PROGRAM Located at C:\cesu_tools\EDCS_SUBMITTER')
            ->with('msgtype', 'success');
        }
    }

    public function notifIndex() {
        $notif_list = PidsrNotifications::orderBy('created_at', 'DESC')
        ->paginate(10);

        return view('pidsr.notif_index', [
            'notif_list' => $notif_list,
        ]);
    }

    public function notifView($id) {
        $d = PidsrNotifications::findOrFail($id);

        if(!($d->ifRead())) {
            $temp_array = explode(',', $d->viewedby_id);

            $temp_array[] = auth()->user()->id;

            $d->viewedby_id = implode(',', $temp_array);
            if($d->isDirty()) {
                $d->save();
            }
        }

        return view('pidsr.notif_view', [
            'd' => $d,
        ]);
    }

    public function generateThreshold() {
        //LIST DISEASES ARRAY
        $diseases = [
            'Abd',
            'Aefi',
            'Aes',
            'Afp',
            'Ahf',
            'Ames',
            'Anthrax',
            'Chikv',
            'Cholera',
            'Dengue',
            'Diph',
            'Hepatitis',
            'Hfmd',
            'Influenza',
            'Leptospirosis',
            'Malaria',
            'Measles',
            'Meningitis',
            'Meningo',
            'Nnt',
            'Nt',
            'Pert',
            'Psp',
            'Rabies',
            'Rotavirus',
            'Typhoid',
        ];

        foreach($diseases as $d) {
            $modelClass = "App\\Models\\$d";

            foreach(range(date('Y', strtotime('-1 Year')), 2018) as $y) {
                //Create Row First if Not Exist
                $s = PidsrThreshold::where('disease', $d)
                ->where('year', $y)
                ->first();

                if(!$s) {
                    $create_row = PidsrThreshold::create([
                        'disease' => mb_strtoupper($d),
                        'year' => $y,
                    ]);
                }

                for($i=1;$i<=53;$i++) {
                    $update = PidsrThreshold::where('year', $y)
                    ->where('disease', $d)
                    ->update([
                        'mw'.$i => $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $y)->where('MorbidityWeek', $i)->count(),
                    ]);
                }
            }
        }
    }

    public function snaxVersionTwoController() {
        if(request()->input('disease') && request()->input('year') && request()->input('mweek')) {
            $sel_disease = request()->input('disease');
            $sel_year = request()->input('year');
            $sel_year_minusone = ($sel_year - 1);
            $sel_year_minusfive = ($sel_year - 5);
            $sel_week = request()->input('mweek');

            if($sel_disease == 'COVID') {
                $modelClass = "App\\Models\\Forms";
            }
            else {
                $modelClass = "App\\Models\\$sel_disease";
            }
            

            for($i=1;$i<=5;$i++) {
                for($j=1;$j<=53;$j++) {
                    ${'year' . $i . '_mw' . $j} = 0;
                }
            }

            //Initialize Display Params (Period na bibilangin sa Mapping, etc.)
            if($sel_disease == 'Pert' || $sel_disease == 'Measles' || $sel_disease == 'Influenza' || $sel_disease == 'Dengue' || $sel_disease == 'COVID') {
                $set_display_params = 'yearly';
            }
            else {
                $set_display_params = 'last3mws';
            }

            //INITIALIZE CURRENT MW
            $current_grand_total = 0;

            for($x=1;$x<=52;$x++) {
                if($sel_year == date('Y')) {
                    if($x <= $sel_week) {
                        if($sel_disease == 'COVID') {
                            ${'current_mw'.$x} = $modelClass::with('records')
                            ->whereHas('records', function ($q) {
                                $q->where('records.address_province', 'CAVITE')
                                ->where('records.address_city', 'GENERAL TRIAS');
                            })
                            ->where('status', 'approved')
                            ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                            ->whereYear('morbidityMonth', $sel_year)
                            ->whereRaw('WEEK(morbidityMonth, 1) = ?', [$x])
                            ->count();
                        }
                        else {
                            ${'current_mw'.$x} = $modelClass::where('Year', $sel_year)
                            ->where('enabled', 1)
                            ->where('match_casedef', 1)
                            ->where('MorbidityWeek', $x)
                            ->count();
                        }
                    }
                    else {
                        ${'current_mw'.$x} = 0;
                    }
                }
                else {
                    ${'current_mw'.$x} = PidsrThreshold::where('year', $sel_year)->where('disease', mb_strtoupper($sel_disease))->first()->{'mw'.$x};
                }
                
                $current_grand_total += ${'current_mw'.$x};
            }

            if($current_grand_total == 0) {
                return redirect()->back()
                ->with('msg', 'Error: Cannot generate report. There are no cases reported in '.$sel_disease.' on year '.$sel_year)
                ->with('msgtype', 'warning');
            }

            if($sel_disease == 'COVID') {
                $previous_grand_total = $modelClass::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year - 1)
                ->count();

                $hospitalized_count = $modelClass::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->count();

                $previous_death_count = $modelClass::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->where('outcomeCondition', 'Died')
                ->whereYear('morbidityMonth', $sel_year - 1)
                ->count();
                
                $death_count = $modelClass::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->where('outcomeCondition', 'Died')
                ->whereYear('morbidityMonth', $sel_year)
                ->count();
            }
            else {
                $previous_grand_total = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year - 1)
                ->count();

                $hospitalized_count = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('Admitted', 1)
                ->count();

                $previous_death_count = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year - 1)
                ->where('Outcome', 'D')
                ->count();
                
                $death_count = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('Outcome', 'D')
                ->count();
            }

            $year_toggle = 1;

            //GET LAST 5 YEARS AND ASSIGN TO VARIABLE
            foreach(range($sel_year_minusone, $sel_year_minusfive) as $y) {
                for($j=1;$j<=53;$j++) {
                    $smw = PidsrThreshold::where('year', $y)->where('disease', mb_strtoupper($sel_disease))->first();
                    if($smw) {
                        ${'year' . $year_toggle . '_mw' . $j} = $smw->{'mw'.$j};
                    }
                    else {
                        ${'year' . $year_toggle . '_mw' . $j} = 0;
                    }
                }

                $year_toggle++;
            }

            //CREATE EPI THRESHOLD

            //CREATE ALERT AND EPIDEMIC THRESHOLD
            $smw52 = PidsrThreshold::where('year', $sel_year_minusfive)->where('disease', mb_strtoupper($sel_disease))->first();

            $year1_mw52_threshold = $year2_mw52;
            $year2_mw52_threshold = $year3_mw52;
            $year3_mw52_threshold = $year4_mw52;
            $year4_mw52_threshold = $year5_mw52;
            $year5_mw52_threshold = ($smw52) ? $smw52->mw52 : 0;

            if($sel_disease == 'COVID') {
                $year1_mw1_threshold = $modelClass::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) = ?', [1])
                ->count();
            }
            else {
                $year1_mw1_threshold = $modelClass::where('Year', $sel_year)
                ->where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('MorbidityWeek', 1)
                ->count();
            }
            
            $year2_mw1_threshold = $year1_mw1;
            $year3_mw1_threshold = $year2_mw1;
            $year4_mw1_threshold = $year3_mw1;
            $year5_mw1_threshold = $year4_mw1;

            for($i=1;$i<=52;$i++) {
                if($i == 1) {
                    $collect_nums = collect([
                        $year1_mw52_threshold, $year2_mw52_threshold, $year3_mw52_threshold, $year4_mw52_threshold, $year5_mw52_threshold,
                        $year1_mw1, $year2_mw1, $year3_mw1, $year4_mw1, $year5_mw1,
                        $year1_mw2, $year2_mw2, $year3_mw2, $year4_mw2, $year5_mw2,
                    ]);
                }
                else if($i == 52) {
                    $collect_nums = collect([
                        $year1_mw1_threshold, $year2_mw1_threshold, $year3_mw1_threshold, $year4_mw1_threshold, $year5_mw1_threshold,
                        $year1_mw51, $year2_mw51, $year3_mw51, $year4_mw51, $year5_mw51,
                        $year1_mw52, $year2_mw52, $year3_mw52, $year4_mw52, $year5_mw52,
                    ]);
                }
                else {
                    $collect_nums = collect([
                        ${'year1_mw'.$i-1}, ${'year2_mw'.$i-1}, ${'year3_mw'.$i-1}, ${'year4_mw'.$i-1}, ${'year5_mw'.$i-1},
                        ${'year1_mw'.$i}, ${'year2_mw'.$i}, ${'year3_mw'.$i}, ${'year4_mw'.$i}, ${'year5_mw'.$i},
                        ${'year1_mw'.$i+1}, ${'year2_mw'.$i+1}, ${'year3_mw'.$i+1}, ${'year4_mw'.$i+1}, ${'year5_mw'.$i+1},
                    ]);
                }

                //ALERT THRESHOLD
                ${'alert_threshold_mw'.$i} = round($collect_nums->avg(), 0);
                
                $mean = $collect_nums->avg();

                $squaredDifferences = $collect_nums->map(function ($item) use ($mean) {
                    return pow($item - $mean, 2);
                });

                $variance = $squaredDifferences->avg();

                ${'epidemic_threshold_mw'.$i} = round(${'alert_threshold_mw'.$i} + (2 * sqrt($variance)),0);
            }

            //PUT THRESHOLDS INTO ARRAY
            $currentmw_array = [];
            $epidemicmw_array = [];
            $alertmw_array = [];

            for($i=1;$i<=52;$i++) {
                $currentmw_array[] = ${'current_mw'.$i};
                $epidemicmw_array[] = ${'epidemic_threshold_mw'.$i};
                $alertmw_array[] = ${'alert_threshold_mw'.$i};
            }

            //TOP 10 BARANGAYS
            $brgys = Brgy::where('city_id', 1)
            ->orderBy('brgyName', 'ASC')
            ->where('displayInList', 1)
            ->get();

            $brgy_cases_array = [];

            $threemws_total = 0;
            $fourmws_total = 0;

            foreach($brgys as $brgy) {
                if($sel_disease == 'COVID') {
                    if($sel_week == 1) {
                        $brgy_last3mw = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [1])
                        ->count();
                    }
                    else if($sel_week == 2) {
                        $brgy_last3mw = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) IN (?,?)', [1, 2])
                        ->count();
                    }
                    else {
                        $selrecentmw1 = $sel_week - 2;
                        $selrecentmw2 = $sel_week - 1;
    
                        $brgy_last3mw = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) IN (?,?,?)', [$selrecentmw1, $selrecentmw2, $sel_week])
                        ->count();
                    }
                }
                else {
                    if($sel_week == 1) {
                        $brgy_last3mw = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', 1)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                    }
                    else if($sel_week == 2) {
                        $brgy_last3mw = $modelClass::where('Year', $sel_year)
                        ->whereIn('MorbidityWeek', [1,2])
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                    }
                    else {
                        $selrecentmw1 = $sel_week - 2;
                        $selrecentmw2 = $sel_week - 1;
    
                        $brgy_last3mw = $modelClass::where('Year', $sel_year)
                        ->whereIn('MorbidityWeek', [$selrecentmw1, $selrecentmw2, $sel_week])
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                    }
                }
                
                //FOR PREVIOUS 4 MWS
                if($sel_disease == 'COVID') {
                    if($sel_week == 1) {
                        $brgy_mw1 = 0;
                        $brgy_mw2 = 0;
                        $brgy_mw3 = 0;
                        $brgy_mw4 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [1])
                        ->count();
                    }
                    else if($sel_week == 2) {
                        $brgy_mw1 = 0;
                        $brgy_mw2 = 0;
                        $brgy_mw3 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [2])
                        ->count();
                        
                        $brgy_mw4 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [1])
                        ->count();
                    }
                    else if($sel_week == 3) {
                        $brgy_mw1 = 0;
                        $brgy_mw2 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [3])
                        ->count();

                        $brgy_mw3 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [2])
                        ->count();

                        $brgy_mw4 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [1])
                        ->count();
                    }
                    else {
                        $brgy_mw1 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [$sel_week - 3])
                        ->count();

                        $brgy_mw2 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [$sel_week - 2])
                        ->count();
                        
                        $brgy_mw3 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [$sel_week - 1])
                        ->count();

                        $brgy_mw4 = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS')
                            ->where('records.address_brgy', $brgy->brgyName);
                        })
                        ->where('status', 'approved')
                        ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [1])
                        ->count();
                    }
                }
                else {
                    if($sel_week == 1) {
                        $brgy_mw1 = 0;
                        $brgy_mw2 = 0;
                        $brgy_mw3 = 0;
                        $brgy_mw4 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', 1)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                    }
                    else if($sel_week == 2) {
                        $brgy_mw1 = 0;
                        $brgy_mw2 = 0;
                        $brgy_mw3 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', 2)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                        $brgy_mw4 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', 1)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                    }
                    else if($sel_week == 3) {
                        $brgy_mw1 = 0;
                        $brgy_mw2 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', 3)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                        $brgy_mw3 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', 2)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                        $brgy_mw4 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', 1)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                    }
                    else {
                        $brgy_mw1 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', $sel_week-3)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                        $brgy_mw2 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', $sel_week-2)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                        $brgy_mw3 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', $sel_week-1)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                        $brgy_mw4 = $modelClass::where('Year', $sel_year)
                        ->where('MorbidityWeek', $sel_week)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Barangay', $brgy->brgyName)
                        ->count();
                    }
                }

                if($sel_disease == 'COVID') {
                    $brgy_grand_total_cases = $modelClass::with('records')
                    ->whereHas('records', function ($q) use ($brgy) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS')
                        ->where('records.address_brgy', $brgy->brgyName);
                    })
                    ->where('status', 'approved')
                    ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                    ->whereYear('morbidityMonth', $sel_year)
                    ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                    ->count();

                    $brgy_previousyear_total_cases = $modelClass::with('records')
                    ->whereHas('records', function ($q) use ($brgy) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS')
                        ->where('records.address_brgy', $brgy->brgyName);
                    })
                    ->where('status', 'approved')
                    ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                    ->whereYear('morbidityMonth', $sel_year)
                    ->count();
                }
                else {
                    $brgy_grand_total_cases = $modelClass::where('Year', $sel_year)
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->where('MorbidityWeek', '<=', $sel_week)
                    ->where('Barangay', $brgy->brgyName)
                    ->count();

                    $brgy_previousyear_total_cases = $modelClass::where('Year', $sel_year-1)
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->where('Barangay', $brgy->brgyName)
                    ->count();
                }

                $brgy_cases_array[] = [
                    'brgy_name' => $brgy->brgyName,
                    'brgy_last3mw' => $brgy_last3mw,
                    'brgy_grand_total_cases' => $brgy_grand_total_cases,
                    'brgy_previousyear_total_cases' => $brgy_previousyear_total_cases, 
                    'brgy_mw1' => $brgy_mw1,
                    'brgy_mw2' => $brgy_mw2,
                    'brgy_mw3' => $brgy_mw3,
                    'brgy_mw4' => $brgy_mw4,
                ];

                $fourmws_total += $brgy_mw1 + $brgy_mw2 + $brgy_mw3 + $brgy_mw4;
                $threemws_total += $brgy_mw2 + $brgy_mw3 + $brgy_mw4;
            }

            $brgy_sortedtohighest_array = $brgy_cases_array;
            $brgy_sortedtohighestweek_array = $brgy_cases_array;

            usort($brgy_sortedtohighest_array, function($a, $b) {
                return $b['brgy_grand_total_cases'] - $a['brgy_grand_total_cases'];
            });

            usort($brgy_sortedtohighestweek_array, function($a, $b) {
                return $b['brgy_last3mw'] - $a['brgy_last3mw'];
            });

            $top10Brgys = array_slice($brgy_sortedtohighest_array, 0, 10);

            //GET CLASSIFICATION
            $classification_titles = [];
            $classification_counts = [];
            $confirmed_titles = [];
            $current_confirmed_grand_total = 0;

            if($sel_disease == 'Dengue') {
                $ccstr = 'CaseClassification';

                $classification_titles = ['S', 'P', 'C', 'NONE'];
                $confirmed_titles = ['C'];
                
                /*
                $ccsuspected_str = 'S';
                $ccprobable_str = 'P';
                $ccconfirmed_str = 'C';
                */
            }
            else if($sel_disease == 'Hfmd') {
                $ccstr = 'CaseClass';

                $classification_titles = ['SUSPECTED CASE OF HFMD', 'PROBABLE CASE OF HFMD', 'CONFIRMED CASE OF HFMD', 'SUSPECTED CASE OF SEVERE ENTEROVIRAL DISEASE', 'CONFIRMED CASE OF SEVERE ENTEROVIRAL DISEASE'];
                $confirmed_titles = ['CONFIRMED CASE OF HFMD', 'CONFIRMED CASE OF SEVERE ENTEROVIRAL DISEASE'];

                /*
                $ccsuspected_str = 'SUSPECTED CASE OF HFMD';
                $ccprobable_str = 'PROBABLE CASE OF HFMD';
                $ccconfirmed_str = 'POSITIVE CASE OF HFMD';
                */
            }
            else if($sel_disease == 'Influenza') {
                $ccstr = 'CASECLASS';

                $classification_titles = ['Suspect', 'Probable', 'Confirmed', 'NONE'];
                $confirmed_titles = ['Confirmed'];

                /*
                $ccsuspected_str = 'Suspect';
                $ccprobable_str = 'Probable';
                $ccconfirmed_str = 'Confirmed';
                */
            }
            else if($sel_disease == 'Measles') {
                $ccstr = 'FinalClass';

                $classification_titles = ['LABORATORY CONFIRMED MEASLES', 'LABORATORY CONFIRMED RUBELLA', 'EPI-LINKED CONFIRMED MEASLES', 'EPI-LINKED CONFIRMED RUBELLA', 'MEASLES COMPATIBLE', 'NONE'];
                $confirmed_titles = ['LABORATORY CONFIRMED MEASLES', 'LABORATORY CONFIRMED RUBELLA', 'EPI-LINKED CONFIRMED MEASLES', 'EPI-LINKED CONFIRMED RUBELLA', 'MEASLES COMPATIBLE'];
            }
            else if($sel_disease == 'COVID') {
                $ccstr = 'caseClassification';

                $classification_titles = ['Suspect', 'Probable', 'Confirmed'];
                $confirmed_titles = ['Confirmed'];
            }
            else if($sel_disease == 'Rabies') {
                $ccstr = 'CASECLASS';
            }
            else {
                $ccstr = 'CaseClassification';

                $classification_titles = ['S', 'P', 'C', 'NONE'];
                $confirmed_titles = ['C'];

                /*
                $ccsuspected_str = 'S';
                $ccprobable_str = 'P';
                $ccconfirmed_str = 'C';
                */
            }

            //dd($classification_titles);

            foreach($classification_titles as $cclass) {
                if($cclass == 'NONE') {
                    $classification_counts[] = $modelClass::where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->where('Year', $sel_year)
                    ->where('MorbidityWeek', '<=', $sel_week)
                    ->where(function ($q) use ($ccstr) {
                        $q->whereNull($ccstr)
                        ->orWhere($ccstr, '');
                    })
                    ->count();
                }
                else {
                    if($sel_disease == 'COVID') {
                        $ccount = $modelClass::with('records')
                        ->whereHas('records', function ($q) use ($brgy) {
                            $q->where('records.address_province', 'CAVITE')
                            ->where('records.address_city', 'GENERAL TRIAS');
                        })
                        ->where('status', 'approved')
                        ->where($ccstr, $cclass)
                        ->whereYear('morbidityMonth', $sel_year)
                        ->whereRaw('WEEK(morbidityMonth, 1) = ?', [$sel_week])
                        ->count();
                    }
                    else {
                        $ccount = $modelClass::where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->where('Year', $sel_year)
                        ->where('MorbidityWeek', '<=', $sel_week)
                        ->where($ccstr, $cclass)
                        ->count();
                    }
                    
                    if(in_array($cclass, $confirmed_titles)) {
                        $current_confirmed_grand_total += $ccount;
                    }

                    $classification_counts[] = $ccount;
                }
            }

            /*
            $current_confirmed_grand_total = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where($ccstr, $ccconfirmed_str)->count();
            $current_probable_grand_total = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where($ccstr, $ccprobable_str)->count();
            $current_suspected_grand_total = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where($ccstr, $ccsuspected_str)->count();

            $current_confirmed_percent = ($current_grand_total != 0) ? round($current_confirmed_grand_total / $current_grand_total * 100,0) : 0;
            $current_probable_percent = ($current_grand_total != 0) ? round($current_probable_grand_total / $current_grand_total * 100,0) : 0;
            $current_suspected_percent = ($current_grand_total != 0) ? round($current_suspected_grand_total / $current_grand_total * 100,0) : 0;

            $current_suspected_grand_total += $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where($ccstr, '')->count();
            */

            //AGE GROUP
            //Search if there is Zero Age Years
            if($sel_disease == 'COVID') {
                $search_zeroage = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', 0)
                ->count();

                if($search_zeroage) {
                    $min_age = $modelClass::with('records')
                    ->whereHas('records', function ($q) use ($brgy) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS');
                    })
                    ->where('status', 'approved')
                    ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                    ->whereYear('morbidityMonth', $sel_year)
                    ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                    ->min('age_months');

                    $min_age = $min_age / 100;
                }
                else {
                    $min_age = $modelClass::with('records')
                    ->whereHas('records', function ($q) use ($brgy) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS');
                    })
                    ->where('status', 'approved')
                    ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                    ->whereYear('morbidityMonth', $sel_year)
                    ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                    ->min('age_years');
                }
            }
            else {
                $search_zeroage = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('AgeYears', 0)
                ->first();

                if($search_zeroage) {
                    $min_age = $modelClass::where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->where('Year', $sel_year)
                    ->where('MorbidityWeek', '<=', $sel_week)
                    ->min('AgeMons');

                    $min_age = $min_age / 100;
                }
                else {
                    $min_age = $modelClass::where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->where('Year', $sel_year)
                    ->where('MorbidityWeek', '<=', $sel_week)
                    ->min('AgeYears');
                }
            }

            $min_age_display = $min_age;

            if($min_age_display == 0.01) {
                $min_age_display = '1 Month';
            }
            else if($min_age_display == 0.02) {
                $min_age_display = '2 Months';
            }
            else if($min_age_display == 0.03) {
                $min_age_display = '3 Months';
            }
            else if($min_age_display == 0.04) {
                $min_age_display = '4 Months';
            }
            else if($min_age_display == 0.05) {
                $min_age_display = '5 Months';
            }
            else if($min_age_display == 0.06) {
                $min_age_display = '6 Months';
            }
            else if($min_age_display == 0.07) {
                $min_age_display = '7 Months';
            }
            else if($min_age_display == 0.08) {
                $min_age_display = '8 Months';
            }
            else if($min_age_display == 0.09) {
                $min_age_display = '9 Months';
            }
            else if($min_age_display == 0.10) {
                $min_age_display = '10 Months';
            }
            else if($min_age_display == 0.11) {
                $min_age_display = '11 Months';
            }

            if($sel_disease == 'COVID') {
                $max_age = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->max('age_years');
            }
            else {
                $max_age = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->max('AgeYears');
            }

            //GET MEDIAN

            /*
            $ages = $modelClass::where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week)
            ->pluck('AgeYears')
            ->toArray();
            */
            
            $age_array = [];

            if($sel_disease == 'COVID') {
                $fetch_age = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->get();

                foreach($fetch_age as $fa) {
                    if($fa->AgeYears == 0) {
                        $age_array[] = $fa->age_months / 100;
                    }
                    else {
                        $age_array[] = $fa->age_years;
                    }
                }
            }
            else {
                $fetch_age = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->get();

                foreach($fetch_age as $fa) {
                    if($fa->AgeYears == 0) {
                        $age_array[] = $fa->AgeMons / 100;
                    }
                    else {
                        $age_array[] = $fa->AgeYears;
                    }
                }
            }
            

            // Sort the ages
            /*
            OLD MEDIAN CODE
            sort($age_array);

            $count = count($age_array);
            $median_age = 0;

            if ($count % 2 == 0) {
                // If the count of ages is even, take the average of the middle two values
                $median_age = ($age_array[($count / 2) - 1] + $age_array[$count / 2]) / 2;
            } else {
                // If the count of ages is odd, take the middle value
                $median_age = $age_array[($count - 1) / 2];
            }
            */
            sort($age_array);

            $count = count($age_array);
            $middle = floor(($count-1)/2);

            /*
            if ($count % 2) {
                $median_age = $age_array[$middle];
            } else {
                $low = $age_array[$middle];
                $high = $age_array[$middle + 1];
                $median_age = (($low + $high) / 2);
            }
            */

            $median_age = $age_array[$middle];

            $median_display = $median_age;

            if($median_display == 0.01) {
                $median_display = '1 Month';
            }
            else if($median_display == 0.02) {
                $median_display = '2 Months';
            }
            else if($median_display == 0.03) {
                $median_display = '3 Months';
            }
            else if($median_display == 0.04) {
                $median_display = '4 Months';
            }
            else if($median_display == 0.05) {
                $median_display = '5 Months';
            }
            else if($median_display == 0.06) {
                $median_display = '6 Months';
            }
            else if($median_display == 0.07) {
                $median_display = '7 Months';
            }
            else if($median_display == 0.08) {
                $median_display = '8 Months';
            }
            else if($median_display == 0.09) {
                $median_display = '9 Months';
            }
            else if($median_display == 0.10) {
                $median_display = '10 Months';
            }
            else if($median_display == 0.11) {
                $median_display = '11 Months';
            }
            else {
                $median_display = $median_display.' '.Str::plural('years', $median_display);
            }

            $ag_male = [];
            $ag_female = [];

            if($sel_disease == 'Pert') {
                $age_display_string = ['>25', '21-25', '16-20', '11-15', '6-10', '1-5', '<1'];
                $age_display_string = json_encode($age_display_string, JSON_UNESCAPED_UNICODE);

                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>', 25)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 21)->where('AgeYears', '<=', 25)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 16)->where('AgeYears', '<=', 20)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 11)->where('AgeYears', '<=', 15)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 6)->where('AgeYears', '<=', 10)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 1)->where('AgeYears', '<=', 5)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '<', 1)->where('Sex', 'M')->count() * -1;
            
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>', 25)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 21)->where('AgeYears', '<=', 25)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 16)->where('AgeYears', '<=', 20)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 11)->where('AgeYears', '<=', 15)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 6)->where('AgeYears', '<=', 10)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 1)->where('AgeYears', '<=', 5)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '<', 1)->where('Sex', 'F')->count();
            }
            else if($sel_disease == 'COVID') {
                $age_display_string = ['>50', '41-50', '31-40', '21-30', '11-20', '1-10', '<1'];
                $age_display_string = json_encode($age_display_string, JSON_UNESCAPED_UNICODE);

                $ag_male[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>', 50)
                ->count() * -1;

                $ag_male[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 41)
                ->where('age_years', '<=', 50)
                ->count() * -1;
                

                $ag_male[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 31)
                ->where('age_years', '<=', 40)
                ->count() * -1;

                $ag_male[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 21)
                ->where('age_years', '<=', 30)
                ->count() * -1;

                $ag_male[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 11)
                ->where('age_years', '<=', 20)
                ->count() * -1;

                $ag_male[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 1)
                ->where('age_years', '<=', 10)
                ->count() * -1;

                $ag_male[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '<1', 50)
                ->count() * -1;
            
                $ag_female[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>', 50)
                ->count();

                $ag_female[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 41)
                ->where('age_years', '<=', 50)
                ->count();
                

                $ag_female[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 31)
                ->where('age_years', '<=', 40)
                ->count();

                $ag_female[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 21)
                ->where('age_years', '<=', 30)
                ->count();

                $ag_female[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 11)
                ->where('age_years', '<=', 20)
                ->count();

                $ag_female[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '>=', 1)
                ->where('age_years', '<=', 10)
                ->count();

                $ag_female[] = $modelClass::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->whereIn('caseClassification', ['Probable', 'Confirmed'])
                ->whereYear('morbidityMonth', $sel_year)
                ->whereRaw('WEEK(morbidityMonth, 1) <= ?', [$sel_week])
                ->where('age_years', '<1', 50)
                ->count();
            }
            else {
                $age_display_string = ['>50', '41-50', '31-40', '21-30', '11-20', '1-10', '<1'];
                $age_display_string = json_encode($age_display_string, JSON_UNESCAPED_UNICODE);
                
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>', 50)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 41)->where('AgeYears', '<=', 50)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 31)->where('AgeYears', '<=', 40)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 21)->where('AgeYears', '<=', 30)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 11)->where('AgeYears', '<=', 20)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 1)->where('AgeYears', '<=', 10)->where('Sex', 'M')->count() * -1;
                $ag_male[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '<', 1)->where('Sex', 'M')->count() * -1;
            
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>', 50)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 41)->where('AgeYears', '<=', 50)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 31)->where('AgeYears', '<=', 40)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 21)->where('AgeYears', '<=', 30)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 11)->where('AgeYears', '<=', 20)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '>=', 1)->where('AgeYears', '<=', 10)->where('Sex', 'F')->count();
                $ag_female[] = $modelClass::where('enabled', 1)->where('match_casedef', 1)->where('Year', $sel_year)->where('MorbidityWeek', '<=', $sel_week)->where('AgeYears', '<', 1)->where('Sex', 'F')->count();
            }

            $male_total = abs(array_sum($ag_male));
            $female_total = $current_grand_total - $male_total;

            //Get Highest Value for Min and Max Suggested Value on Chart
            $ag_male_max = abs(min($ag_male));            
            $ag_female_max = max($ag_female);
            if($ag_male_max == $ag_female_max) {
                $age_highest_value = 'EQUAL';
                $suggestedMinAge = null;
                $suggestedMaxAge = null;
            }
            else if($ag_male_max > $ag_female_max) {
                $age_highest_value = 'MALE';
                $suggestedMinAge = null;
                $suggestedMaxAge = $ag_male_max;
            }
            else {
                $age_highest_value = 'FEMALE';
                $suggestedMinAge = $ag_female_max * -1;
                $suggestedMaxAge = null;
            }

            if($male_total > $female_total) {
                $majority_flavor_singular = 'Male';
                $majority_flavor = 'Males';
                $majority_count = $male_total;
                $majority_percent = ($current_grand_total != 0) ? round($male_total / $current_grand_total * 100) : 0;
            }
            else {
                $majority_flavor_singular = 'Female';
                $majority_flavor = 'Females';
                $majority_count = $female_total;
                $majority_percent = ($current_grand_total != 0) ? round($female_total / $current_grand_total * 100) : 0;
            }

            //Text Flavor
            if($sel_disease == 'Dengue') {
                $flavor_title = 'DENGUE FEVER';
                $flavor_name = 'Dengue';
            }
            else if($sel_disease == 'Hfmd') {
                $flavor_title = 'Hand, Foot and Mouth Disease (HFMD) & Severe Enteroviral Diseases';
                $flavor_name = 'HFMD';
            }
            else if($sel_disease == 'Pert') {
                $flavor_title = 'Pertussis';
                $flavor_name = 'Pertussis';
            }
            else if($sel_disease == 'COVID') {
                $flavor_title = 'COVID-19';
                $flavor_name = 'COVID-19';
            }
            else {
                $flavor_title = strtoupper($sel_disease);
                $flavor_name = ucwords(strtolower($sel_disease));
            }

            // Get the start date of the week
            $startDate = Carbon::now()->isoWeekYear($sel_year)->isoWeek($sel_week)->startOfWeek();

            // Get the end date of the week
            $endDate = Carbon::now()->isoWeekYear($sel_year)->isoWeek($sel_week)->endOfWeek();
            if($sel_week == date('W')) {
                $flavor_enddate = Carbon::now();
            }
            else {
                $flavor_enddate = $endDate;
            }

            if($sel_week == 1) {
                $mWeekCalendarDate = Carbon::parse($sel_year.'-01-01');

                $startDateBasedOnMw = $mWeekCalendarDate->format('M d, Y');

                if($mWeekCalendarDate->dayOfWeek == Carbon::SATURDAY) {
                    $endDateBasedOnMw = $mWeekCalendarDate->copy()->next(Carbon::SATURDAY)->format('M d, Y');
                }
                else {
                    $endDateBasedOnMw = $mWeekCalendarDate->copy()->endOfWeek(Carbon::SATURDAY)->format('M d, Y');
                }
            }
            else {
                $mWeekCalendarDate = Carbon::parse($sel_year.'-01-01');

                $startDateBasedOnMw = $mWeekCalendarDate->copy();

                if($startDateBasedOnMw->dayOfWeek == Carbon::SATURDAY) {
                    $sel_week_params = $sel_week+1;
                }
                else {
                    $sel_week_params = $sel_week;
                }

                for($i=1;$i<$sel_week_params;$i++) {
                    $startDateBasedOnMw = $startDateBasedOnMw->next(Carbon::SUNDAY);
                }
                $startDateBasedOnMw = $startDateBasedOnMw->copy()->format('M d, Y');

                if($sel_week != 52) {
                    $endDateBasedOnMw = Carbon::parse($startDateBasedOnMw)->endOfWeek(Carbon::SATURDAY)->format('M d, Y');
                }
                else {
                    $endDateBasedOnMw = Carbon::parse($sel_year.'-12-31')->format('M d, Y');
                }
                
                /*
                $getLastWeek = Carbon::parse($sel_year.'-01-01')->addDays(6 * ($sel_week - 1));

                $getLastWeekStart = $getLastWeek->copy()->startOfWeek(Carbon::SUNDAY);
                $getLastWeekEnd = $getLastWeek->copy()->endOfWeek(Carbon::SATURDAY);

                $mWeekCalendarDate = Carbon::parse($sel_year.'-01-01')->addDays(6 * $sel_week);

                if($mWeekCalendarDate->dayOfWeek == Carbon::SATURDAY) {
                    $mWeekCalendarDate = $mWeekCalendarDate->addDay(1);
                }
                else if($mWeekCalendarDate->dayOfWeek == Carbon::SUNDAY) {
                    $mWeekCalendarDate = $mWeekCalendarDate->addDay(1);
                }
                
                if($getLastWeekStart->gte($mWeekCalendarDate) && $getLastWeekEnd->lte($mWeekCalendarDate)) {
                    $mWeekCalendarDate = $mWeekCalendarDate->next(Carbon::MONDAY);
                }

                //dd($mWeekCalendarDate);

                $startDateBasedOnMw = $mWeekCalendarDate->startOfWeek(Carbon::SUNDAY)->format('M d, Y');
                $endDateBasedOnMw = $mWeekCalendarDate->startOfWeek(Carbon::SUNDAY)->addDays(6)->format('M d, Y');  
                */
            }

            $returnVars = [
                'flavor_title' => $flavor_title,
                'sel_disease' => $sel_disease,
                'sel_year' => $sel_year,
                'sel_mweek' => $sel_week,
                'previous_grand_total' => $previous_grand_total,
                'current_grand_total' => $current_grand_total,
                'currentmw_array' => $currentmw_array,
                'hospitalized_count' => $hospitalized_count,
                'previous_death_count' => $previous_death_count,
                'death_count' => $death_count,
                'epidemicmw_array' => $epidemicmw_array,
                'alertmw_array' => $alertmw_array,
                'top10Brgys' => $top10Brgys,
                'min_age' => $min_age,
                'max_age' => $max_age,
                'median_age' => $median_age,
                'ag_male' => $ag_male,
                'ag_female' => $ag_female,
                'male_total' => $male_total,
                'female_total' => $female_total,
                'brgy_sortedtohighestweek_array' => $brgy_sortedtohighestweek_array,
                'current_confirmed_grand_total' => $current_confirmed_grand_total,
                //'current_probable_grand_total' => $current_probable_grand_total,
                //'current_suspected_grand_total' => $current_suspected_grand_total,
                //'current_confirmed_percent' => $current_confirmed_percent,
                //'current_probable_percent' => $current_probable_percent,
                //'current_suspected_percent' => $current_suspected_percent,
                'brgy_cases_array' => $brgy_cases_array,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'majority_flavor_singular' => $majority_flavor_singular,
                'majority_flavor' => $majority_flavor,
                'majority_count' => $majority_count,
                'majority_percent' => $majority_percent,
                'fourmws_total' => $fourmws_total,
                'threemws_total' => $threemws_total,
                'classification_titles' => $classification_titles,
                'classification_counts' => $classification_counts,
                'mWeekCalendarDate' => $mWeekCalendarDate,
                'startDateBasedOnMw' => $startDateBasedOnMw,
                'endDateBasedOnMw' => $endDateBasedOnMw,
                'set_display_params' => $set_display_params,
                'flavor_name' => $flavor_name,
                'age_display_string' => $age_display_string,
                'flavor_enddate' => $flavor_enddate,
                'median_display' => $median_display,
                'min_age_display' => $min_age_display,
                'suggestedMinAge' => $suggestedMinAge,
                'suggestedMaxAge' => $suggestedMaxAge,
                'age_highest_value' => $age_highest_value,
            ];

            if($sel_disease == 'Pert') {
                $alive_suspect = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('CaseClassification', 'S')
                ->count();

                /*
                $alive_probable = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('CaseClassification', 'P')
                ->count();
                */

                $alive_confirmed = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('CaseClassification', 'C')
                ->count();

                $alive_positive = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('system_classification', 'CONFIRMED')
                ->count();

                $alive_negative = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('system_classification', 'NEGATIVE')
                ->count();

                $alive_waitresult = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('system_classification', 'WAITING FOR RESULT')
                ->count();

                $alive_noswab = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('system_classification', 'NO SWAB')
                ->count();

                $alive_unknown = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'ALIVE')
                ->where('system_classification', 'UNKNOWN')
                ->count();

                $died_suspect = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('CaseClassification', 'S')
                ->count();

                /*
                $died_probable = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('CaseClassification', 'P')
                ->count();
                */

                $died_confirmed = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('CaseClassification', 'C')
                ->count();

                $died_positive = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('system_classification', 'CONFIRMED')
                ->count();

                $died_negative = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('system_classification', 'NEGATIVE')
                ->count();

                $died_waitresult = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('system_classification', 'WAITING FOR RESULT')
                ->count();

                $died_noswab = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('system_classification', 'NO SWAB')
                ->count();

                $died_unknown = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->where('system_classification', 'UNKNOWN')
                ->count();

                //RECOVERED
                $recovered_suspect = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('CaseClassification', 'S')
                ->count();

                /*
                $recovered_probable = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('CaseClassification', 'P')
                ->count();
                */

                $recovered_confirmed = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('CaseClassification', 'C')
                ->count();

                $recovered_positive = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('system_classification', 'CONFIRMED')
                ->count();

                $recovered_negative = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('system_classification', 'NEGATIVE')
                ->count();

                $recovered_waitresult = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('system_classification', 'WAITING FOR RESULT')
                ->count();

                $recovered_noswab = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('system_classification', 'NO SWAB')
                ->count();

                $recovered_unknown = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'RECOVERED')
                ->where('system_classification', 'UNKNOWN')
                ->count();

                //Penta Vaccine Counter
                $penta1 = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('if_yes_number_of_total_doses_health_facility', 1)
                ->count();

                $penta2 = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('if_yes_number_of_total_doses_health_facility', 2)
                ->count();

                $penta3 = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('if_yes_number_of_total_doses_health_facility', 3)
                ->count();

                $vaccine_array = [];

                $vaccine_array[] = [
                    'name' => 'No Vaccine',
                    'count' =>  $current_grand_total - ($penta1 + $penta2 + $penta3),
                ];

                $vaccine_array[] = [
                    'name' => 'Penta1',
                    'count' => $penta1,
                ];

                $vaccine_array[] = [
                    'name' => 'Penta2',
                    'count' => $penta2,
                ];

                $vaccine_array[] = [
                    'name' => 'Penta3',
                    'count' => $penta3,
                ];

                $get_died = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('Year', $sel_year)
                ->where('MorbidityWeek', '<=', $sel_week)
                ->where('system_outcome', 'DIED')
                ->get();

                $died_brgy_list = [];
                $died_age_list = [];

                $died_unvaccinated = 0;
                $died_penta1 = 0;
                $died_penta2 = 0;
                $died_penta3 = 0;

                foreach($get_died as $gd) {
                    if(!in_array($gd->Barangay, $died_brgy_list)) {
                        $died_brgy_list[] = $gd->Barangay;
                    }

                    $died_age_list[] = $gd->getAgeString();

                    if($gd->if_yes_number_of_total_doses_health_facility == 1) {
                        $died_penta1++;
                    }
                    else if($gd->if_yes_number_of_total_doses_health_facility == 2) {
                        $died_penta2++;
                    }
                    else if($gd->if_yes_number_of_total_doses_health_facility == 3) {
                        $died_penta3++;
                    }
                }

                $died_unvaccinated = $death_count - $died_penta1 - $died_penta2 - $died_penta3;

                $returnVars = $returnVars + [
                    'alive_suspect' => $alive_suspect,
                    //'alive_probable' => $alive_probable,
                    'alive_confirmed' => $alive_confirmed,
                    'alive_positive' => $alive_positive,
                    'alive_negative' => $alive_negative,
                    'alive_waitresult' => $alive_waitresult,
                    'alive_noswab' => $alive_noswab,
                    'alive_unknown' => $alive_unknown,
                    
                    'died_suspect' => $died_suspect,
                    //'died_probable' => $died_probable,
                    'died_confirmed' => $died_confirmed,
                    'died_positive' => $died_positive,
                    'died_negative' => $died_negative,
                    'died_waitresult' => $died_waitresult,
                    'died_noswab' => $died_noswab,
                    'died_unknown' => $died_unknown,

                    'recovered_suspect' => $recovered_suspect,
                    //'recovered_probable' => $recovered_probable,
                    'recovered_confirmed' => $recovered_confirmed,
                    'recovered_positive' => $recovered_positive,
                    'recovered_negative' => $recovered_negative,
                    'recovered_waitresult' => $recovered_waitresult,
                    'recovered_noswab' => $recovered_noswab,
                    'recovered_unknown' => $recovered_unknown,
                    
                    'vaccine_array' => $vaccine_array,
                    'died_unvaccinated' => $died_unvaccinated,
                    'died_penta1' => $died_penta1,
                    'died_penta2' => $died_penta2,
                    'died_penta3' => $died_penta3,
                    'died_brgy_list' => $died_brgy_list,
                    'died_age_list' => $died_age_list,
                ];
            }

            if(request()->input('print')) {
                return view('pidsr.snaxv2.index_print', $returnVars);
            }
            else {
                return view('pidsr.snaxv2.index', $returnVars);
            }
        }
        else {
            return abort(401);
        }
    }

    public static function setBgColor($n) {
        if($n == 0) {
            return '';
        }
        else if($n == 1) {
            return 'background-color: rgba(254,255,205,255);font-weight: bold;';
        }
        else if($n == 2) {
            return 'background-color: rgba(255,153,0,255);color: white;font-weight: bold;';
        }
        else if($n == 3) {
            return 'background-color: rgba(255,1,1,255);color: white;font-weight: bold;';
        }
        else {
            return 'background-color: rgba(129,0,1,255);color: white;font-weight: bold;';
        }
    }

    public static function setMapColor($n) {
        if($n <= 0) {
            return 'GREY.png';
        }
        else if($n == 1) {
            return 'YELLOW.png';
        }
        else if($n == 2) {
            return 'ORANGE.png';
        }
        else if($n == 3) {
            return 'RED.png';
        }
        else {
            return 'DARKRED.png';
        }
    }

    public static function getLabDetails($epi_id, $case_id) {
        $d = EdcsLaboratoryData::where('epi_id', $epi_id)
        ->orWhere('case_id', $case_id)->get();

        return $d;
    }

    public static function searchConfirmedDengue() {
        //Params to Check Confirmed Dengue Cases and return it back to Suspected or Probable Based on Conditions
        $confirmed_dengue = Dengue::where('Year', date('Y'))
        ->where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('CaseClassification', 'C');

        if($confirmed_dengue->get()->count() != 0) {
            foreach($confirmed_dengue->get() as $cd) {
                //Search for PCR in Lab Data
                
                /*
                $positive_search = EdcsLaboratoryData::where('case_id', $cd->edcs_caseid)
                ->where(function ($q) {
                    $q->where('test_type', 'Virus Isolation')
                    ->orWhere('test_type', 'Polymerase Chain Reaction');
                })
                ->where('result', 'POSITIVE')
                ->first();
                */

                $lab_search = EdcsLaboratoryData::where('case_id', $cd->edcs_caseid)->get();

                if($lab_search->count() != 0) {
                    $positive_search = EdcsLaboratoryData::where('case_id', $cd->edcs_caseid)
                    ->where(function ($q) {
                        $q->where('test_type', 'Virus Isolation')
                        ->orWhere('test_type', 'Polymerase Chain Reaction');
                    })
                    ->where('result', 'POSITIVE')
                    ->first();

                    if(!($positive_search)) {
                        $cd->CaseClassification = 'P';
                    }
                }
                else {
                    $cd->CaseClassification = 'S';
                }

                if($cd->isDirty()) {
                    $cd->save();
                }
            }
        }

        //Reverse Search: Search for POSITIVE PCR and Virus Isolation results
        //Group by case_id first
        $caseid_groups = EdcsLaboratoryData::where('case_code', 'DENGUE')
        ->whereYear('timestamp', date('Y'))
        ->groupBy('case_id')
        ->pluck('case_id');

        foreach($caseid_groups as $cid) {
            $confirmed_lab_check = EdcsLaboratoryData::where('case_code', 'DENGUE')
            ->whereYear('timestamp', date('Y'))
            ->where('case_id', $cid)
            ->whereIn('test_type', ['Virus Isolation', 'Polymerase Chain Reaction'])
            ->where('result', 'POSITIVE')
            ->first();

            if($confirmed_lab_check) {
                $d_update = Dengue::where('edcs_caseid', $cid)
                ->whereIn('CaseClassification', ['S', 'P'])
                ->update([
                    'CaseClassification' => 'C',
                ]);
            }
            else {
                $d_update = Dengue::where('edcs_caseid', $cid)
                ->where('CaseClassification', 'S')
                ->update([
                    'CaseClassification' => 'P',
                ]);
            }
        }
    }

    public function dailyMergeProcess(Request $r) {
        //Call EdcsImport
        Excel::import(new EdcsImport(), $r->excel_file);

        PIDSRController::searchConfirmedDengue();

        return redirect()->back()
        ->with('msg', 'EDCS Feedback Excel file was imported successfully.')
        ->with('msgtype', 'success');
    }

    public function weeklyMergeProcess(Request $r) {
        //Call EdcsImport
        Excel::import(new EdcsImport(), $r->excel_file);

        PIDSRController::searchConfirmedDengue();
        
        //Send Automated Email
        Artisan::call('pidsrwndr:weekly');

        return redirect()->back()
        ->with('msg', 'EDCS Feedback Excel File imported successfully. Please check the Email Report at cesu.gentrias@gmail.com after a few minutes.')
        ->with('msgtype', 'success');
    }

    public function viewCif($case, $epi_id) {
        /*
        Acute Bloody Diarrhea
        Acute Flaccid Paralysis
        Acute Meningitis Encephalitis
        Acute Viral Hepatitis
        Chikungunya Viral Disease
        Cholera
        Dengue
        Diphteria
        Hand, Foot & Mouth Disease
        Influenza-like Illness
        Leptospirosis
        Measles
        Meningococcal Disease
        Neonatal Tetanus
        Non-Neonatal Tetanus
        Pertussis
        Rabies
        Rotavirus
        Typhoid and Paratyphoid Fever
        */

        $epiCol = 'EPIID';

        if($case == 'ABD') {
            $d = 'Abd';
            $flavor_title = 'Acute Bloody Diarrhea';
        }
        else if($case == 'AEFI') {
            $d = 'Aefi';
        }
        else if($case == 'AES') {
            $d = 'Aes';
        }
        else if($case == 'AFP') {
            $d = 'Afp';
            $flavor_title = 'Acute Flaccid Paralysis';
        }
        else if($case == 'AHF') {
            $d = 'Ahf';
        }
        else if($case == 'AMES') {
            $d = 'Ames';

            $flavor_title = 'Acute Meningitis Encephalitis';
        }
        else if($case == 'ANTHRAX') {
            $d = 'Anthrax';
        }
        else if($case == 'CHIKV') {
            $d = 'Chikv';

            $flavor_title = 'Chikungunya Viral Disease';
        }
        else if($case == 'CHOLERA') {
            $d = 'Cholera';

            $flavor_title = 'Cholera';
        }
        else if($case == 'DENGUE') {
            $d = 'Dengue';

            $flavor_title = 'Dengue';
        }
        else if($case == 'DIPH') {
            $d = 'Diph';

            $flavor_title = 'Diphteria';
        }
        else if($case == 'HEPATITIS') {
            $d = 'Hepatitis';

            $flavor_title = 'Acute Viral Hepatitis';
        }
        else if($case == 'HFMD') {
            $d = 'Hfmd';

            $flavor_title = 'Hand, Foot & Mouth Disease';
        }
        else if($case == 'INFLUENZA') {
            $d = 'Influenza';

            $flavor_title = 'Influenza-like Illness';
        }
        else if($case == 'LEPTOSPIROSIS') {
            $d = 'Leptospirosis';

            $flavor_title = 'Leptospirosis';
        }
        else if($case == 'MALARIA') {
            $d = 'Malaria';

            $flavor_title = 'Malaria';
        }
        else if($case == 'MEASLES') {
            $d = 'Measles';

            $flavor_title = 'Measles';
        }
        else if($case == 'MENINGITIS') {
            $d = 'MENINGITIS';

            $flavor_title = 'Meningitis';
        }
        else if($case == 'MENINGO') {
            $d = 'Meningo';

            $flavor_title = 'Meningococcal Disease';
        }
        else if($case == 'NNT') {
            $d = 'Nnt';

            $flavor_title = 'Non-Neonatal Tetanus';
        }
        else if($case == 'NT') {
            $d = 'Nt';

            $flavor_title = 'Neonatal Tetanus';
        }
        else if($case == 'PERT') {
            $d = 'Pert';

            $flavor_title = 'Pertussis';
        }
        else if($case == 'PSP') {
            $d = 'Psp';

            $flavor_title = 'TEST';
        }
        else if($case == 'RABIES') {
            $d = 'Rabies';

            $flavor_title = 'Rabies';
        }
        else if($case == 'ROTAVIRUS') {
            $d = 'Rotavirus';

            $flavor_title = 'Rotavirus';
        }
        else if($case == 'TYPHOID') {
            $d = 'Typhoid';

            $flavor_title = 'Typhoid and Paratyphoid Fever';
        }
        else if($case == 'SARI') {
            $d = 'SevereAcuteRespiratoryInfection';

            $flavor_title = 'Severe Acute Respiratory Infection';

            $epiCol = 'epi_id';
        }

        $modelClass = "App\\Models\\$d";

        $p = $modelClass::where($epiCol, $epi_id)->first();

        if($p) {
            if($p->from_edcs == 1) {
                $lab_details = EdcsLaboratoryData::where('epi_id', $epi_id)
                ->orWhere('case_id', $p->edcs_caseid)
                ->orderBy('timestamp', 'DESC')
                ->get();
            }
            else {
                $lab_details = NULL;
            }

            return view('pidsr.casechecker_viewcif', [
                'disease' => $case,
                'p' => $p,
                'flavor_title' => $flavor_title,
                'lab_details' => $lab_details,
            ]);
        }
        else {

        }
    }

    public static function getBlankSubdivisions() {
        $list = [];

        $diseases = [
            'Afp',
            'Measles',
            'Meningo',
            'Nt',
            'Rabies',
            'Hfmd',

            'Abd',
            'Ames',
            'Hepatitis',
            'Chikv',
            'Cholera',
            'Dengue',
            'Diph',
            'Influenza',
            'Leptospirosis',
            'Nnt',
            'Pert',
            'Rotavirus',
            'Typhoid',
            'SevereAcuteRespiratoryInfection',
        ];

        foreach($diseases as $d) {
            $modelClass = "App\\Models\\$d";

            $case_name = mb_strtoupper($d);

            if(request()->input('year')) {
                $year = request()->input('year');
            }
            else {
                $year = date('Y');
            }

            $fetch_case = $modelClass::where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $year)
            ->whereNull('system_subdivision_id')
            ->orderBy('created_at', 'ASC')
            ->get();

            foreach($fetch_case as $f) {
                $list[] = [
                    'case_name' => $case_name,
                    'epi_id' => $f->EPIID,
                    'name' => $f->getName(),
                    'age' => $f->AgeYears,
                    'sex' => $f->Sex,
                    'streetpurok' => (!is_null($f->Streetpurok)) ? $f->Streetpurok : 'N/A',
                    'brgy' => $f->Barangay,
                    'timestamp' => Carbon::parse($f->created_at)->format('m/d/Y h:i A'),
                ];
            }
        }

        return $list;
    }

    public function forValidationIndex() {
        $get_list = PIDSRController::getBlankSubdivisions();

        return view('pidsr.forvalidation', [
            'list' => $get_list,
        ]);
    }

    public static function getEdcsSpecimenTypeList() {
        $arr = [
            'Stool',
            'Blood',
            'Serum',
            'Saliva',
            'Nasopharyngeal/Oropharyngeal Swab (NPS/OPS)',
            'Dried Blood Spot',
            'Cerebrospinal fluid',
            'Brain Tissue',
            'Skin',
            'Cornea',
            'Urine',
            'Throat Swab',
            'Vesicle Swab',
            'Rectal Swab',
        ];

        sort($arr);

        return $arr;
    }

    public static function getEdcsTestConductedList() {
        $arr = [
            'Complete Blood Count',
            'Culture and Sensitivity',
            'Cytology and Chemistry',
            'Direct Fluorescent Antibody Test (FAT)',
            'Enzyme-linked Immunoassay (ELISA)',
            'Gram Stain',
            'IgG Antibody Test',
            'IgM and IgG Antibody Test',
            'IgM Antibody Test',
            'Latex Agglunitation',
            'Microbiology',
            'Microscopic Agglutination Test',
            'Polymerase Chain Reaction',
            'Rapid Antigen Test',
            'Rapid Diagnostic Test',
            'Tubex Test',
            'Typhidot Test',
            'Virus Antigen Detection (NS1)',
            'Virus Isolation',
            'Widals Test',
        ];

        sort($arr);

        return $arr;
    }

    public static function getEdcsTestLabResults() {
        $arr = [
            'DENGUE',
            'EQUIVOCAL',
            'H.INFLUENZAE',
            'INDETERMINATE',
            'JAPANESE ENCEPHALITIS',
            'N.MENINGITIDIS',
            'NEGATIVE',
            'NON POLIO ENTERO VIRUS',
            'NOT DONE',
            'NOT PROCESSED',
            'PENDING',
            'POSITIVE',
            'S.PNEUMONIAE',
            'SABIN - LIKE TYPE 1',
            'SABIN - LIKE TYPE 2',
            'SABIN - LIKE TYPE 3',
            'UNDERTIMINED',
            'UNKNOWN',
            'VACCINE - DERIVED POLIO VIRUS',
            'WILD POLIO VIRUS',
        ];

        sort($arr);

        return $arr;
    }

    public static function listDiseases() {
        $array = [
            'AFP',
            'MEASLES',
            'MENINGO',
            'NT',
            'RABIES',
            'HFMD',

            'ABD',
            'AMES',
            'HEPATITIS',
            'CHIKV',
            'CHOLERA',
            'DENGUE',
            'DIPH',
            'INFLUENZA',
            'LEPTOSPIROSIS',
            'NNT',
            'PERT',
            'ROTAVIRUS',
            'TYPHOID',
            'SARI',
        ];

        sort($array);

        return $array;
    }

    public static function listDiseasesTables() {
        $array = [
            'Afp',
            'Measles',
            'Meningo',
            'Nt',
            'Rabies',
            'Hfmd',

            'Abd',
            'Ames',
            'Hepatitis',
            'Chikv',
            'Cholera',
            'Dengue',
            'Diph',
            'Influenza',
            'Leptospirosis',
            'Nnt',
            'Pert',
            'Rotavirus',
            'Typhoid',
            'SevereAcuteRespiratoryInfection',
            //'EdcsLaboratoryData',
        ];

        sort($array);

        return $array;
    }

    public static function edcsGetIcd10Code($disease) {
        if($disease == 'Afp') {
            return 'A80.3; Acute paralytic poliomyelitis, other and unspecified (Acute Flaccid Paralysis)';
        }
        else if($disease == 'Measles') {
            return 'B05; Measles';
        }
        else if($disease == 'Meningo') {
            return 'A39; Meningococcal infection';
        }
        else if($disease == 'Nt') {
            return 'A33; Tetanus neonatorum';
        }
        else if($disease == 'Rabies') {
            return 'A82; Rabies';
        }
        else if($disease == 'Hfmd') {
            return 'B08; Other viral infections characterized by skin and mucous membrane lesions, not elsewhere classified';
        }
        else if($disease == 'Abd') {
            return 'A09; Infectious gastroenteritis and colitis, unspecified (ACUTE BLOODY DIARRHEA, ACUTE GASTROENTERITIS, ACUTE WATERY DIARRHEA, ENTERITIS, Dysentery) (INFECTIOUS DIARRHEA/DIARRHEA W/DEHYD, LEVEL OF DEHYDRATION NOT SPECIFIED)';
        }
        else if($disease == 'Ames') {
            return 'A39.0; Meningococcal meningitis';
        }
        else if($disease == 'Hepatitis') {
            return 'B17; Other acute viral hepatitis';
        }
        else if($disease == 'Chikv') {
            return 'A92.0; Chikungunya virus disease';
        }
        else if($disease == 'Cholera') {
            return 'A00; Cholera';
        }
        else if($disease == 'Dengue') {
            return 'A90; Dengue fever [Classical Dengue] (Dengue Suspect) (DENGUE FEVER SYNDROME)';
        }
        else if($disease == 'Diph') {
            return 'A36; Diphtheria';
        }
        else if($disease == 'Influenza') {
            return 'J11.1; Influenza with other respiratory manifestations, virus not identified (INFLUENZA-LIKE DISEASE/ILLNESS) (BRONCHIAL, AURI, LARYNGITIS, PHARYNGITIS, PLEURAL EFFUSION, URI, VIRAL)';
        }
        else if($disease == 'Leptospirosis') {
            return 'A27; Leptospirosis';
        }
        else if($disease == 'Nnt') {
            return 'A35; Other tetanus (NON NEONATAL TETANUS)';
        }
        else if($disease == 'Pert') {
            return 'A37.0; Whooping cough due to Bordetella pertussis';
        }
        else if($disease == 'Rotavirus') {
            return 'A08.0; Rotaviral enteritis';
        }
        else if($disease == 'Typhoid') {
            return 'A01; Typhoid and paratyphoid fevers (ENTERIC FEVER)';
        }
        else if($disease == 'SevereAcuteRespiratoryInfection') {
            return 'U04; Severe acute respiratory syndrome [SARS]';
        }
    }

    public static function globalSearchCase($case_id, $epi_id, $disease) {
        $epiCol = 'EPIID';

        if($disease == 'ABD') {
            $d = 'Abd';
            $flavor_title = 'Acute Bloody Diarrhea';
        }
        else if($disease == 'AEFI') {
            $d = 'Aefi';
        }
        else if($disease == 'AES') {
            $d = 'Aes';
        }
        else if($disease == 'AFP') {
            $d = 'Afp';
            $flavor_title = 'Acute Flaccid Paralysis';
        }
        else if($disease == 'AHF') {
            $d = 'Ahf';
        }
        else if($disease == 'AMES') {
            $d = 'Ames';

            $flavor_title = 'Acute Meningitis Encephalitis';
        }
        else if($disease == 'ANTHRAX') {
            $d = 'Anthrax';
        }
        else if($disease == 'CHIKV') {
            $d = 'Chikv';

            $flavor_title = 'Chikungunya Viral Disease';
        }
        else if($disease == 'CHOLERA') {
            $d = 'Cholera';

            $flavor_title = 'Cholera';
        }
        else if($disease == 'DENGUE') {
            $d = 'Dengue';

            $flavor_title = 'Dengue';
        }
        else if($disease == 'DIPH') {
            $d = 'Diph';

            $flavor_title = 'Diphteria';
        }
        else if($disease == 'HEPATITIS') {
            $d = 'Hepatitis';

            $flavor_title = 'Acute Viral Hepatitis';
        }
        else if($disease == 'HFMD') {
            $d = 'Hfmd';

            $flavor_title = 'Hand, Foot & Mouth Disease';
        }
        else if($disease == 'INFLUENZA') {
            $d = 'Influenza';

            $flavor_title = 'Influenza-like Illness';
        }
        else if($disease == 'LEPTOSPIROSIS') {
            $d = 'Leptospirosis';

            $flavor_title = 'Leptospirosis';
        }
        else if($disease == 'MALARIA') {
            $d = 'Malaria';

            $flavor_title = 'Malaria';
        }
        else if($disease == 'MEASLES') {
            $d = 'Measles';

            $flavor_title = 'Measles';
        }
        else if($disease == 'MENINGITIS') {
            $d = 'MENINGITIS';

            $flavor_title = 'Meningitis';
        }
        else if($disease == 'MENINGO') {
            $d = 'Meningo';

            $flavor_title = 'Meningococcal Disease';
        }
        else if($disease == 'NNT') {
            $d = 'Nnt';

            $flavor_title = 'Non-Neonatal Tetanus';
        }
        else if($disease == 'NT') {
            $d = 'Nt';

            $flavor_title = 'Neonatal Tetanus';
        }
        else if($disease == 'PERT') {
            $d = 'Pert';

            $flavor_title = 'Pertussis';
        }
        else if($disease == 'PSP') {
            $d = 'Psp';

            $flavor_title = 'TEST';
        }
        else if($disease == 'RABIES') {
            $d = 'Rabies';

            $flavor_title = 'Rabies';
        }
        else if($disease == 'ROTAVIRUS') {
            $d = 'Rotavirus';

            $flavor_title = 'Rotavirus';
        }
        else if($disease == 'TYPHOID') {
            $d = 'Typhoid';

            $flavor_title = 'Typhoid and Paratyphoid Fever';
        }
        else if($disease == 'SARI') {
            $d = 'SevereAcuteRespiratoryInfection';

            $flavor_title = 'Severe Acute Respiratory Infection';

            $epiCol = 'epi_id';
        }

        $modelClass = "App\\Models\\$d";

        $finalArray = [];

        $p = $modelClass::where('edcs_caseid', $case_id)
        ->first();

        if(!$p) {
            $p = $modelClass::where($epiCol, $epi_id)
            ->first();
        }

        if($p) {
            //Search Lab Details
            if($p->from_edcs == 1) {
                $lab_details = EdcsLaboratoryData::where('epi_id', $epi_id)
                ->orWhere('case_id', $p->edcs_caseid)
                ->orderBy('timestamp', 'DESC')
                ->get()
                ->toArray();
            }
            else {
                $lab_details = NULL;
            }
            
            $finalArray = $finalArray + [
                'details' => $p->toArray(),
                'lab_data' => $lab_details,
            ];

            return $finalArray;
        }
        else {

        }
    }

    public function labLogbook() {
        $list = LabResultLogBookGroup::where('facility_id', auth()->user()->itr_facility_id)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        return view('pidsr.laboratory.home', [
            'list' => $list,
        ]);
    }

    public function storeLogBookGroup(Request $r) {
        $check = LabResultLogBookGroup::where('title', mb_strtoupper($r->title))->first();

        if($check) {
            return redirect()->route('pidsr_laboratory_home')
            ->with('msg', 'Error: Duplicate Title.')
            ->with('msgtype', 'danger');
        }

        $save = $r->user()->lablogbookgroup()->create([
            'disease_tag' => $r->disease_tag,
            'title' => mb_strtoupper($r->title),
            'base_specimen_type' => $r->base_specimen_type,
            'base_test_type' => $r->base_test_type,
            'base_collector_name' => mb_strtoupper($r->base_collector_name),
            'sent_to_ritm' => $r->sent_to_ritm,
            'case_open_date' => date('Y-m-d'),
            'facility_id' => auth()->user()->itr_facility_id,
        ]);

        return redirect()->route('pidsr_laboratory_group_home', $save->id)
        ->with('msg', 'Master linelist successfully created. You may now start linking each patient samples.')
        ->with('msgtype', 'success');
    }

    public function viewLogBookGroup($group) {
        $d = LabResultLogBookGroup::findOrFail($group);

        $fetch_list = LabResultLogBook::where('group_id', $group)->get();

        if(request()->input('case_id')) {
            $case_id = request()->input('case_id');
            $disease = request()->input('disease');
            $link_array = PIDSRController::globalSearchCase($case_id, NULL, $disease);

            if($disease == 'SARI') {
                $lname = $link_array['details']['lname'];
                $fname = $link_array['details']['fname'];
                $mname = $link_array['details']['middle_name'];
                $suffix = $link_array['details']['suffix'];
                $gender = $link_array['details']['sex'];
                $age = $link_array['details']['age_years'];
            }
            else {
                $lname = $link_array['details']['FamilyName'];
                $fname = $link_array['details']['FirstName'];
                $mname = $link_array['details']['middle_name'];
                $suffix = $link_array['details']['suffix'];
                $gender = $link_array['details']['Sex'];
                $age = $link_array['details']['AgeYears'];
            }

            $manual_mode = false;
        }
        else {
            $disease = NULL;
            $manual_mode = true;
            $link_array = NULL;

            $lname = NULL;
            $fname = NULL;
            $mname = NULL;
            $suffix = NULL;
            $gender = NULL;
            $age = NULL;
        }

        return view('pidsr.laboratory.group_view', [
            'd' => $d,
            'fetch_list' => $fetch_list,

            'manual_mode' => $manual_mode,
            'link_array' => $link_array,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'suffix' => $suffix,
            'gender' => $gender,
            'age' => $age,
        ]);
    }

    public function storePatientLabLogBook(Request $r, $group) {
        $d = LabResultLogBookGroup::findOrFail($group);

        if($d->is_finished == 'Y') {
            return redirect()->route('pidsr_laboratory_group_home', $group)
            ->with('msg', 'Error: Case is already closed.')
            ->with('msgtype', 'danger');
        }

        $existing_record = LabResultLogBook::where('group_id', $group)
        ->where('lname', mb_strtoupper($r->lname))
        ->where('fname', mb_strtoupper($r->fname));
        
        if($r->filled('mname')) {
            $existing_record = $existing_record->where('mname', mb_strtoupper($r->mname));
        }

        if($r->filled('suffix')) {
            $existing_record = $existing_record->where('suffix', mb_strtoupper($r->suffix));
        }

        $existing_record = $existing_record->whereDate('date_collected', $r->date_collected)
        ->where('specimen_type', $r->specimen_type)
        ->where('test_type', $r->test_type)
        ->first();

        if($existing_record) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Record already exists. Please double check the fields then try again.')
            ->with('msgtype', 'warning');
        }

        if($r->linkto_caseid) {
            $link_case_id = $r->linkto_caseid;
            $manual_mode = false;
        }
        else {
            $link_case_id = NULL;
            $manual_mode = true;
        }

        $returnVars = [
            'group_id' => $group,
            'for_case_id' => $link_case_id,
            'lname' => mb_strtoupper($r->lname),
            'fname' => mb_strtoupper($r->fname),
            'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
            'age' => $r->age,
            'gender' => $r->gender,
            'specimen_type' => $r->specimen_type,
            'test_type' => $r->test_type,
            'date_collected' => $r->date_collected,
            'collector_name' => mb_strtoupper($r->collector_name),
            'lab_number' => ($r->filled('lab_number')) ? mb_strtoupper($r->lab_number) : NULL,
            
            'date_released' => ($r->filled('date_released') && $r->result != 'PENDING') ? $r->date_released : NULL,
            'result' => $r->result,
            'interpretation' => ($r->filled('interpretation')) ? mb_strtoupper($r->interpretation) : NULL,
            'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,
            
            'facility_id' => auth()->user()->itr_facility_id,
        ];

        if($r->result != 'PENDING') {
            $returnVars = $returnVars + [
                'result_updated_by' => auth()->user()->id,
                'result_updated_date' => date('Y-m-d'),
            ];
        }

        $c = $r->user()->lablogbookpatient()->create($returnVars);
        
        return redirect()->route('pidsr_laboratory_group_home', $group)
        ->with('msg', 'Laboratory data was successfully added to the Logbook.')
        ->with('msgtype', 'success');
    }

    public function viewPatientLabLogBook($group, $patient) {
        $d = LabResultLogBook::findOrFail($patient);

        if($d->group_id != $group) {
            return abort(401);
        }

        return view('pidsr.laboratory.patient_edit', [
            'd' => $d,
        ]);
    }

    public function updatePatientLabLogBook($group, $patient, Request $r) {
        $d = LabResultLogBook::findOrFail($patient);

        if($d->group_id != $group) {
            return abort(401);
        }

        $check = LabResultLogBook::where('id', '!=', $patient)
        ->where('group_id', $group)
        ->where('lname', mb_strtoupper($r->lname))
        ->where('fname', mb_strtoupper($r->fname));

        if($r->filled('mname')) {
            $check = $check->where('mname', mb_strtoupper($r->mname));
        }

        if($r->filled('suffix')) {
            $check = $check->where('suffix', mb_strtoupper($r->suffix));
        }

        $check = $check->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Duplicate patient exist in this group. Kindly double check and try again.')
            ->with('msgtype', 'warning');
        }

        $d->lname = mb_strtoupper($r->lname);
        $d->fname = mb_strtoupper($r->fname);
        $d->mname = ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL;
        $d->suffix = ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL;
        $d->age = $r->age;
        $d->gender = $r->gender;
        $d->specimen_type = $r->specimen_type;
        $d->test_type = $r->test_type;
        $d->date_collected = $r->date_collected;
        $d->collector_name = mb_strtoupper($r->collector_name);
        $d->lab_number = ($r->filled('lab_number')) ? mb_strtoupper($r->lab_number) : NULL;
        
        $d->date_released = ($r->filled('date_released') && $r->result != 'PENDING') ? $r->date_released : NULL;
        $d->result = $r->result;
        $d->interpretation = ($r->filled('interpretation')) ? mb_strtoupper($r->interpretation) : NULL;
        $d->remarks = ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL;

        $d->lname = mb_strtoupper($r->lname);
        $d->lname = mb_strtoupper($r->lname);
        $d->lname = mb_strtoupper($r->lname);

        if($d->isDirty('result')) {
            if($d->result != 'PENDING' && is_null($d->result_updated_by)) {
                $d->result_updated_by = auth()->user()->id;
                $d->result_updated_date = date('Y-m-d');
            }
        }

        if($d->isDirty()) {
            $d->updated_by = auth()->user()->id;
            $d->save();
        }

        return redirect()->route('pidsr_laboratory_group_home', $group)
            ->with('msg', 'Updated the patient details successfully.')
            ->with('msgtype', 'success');
    }

    public function updateLabLogBookGroup($group, Request $r) {
        $d = LabResultLogBookGroup::findOrFail($group);

        $c = LabResultLogBookGroup::where('id', '!=', $group)
        ->where('title', mb_strtoupper($r->title))
        ->first();

        if($c) {
            return redirect()->route('pidsr_laboratory_group_home', $group)
            ->with('msg', 'Error: Duplicate Case Title was found. Please try another.')
            ->with('msgtype', 'warning');
        }

        $d->disease_tag = $r->disease_tag;
        $d->title = mb_strtoupper($r->title);
        $d->base_specimen_type = $r->base_specimen_type;
        $d->base_test_type = $r->base_test_type;
        $d->base_collector_name = mb_strtoupper($r->base_collector_name);
        $d->sent_to_ritm = $r->sent_to_ritm;
        $d->ritm_date_sent = ($r->sent_to_ritm == 'Y') ? $r->ritm_date_sent : NULL;
        $d->ritm_date_received = ($r->sent_to_ritm == 'Y') ? $r->ritm_date_received : NULL;
        $d->driver_name = mb_strtoupper($r->driver_name);
        $d->is_finished = $r->is_finished;
        $d->case_open_date = $r->case_open_date;
        $d->case_close_date = ($r->is_finished == 'Y') ? $r->case_close_date : NULL;
        $d->remarks = ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL;

        if($d->isDirty()) {
            if($r->is_finished == 'Y' && is_null($d->case_closed_by)) {
                $d->case_closed_by = auth()->user()->id;
            }
            $d->updated_by = auth()->user()->id;
            $d->save();
        }

        return redirect()->route('pidsr_laboratory_group_home', $group)
        ->with('msg', 'Linelist data successfully updated.')
        ->with('msgtype', 'success');
    }

    public function deleteLabLogBook(LabResultLogBook $id) {
        $id->delete();

        return redirect()->route('pidsr_laboratory_home')
        ->with('msg', 'Specimen data was deleted successfully.')
        ->with('msgtype', 'success');
    }

    public function printLabLogBook($group) {
        $d = LabResultLogBookGroup::findOrFail($group);

        if($d->sent_to_ritm == 'Y' && is_null($d->ritm_date_sent)) {
            return redirect()->route('pidsr_laboratory_group_home', $group)
            ->with('msg', 'Error: Please set [Date Sent on RITM] and other required fields on the "Settings" button.')
            ->with('msgtype', 'warning');
        }

        $fetch_list = LabResultLogBook::where('group_id', $group)->get();

        return view('pidsr.laboratory.print_new', [
            'd' => $d,
            'fetch_list' => $fetch_list,
        ]);
    }

    public function linkEdcs() {
        $open_list = LabResultLogBookGroup::where('is_finished', 'N')
        ->get();

        return view('pidsr.laboratory.select_group', [
            'list' => $open_list,
        ]);
    }

    public function linkEdcsProcess(Request $r) {
        return redirect()->route('pidsr_laboratory_group_home', [
            'group' => $r->group_id,
            'case_id' => $r->edcs_cid,
            'disease' => $r->disease,
        ]);
    }

    public function brgyCaseViewerWelcome() {
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->get();

        return view('pidsr.barangay.brgy_case_viewer_welcome', [
            'brgy_list' => $brgy_list,
        ]);
    }

    public function brgyCaseViewerQuickLogin() {
        if(request()->input('brgy') && request()->input('qlcode')) {
            $brgy = request()->input('brgy');
            $code = request()->input('qlcode');
            
            $search = Brgy::where('brgyName', $brgy)->where('edcs_quicklogin_code', $code)->first();

            if($search) {
                $session_code = mb_strtoupper(Str::random(5));

                request()->session()->regenerate();

                $search->edcs_session_code = $session_code;
                $search->edcs_ip = request()->ip();
                $search->edcs_lastlogin_date = date('Y-m-d H:i:s');

                if($search->isDirty()) {
                    $search->save();
                }

                Session::put('brgyName', $brgy); // Set custom session variable
                //Session::put('edcs_pw', $credentials['password']); // Set custom session variable
                Session::put('session_code', $session_code);
                Session::put('isPoblacion', $search->isPoblacion());

                return redirect()->route('edcs_barangay_home');
            }
            else {
                return 'Error: Wrong Barangay or Code given. You may contact General Trias CESU Staff (Luis P. Broas or Christian James Historillo) for assistance.';
            }
        }
        else {
            return abort(401);
        }
    }

    public function brgyCaseViewerLogin(Request $r) {
        // Validate the input
        $r->validate([
            'brgy' => 'required',
            'password' => 'required',
        ]);

        // Get the credentials from the request
        $credentials = $r->only('brgy', 'password');

        // Attempt to authenticate the user
        $auth = Brgy::where('brgyName', $credentials['brgy'])->where('edcs_pw', $credentials['password'])->first();

        if ($auth) {
            $session_code = mb_strtoupper(Str::random(5));

            /*
            Remove then add anti-flood login code later...
            
            //put session code and IP address on the database
            $record = Brgy::where('brgyName', $credentials['brgy'])->where('edcs_pw', $credentials['password'])->first();
            
            if($record->edcs_session_code != $session_code) {
                //check if 5 minutes has passed
                $date1 = Carbon::parse($record->edcs_lastlogin_date);
                $currentDatetime = Carbon::now();

                if($date1->lte($currentDatetime->subMinutes(5)) || is_null($record->edcs_lastlogin_date)) {
                    
                }
                else {
                    return redirect()->route('edcs_barangay_welcome')
                    ->with('msg', 'Account is already logged in. Please try again after 5 minutes.')
                    ->with('msgtype', 'warning');
                }
            }
            */

            // Authentication passed, create session
            $r->session()->regenerate();
            Session::put('brgyName', $credentials['brgy']); // Set custom session variable
            //Session::put('edcs_pw', $credentials['password']); // Set custom session variable
            Session::put('session_code', $session_code);
            Session::put('isPoblacion', $auth->isPoblacion());

            $update = Brgy::where('brgyName', $credentials['brgy'])->where('edcs_pw', $credentials['password'])->update([
                'edcs_lastlogin_date' => date('Y-m-d H:i:s'),
                'edcs_session_code' => $session_code,
                'edcs_ip' =>  request()->ip(),
            ]);

            // Redirect to a route or return a response
            return redirect()->route('edcs_barangay_home');
        }

        // Authentication failed, redirect back with error
        return redirect()->back()->withInput()
        ->with('msg', 'The barangay or password is incorrect. Kindly double check and try again. For account concerns, you may contact CESU Staff.')
        ->with('msgtype', 'warning');
    }

    public function brgyCaseViewerHome() { 
        $brgy = session('brgyName');

        $abd_query = Abd::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $afp_query = Afp::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $ames_query = Ames::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $hepa_query = Hepatitis::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $chikv_query = Chikv::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $cholera_query = Cholera::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $dengue_query = Dengue::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $diph_query = Diph::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $hfmd_query = Hfmd::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $ili_query = Influenza::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $lepto_query = Leptospirosis::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $measles_query = Measles::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $meningo_query = Meningo::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $nnt_query = Nnt::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $nt_query = Nt::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $pert_query = Pert::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $rabies_query = Rabies::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $rotavirus_query = Rotavirus::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $sari_query = SevereAcuteRespiratoryInfection::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('barangay', $brgy);
        $typhoid_query = Typhoid::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);

        $aes_query = Aes::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $aefi_query = Aefi::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $ahf_query = Ahf::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $anthrax_query = Anthrax::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $malaria_query = Malaria::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $meningitis_query = Meningitis::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        $psp_query = Psp::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Barangay', $brgy);
        
        $covid_query = Forms::with('records')
        ->whereHas('records', function ($q) use ($brgy) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->where('records.address_brgy', $brgy);
        })
        ->where('status', 'approved')
        ->whereIn('caseClassification', ['Confirmed', 'Probable']);

        if(request()->input('year')) {
            $year = request()->input('year');
        }
        else {
            $year = date('Y');
        }

        $abd_count = (clone $abd_query)->where('Year', $year)->count();
        $afp_count = (clone $afp_query)->where('Year', $year)->count();
        $ames_count = (clone $ames_query)->where('Year', $year)->count();
        $hepa_count = (clone $hepa_query)->where('Year', $year)->count();
        $chikv_count = (clone $chikv_query)->where('Year', $year)->count();
        $cholera_count = (clone $cholera_query)->where('Year', $year)->count();
        $dengue_count = (clone $dengue_query)->where('Year', $year)->count();
        $diph_count = (clone $diph_query)->where('Year', $year)->count();
        $hfmd_count = (clone $hfmd_query)->where('Year', $year)->count();
        $ili_count = (clone $ili_query)->where('Year', $year)->count();
        $lepto_count = (clone $lepto_query)->where('Year', $year)->count();
        $measles_count = (clone $measles_query)->where('Year', $year)->count();
        $meningo_count = (clone $meningo_query)->where('Year', $year)->count();
        $nnt_count = (clone $nnt_query)->where('Year', $year)->count();
        $nt_count = (clone $nt_query)->where('Year', $year)->count();
        $pert_count = (clone $pert_query)->where('Year', $year)->count();
        $rabies_count = (clone $rabies_query)->where('Year', $year)->count();
        $rotavirus_count = (clone $rotavirus_query)->where('Year', $year)->count();
        $sari_count = (clone $sari_query)->where('year', $year)->count();
        $typhoid_count = (clone $typhoid_query)->where('Year', $year)->count();
        $covid_count = (clone $covid_query)->whereYear('morbidityMonth', $year)->count();
        $aes_count = (clone $aes_query)->where('Year', $year)->count();
        $aefi_count = (clone $aefi_query)->where('Year', $year)->count();
        $ahf_count = (clone $ahf_query)->where('Year', $year)->count();
        $anthrax_count = (clone $anthrax_query)->where('Year', $year)->count();
        $malaria_count = (clone $malaria_query)->where('Year', $year)->count();
        $meningitis_count = (clone $meningitis_query)->where('Year', $year)->count();
        $psp_count = (clone $psp_query)->where('Year', $year)->count();

        $abd_count_death = (clone $abd_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $afp_count_death = (clone $afp_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $ames_count_death = (clone $ames_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $hepa_count_death = (clone $hepa_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $chikv_count_death = (clone $chikv_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $cholera_count_death = (clone $cholera_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $dengue_count_death = (clone $dengue_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $diph_count_death = (clone $diph_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $hfmd_count_death = (clone $hfmd_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $ili_count_death = (clone $ili_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $lepto_count_death = (clone $lepto_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $measles_count_death = (clone $measles_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $meningo_count_death = (clone $meningo_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $nnt_count_death = (clone $nnt_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $nt_count_death = (clone $nt_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $pert_count_death = (clone $pert_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $rabies_count_death = (clone $rabies_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $rotavirus_count_death = (clone $rotavirus_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $sari_count_death = (clone $sari_query)->where('year', $year)->where('outcome', 'Died')->count();
        $typhoid_count_death = (clone $typhoid_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $covid_count_death = (clone $covid_query)->whereYear('morbidityMonth', $year)->where('outcomeCondition', 'Died')->count();
        $aes_count_death = (clone $aes_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $aefi_count_death = (clone $aefi_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $ahf_count_death = (clone $ahf_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $anthrax_count_death = (clone $anthrax_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $malaria_count_death = (clone $malaria_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $meningitis_count_death = (clone $meningitis_query)->where('Year', $year)->where('Outcome', 'D')->count();
        $psp_count_death = (clone $psp_query)->where('Year', $year)->where('Outcome', 'D')->count();

        $abd_route = route('edcs_barangay_view_list', ['case' => 'Abd', 'year' => $year]);
        $afp_route = route('edcs_barangay_view_list', ['case' => 'Afp', 'year' => $year]);
        $ames_route = route('edcs_barangay_view_list', ['case' => 'Ames', 'year' => $year]);
        $hepa_route = route('edcs_barangay_view_list', ['case' => 'Hepatitis', 'year' => $year]);
        $chikv_route = route('edcs_barangay_view_list', ['case' => 'Chikv', 'year' => $year]);
        $cholera_route = route('edcs_barangay_view_list', ['case' => 'Cholera', 'year' => $year]);
        $dengue_route = route('edcs_barangay_view_list', ['case' => 'Dengue', 'year' => $year]);
        $diph_route = route('edcs_barangay_view_list', ['case' => 'Diph', 'year' => $year]);
        $hfmd_route = route('edcs_barangay_view_list', ['case' => 'Hfmd', 'year' => $year]);
        $ili_route = route('edcs_barangay_view_list', ['case' => 'Influenza', 'year' => $year]);
        $lepto_route = route('edcs_barangay_view_list', ['case' => 'Leptospirosis', 'year' => $year]);
        $measles_route = route('edcs_barangay_view_list', ['case' => 'Measles', 'year' => $year]);
        $meningo_route = route('edcs_barangay_view_list', ['case' => 'Meningo', 'year' => $year]);
        $nnt_route = route('edcs_barangay_view_list', ['case' => 'Nt', 'year' => $year]);
        $nt_route = route('edcs_barangay_view_list', ['case' => 'Nnt', 'year' => $year]);
        $pert_route = route('edcs_barangay_view_list', ['case' => 'Pert', 'year' => $year]);
        $rabies_route = route('edcs_barangay_view_list', ['case' => 'Rabies', 'year' => $year]);
        $rotavirus_route = route('edcs_barangay_view_list', ['case' => 'Rotavirus', 'year' => $year]);
        $sari_route = route('edcs_barangay_view_list', ['case' => 'SevereAcuteRespiratoryInfection', 'year' => $year]);
        $typhoid_route = route('edcs_barangay_view_list', ['case' => 'Typhoid', 'year' => $year]);

        $aes_route = route('edcs_barangay_view_list', ['case' => 'Aes', 'year' => $year]);
        $aefi_route = route('edcs_barangay_view_list', ['case' => 'Aefi', 'year' => $year]);
        $ahf_route = route('edcs_barangay_view_list', ['case' => 'Ahf', 'year' => $year]);
        $anthrax_route = route('edcs_barangay_view_list', ['case' => 'Anthrax', 'year' => $year]);
        $malaria_route = route('edcs_barangay_view_list', ['case' => 'Malaria', 'year' => $year]);
        $meningitis_route = route('edcs_barangay_view_list', ['case' => 'Meningitis', 'year' => $year]);
        $psp_route = route('edcs_barangay_view_list', ['case' => 'Psp', 'year' => $year]);

        return view('pidsr.barangay.brgy_case_viewer_home', [
            'abd_count' => $abd_count,
            'afp_count' => $afp_count,
            'ames_count' => $ames_count,
            'hepa_count' => $hepa_count,
            'chikv_count' => $chikv_count,
            'cholera_count' => $cholera_count,
            'dengue_count' => $dengue_count,
            'diph_count' => $diph_count,
            'hfmd_count' => $hfmd_count,
            'ili_count' => $ili_count,
            'lepto_count' => $lepto_count,
            'measles_count' => $measles_count,
            'meningo_count' => $meningo_count,
            'nnt_count' => $nnt_count,
            'nt_count' => $nt_count,
            'pert_count' => $pert_count,
            'rabies_count' => $rabies_count,
            'rotavirus_count' => $rotavirus_count,
            'sari_count' => $sari_count,
            'typhoid_count' => $typhoid_count,
            'covid_count' => $covid_count,
            'aes_count' => $aes_count,
            'aefi_count' => $aefi_count,
            'ahf_count' => $ahf_count,
            'anthrax_count' => $anthrax_count,
            'malaria_count' => $malaria_count,
            'meningitis_count' => $meningitis_count,
            'psp_count' => $psp_count,

            'year' => $year,

            'abd_route' => $abd_route,
            'afp_route' => $afp_route,
            'ames_route' => $ames_route,
            'hepa_route' => $hepa_route,
            'chikv_route' => $chikv_route,
            'cholera_route' => $cholera_route,
            'dengue_route' => $dengue_route,
            'diph_route' => $diph_route,
            'hfmd_route' => $hfmd_route,
            'ili_route' => $ili_route,
            'lepto_route' => $lepto_route,
            'measles_route' => $measles_route,
            'meningo_route' => $meningo_route,
            'nnt_route' => $nnt_route,
            'nt_route' => $nt_route,
            'pert_route' => $pert_route,
            'rabies_route' => $rabies_route,
            'rotavirus_route' => $rotavirus_route,
            'sari_route' => $sari_route,
            'typhoid_route' => $typhoid_route,
            'aefi_route' => $aefi_route,
            'aes_route' => $aes_route,
            'ahf_route' => $ahf_route,
            'anthrax_route' => $anthrax_route,
            'malaria_route' => $malaria_route,
            'meningitis_route' => $meningitis_route,
            'psp_route' => $psp_route,

            'abd_count_death' => $abd_count_death,
            'afp_count_death' => $afp_count_death,
            'ames_count_death' => $ames_count_death,
            'hepa_count_death' => $hepa_count_death,
            'chikv_count_death' => $chikv_count_death,
            'cholera_count_death' => $cholera_count_death,
            'dengue_count_death' => $dengue_count_death,
            'diph_count_death' => $diph_count_death,
            'hfmd_count_death' => $hfmd_count_death,
            'ili_count_death' => $ili_count_death,
            'lepto_count_death' => $lepto_count_death,
            'measles_count_death' => $measles_count_death,
            'meningo_count_death' => $meningo_count_death,
            'nnt_count_death' => $nnt_count_death,
            'nt_count_death' => $nt_count_death,
            'pert_count_death' => $pert_count_death,
            'rabies_count_death' => $rabies_count_death,
            'rotavirus_count_death' => $rotavirus_count_death,
            'sari_count_death' => $sari_count_death,
            'typhoid_count_death' => $typhoid_count_death,
            'covid_count_death' => $covid_count_death,
            'aes_count_death' => $aes_count_death,
            'aefi_count_death' => $aefi_count_death,
            'ahf_count_death' => $ahf_count_death,
            'anthrax_count_death' => $anthrax_count_death,
            'malaria_count_death' => $malaria_count_death,
            'meningitis_count_death' => $meningitis_count_death,
            'psp_count_death' => $psp_count_death,
        ]);
    }

    public function brgyCaseViewerViewList($case) {
        $brgy = session('brgyName');

        $model = "App\\Models\\$case";

        $list = $model::where('enabled', 1)
        ->where('match_casedef', 1);

        if(session('isPoblacion')) {
            $list = $list->where(function ($q) {
                $q->where('Barangay', 'ARNALDO POB. (BGY. 7)')
                ->orWhere('Barangay', 'BAGUMBAYAN POB. (BGY. 5)')
                ->orWhere('Barangay', 'CORREGIDOR POB. (BGY. 10)')
                ->orWhere('Barangay', 'DULONG BAYAN POB. (BGY. 3)')
                ->orWhere('Barangay', 'GOV. FERRER POB. (BGY. 1)')
                ->orWhere('Barangay', 'NINETY SIXTH POB. (BGY. 8)')
                ->orWhere('Barangay', 'PRINZA POB. (BGY. 9)')
                ->orWhere('Barangay', 'SAMPALUCAN POB. (BGY. 2)')
                ->orWhere('Barangay', 'SAN GABRIEL POB. (BGY. 4)')
                ->orWhere('Barangay', 'VIBORA POB. (BGY. 6)');
            });
        }
        else {
            $list = $list->where('Barangay', $brgy);
        }

        if(request()->input('year')) {
            $year = request()->input('year');
        }
        else {
            $year = date('Y');
        }

        if($case == 'SevereAcuteRespiratoryInfection') {
            $mweekcol = 'morbidity_week';
        }
        else {
            $mweekcol = 'MorbidityWeek';
        }

        $list = $list = $list->where('Year', $year)
        ->orderBy($mweekcol, 'DESC')
        ->get();

        return view('pidsr.barangay.brgy_case_viewer_viewlist', [
            'list' => $list,
            'case' => $case,
            'year' => $year,
        ]);
    }

    public function brgyCaseViewerLogout() {
        session()->flush(); // Clears all session data

        return redirect()->route('edcs_barangay_welcome');
    }

    public function epDroneHome() {
        $abd_query = Abd::where('enabled', 1)
        ->where('match_casedef', 1);
        $afp_query = Afp::where('enabled', 1)
        ->where('match_casedef', 1);
        $ames_query = Ames::where('enabled', 1)
        ->where('match_casedef', 1);
        $hepa_query = Hepatitis::where('enabled', 1)
        ->where('match_casedef', 1);
        $chikv_query = Chikv::where('enabled', 1)
        ->where('match_casedef', 1);
        $cholera_query = Cholera::where('enabled', 1)
        ->where('match_casedef', 1);
        $dengue_query = Dengue::where('enabled', 1)
        ->where('match_casedef', 1);
        $diph_query = Diph::where('enabled', 1)
        ->where('match_casedef', 1);
        $hfmd_query = Hfmd::where('enabled', 1)
        ->where('match_casedef', 1);
        $ili_query = Influenza::where('enabled', 1)
        ->where('match_casedef', 1);
        $lepto_query = Leptospirosis::where('enabled', 1)
        ->where('match_casedef', 1);
        $measles_query = Measles::where('enabled', 1)
        ->where('match_casedef', 1);
        $meningo_query = Meningo::where('enabled', 1)
        ->where('match_casedef', 1);
        $nnt_query = Nnt::where('enabled', 1)
        ->where('match_casedef', 1);
        $nt_query = Nt::where('enabled', 1)
        ->where('match_casedef', 1);
        $pert_query = Pert::where('enabled', 1)
        ->where('match_casedef', 1);
        $rabies_query = Rabies::where('enabled', 1)
        ->where('match_casedef', 1);
        $rotavirus_query = Rotavirus::where('enabled', 1)
        ->where('match_casedef', 1);
        $sari_query = SevereAcuteRespiratoryInfection::where('enabled', 1)
        ->where('match_casedef', 1);
        $typhoid_query = Typhoid::where('enabled', 1)
        ->where('match_casedef', 1);

        $aes_query = Aes::where('enabled', 1)
        ->where('match_casedef', 1);
        $aefi_query = Aefi::where('enabled', 1)
        ->where('match_casedef', 1);
        $ahf_query = Ahf::where('enabled', 1)
        ->where('match_casedef', 1);
        $anthrax_query = Anthrax::where('enabled', 1)
        ->where('match_casedef', 1);
        $malaria_query = Malaria::where('enabled', 1)
        ->where('match_casedef', 1);
        $meningitis_query = Meningitis::where('enabled', 1)
        ->where('match_casedef', 1);
        $psp_query = Psp::where('enabled', 1)
        ->where('match_casedef', 1);

        $covid_query = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('caseClassification', ['Confirmed', 'Probable']);

        if(request()->input('year')) {
            $year = request()->input('year');
        }
        else {
            $year = date('Y');
        }

        $abd_count = $abd_query->where('Year', $year)->count();
        $afp_count = $afp_query->where('Year', $year)->count();
        $ames_count = $ames_query->where('Year', $year)->count();
        $hepa_count = $hepa_query->where('Year', $year)->count();
        $chikv_count = $chikv_query->where('Year', $year)->count();
        $cholera_count = $cholera_query->where('Year', $year)->count();
        $dengue_count = $dengue_query->where('Year', $year)->count();
        $diph_count = $diph_query->where('Year', $year)->count();
        $hfmd_count = $hfmd_query->where('Year', $year)->count();
        $ili_count = $ili_query->where('Year', $year)->count();
        $lepto_count = $lepto_query->where('Year', $year)->count();
        $measles_count = $measles_query->where('Year', $year)->count();
        $meningo_count = $meningo_query->where('Year', $year)->count();
        $nnt_count = $nnt_query->where('Year', $year)->count();
        $nt_count = $nt_query->where('Year', $year)->count();
        $pert_count = $pert_query->where('Year', $year)->count();
        $rabies_count = $rabies_query->where('Year', $year)->count();
        $rotavirus_count = $rotavirus_query->where('Year', $year)->count();
        $sari_count = $sari_query->where('year', $year)->count();
        $typhoid_count = $typhoid_query->where('Year', $year)->count();
        $covid_count = $covid_query->whereYear('morbidityMonth', $year)->count();
        $aes_count = $aes_query->where('Year', $year)->count();
        $aefi_count = $aefi_query->where('Year', $year)->count();
        $ahf_count = $ahf_query->where('Year', $year)->count();
        $anthrax_count = $anthrax_query->where('Year', $year)->count();
        $malaria_count = $malaria_query->where('Year', $year)->count();
        $meningitis_count = $meningitis_query->where('Year', $year)->count();
        $psp_count = $psp_query->where('Year', $year)->count();

        $abd_count_death = $abd_query->where('Year', $year)->where('Outcome', 'D')->count();
        $afp_count_death = $afp_query->where('Year', $year)->where('Outcome', 'D')->count();
        $ames_count_death = $ames_query->where('Year', $year)->where('Outcome', 'D')->count();
        $hepa_count_death = $hepa_query->where('Year', $year)->where('Outcome', 'D')->count();
        $chikv_count_death = $chikv_query->where('Year', $year)->where('Outcome', 'D')->count();
        $cholera_count_death = $cholera_query->where('Year', $year)->where('Outcome', 'D')->count();
        $dengue_count_death = $dengue_query->where('Year', $year)->where('Outcome', 'D')->count();
        $diph_count_death = $diph_query->where('Year', $year)->where('Outcome', 'D')->count();
        $hfmd_count_death = $hfmd_query->where('Year', $year)->where('Outcome', 'D')->count();
        $ili_count_death = $ili_query->where('Year', $year)->where('Outcome', 'D')->count();
        $lepto_count_death = $lepto_query->where('Year', $year)->where('Outcome', 'D')->count();
        $measles_count_death = $measles_query->where('Year', $year)->where('Outcome', 'D')->count();
        $meningo_count_death = $meningo_query->where('Year', $year)->where('Outcome', 'D')->count();
        $nnt_count_death = $nnt_query->where('Year', $year)->where('Outcome', 'D')->count();
        $nt_count_death = $nt_query->where('Year', $year)->where('Outcome', 'D')->count();
        $pert_count_death = $pert_query->where('Year', $year)->where('Outcome', 'D')->count();
        $rabies_count_death = $rabies_query->where('Year', $year)->where('Outcome', 'D')->count();
        $rotavirus_count_death = $rotavirus_query->where('Year', $year)->where('Outcome', 'D')->count();
        $sari_count_death = $sari_query->where('year', $year)->where('outcome', 'Died')->count();
        $typhoid_count_death = $typhoid_query->where('Year', $year)->where('Outcome', 'D')->count();
        $covid_count_death = $covid_query->whereYear('morbidityMonth', $year)->where('outcomeCondition', 'Died')->count();
        $aes_count_death = $aes_query->where('Year', $year)->where('Outcome', 'D')->count();
        $aefi_count_death = $aefi_query->where('Year', $year)->where('Outcome', 'D')->count();
        $ahf_count_death = $ahf_query->where('Year', $year)->where('Outcome', 'D')->count();
        $anthrax_count_death = $anthrax_query->where('Year', $year)->where('Outcome', 'D')->count();
        $malaria_count_death = $malaria_query->where('Year', $year)->where('Outcome', 'D')->count();
        $meningitis_count_death = $meningitis_query->where('Year', $year)->where('Outcome', 'D')->count();
        $psp_count_death = $psp_query->where('Year', $year)->where('Outcome', 'D')->count();
        
        $abd_route = route('pidsr.casechecker', ['case' => 'ABD', 'year' => $year]);
        $afp_route = route('pidsr.casechecker', ['case' => 'AFP', 'year' => $year]);
        $ames_route = route('pidsr.casechecker', ['case' => 'AMES', 'year' => $year]);
        $hepa_route = route('pidsr.casechecker', ['case' => 'HEPA', 'year' => $year]);
        $chikv_route = route('pidsr.casechecker', ['case' => 'CHIKV', 'year' => $year]);
        $cholera_route = route('pidsr.casechecker', ['case' => 'CHOLERA', 'year' => $year]);
        $dengue_route = route('pidsr.casechecker', ['case' => 'DENGUE', 'year' => $year]);
        $diph_route = route('pidsr.casechecker', ['case' => 'DIPH', 'year' => $year]);
        $hfmd_route = route('pidsr.casechecker', ['case' => 'HFMD', 'year' => $year]);
        $ili_route = route('pidsr.casechecker', ['case' => 'INFLUENZA', 'year' => $year]);
        $lepto_route = route('pidsr.casechecker', ['case' => 'LEPTOSPIROSIS', 'year' => $year]);
        $measles_route = route('pidsr.casechecker', ['case' => 'MEASLES', 'year' => $year]);
        $meningo_route = route('pidsr.casechecker', ['case' => 'MENINGO', 'year' => $year]);
        $nnt_route = route('pidsr.casechecker', ['case' => 'NNT', 'year' => $year]);
        $nt_route = route('pidsr.casechecker', ['case' => 'NT', 'year' => $year]);
        $pert_route = route('pidsr.casechecker', ['case' => 'PERT', 'year' => $year]);
        $rabies_route = route('pidsr.casechecker', ['case' => 'RABIES', 'year' => $year]);
        $rotavirus_route = route('pidsr.casechecker', ['case' => 'ROTAVIRUS', 'year' => $year]);
        $sari_route = route('pidsr.casechecker', ['case' => 'SARI', 'year' => $year]);
        $typhoid_route = route('pidsr.casechecker', ['case' => 'TYPHOID', 'year' => $year]);

        $aes_route = route('pidsr.casechecker', ['case' => 'AES', 'year' => $year]);
        $aefi_route = route('pidsr.casechecker', ['case' => 'AEFI', 'year' => $year]);
        $ahf_route = route('pidsr.casechecker', ['case' => 'AHF', 'year' => $year]);
        $anthrax_route = route('pidsr.casechecker', ['case' => 'ANTHRAX', 'year' => $year]);
        $malaria_route = route('pidsr.casechecker', ['case' => 'MALARIA', 'year' => $year]);
        $meningitis_route = route('pidsr.casechecker', ['case' => 'MENINGITIS', 'year' => $year]);
        $psp_route = route('pidsr.casechecker', ['case' => 'PSP', 'year' => $year]);

        return view('pidsr.epdrone_home', [
            'abd_count' => $abd_count,
            'afp_count' => $afp_count,
            'ames_count' => $ames_count,
            'hepa_count' => $hepa_count,
            'chikv_count' => $chikv_count,
            'cholera_count' => $cholera_count,
            'dengue_count' => $dengue_count,
            'diph_count' => $diph_count,
            'hfmd_count' => $hfmd_count,
            'ili_count' => $ili_count,
            'lepto_count' => $lepto_count,
            'measles_count' => $measles_count,
            'meningo_count' => $meningo_count,
            'nnt_count' => $nnt_count,
            'nt_count' => $nt_count,
            'pert_count' => $pert_count,
            'rabies_count' => $rabies_count,
            'rotavirus_count' => $rotavirus_count,
            'sari_count' => $sari_count,
            'typhoid_count' => $typhoid_count,
            'covid_count' => $covid_count,
            'aes_count' => $aes_count,
            'aefi_count' => $aefi_count,
            'ahf_count' => $ahf_count,
            'anthrax_count' => $anthrax_count,
            'malaria_count' => $malaria_count,
            'meningitis_count' => $meningitis_count,
            'psp_count' => $psp_count,

            'abd_count_death' => $abd_count_death,
            'afp_count_death' => $afp_count_death,
            'ames_count_death' => $ames_count_death,
            'hepa_count_death' => $hepa_count_death,
            'chikv_count_death' => $chikv_count_death,
            'cholera_count_death' => $cholera_count_death,
            'dengue_count_death' => $dengue_count_death,
            'diph_count_death' => $diph_count_death,
            'hfmd_count_death' => $hfmd_count_death,
            'ili_count_death' => $ili_count_death,
            'lepto_count_death' => $lepto_count_death,
            'measles_count_death' => $measles_count_death,
            'meningo_count_death' => $meningo_count_death,
            'nnt_count_death' => $nnt_count_death,
            'nt_count_death' => $nt_count_death,
            'pert_count_death' => $pert_count_death,
            'rabies_count_death' => $rabies_count_death,
            'rotavirus_count_death' => $rotavirus_count_death,
            'sari_count_death' => $sari_count_death,
            'typhoid_count_death' => $typhoid_count_death,
            'covid_count_death' => $covid_count_death,
            'aes_count_death' => $aes_count_death,
            'aefi_count_death' => $aefi_count_death,
            'ahf_count_death' => $ahf_count_death,
            'anthrax_count_death' => $anthrax_count_death,
            'malaria_count_death' => $malaria_count_death,
            'meningitis_count_death' => $meningitis_count_death,
            'psp_count_death' => $psp_count_death,

            'year' => $year,

            'abd_route' => $abd_route,
            'afp_route' => $afp_route,
            'ames_route' => $ames_route,
            'hepa_route' => $hepa_route,
            'chikv_route' => $chikv_route,
            'cholera_route' => $cholera_route,
            'dengue_route' => $dengue_route,
            'diph_route' => $diph_route,
            'hfmd_route' => $hfmd_route,
            'ili_route' => $ili_route,
            'lepto_route' => $lepto_route,
            'measles_route' => $measles_route,
            'meningo_route' => $meningo_route,
            'nnt_route' => $nnt_route,
            'nt_route' => $nt_route,
            'pert_route' => $pert_route,
            'rabies_route' => $rabies_route,
            'rotavirus_route' => $rotavirus_route,
            'sari_route' => $sari_route,
            'typhoid_route' => $typhoid_route,
            'aefi_route' => $aefi_route,
            'aes_route' => $aes_route,
            'ahf_route' => $ahf_route,
            'anthrax_route' => $anthrax_route,
            'malaria_route' => $malaria_route,
            'meningitis_route' => $meningitis_route,
            'psp_route' => $psp_route,
        ]);
    }

    public function mapViewerIndex($case) {
        $modelClass = "App\\Models\\".ucwords(strtolower($case));

        if(request()->input('year')) {
            $year = request()->input('year');
        }
        else {
            $year = date('Y');
        }
        
        $list_case = $modelClass::where('enabled', 1)
        ->where('match_casedef', 1)
        ->where('Year', $year);
        
        if(!request()->is('*barangayportal*')) {
            $list_case = $list_case->get();
        }
        else {
            $brgy = session('brgyName');

            if(session('isPoblacion')) {
                $list_case = $list_case->where(function ($q) {
                    $q->where('Barangay', 'ARNALDO POB. (BGY. 7)')
                    ->orWhere('Barangay', 'BAGUMBAYAN POB. (BGY. 5)')
                    ->orWhere('Barangay', 'CORREGIDOR POB. (BGY. 10)')
                    ->orWhere('Barangay', 'DULONG BAYAN POB. (BGY. 3)')
                    ->orWhere('Barangay', 'GOV. FERRER POB. (BGY. 1)')
                    ->orWhere('Barangay', 'NINETY SIXTH POB. (BGY. 8)')
                    ->orWhere('Barangay', 'PRINZA POB. (BGY. 9)')
                    ->orWhere('Barangay', 'SAMPALUCAN POB. (BGY. 2)')
                    ->orWhere('Barangay', 'SAN GABRIEL POB. (BGY. 4)')
                    ->orWhere('Barangay', 'VIBORA POB. (BGY. 6)');
                })->get();
            }
            else {
                $list_case = $list_case->where('Barangay', $brgy)->get();
            }
            
        }

        return view('pidsr.mapviewer', [
            'list_case' => $list_case,
            'case' => $case,
        ]);
    }

    public function mapViewerGetColor() {
        $case = request()->input('disease');
        $brgy = request()->input('brgy');
        $year = request()->input('year');

        if($case == 'Pert') {
            $count = Pert::where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Barangay', $brgy)
            ->where('Year', $year)
            ->count();
        }

        if($count < 1) {
            $return_color = 'white';
        }
        else if($count == 1) {
            $return_color = 'yellow';
        }
        else if($count == 2) {
            $return_color = 'orange';
        }
        else {
            $return_color = 'red';
        }

        return response()->json(['color' => $return_color]);
    }

    public function weeklyMonitoring() {
        $facilities_array = [
            'CITY OF GENERAL TRIAS DOCTORS MEDICAL CENTER, INC.',
            'CITY OF GENERAL TRIAS MEDICARE HOSPITAL',
            'DIVINE GRACE MEDICAL CENTER',
            //'GENERAL TRIAS CITY HEALTH OFFICE',
            'GENERAL TRIAS MATERNITY AND PEDIATRIC HOSPITAL',
            'GENTRI MEDICAL CENTER AND HOSPITAL, INC.',
            'MAMA RACHEL HOSPITAL OF MERCY',
        ];

        if(request()->input('year')) {
            $year = request()->input('year');
            $maxweek = 52;
        }
        else {
            $year = date('Y');
            $maxweek = date('W');
        }
        
        $final_array = [];
        

        foreach($facilities_array as $f) {
            $week_array = [];

            for($i=1; $i <= $maxweek; $i++) {
                $val = EdcsWeeklySubmissionChecker::where('facility_name', $f)
                ->where('year', $year)
                ->where('week', $i)
                ->first();

                if($val) {
                    $val = $val->status;

                    if($val == 'SUBMITTED') {
                        $val = '✔';
                    }
                    else if($val == 'ZERO CASE') {
                        $val = 'Z';
                    }
                }
                else {
                    $val = 'X';
                }

                $week_array[] = $val;
            }

            $final_array[] = [
                'name' => $f,
                'weeks' => $week_array,
            ];
        }

        $fetch_list = EdcsWeeklySubmissionChecker::where('year', $year)->get();

        return view('pidsr.weeklymonitoring', [
            'year' => $year,
            'maxweek' => $maxweek,
            'fetch_list' => $fetch_list,
            'facilities_array' => $facilities_array,

            'final_array' => $final_array,
        ]);
    }

    public function addCaseCheck() {
        $disease = request()->input('disease');
        
        $lname = mb_strtoupper(request()->input('lname'));
        $fname = mb_strtoupper(request()->input('fname'));
        $mname = (!is_null(request()->input('mname'))) ? mb_strtoupper(request()->input('mname')) : NULL;
        $suffix = (!is_null(request()->input('mname'))) ? mb_strtoupper(request()->input('suffix')) : NULL;
        $bdate = request()->input('bdate');

        if($disease == 'MPOX') {
            $check = MonkeyPox::where('lname', $lname)
            ->where('fname', $fname)
            ->where('year', date('Y'))
            ->where('morbidity_month', date('n'))
            ->first();

            if(!$check) {
                return $this->mPoxNewOrEdit(new MonkeyPox())->with('mode', 'NEW');
            }
        }
    }

    public function addCaseStore($disease, Request $r) {
        //Get Facility
        $f = DohFacility::where('id', auth()->user()->itr_facility_id)->first();

        $birthdate = Carbon::parse($r->bdate);
        $currentDate = Carbon::parse($r->date_investigation);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        if($r->permaddress_isdifferent == 'N') {
            $perm_address_region_code = $r->perm_address_region_code;
            $perm_address_region_text = $r->perm_address_region_text;
            $perm_address_province_code = $r->perm_address_province_code;
            $perm_address_province_text = $r->perm_address_province_text;
            $perm_address_muncity_code = $r->perm_address_muncity_code;
            $perm_address_muncity_text = $r->perm_address_muncity_text;
            $perm_address_brgy_code = $r->perm_address_brgy_text;
            $perm_address_brgy_text = $r->perm_address_brgy_text;
            $perm_address_street = mb_strtoupper($r->perm_address_street);
            $perm_address_houseno = mb_strtoupper($r->perm_address_houseno);
        }
        else {
            $perm_address_region_code = $r->address_region_code;
            $perm_address_region_text = $r->address_region_text;
            $perm_address_province_code = $r->address_province_code;
            $perm_address_province_text = $r->address_province_text;
            $perm_address_muncity_code = $r->address_muncity_code;
            $perm_address_muncity_text = $r->address_muncity_text;
            $perm_address_brgy_code = $r->address_brgy_text;
            $perm_address_brgy_text = $r->address_brgy_text;
            $perm_address_street = mb_strtoupper($r->address_street);
            $perm_address_houseno = mb_strtoupper($r->address_houseno);
        }

        if($disease == 'MPOX') {
            //Second layer Record Checking
            $check = MonkeyPox::where('lname', $r->lname)
            ->where('fname', $f->fname)
            ->where('year', $currentDate->format('Y'))
            ->where('morbidity_month', $currentDate->format('n'))
            ->first();

            if(!$check) {
                $c = MonkeyPox::create([
                    'date_investigation' => $r->date_investigation,
                    'laboratory_id' => (!is_null($r->laboratory_id)) ? mb_strtoupper($r->laboratory_id) : NULL,
                    //'epi_id',
                    'enabled' => 1,
                    'match_casedef' => 1,
                    'dru_name' => $f->facility_name,
                    'dru_region' => $f->address_region,
                    'dru_province' => $f->address_province,
                    'dru_muncity' => $f->address_muncity,
                    'dru_street' => NULL,
                    'dru_type' => $r->dru_type,
                    'patient_number' => (!is_null($r->patient_number)) ? mb_strtoupper($r->patient_number) : NULL,
                    'lname' => mb_strtoupper($r->lname),
                    'fname' => mb_strtoupper($r->fname),
                    'mname' => (!is_null($r->mname)) ? mb_strtoupper($r->mname) : NULL,
                    'suffix'  => (!is_null($r->suffix)) ? mb_strtoupper($r->suffix) : NULL,
                    'bdate' => $r->bdate,
                    'gender' => $r->gender,
                    'is_pregnant' => ($r->gender == 'F') ? $r->is_pregnant : 'N',
                    'is_pregnant_weeks' => ($r->gender == 'F' && $r->is_pregnant == 'Y') ? $r->is_pregnant_weeks : NULL,
                    'other_medical_information' => (!is_null($r->other_medical_information)) ? mb_strtoupper($r->other_medical_information) : NULL,
                    'is_ip' => $r->is_ip,
                    'is_ip_specify' => ($r->is_ip == 'Y') ? mb_strtoupper($r->is_ip_specify) : NULL,
                    'nationality' => $r->nationality,
                    'contact_number' => $r->contact_number,
                    'address_region_code' => $r->address_region_code,
                    'address_region_text' => $r->address_region_text,
                    'address_province_code' => $r->address_province_code,
                    'address_province_text' => $r->address_province_text,
                    'address_muncity_code' => $r->address_muncity_code,
                    'address_muncity_text' => $r->address_muncity_text,
                    'address_brgy_code' => $r->address_brgy_text,
                    'address_brgy_text' => $r->address_brgy_text,
                    'address_street' => mb_strtoupper($r->address_street),
                    'address_houseno' => mb_strtoupper($r->address_houseno),
                    'perm_address_region_code' => $perm_address_region_code,
                    'perm_address_region_text' => $perm_address_region_text,
                    'perm_address_province_code' => $perm_address_province_code,
                    'perm_address_province_text' => $perm_address_province_text,
                    'perm_address_muncity_code' => $perm_address_muncity_code,
                    'perm_address_muncity_text' => $perm_address_muncity_text,
                    'perm_address_brgy_code' => $perm_address_brgy_code,
                    'perm_address_brgy_text' => $perm_address_brgy_text,
                    'perm_address_street' => $perm_address_street,
                    'perm_address_houseno' => $perm_address_houseno,
                    'occupation' => (!is_null($r->occupation)) ? mb_strtoupper($r->occupation) : NULL,
                    'workplace_name' => (!is_null($r->workplace_name)) ? mb_strtoupper($r->workplace_name) : NULL,
                    'workplace_address' => (!is_null($r->workplace_address)) ? mb_strtoupper($r->workplace_address) : NULL,
                    'workplace_contactnumber' => (!is_null($r->workplace_contactnumber)) ? mb_strtoupper($r->workplace_contactnumber) : NULL,
                    'informant_name' => (!is_null($r->informant_name)) ? mb_strtoupper($r->informant_name) : NULL,
                    'informant_relationship' => (!is_null($r->informant_relationship)) ? mb_strtoupper($r->informant_relationship) : NULL,
                    'informant_contactnumber' => (!is_null($r->informant_contactnumber)) ? $r->informant_contactnumber : NULL,
                    'date_admitted_seen_consulted' => $r->date_admitted_seen_consulted,
                    'admission_er' => $r->admission_er,
                    'admission_ward' => $r->admission_ward,
                    'admission_icu' => $r->admission_icu,
                    'ifhashistory_blooddonation_transfusion' => $r->ifhashistory_blooddonation_transfusion,
                    'ifhashistory_blooddonation_transfusion_place' => (!is_null($r->ifhashistory_blooddonation_transfusion)) ? $r->ifhashistory_blooddonation_transfusion_place : NULL,
                    'ifhashistory_blooddonation_transfusion_date' => (!is_null($r->ifhashistory_blooddonation_transfusion)) ? $r->ifhashistory_blooddonation_transfusion_date : NULL,
                    'date_onsetofillness' => $r->date_onsetofillness,
                    'have_cutaneous_rash' => $r->have_cutaneous_rash,
                    'have_cutaneous_rash_date' => ($r->have_cutaneous_rash == 'Y') ? $r->have_cutaneous_rash_date : NULL,
                    'have_fever' => $r->have_fever,
                    'have_fever_date' => ($r->have_fever == 'Y') ? $r->have_fever_date : NULL,
                    'have_fever_days_duration' => ($r->have_fever == 'Y') ? $r->have_fever_days_duration : NULL,
                    'have_activedisease_lesion_samestate' => $r->have_activedisease_lesion_samestate,
                    'have_activedisease_lesion_samesize' => $r->have_activedisease_lesion_samesize,
                    'have_activedisease_lesion_deep' => $r->have_activedisease_lesion_deep,
                    'have_activedisease_develop_ulcers' => $r->have_activedisease_develop_ulcers,
                    'have_activedisease_lesion_type' => $r->filled('have_activedisease_lesion_type') ? implode(',', $r->have_activedisease_lesion_type) : NULL,
                    'have_activedisease_lesion_localization' => $r->filled('have_activedisease_lesion_localization') ? implode(',', $r->have_activedisease_lesion_localization) : NULL,
                    'have_activedisease_lesion_localization_otherareas' => (!is_null($r->have_activedisease_lesion_localization_otherareas)) ? mb_strtoupper($r->have_activedisease_lesion_localization_otherareas) : NULL,
                    'symptoms_list' => $r->filled('symptoms_list') ? implode(',', $r->symptoms_list) : NULL,
                    'symptoms_lymphadenopathy_localization' => (in_array('LYMPHADENOPATHY', $r->symptoms_list)) ? implode(',', $r->symptoms_lymphadenopathy_localization) : NULL,
                    'history1_yn' => $r->history1_yn,
                    'history1_specify' => ($r->history1_yn == 'Y') ? mb_strtoupper($r->history1_specify) : NULL,
                    'history1_date_travel'  => ($r->history1_yn == 'Y') ? $r->history1_date_travel : NULL,
                    'history1_flightno' => ($r->history1_yn == 'Y') ? mb_strtoupper($r->history1_flightno) : NULL,
                    'history1_date_arrival' => ($r->history1_yn == 'Y') ? $r->history1_date_arrival : NULL,
                    'history1_pointandexitentry' => ($r->history1_yn == 'Y') ? mb_strtoupper($r->history1_pointandexitentry) : NULL,
                    'history2_yn' => $r->history2_yn,
                    'history2_specify' => ($r->history2_yn == 'Y') ? mb_strtoupper($r->history2_specify) : NULL,
                    'history2_date_travel' => ($r->history2_yn == 'Y') ? $r->history2_date_travel : NULL,
                    'history2_flightno' => ($r->history2_yn == 'Y') ? mb_strtoupper($r->history2_flightno) : NULL,
                    'history2_date_arrival' => ($r->history2_yn == 'Y') ? $r->history2_date_arrival : NULL,
                    'history2_pointandexitentry' => ($r->history2_yn == 'Y') ? mb_strtoupper($r->history2_pointandexitentry) : NULL,
                    'history3_yn' => $r->history3_yn,
                    'history4_yn' => $r->history4_yn,
                    'history4_typeofanimal' => ($r->history4_yn == 'Y') ? mb_strtoupper($r->history4_typeofanimal) : NULL,
                    'history4_firstexposure' => ($r->history4_yn == 'Y') ? $r->history4_firstexposure : NULL,
                    'history4_lastexposure' => ($r->history4_yn == 'Y') ? $r->history4_lastexposure : NULL,
                    'history4_type' => ($r->history4_yn == 'Y') ? implode(',', $r->history4_type) : NULL,
                    'history4_type_others' => ($r->history4_yn == 'Y' && in_array('Others', $r->history4_type)) ? mb_strtoupper($r->history4_type_others) : NULL,
                    'history5_genderidentity' => $r->history5_genderidentity,
                    'history6_yn' => $r->history6_yn,
                    'history6_mtm' => ($r->history6_yn == 'Y') ? $r->history6_mtm : NULL,
                    'history6_mtm_nosp' => ($r->history6_yn == 'Y') ? $r->history6_mtm_nosp : NULL,
                    'history6_mtf' => ($r->history6_yn == 'Y') ? $r->history6_mtf : NULL,
                    'history6_mtf_nosp' => ($r->history6_yn == 'Y') ? $r->history6_mtf_nosp : NULL,
                    'history6_uknown' => ($r->history6_yn == 'Y') ? $r->history6_uknown : NULL,
                    'history6_uknown_nosp' => ($r->history6_yn == 'Y') ? $r->history6_uknown_nosp : NULL,
                    'history7_yn' => $r->history7_yn,
                    'history8_yn' => $r->history8_yn,
                    'history9_choice' => $r->history9_choice,
                    'history9_choice_othercountry' => ($r->history9_choice == 'YES, TO ANOTHER COUNTRY') ? mb_strtoupper($r->history9_choice_othercountry) : NULL,
                    'health_status' => $r->health_status,
                    'health_status_date_discharged' => ($r->health_status == 'DISCHARGED') ? $r->health_status_date_discharged : NULL,
                    'health_status_final_diagnosis' => (!is_null($r->health_status_final_diagnosis)) ? mb_strtoupper($r->health_status_final_diagnosis) : NULL,
                    'outcome' => (!is_null($r->outcome)) ? mb_strtoupper($r->outcome) : NULL,
                    'outcome_unknown_type' => ($r->outcome == 'UNKNOWN') ? $r->outcome_unknown_type : NULL,
                    'outcome_date_recovered' => ($r->outcome == 'RECOVERED') ? $r->outcome_date_recovered : NULL,
                    'outcome_date_died' => ($r->outcome == 'DIED') ? $r->outcome_date_died : NULL,
                    'outcome_causeofdeath' => ($r->outcome == 'DIED') ? $r->outcome_causeofdeath : NULL,
                    'case_classification' => $r->case_classification,
                    'remarks' => (!is_null($r->remarks)) ? mb_strtoupper($r->remarks) : NULL,
                    'brgy_remarks' => (!is_null($r->brgy_remarks)) ? mb_strtoupper($r->brgy_remarks) : NULL,
                    'age_years' => $get_ageyears,
                    'age_months' => $get_agemonths,
                    'age_days' => $get_agedays,
                    'morbidity_month' => $currentDate->format('n'),
                    'morbidity_week' => $currentDate->format('W'),
                    'year' => $currentDate->format('Y'),
                    //'gps_x'
                    //'gps_y',
                    'created_by' => Auth::id(),
                    //'updated_by',
                ]);

                return redirect()->route('pidsr.home')
                ->with('msg', 'Mpox Case Successfully encoded.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Mpox Case already exists!')
                ->with('msgtype', 'warning');
            }
        }
    }

    public function mPoxNewOrEdit(MonkeyPox $record) {
        //Get Facility
        $f = DohFacility::where('id', auth()->user()->itr_facility_id)->first();

        return view('pidsr.mpox.cif', [
            'd' => $record,
            'f' => $f,
        ]);
    }

    public function mPoxViewer() {
        return view('pidsr.mpox.list');
    }
}
