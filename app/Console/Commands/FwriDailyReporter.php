<?php

namespace App\Console\Commands;

use App\Mail\FwriDailyMailer;
use App\Models\BarangayHealthStation;
use App\Models\DohFacility;
use Carbon\Carbon;
use App\Models\FwInjury;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;

class FwriDailyReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fwrireporter:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $currentDate = Carbon::now();
        
        //check if reporting period
        if ($currentDate->month === Carbon::DECEMBER) {
            $date1 = Carbon::parse(date('Y-12-21'));
            $date2 = Carbon::parse((date('Y') +1).'-01-05');
        }
        else {
            $date1 = Carbon::parse((date('Y')-1).'-12-21');
            $date2 = Carbon::parse(date('Y-01-05'));
        }

        if ($currentDate->greaterThanOrEqualTo($date1) && $currentDate->lessThanOrEqualTo($date2)) {
            //Check if there was reportable cases yesterday
            $list = FwInjury::whereDate('injury_date', date('Y-m-d', strtotime('-1 Day')))->get();

            $startCell1 = 8;
            $startCell2 = 2;

            if($list->count() != 0) {
                //get fwri excel and create a copy
                $spreadsheet1 = IOFactory::load(storage_path('FWRI1.xlsx'));
                $sheet1 = $spreadsheet1->getActiveSheet();

                $spreadsheet2 = IOFactory::load(storage_path('FWRI2.xlsx'));
                $sheet2 = $spreadsheet2->getActiveSheet();

                $sheet1->setCellValue('B4', 'DATE: '.date('m/d/Y').' - Hospital: GENERAL TRIAS');

                foreach($list as $d) {
                    //SHEET 1
                    //$sheet1->setCellValue('A'.$startCell1, '');

                    //GET TYPE OF INJURY
                    if($d->nature_injury == 'FIREWORKS INJURY') {
                        $getInjuryType = $d->iffw_typeofinjury;
                    }
                    else {
                        $getInjuryType = $d->nature_injury;
                    }

                    //GET DIAGNOSIS
                    if(!is_null($d->complete_diagnosis)) {
                        $getDiag = $d->complete_diagnosis.' - '.$d->anatomical_location;
                    }
                    else {
                        $getDiag = $d->anatomical_location;
                    }

                    //GET DISPOSITION
                    if($d->disposition_after_admission == 'DIED DURING ADMISSION') {
                        $getDispo = 'DIED '.date('(m/d/Y)', strtotime($d->date_died));
                    }
                    else {
                        $getDispo = $d->disposition_after_admission;
                    }
                    
                    $sheet1->setCellValue('B'.$startCell1, $d->getName());
                    $sheet1->setCellValue('C'.$startCell1, $d->getAgeInt().'/'.$d->sg());
                    $sheet1->setCellValue('D'.$startCell1, $d->getCompleteAddress());
                    $sheet1->setCellValue('E'.$startCell1, date('m/d/Y h:i A', strtotime($d->injury_date)).' - '.$d->place_of_occurrence);
                    $sheet1->setCellValue('F'.$startCell1, date('m/d/Y h:i A', strtotime($d->consultation_date)));
                    $sheet1->setCellValue('G'.$startCell1, $d->involvement_type);
                    $sheet1->setCellValue('H'.$startCell1, $getInjuryType);
                    $sheet1->setCellValue('I'.$startCell1, $getDiag);
                    $sheet1->setCellValue('J'.$startCell1, $d->firework_name);
                    $sheet1->setCellValue('K'.$startCell1, $d->liquor_intoxication);
                    $sheet1->setCellValue('L'.$startCell1, $d->treatment_given);
                    $sheet1->setCellValue('M'.$startCell1, $getDispo);

                    //SHEET2

                    //POI CHECK IF SAME
                    $address1 = $d->getAddress();
                    $address2 = $d->getInjuryAddress();
                    if($address1 == $address2) {
                        $same_poi = 'Y';
                    }
                    else {
                        $same_poi = 'N';
                    }

                    //IF MULTIPLE INJURY
                    $exp_typeOfInjury = explode(',', $d->iffw_typeofinjury);

                    if(count($exp_typeOfInjury) > 1) {
                        $mult_inj = 'Y';
                    }
                    else {
                        $mult_inj = 'N';
                    }

                    $exp_aloc = explode(',', $d->anatomical_location);
                    $exp_treat = explode(',', $d->treatment_given);

                    //FACILITY CODES SEARCH
                    $searchCode = BarangayHealthStation::where('sys_code1', $d->facility_code)->first();

                    if($searchCode) {
                        $facReg = 'IV-A';
                        $facProv = 'CAVITE';
                        $facMuncity = 'GENERAL TRIAS';
                    }
                    else {
                        $searchCode = DohFacility::where('sys_code1', $d->facility_code)->first();

                        $facReg = $searchCode->address_region;
                        $facProv = $searchCode->address_province;
                        $facMuncity = $searchCode->address_muncity;
                    }

                    //$sheet2->setCellValue('A'.$startCell2, '');
                    $sheet2->setCellValue('A'.$startCell2, $d->id);
                    $sheet2->setCellValue('B'.$startCell2, '');
                    $sheet2->setCellValue('C'.$startCell2, '');
                    $sheet2->setCellValue('D'.$startCell2, '');
                    $sheet2->setCellValue('E'.$startCell2, date('m/d/Y', strtotime($d->report_date)));
                    $sheet2->setCellValue('F'.$startCell2, '');
                    $sheet2->setCellValue('G'.$startCell2, '');
                    $sheet2->setCellValue('H'.$startCell2, '');
                    $sheet2->setCellValue('I'.$startCell2, $d->lname);
                    $sheet2->setCellValue('J'.$startCell2, $d->fname);
                    $sheet2->setCellValue('K'.$startCell2, $d->mname);
                    $sheet2->setCellValue('L'.$startCell2, date('m/d/Y', strtotime($d->bdate)));
                    $sheet2->setCellValue('M'.$startCell2, $d->age_years);
                    $sheet2->setCellValue('N'.$startCell2, $d->age_months);
                    $sheet2->setCellValue('O'.$startCell2, $d->age_days);
                    $sheet2->setCellValue('P'.$startCell2, $d->sg());
                    $sheet2->setCellValue('Q'.$startCell2, $d->address_province_text);
                    $sheet2->setCellValue('R'.$startCell2, $d->getStreetPurok());
                    $sheet2->setCellValue('S'.$startCell2, $d->address_region_text);
                    $sheet2->setCellValue('T'.$startCell2, $d->address_muncity_text);
                    $sheet2->setCellValue('U'.$startCell2, $d->address_brgy_text);
                    $sheet2->setCellValue('V'.$startCell2, '');
                    $sheet2->setCellValue('W'.$startCell2, $d->contact_number);
                    $sheet2->setCellValue('X'.$startCell2, date('m/d/Y', strtotime($d->injury_date)));
                    $sheet2->setCellValue('Y'.$startCell2, '');
                    $sheet2->setCellValue('Z'.$startCell2, date('H:i', strtotime($d->injury_date)));

                    $sheet2->setCellValue('AA'.$startCell2, $same_poi);
                    $sheet2->setCellValue('AB'.$startCell2, $d->injury_address_region_code);
                    $sheet2->setCellValue('AC'.$startCell2, $d->injury_address_province_code);
                    $sheet2->setCellValue('AD'.$startCell2, $d->injury_address_muncity_code);
                    $sheet2->setCellValue('AE'.$startCell2, $d->getBrgyCode());
                    $sheet2->setCellValue('AF'.$startCell2, $d->place_of_occurrence);
                    $sheet2->setCellValue('AG'.$startCell2, $d->place_of_occurrence_others);
                    $sheet2->setCellValue('AH'.$startCell2, date('m/d/Y', strtotime($d->consultation_date)));
                    $sheet2->setCellValue('AI'.$startCell2, date('H:i', strtotime($d->consultation_date)));
                    $sheet2->setCellValue('AJ'.$startCell2, $d->nature_injury);
                    $sheet2->setCellValue('AK'.$startCell2, '');
                    $sheet2->setCellValue('AL'.$startCell2, $mult_inj);
                    $sheet2->setCellValue('AM'.$startCell2, (in_array('BLAST/BURN INJURY WITH AMPUTATION', $exp_typeOfInjury)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AN'.$startCell2, (in_array('BLAST/BURN INJURY NO AMPUTATION', $exp_typeOfInjury)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AO'.$startCell2, (in_array('EYE INJURY', $exp_typeOfInjury)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AP'.$startCell2, ($d->nature_injury == 'TETANUS') ? 'Y' : 'N');
                    $sheet2->setCellValue('AQ'.$startCell2, '');
                    $sheet2->setCellValue('AR'.$startCell2, $d->involvement_type);
                    $sheet2->setCellValue('AS'.$startCell2, $d->complete_diagnosis);
                    $sheet2->setCellValue('AT'.$startCell2, (in_array('EYE', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AU'.$startCell2, (in_array('HEAD', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AV'.$startCell2, (in_array('CHEST', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AW'.$startCell2, (in_array('NECK', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AX'.$startCell2, (in_array('BACK', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AY'.$startCell2, (in_array('ABDOMEN', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('AZ'.$startCell2, (in_array('BUTTOCKS', $exp_aloc)) ? 'Y' : 'N');

                    $sheet2->setCellValue('BA'.$startCell2, (in_array('HAND', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BB'.$startCell2, (in_array('FOREARM/ARM', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BC'.$startCell2, (in_array('PELVIS', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BD'.$startCell2, (in_array('THIGH', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BE'.$startCell2, (in_array('LEGS', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BF'.$startCell2, (in_array('KNEE', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BG'.$startCell2, (in_array('FOOT', $exp_aloc)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BH'.$startCell2, '');
                    $sheet2->setCellValue('BI'.$startCell2, '');
                    $sheet2->setCellValue('BJ'.$startCell2, $d->firework_name);
                    $sheet2->setCellValue('BK'.$startCell2, '');
                    $sheet2->setCellValue('BL'.$startCell2, '');
                    $sheet2->setCellValue('BM'.$startCell2, $d->liquor_intoxication);
                    $sheet2->setCellValue('BN'.$startCell2, (in_array('ATS/TIG', $exp_treat)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BO'.$startCell2, (in_array('TOXOID', $exp_treat)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BP'.$startCell2, (in_array('OTHER', $exp_treat)) ? 'Y' : 'N');
                    $sheet2->setCellValue('BQ'.$startCell2, '');
                    $sheet2->setCellValue('BR'.$startCell2, '');
                    $sheet2->setCellValue('BS'.$startCell2, $d->disposition_after_consultation);
                    $sheet2->setCellValue('BT'.$startCell2, $d->disposition_after_consultation_transferred_hospital);
                    $sheet2->setCellValue('BU'.$startCell2, $d->disposition_after_admission);
                    $sheet2->setCellValue('BV'.$startCell2, $d->disposition_after_admission_transferred_hospital);
                    $sheet2->setCellValue('BW'.$startCell2, ($d->disposition_after_admission == 'DIED DURING ADMISSION') ? $d->date_died : '');
                    $sheet2->setCellValue('BX'.$startCell2, '');
                    $sheet2->setCellValue('BY'.$startCell2, '');
                    $sheet2->setCellValue('BZ'.$startCell2, $facReg);

                    $sheet2->setCellValue('CA'.$startCell2, $facProv);
                    $sheet2->setCellValue('CB'.$startCell2, $facMuncity);

                    $startCell1++;
                    $startCell2++;
                }

                $writer1 = new Xlsx($spreadsheet1);
                $writer1->save(storage_path('app/fwri/CESUGENTRIAS_APIR_LINELIST_'.date('mdY', strtotime('-1 Day')).'.xlsx'));

                $writer2 = new Xlsx($spreadsheet2);
                $writer2->save(storage_path('app/fwri/CESUGENTRIAS_FWRI_LINELIST_'.date('mdY', strtotime('-1 Day')).'.xlsx'));

                //send a mail attaching both files
                Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new FwriDailyMailer());
            
                //DELETE YESTERDAY FILE
                File::delete(storage_path('app/fwri/CESUGENTRIAS_APIR_LINELIST_'.date('mdY', strtotime('-2 Days')).'.xlsx'));
                File::delete(storage_path('app/fwri/CESUGENTRIAS_FWRI_LINELIST_'.date('mdY', strtotime('-2 Days')).'.xlsx'));
            }
        }


    }
}
