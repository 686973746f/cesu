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
use App\Imports\EdcsHospitalImport;
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
39708 - SF SUPERHEALTH
*/

class TestController extends Controller
{
    public function index() {
        $lab_group = [];

        $lab_group[] = [
            'test_type' => 'RTPCR',
            'date_collected' => '2024-10-12',
            'lab_name' => 'LASALLE',
            'lab_result' => 'NEGATIVE',
        ];

        $lab_group[] = [
            'test_type' => 'ANTIGEN',
            'date_collected' => '2024-10-13',
            'lab_name' => 'LASALLE',
            'lab_result' => 'NEGATIVE',
        ];

        $lab_group[] = [
            'test_type' => 'RTPCR',
            'date_collected' => '2024-10-14',
            'lab_name' => 'LASALLE',
            'lab_result' => 'POSITIVE',
        ];

        $lab_group[] = [
            'test_type' => 'RTPCR',
            'date_collected' => '2024-10-15',
            'lab_name' => 'LASALLE',
            'lab_result' => 'NEGATIVE',
        ];
    }
}