<?php

namespace App\Console\Commands;

use App\Models\Nt;
use App\Models\Abd;
use App\Models\Nnt;
use App\Models\Psp;
use App\Models\Brgy;
use App\Models\Diph;
use App\Models\Forms;
use App\Models\Dengue;
use App\Models\Cholera;
use App\Models\Malaria;
use App\Models\Measles;
use App\Models\Meningo;
use App\Models\Typhoid;
use App\Mail\SendFhsisM2;
use App\Models\Hepatitis;
use App\Models\Influenza;
use App\Models\Meningitis;
use App\Models\Leptospirosis;
use Illuminate\Console\Command;
use App\Models\AbtcBakunaRecords;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;

class FhsisAutoM2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fhsism2autosender:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'FHSIS Auto Sender Email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $slist = [
            'ANIMALBITE',
            'DENGUE',
            'COVID',
            'ACUTE_BLOODY_DIARRHEA',
            'ACUTE_FLACCID_PARALYSIS',
            'CHOLERA',
            'DIPHTHERIA',
            'INFLUENZALIKE',
            'LEPTOSPIROSIS',
            'MALARIA',
            'MEASLES',
            'MENINGO',
            'NEONATAL_TETANUS',
            'NONNEONATAL_TETANUS',
            'PARALYTIC_SHELLFISH_POISONING',
            'TYPHOID_PARATYPHOID',
            'VIRAL_HEPATITIS',
            'VIRAL_MENINGITIS',
        ];

        $spreadsheet = IOFactory::load(storage_path('FHSIS_REPORT.xlsx'));

        $brgyList = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $agebrackets = [
            '1,4',
            '5,9',
            '10,14',
            '15,19',
            '20,24',
            '25,29',
            '30,34',
            '35,39',
            '40,44',
            '45,49',
            '50,54',
            '55,59',
            '60,64',
            '65,69',
        ];

        foreach($slist as $s) {
            $sheet = $spreadsheet->getSheetByName($s);
            $sheet->setCellValue('C1', date('F'));
            $sheet->setCellValue('F1', date('Y'));

            foreach($brgyList as $i => $b) {

                $select_index = $i + 6;

                if($s == 'ANIMALBITE') {
                    $item1 = 0;
                    $item2 = 0;

                    $item3 = 0;
                    $item4 = 0;

                    $item5 = 0;
                    $item6 = 0;

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = AbtcBakunaRecords::whereHas('patient', function($q) use ($b, $ae) {
                            $q->where('register_status', 'VERIFIED')
                            ->where('address_muncity_text', $b->city->cityName)
                            ->where('address_brgy_text', $b->brgyName)
                            ->whereBetween('age', $ae)
                            ->where('gender', 'MALE');
                        })
                        ->whereYear('case_date', date('Y'))
                        ->whereMonth('case_date', date('m'))
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = AbtcBakunaRecords::whereHas('patient', function($q) use ($b, $ae) {
                            $q->where('register_status', 'VERIFIED')
                            ->where('address_muncity_text', $b->city->cityName)
                            ->where('address_brgy_text', $b->brgyName)
                            ->whereBetween('age', $ae)
                            ->where('gender', 'FEMALE');
                        })
                        ->whereYear('case_date', date('Y'))
                        ->whereMonth('case_date', date('m'))
                        ->count();
                    }

                    $item35 = AbtcBakunaRecords::whereHas('patient', function($q) use ($b) {
                        $q->where('register_status', 'VERIFIED')
                        ->where('address_muncity_text', $b->city->cityName)
                        ->where('address_brgy_text', $b->brgyName)
                        ->where('age', '>=', 70)
                        ->where('gender', 'FEMALE');
                    })
                    ->whereYear('case_date', date('Y'))
                    ->whereMonth('case_date', date('m'))
                    ->count();

                    $item36 = AbtcBakunaRecords::whereHas('patient', function($q) use ($b) {
                        $q->where('register_status', 'VERIFIED')
                        ->where('address_muncity_text', $b->city->cityName)
                        ->where('address_brgy_text', $b->brgyName)
                        ->where('age', '>=', 70)
                        ->where('gender', 'MALE');
                    })
                    ->whereYear('case_date', date('Y'))
                    ->whereMonth('case_date', date('m'))
                    ->count();
                }
                else if($s == 'DENGUE') {
                    $item1 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Dengue::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Dengue::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'COVID') {
                    $item1 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'MALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->whereBetWeen('age_days', [0,6])
                    ->count();

                    $item2 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'FEMALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->whereBetWeen('age_days', [0,6])
                    ->count();

                    $item3 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'MALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->whereBetWeen('age_days', [7,28])
                    ->count();

                    $item4 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'FEMALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->whereBetWeen('age_days', [7,28])
                    ->count();
                    
                    $item5 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'MALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->whereBetWeen('age_days', [7,28])
                    ->count();

                    $item6 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'FEMALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->where('age_years', 0)
                    ->where('age_months', '<=', 11)
                    ->where('age_days', '>=', 29)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Forms::whereHas('records', function ($q) use ($b) {
                            $q->where('records.address_city', $b->city->cityName)
                            ->where('records.address_brgy', $b->brgyName)
                            ->where('records.gender', 'MALE');
                        })
                        ->where('caseClassification', 'Confirmed')
                        ->whereYear('morbidityMonth', date('Y'))
                        ->whereMonth('morbidityMonth', date('n'))
                        ->whereBetween('age_years', $ae)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Forms::whereHas('records', function ($q) use ($b) {
                            $q->where('records.address_city', $b->city->cityName)
                            ->where('records.address_brgy', $b->brgyName)
                            ->where('records.gender', 'FEMALE');
                        })
                        ->where('caseClassification', 'Confirmed')
                        ->whereYear('morbidityMonth', date('Y'))
                        ->whereMonth('morbidityMonth', date('n'))
                        ->whereBetween('age_years', $ae)
                        ->count();
                    }

                    $item35 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'MALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->where('age_years', '>=', 70)
                    ->count();

                    $item36 = Forms::whereHas('records', function ($q) use ($b) {
                        $q->where('records.address_city', $b->city->cityName)
                        ->where('records.address_brgy', $b->brgyName)
                        ->where('records.gender', 'FEMALE');
                    })
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', date('Y'))
                    ->whereMonth('morbidityMonth', date('n'))
                    ->where('age_years', '>=', 70)
                    ->count();
                }
                else if($s == 'ACUTE_BLOODY_DIARRHEA') {
                    $item1 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Abd::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Abd::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Abd::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'CHOLERA') {
                    $item1 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Cholera::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Cholera::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Cholera::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'DIPTHERIA') {
                    $item1 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Diph::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Diph::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Diph::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'INFLUENZALIKE') {
                    $item1 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Influenza::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Influenza::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Influenza::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'LEPTOSPIROSIS') {
                    $item1 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Leptospirosis::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Leptospirosis::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Leptospirosis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'MALARIA') {
                    $item1 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Malaria::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Malaria::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Malaria::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'MEASLES') {
                    $item1 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Measles::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Measles::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Measles::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'MENINGO') {
                    $item1 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Meningo::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Meningo::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Meningo::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'NEONATAL_TETANUS') {
                    $item1 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Nt::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Nt::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Nt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'NONNEONATAL_TETANUS') {
                    $item1 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Nnt::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Nnt::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Nnt::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'PARALYRIC_SHELLFISH_POISONING') {
                    $item1 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Psp::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Psp::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Psp::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'TYPHOID_PARATHYPOID') {
                    $item1 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Typhoid::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Typhoid::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Typhoid::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'VIRAL_HEPATITIS') {
                    $item1 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Hepatitis::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Hepatitis::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Hepatitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }
                else if($s == 'VIRAL_MENINGITIS') {
                    $item1 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item2 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item3 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item4 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item5 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item6 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    foreach($agebrackets as $ind => $ab) {
                        if($ind == 0) {
                            $vind = $ind + 7;
                        }
                        else {
                            $vind = $ind + $ind + 7;
                        }
                        
                        $ae = explode(',', $ab);

                        ${'item'.$vind} = Meningitis::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'M')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();

                        if($ind == 0) {
                            $vind = $ind + 8;
                        }
                        else {
                            $vind = $ind + $ind + 8;
                        }

                        ${'item'.$vind} = Meningitis::where('Muncity', $b->city->cityName)
                        ->where('Barangay', $b->brgyName)
                        ->whereBetween('AgeYears', $ae)
                        ->where('Year', date('Y'))
                        ->where('MorbidityMonth', date('n'))
                        ->where('Sex', 'F')
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }

                    $item35 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $item36 = Meningitis::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();
                }

                $gtotal = $item1 +
                $item2 +
                $item3 +
                $item4 +
                $item5 +
                $item6 +
                $item7 +
                $item8 +
                $item9 +
                $item10 +
                $item11 +
                $item12 +
                $item13 +
                $item14 +
                $item15 +
                $item16 +
                $item17 +
                $item18 +
                $item19 +
                $item20 +
                $item21 +
                $item22 +
                $item23 +
                $item24 +
                $item25 +
                $item26 +
                $item27 +
                $item28 +
                $item29 +
                $item30 +
                $item31 +
                $item32 +
                $item33 +
                $item34 +
                $item35 +
                $item36;

                if($gtotal == 0) {
                    $sheet->setCellValue('A'.$select_index, '');
                }

                $sheet->setCellValue('B'.$select_index, ($item1 != 0) ? $item1 : '');
                $sheet->setCellValue('C'.$select_index, ($item2 != 0) ? $item2 : '');

                $sheet->setCellValue('D'.$select_index, ($item3 != 0) ? $item3 : '');
                $sheet->setCellValue('E'.$select_index, ($item4 != 0) ? $item4 : '');

                $sheet->setCellValue('F'.$select_index, ($item5 != 0) ? $item5 : '');
                $sheet->setCellValue('G'.$select_index, ($item6 != 0) ? $item6 : '');

                $sheet->setCellValue('H'.$select_index, ($item7 != 0) ? $item7 : '');
                $sheet->setCellValue('I'.$select_index, ($item8 != 0) ? $item8 : '');

                $sheet->setCellValue('J'.$select_index, ($item9 != 0) ? $item9 : '');
                $sheet->setCellValue('K'.$select_index, ($item10 != 0) ? $item10 : '');

                $sheet->setCellValue('L'.$select_index, ($item11 != 0) ? $item11 : '');
                $sheet->setCellValue('M'.$select_index, ($item12 != 0) ? $item12 : '');

                $sheet->setCellValue('N'.$select_index, ($item13 != 0) ? $item13 : '');
                $sheet->setCellValue('O'.$select_index, ($item14 != 0) ? $item14 : '');

                $sheet->setCellValue('P'.$select_index, ($item15 != 0) ? $item15 : '');
                $sheet->setCellValue('Q'.$select_index, ($item16 != 0) ? $item16 : '');

                $sheet->setCellValue('R'.$select_index, ($item17 != 0) ? $item17 : '');
                $sheet->setCellValue('S'.$select_index, ($item18 != 0) ? $item18 : '');

                $sheet->setCellValue('T'.$select_index, ($item19 != 0) ? $item19 : '');
                $sheet->setCellValue('U'.$select_index, ($item20 != 0) ? $item20 : '');

                $sheet->setCellValue('V'.$select_index, ($item21 != 0) ? $item21 : '');
                $sheet->setCellValue('W'.$select_index, ($item22 != 0) ? $item22 : '');

                $sheet->setCellValue('X'.$select_index, ($item23 != 0) ? $item23 : '');
                $sheet->setCellValue('Y'.$select_index, ($item24 != 0) ? $item24 : '');

                $sheet->setCellValue('Z'.$select_index, ($item25 != 0) ? $item25 : '');
                $sheet->setCellValue('AA'.$select_index, ($item26 != 0) ? $item26 : '');

                $sheet->setCellValue('AB'.$select_index, ($item27 != 0) ? $item27 : '');
                $sheet->setCellValue('AC'.$select_index, ($item28 != 0) ? $item28 : '');

                $sheet->setCellValue('AD'.$select_index, ($item29 != 0) ? $item29 : '');
                $sheet->setCellValue('AE'.$select_index, ($item30 != 0) ? $item30 : '');

                $sheet->setCellValue('AF'.$select_index, ($item31 != 0) ? $item31 : '');
                $sheet->setCellValue('AG'.$select_index, ($item32 != 0) ? $item32 : '');

                $sheet->setCellValue('AH'.$select_index, ($item33 != 0) ? $item33 : '');
                $sheet->setCellValue('AI'.$select_index, ($item34 != 0) ? $item34 : '');

                $sheet->setCellValue('AJ'.$select_index, ($item35 != 0) ? $item35 : '');
                $sheet->setCellValue('AK'.$select_index, ($item36 != 0) ? $item36 : '');
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('FHSIS_M2_REPORT_'.date('F_Y').'.xlsx'));
        
        Mail::to(['cjh687332@gmail.com', 'cesu.gentrias@gmail.com', 'chogentri@gmail.com', 'fhsisgentri@gmail.com'])->send(new SendFhsisM2());

        File::delete(storage_path('FHSIS_M2_REPORT_'.date('F_Y', strtotime('-1 Month')).'.xlsx'));
        //Mail::to(['cjh687332@gmail.com', 'cesu.gentrias@gmail.com'])->send(new SendFhsisM2());
    }
}
