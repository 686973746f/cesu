<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\TestMail;
use App\Models\Measles;
use App\Imports\EdcsImport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\TkcExcelImport;
use App\Models\AbtcVaccineLogs;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use App\Imports\DohFacilityImport;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use App\Models\DohFacility;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function index() {
        $getlist = DohFacility::where('address_muncity', 'CITY OF GENERAL TRIAS')->get();

        foreach($getlist as $g) {
            $g->sys_code1 = mb_strtoupper(Str::random(10));

            if($g->isDirty()) {
                $g->save();
            }
        }
    }
}