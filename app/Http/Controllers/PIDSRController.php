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

        if(File::exists(storage_path('app/pidsr/Anthrax.xlsx'))) {
            Excel::import(new PidsrImport('ANTHRAX'), storage_path('app/pidsr/Anthrax.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/ChikV.xlsx'))) {
            Excel::import(new PidsrImport('CHIKV'), storage_path('app/pidsr/ChikV.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Cholera.xlsx'))) {
            Excel::import(new PidsrImport('CHOLERA'), storage_path('app/pidsr/Cholera.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/DENGUE.xlsx'))) {
            Excel::import(new PidsrImport('DENGUE'), storage_path('app/pidsr/DENGUE.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Diph.xlsx'))) {
            Excel::import(new PidsrImport('DIPH'), storage_path('app/pidsr/Diph.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/HEPATITIS.xlsx'))) {
            Excel::import(new PidsrImport('HEPATITIS'), storage_path('app/pidsr/HEPATITIS.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/HFMD.xlsx'))) {
            Excel::import(new PidsrImport('HFMD'), storage_path('app/pidsr/HFMD.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Influenza.xlsx'))) {
            Excel::import(new PidsrImport('INFLUENZA'), storage_path('app/pidsr/Influenza.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Leptospirosis.xlsx'))) {
            Excel::import(new PidsrImport('LEPTOSPIROSIS'), storage_path('app/pidsr/Leptospirosis.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Malaria.xlsx'))) {
            Excel::import(new PidsrImport('MALARIA'), storage_path('app/pidsr/Malaria.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Measles.xlsx'))) {
            Excel::import(new PidsrImport('MEASLES'), storage_path('app/pidsr/Measles.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Meningitis.xlsx'))) {
            Excel::import(new PidsrImport('MENINGITIS'), storage_path('app/pidsr/Meningitis.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Meningo.xlsx'))) {
            Excel::import(new PidsrImport('MENINGO'), storage_path('app/pidsr/Meningo.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/NNT.xlsx'))) {
            Excel::import(new PidsrImport('NNT'), storage_path('app/pidsr/NNT.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/NT.xlsx'))) {
            Excel::import(new PidsrImport('NT'), storage_path('app/pidsr/NT.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Pert.xlsx'))) {
            Excel::import(new PidsrImport('PERT'), storage_path('app/pidsr/Pert.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/PSP.xlsx'))) {
            Excel::import(new PidsrImport('PSP'), storage_path('app/pidsr/PSP.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Rabies.xlsx'))) {
            Excel::import(new PidsrImport('RABIES'), storage_path('app/pidsr/Rabies.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/RotaVirus.xlsx'))) {
            Excel::import(new PidsrImport('ROTAVIRUS'), storage_path('app/pidsr/RotaVirus.xlsx'));
        }

        if(File::exists(storage_path('app/pidsr/Typhoid.xlsx'))) {
            Excel::import(new PidsrImport('TYPHOID'), storage_path('app/pidsr/Typhoid.xlsx'));
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
        File::delete(storage_path('app/pidsr/Anthrax.xlsx'));
        File::delete(storage_path('app/pidsr/ChikV.xlsx'));
        File::delete(storage_path('app/pidsr/Cholera.xlsx'));
        File::delete(storage_path('app/pidsr/DENGUE.xlsx'));
        File::delete(storage_path('app/pidsr/Diph.xlsx'));
        File::delete(storage_path('app/pidsr/HEPATITIS.xlsx'));
        File::delete(storage_path('app/pidsr/HFMD.xlsx'));
        File::delete(storage_path('app/pidsr/Influenza.xlsx'));
        File::delete(storage_path('app/pidsr/Leptospirosis.xlsx'));
        File::delete(storage_path('app/pidsr/Malaria.xlsx'));
        File::delete(storage_path('app/pidsr/Measles.xlsx'));
        File::delete(storage_path('app/pidsr/Meningitis.xlsx'));
        File::delete(storage_path('app/pidsr/Meningo.xlsx'));
        File::delete(storage_path('app/pidsr/NNT.xlsx'));
        File::delete(storage_path('app/pidsr/NT.xlsx'));
        File::delete(storage_path('app/pidsr/Pert.xlsx'));
        File::delete(storage_path('app/pidsr/PSP.xlsx'));
        File::delete(storage_path('app/pidsr/Rabies.xlsx'));
        File::delete(storage_path('app/pidsr/RotaVirus.xlsx'));
        File::delete(storage_path('app/pidsr/Typhoid.xlsx'));
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
                $columns = Schema::getColumnListing('abd');
            }
            else if($case == 'AEFI') {
                $query = Aefi::where('year', $year);
                $columns = Schema::getColumnListing('pidsr_aefi');
            }
            else if($case == 'AES') {
                $query = Aes::where('year', $year);
                $columns = Schema::getColumnListing('aes');
            }
            else if($case == 'AFP') {
                $query = Afp::where('year', $year);
                $columns = Schema::getColumnListing('afp');
            }
            else if($case == 'AHF') {
                $query = Ahf::where('year', $year);
                $columns = Schema::getColumnListing('ahf');
            }
            else if($case == 'AMES') {
                $query = Ames::where('year', $year);
                $columns = Schema::getColumnListing('ames');
            }
            else if($case == 'ANTHRAX') {
                $query = Anthrax::where('year', $year);
                $columns = Schema::getColumnListing('anthrax');
            }
            else if($case == 'CHIKV') {
                $query = Chikv::where('year', $year);
                $columns = Schema::getColumnListing('chikv');
            }
            else if($case == 'CHOLERA') {
                $query = Cholera::where('year', $year);
                $columns = Schema::getColumnListing('cholera');
            }
            else if($case == 'DENGUE') {
                $query = Dengue::where('year', $year);
                $columns = Schema::getColumnListing('dengue');
            }
            else if($case == 'DIPH') {
                $query = Diph::where('year', $year);
                $columns = Schema::getColumnListing('diph');
            }
            else if($case == 'HEPATITIS') {
                $query = Hepatitis::where('year', $year);
                $columns = Schema::getColumnListing('hepatitis');
            }
            else if($case == 'HFMD') {
                $query = Hfmd::where('year', $year);
                $columns = Schema::getColumnListing('hfmd');
            }
            else if($case == 'INFLUENZA') {
                $query = Influenza::where('year', $year);
                $columns = Schema::getColumnListing('influenza');
            }
            else if($case == 'LEPTOSPIROSIS') {
                $query = Leptospirosis::where('year', $year);
                $columns = Schema::getColumnListing('leptospirosis');
            }
            else if($case == 'MALARIA') {
                $query = Malaria::where('year', $year);
                $columns = Schema::getColumnListing('malaria');
            }
            else if($case == 'MEASLES') {
                $query = Measles::where('year', $year);
                $columns = Schema::getColumnListing('measles');
            }
            else if($case == 'MENINGITIS') {
                $query = Meningitis::where('year', $year);
                $columns = Schema::getColumnListing('meningitis');
            }
            else if($case == 'MENINGO') {
                $query = Meningo::where('year', $year);
                $columns = Schema::getColumnListing('meningo');
            }
            else if($case == 'NNT') {
                $query = Nnt::where('year', $year);
                $columns = Schema::getColumnListing('nnt');
            }
            else if($case == 'NT') {
                $query = Nt::where('year', $year);
                $columns = Schema::getColumnListing('nt');
            }
            else if($case == 'PERT') {
                $query = Pert::where('year', $year);
                $columns = Schema::getColumnListing('pert');
            }
            else if($case == 'PSP') {
                $query = Psp::where('year', $year);
                $columns = Schema::getColumnListing('psp');
            }
            else if($case == 'RABIES') {
                $query = Rabies::where('year', $year);
                $columns = Schema::getColumnListing('rabies');
            }
            else if($case == 'ROTAVIRUS') {
                $query = Rotavirus::where('year', $year);
                $columns = Schema::getColumnListing('rotavirus');
            }
            else if($case == 'TYPHOID') {
                $query = Typhoid::where('year', $year);
                $columns = Schema::getColumnListing('typhoid');
            }

            $query = $query->where('Muncity', 'GENERAL TRIAS')
            ->where('Province', 'CAVITE')
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

    public function weeklycaseviewer($year, $mw) {
        if(session('bypass')) {
            $proceed = true;
        }
        else {
            $proceed = false;

            return view();
        }
    }
}
