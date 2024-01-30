<?php

namespace App\Console\Commands;

use App\Http\Controllers\PIDSRController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\EdcsHourlyCaseCheckerMail;

class EdcsHourlyCaseEmailer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edcscaseemailer:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mostly used for Category 1 or Immediate Notifiable';

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
        $now = Carbon::now();

        $startDateTime = Carbon::now()->setTime(13, 0, 0);
        $endDateTime = Carbon::now()->setTime(23, 59, 59);

        if($now->dayOfWeek == Carbon::TUESDAY) {
            if ($now->between($startDateTime, $endDateTime)) {
                $proceed = true;
            }
            else {
                $proceed = false;
            }
        }
        else {
            $proceed = true;
        }
        
        if($proceed) {
            $list = [];

            /*
            IMMEDIATE NOTIFIABLES:
            AFP
            MEASLES
            MENINGO
            NEONATAL TETANUS
            RABIES
            HFMD

            
            WEEKLY NOTIFIABLES:
            Abd
            Ames
            Hepatitis
            Chikv
            Cholera
            Dengue
            Diph
            Influenza
            Leptospirosis
            Nnt
            Pert
            Rotavirus
            Typhoid
            */

            $diseases = [
                'Afp',
                'Measles',
                'Meningo',
                'Nt',
                'Rabies',
                'Hfmd',

                'Abd',
                'Ames',
                'Hepatitis',
                'Chikv',
                'Cholera',
                'Dengue',
                'Diph',
                'Influenza',
                'Leptospirosis',
                'Nnt',
                'Pert',
                'Rotavirus',
                'Typhoid',
            ];

            foreach($diseases as $d) {
                $modelClass = "App\\Models\\$d";

                $fetch_case = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where('notify_email_sent', 0);

                if($fetch_case->count() != 0) {
                    $l = $fetch_case->get();

                    if($d == 'Afp') {
                        $get_type = 'Acute Flaccid Paralysis';
                    }
                    else if($d == 'Measles') {
                        $get_type = 'Measles';
                    }
                    else if($d == 'Meningo') {
                        $get_type = 'Meningococcal Disease';
                    }
                    else if($d == 'Nt') {
                        $get_type = 'Neonatal Tetanus';
                    }
                    else if($d == 'Rabies') {
                        $get_type = 'Rabies';
                    }
                    else if($d == 'Hfmd') {
                        $get_type = 'HFMD';
                    }
                    else if($d == 'Abd') {
                        $get_type = 'Acute Bloody Diarrhea';
                    }
                    else if($d == 'Ames') {
                        $get_type = 'AMES';
                    }
                    else if($d == 'Hepatitis') {
                        $get_type = 'Acute Viral Hepatitis';
                    }
                    else if($d == 'Chikv') {
                        $get_type = 'Chikungunya';
                    }
                    else if($d == 'Cholera') {
                        $get_type = 'Cholera';
                    }
                    else if($d == 'Dengue') {
                        $get_type = 'Dengue';
                    }
                    else if($d == 'Diph') {
                        $get_type = 'Diphtheria';
                    }
                    else if($d == 'Influenza') {
                        $get_type = 'Influenza-like Illness';
                    }
                    else if($d == 'Leptospirosis') {
                        $get_type = 'Leptospirosis';
                    }
                    else if($d == 'Nnt') {
                        $get_type = 'Non-Neonatal Tetanus';
                    }
                    else if($d == 'Pert') {
                        $get_type = 'Pertussis';
                    }
                    else if($d == 'Rotavirus') {
                        $get_type = 'RotaVirus';
                    }
                    else if($d == 'Typhoid') {
                        $get_type = 'Typhoid and Parathyphoid Fever';
                    }

                    $lab_array = [];

                    foreach($l as $i) {
                        //Check Lab Details
                        $getLabDetails = PIDSRController::getLabDetails($i->EPIID, $i->edcs_caseid);

                        if($getLabDetails->count() != 0) {
                            foreach($getLabDetails as $ld) {
                                $lab_array[] = [
                                    'test_type' => $ld->test_type,
                                    'specimen_type' => $ld->specimen_type,
                                    'date_collected' => Carbon::parse($ld->specimen_collected_date)->format('m/d/Y'),
                                    'result' => $ld->result,
                                ];
                            }
                        }

                        array_push($list, [
                            'type' => $get_type,
                            'name' => $i->FullName,
                            'age' => $i->AgeYears,
                            'sex' => $i->Sex,
                            'brgy' => $i->Barangay,
                            'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                            'doe' => $i->DateOfEntry,
                            'dru' => $i->NameOfDru,
                            'lab_data' => $lab_array,
                        ]);
                    }

                    $update = $fetch_case->update([
                        'notify_email_sent' => 1,
                    ]);
                }
            }

            if(!empty($list)) {
                Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new EdcsHourlyCaseCheckerMail($list));
            }
        }
    }
}
