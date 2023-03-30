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
use App\Models\Meningitis;
use App\Imports\PidsrImport;
use Illuminate\Http\Request;
use App\Models\Leptospirosis;
use App\Models\Rotavirus;
use RebaseData\Converter\Converter;
use RebaseData\InputFile\InputFile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

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
                $year = request()->input('year');
    
                if($rtype == 'YEARLY') {
                    //Category 1
                    $afp = Afp::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $anthrax = Anthrax::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $hfmd = Hfmd::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $measles = Measles::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $meningo = Meningo::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $nt = Nt::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $psp = Psp::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $rabies = Rabies::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    //Category 2
    
                    $abd = Abd::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $aes = Aes::where('Barangay', $b->brgyName)->where('year', $year)->count();
                    
                    $ahf = Ahf::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $hepatitis = Hepatitis::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $ames = Ames::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $meningitis = Meningitis::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $chikv = Chikv::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $cholera = Cholera::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $dengue = Dengue::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $diph = Diph::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $influenza = Influenza::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $leptospirosis = Leptospirosis::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $malaria = Malaria::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $nnt = Nnt::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $pert = Pert::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $rotavirus = Rotavirus::where('Barangay', $b->brgyName)->where('year', $year)->count();
    
                    $typhoid = Typhoid::where('Barangay', $b->brgyName)->where('year', $year)->count();
                }
                else if($rtype == 'QUARTERLY') {
    
                }
                else if($rtype == 'MONTHLY') {
    
                }
                else if($rtype == 'WEEKLY') {
    
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

    public function report_two() {
        
    }

    public function fhsis_report() {

    }
}
