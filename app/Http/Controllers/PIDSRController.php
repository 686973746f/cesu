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

    public function xlstosql(Request $request) {
        $directory = $request->file('directory');

        foreach ($directory as $file) {
            if($file->getClientOriginalExtension() === 'mdb' && $file->getClientOriginalName() != 'AEFI.mdb') {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('pidsr/', $fileName);   
            }
        }
    }

    public function readdb() {
        ini_set('max_file_uploads', '40');

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
