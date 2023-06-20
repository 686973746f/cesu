<?php

namespace App\Console\Commands;

use App\Mail\SendFhsisM2;
use App\Models\Brgy;
use Illuminate\Console\Command;
use App\Models\AbtcBakunaRecords;
use App\Models\Dengue;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            'DIPTHERIA',
            'LEPTOSPIROSIS',
            'MALARIA',
            'MEASLES',
            'MENINGO',
            'NEONATAL_TETANUS',
            'NONNEONATAL_TETANUS',
            'PARALYRIC_SHELLFISH_POISONING',
            'TYPHOID_PARATHYPOID',
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
                    ->where('AgeYears', 0)
                    ->where('AgeMons', 0)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->count();

                    $item2 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', 0)
                    ->whereBetween('AgeDays', [0,6])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->count();

                    $item3 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', 0)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->count();

                    $item4 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', 0)
                    ->whereBetween('AgeDays', [7,28])
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->count();

                    $item5 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->count();

                    $item6 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', 0)
                    ->where('AgeMons', '<=', 11)
                    ->where('AgeDays', '>=', 29)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
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
                        ->count();
                    }

                    $item35 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'M')
                    ->count();

                    $item36 = Dengue::where('Muncity', $b->city->cityName)
                    ->where('Barangay', $b->brgyName)
                    ->where('AgeYears', '>=', 70)
                    ->where('Year', date('Y'))
                    ->where('MorbidityMonth', date('n'))
                    ->where('Sex', 'F')
                    ->count();
                }
                else if($s == 'COVID') {

                }
                else if($s == 'ACUTE_BLOODY_DIARRHEA') {

                }
                else if($s == 'CHOLERA') {

                }
                else if($s == 'DIPTHERIA') {

                }
                else if($s == 'LEPTOSPIROSIS') {

                }
                else if($s == 'MALARIA') {

                }
                else if($s == 'MEASLES') {

                }
                else if($s == 'MENINGO') {

                }
                else if($s == 'NEONATAL_TETANUS') {

                }
                else if($s == 'NONNEONATAL_TETANUS') {

                }
                else if($s == 'PARALYRIC_SHELLFISH_POISONING') {

                }
                else if($s == 'TYPHOID_PARATHYPOID') {

                }
                else if($s == 'VIRAL_HEPATITIS') {

                }
                else if($s == 'VIRAL_MENINGITIS') {

                }

                $sheet->setCellValue('B'.$select_index, $item1);
                $sheet->setCellValue('C'.$select_index, $item2);

                $sheet->setCellValue('D'.$select_index, $item3);
                $sheet->setCellValue('E'.$select_index, $item4);

                $sheet->setCellValue('F'.$select_index, $item5);
                $sheet->setCellValue('G'.$select_index, $item6);

                $sheet->setCellValue('H'.$select_index, $item7);
                $sheet->setCellValue('I'.$select_index, $item8);

                $sheet->setCellValue('J'.$select_index, $item9);
                $sheet->setCellValue('K'.$select_index, $item10);

                $sheet->setCellValue('L'.$select_index, $item11);
                $sheet->setCellValue('M'.$select_index, $item12);

                $sheet->setCellValue('N'.$select_index, $item13);
                $sheet->setCellValue('O'.$select_index, $item14);

                $sheet->setCellValue('P'.$select_index, $item15);
                $sheet->setCellValue('Q'.$select_index, $item16);

                $sheet->setCellValue('R'.$select_index, $item17);
                $sheet->setCellValue('S'.$select_index, $item18);

                $sheet->setCellValue('T'.$select_index, $item19);
                $sheet->setCellValue('U'.$select_index, $item20);

                $sheet->setCellValue('V'.$select_index, $item21);
                $sheet->setCellValue('W'.$select_index, $item22);

                $sheet->setCellValue('X'.$select_index, $item23);
                $sheet->setCellValue('Y'.$select_index, $item24);

                $sheet->setCellValue('Z'.$select_index, $item25);
                $sheet->setCellValue('AA'.$select_index, $item26);

                $sheet->setCellValue('AB'.$select_index, $item27);
                $sheet->setCellValue('AC'.$select_index, $item28);

                $sheet->setCellValue('AD'.$select_index, $item29);
                $sheet->setCellValue('AE'.$select_index, $item30);

                $sheet->setCellValue('AF'.$select_index, $item31);
                $sheet->setCellValue('AG'.$select_index, $item32);

                $sheet->setCellValue('AH'.$select_index, $item33);
                $sheet->setCellValue('AI'.$select_index, $item34);

                $sheet->setCellValue('AJ'.$select_index, $item35);
                $sheet->setCellValue('AK'.$select_index, $item36);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('FHSIS_REPORT_'.date('F_Y').'.xlsx'));

        //Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new SendFhsisM2());
    }
}
