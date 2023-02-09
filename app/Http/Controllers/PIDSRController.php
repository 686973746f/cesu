<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Hfmd;
use App\Models\Dengue;
use App\Models\Measles;
use App\Imports\PidsrImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RebaseData\Converter\Converter;
use RebaseData\InputFile\InputFile;
use Illuminate\Support\Facades\File;

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

        if($s == 'DENGUE') {
            for($i=1;$i <= $compa;$i++) {
                ${'mw'.$i} = Dengue::where('Province', 'CAVITE')
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
        else if($s == 'MONKEYPOX') {

        }

        return view('pidsr.threshold', [
            's' => $s,
            'arr' => $arr,
            'compa' => $compa,
        ]);
    }

    public function import_start(Request $request) {
        //Test Import
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
    }

    public function readdb() {
        

        $dbName = "C:\path\to\database.mdb";

        $conn = new COM("ADODB.Connection") or die("Cannot start ADO");
        $conn->Open("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName;");

        $rs = $conn->Execute("SELECT * FROM table_name");

        while (!$rs->EOF) {
            echo $rs->Fields("column_name")->Value . "<br>";
            $rs->MoveNext();
        }

        $rs->Close();
        $conn->Close();
    }

        /*
        $inputFiles = [new InputFile("C:\PIDSR\Current\ABD.mdb")];

        $converter = new Converter();
        $database = $converter->convertToDatabase($inputFiles);
        $tables = $database->getTables();

        foreach ($tables as $table) {
            echo "Reading table '".$table->getName()."'\n";

            $rows = $table->getRowsIterator();
            foreach ($rows as $row) {
                echo implode(', ', $row)."\n";
            }
        }
        */
        /*
        $sd = $request->sd;


        if($sd == 'DENGUE') {
            $pass = 'Dengue';
        }

        Excel::import(new PidsrImport($pass), $request->ff);

        return 'Success';
        */
}
