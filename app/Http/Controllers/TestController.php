<?php

namespace App\Http\Controllers;

use App\Imports\DohFacilityImport;
use App\Imports\EdcsImport;
use App\Imports\TkcExcelImport;
use Carbon\Carbon;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use App\Models\AbtcVaccineLogs;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use App\Models\Measles;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function index() {
        $bdate = Carbon::parse('1999-08-19');
        $dateOfEntry = Carbon::parse('2021-01-14');

        echo $bdate->diffInYears($dateOfEntry).' years';
        echo $bdate->diffInMonths($dateOfEntry).' months';
        echo $bdate->diffInDays($dateOfEntry).' days';
    }
}