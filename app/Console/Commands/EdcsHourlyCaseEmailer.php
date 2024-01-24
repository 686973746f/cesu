<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

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
            IMMEDIATE NOTIFIABLE:
            AFP
            MEASLES
            MENINGO
            NEONATAL TETANUS
            RABIES
            HFMD

            ABD
            AMES
            HEPATITIS
            CHIKV
            CHOLERA
            DENGUE
            DIPHTHERIA
            ILI
            LEPTOSPIROSIS
            NNT
            PERTUSSIS
            ROTAVIRUS
            TYPHOID
            */

            $diseases = [
                'Afp',
                'Measles',
                'Meningo',
                'Nt',
                'Rabies',
                'Hfmd',
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
                    else {
                        $get_type = $d;
                    }

                    foreach($l as $i) {
                        array_push($list, [
                            'type' => $get_type,
                            'name' => $i->FullName,
                            'age' => $i->AgeYears,
                            'sex' => $i->Sex,
                            'brgy' => $i->Barangay,
                            'address' => $i->Streetpurok,
                            'doe' => $i->DateOfEntry,
                            'dru' => $i->NameOfDru,
                        ]);
                    }

                    $update = $fetch_case->update([
                        'notify_email_sent' => 1,
                    ]);
                }
            }

            
        }
    }
}
