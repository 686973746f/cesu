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

/*
Adding ForeignID

ALTER TABLE syndromic_records ADD facility_id bigint UNSIGNED DEFAULT NULL;
ALTER TABLE syndromic_records ADD KEY `syndromic_records_facility_id_foreign` (`facility_id`);
ALTER TABLE syndromic_records ADD CONSTRAINT `syndromic_records_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `doh_facilities` (`id`) ON DELETE CASCADE;

10886 - CHO GENTRIAS FACILITY ID
10525 - MEDICARE 
*/

class TestController extends Controller
{
    public function index() {
        //Carbon::parse('2024-01-01')->next(Carbon::SATURDAY)->format('m/d/Y')
        dd(Carbon::parse('2024-01-09')->startOfWeek()->format('m/d/Y'));
    }
}