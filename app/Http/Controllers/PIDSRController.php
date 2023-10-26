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
use App\Models\Rotavirus;
use App\Models\Meningitis;
use App\Imports\PidsrImport;
use App\Imports\RabiesImport;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use App\Models\Leptospirosis;
use Illuminate\Support\Facades\DB;
use RebaseData\Converter\Converter;
use RebaseData\InputFile\InputFile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

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
*/

class PIDSRController extends Controller
{
    public function home() {
        return view('pidsr.home');
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
        File::delete(storage_path('app/pidsr/CHIKV.xlsx'));
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
        File::delete(storage_path('app/pidsr/ROTAVIRUS.xlsx'));
        File::delete(storage_path('app/pidsr/TYPHOID.xlsx'));
        File::delete(storage_path('app/pidsr/aefi.sql'));

        if(request()->input('m')) {
            return redirect()->route('pidsr.home')
            ->with('msg', 'Import Successful.')
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

    public function manualsend() {
        $s = SiteSettings::find(1);
        
        Artisan::call('pidsrwndr:weekly');

        $s->pidsr_early_sent = 1;
        
        if($s->isDirty()) {
            $s->save();
        }
        
        return redirect()->route('pidsr.home')
        ->with('msg', 'Email Sent. Please check your Email.')
        ->with('msgtype', 'success');
    }

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

            $query = $query->where('Muncity', 'GENERAL TRIAS')
            ->where('Province', 'CAVITE')
            ->where('enabled', 1)
            ->get();

            return view('pidsr.casechecker', [
                'list' => $query,
                'columns' => $columns,
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

            if($action == "DEL") {
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

                $data->enabled = 0;

                if($data->isDirty()) {
                    $data->save();
                }

                return redirect()->back()
                ->with('msg', $d.' Case EPI_ID ['.$data->EPIID.'] Patient ['.$data->FullName.'] has been disabled successfully.')
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
}
