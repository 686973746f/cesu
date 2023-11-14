<?php

namespace App\Http\Controllers;

use App\Imports\DohFacilityImport;
use Carbon\Carbon;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use App\Models\AbtcVaccineLogs;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function index() {
        //CODE HERE
    }
}