<?php

namespace App\Console\Commands;

use App\Models\Forms;
use Illuminate\Console\Command;
use App\Mail\SendCompositeMeasureV2;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\TemplateProcessor;

class AutoEmailCompositeMeasureV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compositemeasurev2:on15and30';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Composite Measure V2';

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
        $templateProcessor  = new TemplateProcessor(storage_path('CMEASURE_TEMPLATE.docx'));

        if(date('d') <= 15) { 
            $sdate = date('Y-m-01');
            $edate = date('Y-m-15');
            $period = date('F 01, Y').' - '.date('F 15, Y');
            $fnameToDelete = 'COMPOSITE_MEASURE_'.date('F_t_Y', strtotime('-1 Month')).'.docx';
        }
        else if(date('d') >= 16) {
            $sdate = date('Y-m-16');
            $edate = date('Y-m-t');
            $period = date('F 16, Y').' - '.date('F t, Y');
            $fnameToDelete = 'COMPOSITE_MEASURE_'.date('F_15_Y').'.docx';
        }

        $lastsevendays = date('Y-m-d', strtotime('-7 Days'));

        $cc_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->where('outcomeCondition', 'Active')
        ->count();

        $cc_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->where('dispoType', 3)
        ->where('outcomeCondition', 'Active')
        ->count();

        $cc_count_hq_percent = ($cc_count_total != 0) ? round((($cc_count_hq / $cc_count_total) * 100), 2) : 0;

        $cc_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->whereIn('dispoType', [6,7,2,5])
        ->where('outcomeCondition', 'Active')
        ->count();

        $cc_count_ttmf_percent = ($cc_count_total != 0) ? round(($cc_count_ttmf / $cc_count_total) * 100, 2) : 0;

        $cc_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->where('dispoType', 1)
        ->where('outcomeCondition', 'Active')
        ->count();

        $probable_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Probable')
        ->where('outcomeCondition', 'Active')
        ->count();

        $probable_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Probable')
        ->where('dispoType', 3)
        ->where('outcomeCondition', 'Active')
        ->count();

        $probable_count_hq_percent = ($probable_count_total != 0) ? round((($probable_count_hq / $probable_count_total) * 100), 2) : 0;

        $probable_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Probable')
        ->whereIn('dispoType', [6,7,2,5])
        ->where('outcomeCondition', 'Active')
        ->count();
        
        $probable_count_ttmf_percent = ($probable_count_total != 0) ? round((($probable_count_ttmf / $probable_count_total) * 100), 2) : 0;

        $probable_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Probable')
        ->where('dispoType', 1)
        ->where('outcomeCondition', 'Active')
        ->count();

        $suspected_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Suspect')
        ->where('outcomeCondition', 'Active')
        ->count();

        $suspected_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Suspect')
        ->where('dispoType', 3)
        ->where('outcomeCondition', 'Active')
        ->count();

        $suspected_count_hq_percent = ($suspected_count_total != 0) ? round(($suspected_count_hq / $suspected_count_total) * 100, 2) : 0;

        $suspected_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Suspect')
        ->whereIn('dispoType', [6,7,2,5])
        ->where('outcomeCondition', 'Active')
        ->count();

        $suspected_count_ttmf_percent = ($suspected_count_total != 0) ? round(($suspected_count_ttmf / $suspected_count_total) * 100, 2) : 0;

        $suspected_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('caseClassification', 'Suspect')
        ->where('dispoType', 1)
        ->where('outcomeCondition', 'Active')
        ->count();

        $activecases_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->count();

        $activecases_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_asymptomatic_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->count();
        
        $activecases_count_asymptomatic_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_asymptomatic_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_asymptomatic_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_mild_nocomorbid_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->count();

        $activecases_count_mild_nocomorbid_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_mild_nocomorbid_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $cm_3a_pos = ($activecases_count_total != 0) ? round((($activecases_count_mild_nocomorbid_hq + $activecases_count_asymptomatic_hq) / $activecases_count_total) * 100, 2) : 0;
        $cm_3b_pos = ($activecases_count_total != 0) ? round((($activecases_count_mild_nocomorbid_ttmf + $activecases_count_asymptomatic_ttmf) / $activecases_count_total) * 100, 2) : 0;

        $activecases_count_mild_nocomorbid_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_mild_withcomorbid_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->count();

        $activecases_count_mild_withcomorbid_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_mild_withcomorbid_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_mild_withcomorbid_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_mild_withcomorbid_hospital_percent = ($activecases_count_mild_withcomorbid_total != 0) ? round(($activecases_count_mild_withcomorbid_hospital / $activecases_count_mild_withcomorbid_total) * 100, 2) : 0;

        $activecases_count_moderate_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->count();

        $activecases_count_moderate_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_moderate_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_moderate_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_moderate_hospital_percent = ($activecases_count_moderate_total != 0) ? round(($activecases_count_moderate_hospital / $activecases_count_moderate_total) * 100, 2) : 0;

        $activecases_count_severe_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->count();

        $activecases_count_severe_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_severe_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_severe_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->where('dispoType', 1)
        ->count();
        
        $activecases_count_severe_hospital_percent = ($activecases_count_severe_total != 0) ? round(($activecases_count_severe_hospital / $activecases_count_severe_total) * 100, 2) : 0;

        $activecases_count_critical_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->count();

        $activecases_count_critical_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_critical_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_critical_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_critical_hospital_percentage = ($activecases_count_critical_total != 0) ? round(($activecases_count_critical_hospital / $activecases_count_critical_total) * 100, 2) : 0;

        $rat = ($activecases_count_total != 0) ? round(($cc_count_total / $activecases_count_total), 2) : 0;
        $ct_per = ($activecases_count_total != 0) ? round((($cc_count_total / ($activecases_count_total * 15)) * 100), 2) : 0;

        $templateProcessor->setValue('DCREATED', date('m/d/Y'));
        $templateProcessor->setValue('RANGE', $period);

        $templateProcessor->setValue('m_rat', $rat);
        $templateProcessor->setValue('c_per', $ct_per);

        $templateProcessor->setValue('cm_3a_cc', $cc_count_hq_percent);
        $templateProcessor->setValue('cm_3a_pro', $probable_count_hq_percent);
        $templateProcessor->setValue('cm_3a_ass', $suspected_count_hq_percent);
        $templateProcessor->setValue('cm_3a_pos', $cm_3a_pos);

        $templateProcessor->setValue('cm_3b_cc', $cc_count_ttmf_percent);
        $templateProcessor->setValue('cm_3b_pro', $probable_count_ttmf_percent);
        $templateProcessor->setValue('cm_3b_ass', $suspected_count_ttmf_percent);
        $templateProcessor->setValue('cm_3b_pos', $cm_3b_pos);

        $templateProcessor->setValue('cm_3c_mil', $activecases_count_mild_withcomorbid_hospital_percent);
        $templateProcessor->setValue('cm_3c_mod', $activecases_count_moderate_hospital_percent);
        $templateProcessor->setValue('cm_3c_sev', $activecases_count_severe_hospital_percent);
        $templateProcessor->setValue('cm_3c_cri', $activecases_count_critical_hospital_percentage);

        $templateProcessor->setValue('CTA_A', $cc_count_total);
        $templateProcessor->setValue('CTA_B', ($activecases_count_total * 15));
        $templateProcessor->setValue('CTA_PER', $ct_per);

        $templateProcessor->setValue('CTB_A', $cc_count_total);
        $templateProcessor->setValue('CTB_B', $activecases_count_total);
        $templateProcessor->setValue('CTB_RAT', $rat);

        $templateProcessor->setValue('T1_T', $cc_count_total);
        $templateProcessor->setValue('T1_HQ', $cc_count_hq);
        $templateProcessor->setValue('T1_TF', $cc_count_ttmf);
        $templateProcessor->setValue('T1_HS', $cc_count_hospital);

        $templateProcessor->setValue('T2_T', $probable_count_total);
        $templateProcessor->setValue('T2_HQ', $probable_count_hq);
        $templateProcessor->setValue('T2_TF', $probable_count_ttmf);
        $templateProcessor->setValue('T2_HS', $probable_count_hospital);

        $templateProcessor->setValue('T3_T', $suspected_count_total);
        $templateProcessor->setValue('T3_HQ', $suspected_count_hq);
        $templateProcessor->setValue('T3_TF', $suspected_count_ttmf);
        $templateProcessor->setValue('T3_HS', $suspected_count_hospital);

        $templateProcessor->setValue('T4_T', $activecases_count_total);
        $templateProcessor->setValue('T4_HQ', $activecases_count_hq);
        $templateProcessor->setValue('T4_TF', $activecases_count_ttmf);
        $templateProcessor->setValue('T4_HS', $activecases_count_hospital);

        $templateProcessor->setValue('T5_T', $activecases_count_asymptomatic_total);
        $templateProcessor->setValue('T5_HQ', $activecases_count_asymptomatic_hq);
        $templateProcessor->setValue('T5_TF', $activecases_count_asymptomatic_ttmf);
        $templateProcessor->setValue('T5_HS', $activecases_count_asymptomatic_hospital);

        $templateProcessor->setValue('T6_T', $activecases_count_mild_nocomorbid_total);
        $templateProcessor->setValue('T6_HQ', $activecases_count_mild_nocomorbid_hq);
        $templateProcessor->setValue('T6_TF', $activecases_count_mild_nocomorbid_ttmf);
        $templateProcessor->setValue('T6_HS', $activecases_count_mild_nocomorbid_hospital);

        $templateProcessor->setValue('T7_T', $activecases_count_mild_withcomorbid_total);
        $templateProcessor->setValue('T7_HQ', $activecases_count_mild_withcomorbid_hq);
        $templateProcessor->setValue('T7_TF', $activecases_count_mild_withcomorbid_ttmf);
        $templateProcessor->setValue('T7_HS', $activecases_count_mild_withcomorbid_hospital);

        $templateProcessor->setValue('T8_T', $activecases_count_moderate_total);
        $templateProcessor->setValue('T8_HQ', $activecases_count_moderate_hq);
        $templateProcessor->setValue('T8_TF', $activecases_count_moderate_ttmf);
        $templateProcessor->setValue('T8_HS', $activecases_count_moderate_hospital);

        $templateProcessor->setValue('T9_T', $activecases_count_severe_total);
        $templateProcessor->setValue('T9_HQ', $activecases_count_severe_hq);
        $templateProcessor->setValue('T9_TF', $activecases_count_severe_ttmf);
        $templateProcessor->setValue('T9_HS', $activecases_count_severe_hospital);

        $templateProcessor->setValue('T10_T', $activecases_count_critical_total);
        $templateProcessor->setValue('T10_HQ', $activecases_count_critical_hq);
        $templateProcessor->setValue('T10_TF', $activecases_count_critical_ttmf);
        $templateProcessor->setValue('T10_HS', $activecases_count_critical_hospital);

        $templateProcessor->saveAs(storage_path('COMPOSITE_MEASURE_'.date('F_d_Y').'.docx'));
        
        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'ludettelontoc@gmail.com', 'chogentri2@proton.me'])->send(new SendCompositeMeasureV2());

        File::delete(storage_path($fnameToDelete));
    }
}
