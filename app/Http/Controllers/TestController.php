<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\TestMail;
use App\Models\Measles;
use App\Imports\EdcsImport;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Imports\TkcExcelImport;
use App\Models\AbtcVaccineLogs;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use App\Imports\DohFacilityImport;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
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
*/

class TestController extends Controller
{
    public function index() {
        //Carbon::parse('2024-01-01')->next(Carbon::SATURDAY)->format('m/d/Y')

        $manager = new ImageManager(new Driver());

        
        $image = $manager->read(storage_path('TESTIMG.jpg'));

        // Save the compressed image
        $save = $image->toJpeg(70)->save(storage_path('test/testimage2.jpg'));
    }
}