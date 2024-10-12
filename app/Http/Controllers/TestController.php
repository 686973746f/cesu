<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\TestMail;
use App\Models\Measles;
use App\Imports\EdcsImport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Imports\TkcExcelImport;
use App\Models\AbtcVaccineLogs;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use App\Imports\DohFacilityImport;
use App\Imports\EdcsGeoExportBrgy;
use App\Imports\EdcsGeoExportCity;
use App\Imports\EdcsGeoExportProvince;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Drivers\Imagick\Driver;

/*
Adding ForeignID

ALTER TABLE syndromic_records ADD facility_id bigint UNSIGNED DEFAULT NULL;
ALTER TABLE syndromic_records ADD KEY `syndromic_records_facility_id_foreign` (`facility_id`);
ALTER TABLE syndromic_records ADD CONSTRAINT `syndromic_records_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `doh_facilities` (`id`) ON DELETE CASCADE;

10886 - CHO GENTRIAS FACILITY ID
10525 - MEDICARE 
11730 - MANGGAHAN
*/

class TestController extends Controller
{
    public function index() {
        //Carbon::parse('2024-01-01')->next(Carbon::SATURDAY)->format('m/d/Y')

        Excel::import(new EdcsGeoExportProvince, storage_path('edcs_provinces.xlsx'));

        Excel::import(new EdcsGeoExportCity, storage_path('edcs_cities.xlsx'));

        Excel::import(new EdcsGeoExportBrgy, storage_path('edcs_brgys.xlsx'));

        dd('done');
    }
}