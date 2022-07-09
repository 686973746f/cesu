<?php

namespace App\Console\Commands;

use App\Models\Brgy;
use App\Models\Forms;
use App\Models\DailyCases;
use App\Mail\CovidReportWord;
use App\Mail\DilgReportExcel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AutoEmailReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoemailreport:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduler to Email COVID Gentri Daily Report Every 4:10 PM';

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
        $data = DailyCases::whereDate('set_date', date('Y-m-d'))
        ->where('type', '4PM')
        ->first();

        if(!($data)) {
            $data = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '4PM')
            ->first();
        }

        $templateProcessor  = new TemplateProcessor(public_path('/assets/docs/CovidGentriTemplate.docx'));
        $spreadsheet = IOFactory::load(public_path('/assets/docs/DilgExcelTemplate.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $templateProcessor->setValue('date', date('F d, Y'));
        $templateProcessor->setValue('c_n', number_format($data->new_cases));
        $templateProcessor->setValue('c_l', number_format($data->late_cases));
        $templateProcessor->setValue('c_p', round(($data->total_active / $data->total_all_confirmed_cases) * 100, 1).'%');
        $templateProcessor->setValue('c_t', number_format($data->total_active));

        $templateProcessor->setValue('vcu', number_format($data->total_active_unvaccinated));
        $templateProcessor->setValue('vcp', number_format($data->total_active_halfvax));
        $templateProcessor->setValue('vcf', number_format($data->total_active_fullvax));
        $templateProcessor->setValue('vcb', number_format($data->total_active_booster));

        $templateProcessor->setValue('r_n', number_format($data->new_recoveries));
        $templateProcessor->setValue('r_l', number_format($data->late_recoveries));
        $templateProcessor->setValue('r_p', round(($data->total_recoveries / $data->total_all_confirmed_cases) * 100, 1).'%');
        $templateProcessor->setValue('r_t', number_format($data->total_recoveries));

        $templateProcessor->setValue('vru', number_format($data->total_recoveries_unvaccinated));
        $templateProcessor->setValue('vrp', number_format($data->total_recoveries_halfvax));
        $templateProcessor->setValue('vrf', number_format($data->total_recoveries_fullvax));
        $templateProcessor->setValue('vrb', number_format($data->total_recoveries_booster));

        $templateProcessor->setValue('d_n', number_format($data->new_deaths));
        $templateProcessor->setValue('d_p', round(($data->total_deaths / $data->total_all_confirmed_cases) * 100, 1).'%');
        $templateProcessor->setValue('d_t', number_format($data->total_deaths));

        $templateProcessor->setValue('vdu', number_format($data->total_deaths_unvaccinated));
        $templateProcessor->setValue('vdp', number_format($data->total_deaths_halfvax));
        $templateProcessor->setValue('vdf', number_format($data->total_deaths_fullvax));
        $templateProcessor->setValue('vdb', number_format($data->total_deaths_booster));

        $templateProcessor->setValue('gt_cases', number_format($data->total_all_confirmed_cases));

        $templateProcessor->setValue('facility_one', number_format($data->facility_one_count));
        $templateProcessor->setValue('hq', number_format($data->hq_count));
        $templateProcessor->setValue('o_hosp', number_format($data->hospital_count));

        $templateProcessor->setValue('as', number_format($data->active_asymptomatic_count));
        $templateProcessor->setValue('as_p', round(($data->active_asymptomatic_count / $data->total_active) * 100, 1).'%');
        $templateProcessor->setValue('mi', number_format($data->active_mild_with_comorbid_count + $data->active_mild_without_comorbid_count));
        $templateProcessor->setValue('mi_p', round(($data->active_mild_with_comorbid_count + $data->active_mild_without_comorbid_count / $data->total_active) * 100, 1).'%');
        $templateProcessor->setValue('mo', number_format($data->active_moderate_count));
        $templateProcessor->setValue('mo_p', round(($data->active_moderate_count / $data->total_active) * 100, 1).'%');
        $templateProcessor->setValue('se', number_format($data->active_severe_count));
        $templateProcessor->setValue('se_p', round(($data->active_severe_count / $data->total_active) * 100, 1).'%');
        $templateProcessor->setValue('cr', number_format($data->active_critical_count));
        $templateProcessor->setValue('cr_p', round(($data->active_critical_count / $data->total_active) * 100, 1).'%');

        $brgyList = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $ind = 1;

        $eind = 3;

        $bct = 0;
        $bat = 0;
        $bdt = 0;
        $brt = 0;

        $bst = 0;
        $bpt = 0;

        foreach($brgyList as $brgy) {
            $brgyConfirmedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            $brgyActiveCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            $brgyDeathCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            $brgyRecoveryCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            /*
            $brgySuspectedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('isPresentOnSwabDay', 0)
                ->orwhereNull('isPresentOnSwabDay');
            })
            ->where('caseClassification', 'Suspect')
            ->where('outcomeCondition', 'Active')
            ->where(function ($q) {
                $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
            })
            ->count();

            $brgyProbableCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->where(function ($q) {
                $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
            })
            ->count();
            */

            $brgySuspectedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('isPresentOnSwabDay', 0)
                ->orwhereNull('isPresentOnSwabDay');
            })
            ->where('caseClassification', 'Suspect')
            ->where('outcomeCondition', 'Active')
            ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')])
            ->count();

            $brgyProbableCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')])
            ->count();

            $templateProcessor->setValue('bc'.$ind, number_format($brgyConfirmedCount));
            $templateProcessor->setValue('ba'.$ind, number_format($brgyActiveCount));
            $templateProcessor->setValue('bd'.$ind, number_format($brgyDeathCount));
            $templateProcessor->setValue('br'.$ind, number_format($brgyRecoveryCount));

            $templateProcessor->setValue('bs'.$ind, number_format($brgySuspectedCount));
            $templateProcessor->setValue('bp'.$ind, number_format($brgyProbableCount));

            //Excel
            $sheet->setCellValue('D'.$eind, $brgySuspectedCount);
            $sheet->setCellValue('E'.$eind, $brgyProbableCount);

            $sheet->setCellValue('G'.$eind, $brgyConfirmedCount);
            $sheet->setCellValue('H'.$eind, $brgyActiveCount);
            $sheet->setCellValue('I'.$eind, $brgyDeathCount);
            $sheet->setCellValue('J'.$eind, $brgyRecoveryCount);

            $ind++;
            $eind++;

            $bct += $brgyConfirmedCount;
            $bat += $brgyActiveCount;
            $bdt += $brgyDeathCount;
            $brt += $brgyRecoveryCount;
    
            $bst += $brgySuspectedCount;
            $bpt += $brgyProbableCount;
        }

        $templateProcessor->setValue('bct', number_format($bct));
        $templateProcessor->setValue('bat', number_format($bat));
        $templateProcessor->setValue('bdt', number_format($bdt));
        $templateProcessor->setValue('brt', number_format($brt));

        $templateProcessor->setValue('bst', number_format($bst));
        $templateProcessor->setValue('bpt', number_format($bpt));

        $templateProcessor->saveAs(public_path('CITY-OF-GENERAL-TRIAS-'.date('F-d-Y').'.docx'));

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('GEN.TRIAS-DILG-CHO-REPORT-'.date('F-d-Y').'.xlsx'));

        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'jango_m14@yahoo.com', 'ronald888mojica@gmail.com', 'glorybemendez06@gmail.com', 'citymayor.generaltriascavite@gmail.com'])->send(new CovidReportWord());
        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'ronald888mojica@gmail.com'])->send(new DilgReportExcel());

        File::delete(public_path('CITY-OF-GENERAL-TRIAS-'.date('F-d-Y', strtotime('-1 Day')).'.docx'));
        File::delete(public_path('GEN.TRIAS-DILG-CHO-REPORT-'.date('F-d-Y', strtotime('-1 Day')).'.xlsx'));
    }
}
