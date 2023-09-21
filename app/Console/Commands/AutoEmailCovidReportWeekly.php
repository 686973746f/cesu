<?php

namespace App\Console\Commands;

use App\Models\Brgy;
use App\Models\Forms;
use App\Models\DailyCases;
use Illuminate\Console\Command;
use App\Mail\CovidReportWordWeekly;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\TemplateProcessor;

class AutoEmailCovidReportWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoemailcovidreport:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'NEW COVID Weekly Report Every Friday 4:10 PM';

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
        $set_temp = true;

        //temp format for last week
        if($set_temp) {
            $curr_date = '2023-09-15';
            $last_week_date = '2023-09-09';
        }
        else {
            $curr_date = date('Y-m-d');
            $last_week_date = date('Y-m-d', strtotime('-6 Days'));
        }
        
        $data = DailyCases::whereDate('set_date', $curr_date)
        ->where('type', '4PM')
        ->first();
        
        $weekly_new_recoveries = DailyCases::whereBetween('set_date', [$last_week_date, $curr_date])
        ->where('type', '4PM')
        ->sum('new_recoveries');

        $weekly_late_recoveries = DailyCases::whereBetween('set_date', [$last_week_date, $curr_date])
        ->where('type', '4PM')
        ->sum('late_recoveries');

        $weekly_new_deaths = DailyCases::whereBetween('set_date', [$last_week_date, $curr_date])
        ->where('type', '4PM')
        ->sum('new_deaths');
        
        $templateProcessor  = new TemplateProcessor(storage_path('COVIDGENTRITEMPLATE_NEW.docx'));

        $templateProcessor->setValue('date', date('m/d/Y', strtotime($last_week_date)).' - '.date('m/d/Y', strtotime($curr_date)));
        $templateProcessor->setValue('gt_cases', number_format($data->total_all_confirmed_cases));
        $templateProcessor->setValue('c_t', number_format($data->total_active));
        $templateProcessor->setValue('c_n', number_format($data->new_cases));
        $templateProcessor->setValue('c_l', number_format($data->late_cases));
        $templateProcessor->setValue('r_t', number_format($data->total_recoveries));
        $templateProcessor->setValue('d_t', number_format($data->total_deaths));

        $templateProcessor->setValue('r_n', number_format($weekly_new_recoveries));
        $templateProcessor->setValue('r_l', number_format($weekly_late_recoveries));
        $templateProcessor->setValue('d_n', number_format($weekly_new_deaths));

        $templateProcessor->setValue('as', number_format($data->active_asymptomatic_count));
        $templateProcessor->setValue('mi', number_format($data->active_mild_with_comorbid_count + $data->active_mild_without_comorbid_count));
        $templateProcessor->setValue('mo', number_format($data->active_moderate_count));
        $templateProcessor->setValue('se', number_format($data->active_severe_count));
        $templateProcessor->setValue('cr', number_format($data->active_critical_count));

        $brgyList = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $bgynew_total = 0;
        $bgynew_recovered_total = 0;

        $v2bgy_list = [];

        foreach($brgyList as $brgy) {
            $brgyActiveCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', $curr_date)
            ->count();
            
            $bgynew_total += $brgyActiveCount;
            
            $brgyWeeklyRecoveryCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->whereBetween('morbidityMonth', [$last_week_date, $curr_date])
            ->count();

            $bgynew_recovered_total += $brgyWeeklyRecoveryCount;

            array_push($v2bgy_list, [
                'barangay' => $brgy->brgyName,
                'active' => $brgyActiveCount,
                'recovered_weekly' => $brgyWeeklyRecoveryCount,
            ]);
        }

        $placeval = 1;

        for($i=1;$i<=33;$i++) {
            if(isset($v2bgy_list[$i-1])) {
                if($v2bgy_list[$i-1]['active'] != 0 || $v2bgy_list[$i-1]['recovered_weekly'] != 0) {
                    $templateProcessor->setValue('bgy'.$placeval, $v2bgy_list[$i-1]['barangay']);
                    $templateProcessor->setValue('bgy'.$placeval.'_count', $v2bgy_list[$i-1]['active']);
                    $templateProcessor->setValue('bgy'.$placeval.'_rec', $v2bgy_list[$i-1]['recovered_weekly']);
                    
                    $placeval++;
                }
            }
            /*
            else {
                $templateProcessor->setValue('bgy'.$i, '');
                $templateProcessor->setValue('bgy'.$i.'_count', '');
                $templateProcessor->setValue('bgy'.$i.'_rec', '');
            }
            */
        }

        //emptying blank brgy
        for($i=$placeval;$i<=33;$i++) {
            $templateProcessor->setValue('bgy'.$i, '');
            $templateProcessor->setValue('bgy'.$i.'_count', '');
            $templateProcessor->setValue('bgy'.$i.'_rec', '');
        }

        $templateProcessor->setValue('bgynew_gtotal', number_format($bgynew_total));
        $templateProcessor->setValue('bgynew_gtotal_rec', number_format($bgynew_recovered_total));
        $templateProcessor->saveAs(storage_path('CITY-OF-GENERAL-TRIAS-WEEKLY-'.date('F-d-Y').'.docx'));

        if($set_temp) {
            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new CovidReportWordWeekly());
        }
        else {
            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'jango_m14@yahoo.com', 'ronald888mojica@gmail.com', 'citymayor.generaltriascavite@gmail.com', 'chogentri2@proton.me', 'mjmugol@gmail.com', 'gtcdrrmogentri@gmail.com'])->send(new CovidReportWordWeekly());
            File::delete(public_path('CITY-OF-GENERAL-WEEKLY-'.date('F-d-Y', strtotime('-7 Days')).'.docx'));
        }
    }
}
