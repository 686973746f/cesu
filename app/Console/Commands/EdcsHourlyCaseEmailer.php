<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\EdcsHourlyCaseCheckerMail;
use App\Http\Controllers\PIDSRController;

class EdcsHourlyCaseEmailer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edcscaseemailer:hourly';

    //Not Hourly Anymore, Every minute na. Tinamad lang ako mag-rename

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
                'SevereAcuteRespiratoryInfection',

                'Covid',
            ];

            foreach($diseases as $d) {
                if($d != 'Covid') {
                    $modelClass = "App\\Models\\$d";

                    $fetch_case = $modelClass::where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->where('notify_email_sent', 0);
                }
                else {
                    $fetch_case = Forms::where('status', 'approved')
                    ->whereHas('records', function ($q) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS');
                    })
                    ->where('notify_email_sent', 0);
                }
                
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
                    else if($d == 'SevereAcuteRespiratoryInfection') {
                        $get_type = 'Severe Acute Respiratory Infection';
                    }
                    else if($d == 'Covid') {
                        $get_type = 'COVID-19';
                    }

                    $lab_array = [];

                    foreach($l as $i) {
                        /*
                        //Check Lab Details
                        if($d == 'SevereAcuteRespiratoryInfection') {
                            $get_epiid = $i->epi_id;
                            $get_caseid = $i->edcs_caseid;
                        }
                        else {
                            $get_epiid = $i->EPIID;
                            $get_caseid = $i->edcs_caseid;
                        }

                        $getLabDetails = PIDSRController::getLabDetails($get_epiid, $get_caseid);

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
                        */

                        if($d == 'SevereAcuteRespiratoryInfection') {
                            $get_fullname = $i->getFullName();
                            $get_age = $i->age_years;
                            $get_sex = substr($i->sex,0,1);
                            $get_brgy = $i->barangay;
                            $get_address = (!is_null($i->streetpurok)) ? mb_strtoupper($i->streetpurok) : 'NO ADDRESS ENCODED';
                            $get_doe = $i->created_at;
                            $get_dru = $i->facility_name;
                        }
                        else if($d == 'Covid') {
                            $get_fullname = $i->records->getName();
                            $get_sex = substr($i->records->gender,0,1);
                            $get_brgy = $i->records->address_brgy;
                            $get_address = $i->records->getStreetPurok();
                            $get_doe = ($i->from_tkc == 1) ? $i->dateReported : $i->created_at;
                            $get_dru = $i->drunit;
                        }
                        else {
                            $get_fullname = $i->FullName;
                            $get_age = $i->AgeYears;
                            $get_sex = $i->Sex;
                            $get_brgy = $i->Barangay;
                            $get_address = (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED';
                            $get_doe = $i->DateOfEntry;
                            $get_dru = $i->NameOfDru;
                        }

                        $table_params = [
                            'type' => $get_type,
                            'name' => $get_fullname,
                            'age' => $i->displayAgeStringToReport(),
                            'sex' => $get_sex,
                            'brgy' => $get_brgy,
                            'address' => $get_address,
                            'doe' => $get_doe,
                            'dru' => $get_dru,
                            //'lab_data' => $lab_array,
                        ];

                        if($d == 'Dengue') {
                            $table_params = $table_params + [
                                'cc' => $i->getClassificationString(),
                            ];
                        }

                        if($d == 'Measles') {
                            $table_params = $table_params + [
                                'sx' => $i->listSymptoms(),
                                'name_of_parentcaregiver' => $i->name_of_parentcaregiver,
                                'parent_contactno' => $i->parent_contactno,
                            ];
                        }

                        if($d == 'Covid') {
                            $table_params = $table_params + [
                                'cc' => $i->getClassificationString(),
                                'mobile' => $i->records->mobile,
                            ];
                        }
 
                        array_push($list, $table_params);
                    }

                    $update = $fetch_case->update([
                        'notify_email_sent' => 1,
                        'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            if(!empty($list)) {
                Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'barzagaanalyn@gmail.com', 'quionjanzen@gmail.com'])->send(new EdcsHourlyCaseCheckerMail($list));
            }
        }
    }
}
