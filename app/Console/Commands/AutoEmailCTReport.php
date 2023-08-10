<?php

namespace App\Console\Commands;

use App\Models\Forms;
use App\Mail\SendCTReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AutoEmailCTReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoemailctreport:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send CT Report Daily';

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
        $spreadsheet = IOFactory::load(storage_path('CTREPORT.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        //No. of Suspect/Probable case of the day
        $item1 = Forms::where('status', 'approved')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->count();

        //No. of Suspect/Probable case of the day traced within 24 hours
        $item2 = Forms::where('status', 'approved')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('interviewDate', '<=', date('Y-m-d', strtotime('-1 Day')))
        ->count();

        //% of Suspect/ Probable case of the day traced within 24 hours
        $item3 = ($item1 != 0 && $item2 != 0) ? round(($item2 / $item1) * 100) : 0;

        //No. of Suspect/ Probable case traced and isolated within 24 hours
        $item4 = Forms::where('status', 'approved')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereIn('dispoType', [2,6,7])
        ->count();
        
        //% of Suspect/ Probable case isolated within 24 hours
        $item5 = ($item4 != 0 && $item4 != 0) ? round(($item4 / $item1) * 100) : 0;

        //No. of Confirmed/ Active Cases of the day
        $item6 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //No. of Confirmed/ Active Cases of the day traced within 24 hours
        $item7 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('dateReported', date('Y-m-d'))
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->count();

        //% of Confirmed/ Active Cases of the day traced within 24 hours
        $item8 = ($item6 != 0 && $item7 != 0) ? round(($item7 / $item6) * 100) : 0;
        
        //No. of Pending Confirmed/ Active Cases still to be traced
        $item9  = 0;

        //No. of Pending Confirmed/ Active Cases traced
        $item10 = 0;

        //% of pending Confirmed/ Active Cases still to be traced traced within 24 hours
        $item11 = ($item9 != 0 && $item10 != 0) ? round((($item9 - $item10)/$item9) * 100) : 0;

        //No. of Confirmed/ Active Cases traced and quarantined/isolated within 24 hours
        $item12 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->whereIn('dispoType', [2,6,7])
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of Confirmed/ Active Cases isolated/quarantined within 24 hours
        //$item13 = ($item12 != 0 && ($item6 + $item9) != 0) ? round(($item12 / ($item6 + $item9)) * 100) : 0;

        $item13 = ($item12 != 0 && $item6 != 0) ? round(($item12 / $item6) * 100) : 0;

        //No. of CCs listed from the Confirmed/ Active Cases
        $item14 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //No. of CCs listed Traced and Assessed within 24 hours
        $item15 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->whereDate('dateReported', date('Y-m-d'))
        ->count();

        //% of CCs listed Traced and Assesed within 24 hours
        $item16 = ($item15 != 0 && $item14 != 0) ? round(($item15 / $item14) * 100) : 0;

        //Case: Close Contact Ratio
        if($item6 == 0 && $item14 == 0) {
            $item17 = '0:0';
        }
        else {
            $item17 = ($item6/$item6).':'.($item14/$item6);
        }

        //No. of CCs placed under home quarantine within 24 hours
        $item18 = Forms::where('status', 'approved')
        ->where('ptype', 'CLOSE CONTACT')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->count();
        
        //% of CCs placed under home quarantine within 24 hours
        $item19 = ($item18 != 0 && $item14 != 0) ? round(($item18 / $item14) * 100) : 0;

        //Total no. of active asymptomatic or mild with no comorbidities, confirmed cases
        $item20 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Asymptomatic', 'Mild'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', 'None')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        
        //Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Home Quarantine
        $item21 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Asymptomatic', 'Mild'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', 'None')
        ->where('dispoType', 3)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of total no. of active asymptomatic, mild with no comorbidity, confirmed cases under Home Quarantine
        $item22 = ($item21 != 0 && $item20 != 0) ? round(($item21 / $item20) * 100) : 0;

        //Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility
        $item23 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Asymptomatic', 'Mild'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', 'None')
        ->whereIn('dispoType', [2,6,7])
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility
        $item24 = ($item23 != 0 && $item20 != 0) ? round(($item23 / $item20) * 100) : 0;

        //Total number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases
        $item25 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Mild', 'Moderate', 'Severe', 'Critical'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', '!=', 'None')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //Total Number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital
        $item26 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('dispoType', 1)
        ->whereIn('healthStatus', ['Mild', 'Moderate', 'Severe', 'Critical'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', '!=', 'None')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of total number Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital
        $item27 = ($item26 != 0 && $item25 != 0) ? round(($item26 / $item25) * 100) : 0;

        $sheet->setCellValue('A4', date('m/d/Y'));
        $sheet->setCellValue('B4', $item1);
        $sheet->setCellValue('C4', $item2);
        $sheet->setCellValue('D4', $item3);
        $sheet->setCellValue('E4', $item4);
        $sheet->setCellValue('F4', $item5);
        $sheet->setCellValue('G4', $item6);
        $sheet->setCellValue('H4', $item7);
        $sheet->setCellValue('I4', $item8);
        $sheet->setCellValue('J4', $item9);
        $sheet->setCellValue('K4', $item10);
        $sheet->setCellValue('L4', $item11);
        $sheet->setCellValue('M4', $item12);
        $sheet->setCellValue('N4', $item13);
        $sheet->setCellValue('O4', $item14);
        $sheet->setCellValue('P4', $item15);
        $sheet->setCellValue('Q4', $item16);
        $sheet->setCellValue('R4', $item17);
        $sheet->setCellValue('S4', $item18);
        $sheet->setCellValue('T4', $item19);
        $sheet->setCellValue('U4', $item20);
        $sheet->setCellValue('V4', $item21);
        $sheet->setCellValue('W4', $item22);
        $sheet->setCellValue('X4', $item23);
        $sheet->setCellValue('Y4', $item24);
        $sheet->setCellValue('Z4', $item25);
        $sheet->setCellValue('AA4', $item26);
        $sheet->setCellValue('AB4', $item27);

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('CTREPORT_'.date('m_d_Y').'.xlsx'));

        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'xrizzymendoza@gmail.com'])->send(new SendCTReport());

        File::delete(public_path('CTREPORT_'.date('m_d_Y', strtotime('-1 Day')).'.xlsx'));
    }
}
