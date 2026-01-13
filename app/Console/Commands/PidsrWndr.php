<?php

namespace App\Console\Commands;

use App\Models\Nt;
use Carbon\Carbon;
use App\Models\Abd;
use App\Models\Aes;
use App\Models\Afp;
use App\Models\Ahf;
use App\Models\Nnt;
use App\Models\Psp;
use App\Models\Aefi;
use App\Models\Ames;
use App\Models\Diph;
use App\Models\Hfmd;
use App\Models\Pert;
use App\Models\Chikv;
use App\Models\Forms;
use App\Models\Dengue;
use App\Models\Rabies;
use App\Models\Anthrax;
use App\Models\Cholera;
use App\Models\Malaria;
use App\Models\Measles;
use App\Models\Meningo;
use App\Models\Typhoid;
use App\Models\Hepatitis;
use App\Models\Influenza;
use App\Models\Rotavirus;
use App\Models\Meningitis;
use App\Mail\PidsrWndrMail;
use App\Models\SiteSettings;
use App\Models\Leptospirosis;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Console\Command;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Http\Controllers\PIDSRController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\SevereAcuteRespiratoryInfection;
use PhpOffice\PhpWord\IOFactory as WordFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelFactory;

class PidsrWndr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pidsrwndr:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send PIDSR';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        DB::setDefaultConnection('cesusyndromic');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*
        Settings::setPdfRendererName('TCPDF');
        Settings::setPdfRendererPath(base_path() . '/vendor/tecnickcom/tcpdf');
        */

        /*
        Settings::setPdfRendererName('DomPDF');
        Settings::setPdfRendererPath(base_path() . '/vendor/barryvdh/laravel-dompdf');
        */

        $s = SiteSettings::find(1);

        $always_send = true; //because automation is turned off

        if($always_send) {
            Settings::setPdfRendererName('MPDF');
            Settings::setPdfRendererPath(base_path() . '/vendor/mpdf/mpdf');
            
            /*
            $templateProcessor  = new TemplateProcessor(storage_path('WNDR.docx'));
            
            $templateProcessor->setValue('mweek', date('W', strtotime('-1 Week')));
            $templateProcessor->setValue('myear', date('Y', strtotime('-1 Week')));

            $templateProcessor->setValue('pdate', date('m/d/Y'));
            $templateProcessor->setValue('adate', date('m/d/Y'));
            $templateProcessor->setValue('sdate', date('m/d/Y'));
            */

            $list = [];

            /*
            OLD CODE
            $afp = Afp::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->where('Year', date('Y', strtotime('-1 Week')))
                    ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                    ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->where('Year', '<=', date('Y', strtotime('-2 Weeks')))
                    ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                    ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            });
            */

            $current_mw = PidsrController::getMonitoringMw()->getPreviousWeek();
            $previous_mw = PidsrController::getMonitoringMw()->getPreviousWeek()->getPreviousWeek();
            $sel_year = $current_mw->year;
            $sel_week = $current_mw->mw;

            /*
            if(date('W') == 01) {
                $sel_year = date('Y') - 1;
                $sel_week = 52;
            }
            else if(date('W') == 02) { //To Avoid First Week Submittal Year Bug
                $sel_year = date('Y');
                $sel_week = 1;
            }
            else {
                $sel_year = date('Y', strtotime('-1 Week'));
                $sel_week = Carbon::now()->week - 1;
            }
            */

            $afp = Afp::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($afp->count() != 0) {
                $l = $afp->get();
                $get_type = 'Acute Flaccid Paralysis';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $afp_count = $afp->count();

            $afp_update = $afp->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            //$aefi_count = 0;

            $aefi = Aefi::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($aefi->count() != 0) {
                $l = $aefi->get();
                $get_type = 'AEFI';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */
                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DAdmit,
                        'aefi_type' => $i->Kaso,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $aefi_count = $aefi->count();

            $aefi_update = $aefi->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);
            

            $ant = Anthrax::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($ant->count() != 0) {
                $l = $ant->get();
                $get_type = 'Anthrax';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $ant_count = $ant->count();

            $ant_update = $ant->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $hai_count = 0; 

            $mea = Measles::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($mea->count() != 0) {
                $l = $mea->get();
                $get_type = 'Measles';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        'sx' => $i->listSymptoms(),
                        'name_of_parentcaregiver' => $i->name_of_parentcaregiver,
                        'parent_contactno' => $i->parent_contactno,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $mea_count = $mea->count();

            $mea_update = $mea->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $mgc = Meningo::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($mgc->count() != 0) {
                $l = $mgc->get();
                $get_type = 'Meningococcal Disease';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $mgc_count = $mgc->count();

            $mgc_update = $mgc->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $nt = Nt::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($nt->count() != 0) {
                $l = $nt->get();
                $get_type = 'Neonatal Tetanus';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $nt_count = $nt->count();

            $nt_update = $nt->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $psp = Psp::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($psp->count() != 0) {
                $l = $psp->get();
                $get_type = 'Paralytic Shellfish Poisoning';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $psp_count = $psp->count();

            $psp_update = $psp->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $rab = Rabies::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($rab->count() != 0) {
                $l = $rab->get();
                $get_type = 'Rabies';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $rab_count = $rab->count();
            
            $rab_update = $rab->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);
            
            $sar_count = 0;

            $abd = Abd::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($abd->count() != 0) {
                $l = $abd->get();
                $get_type = 'Acute Bloody Diarrhea';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $abd_count = $abd->count();

            $abd_update = $abd->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $aes = Aes::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($aes->count() != 0) {
                $l = $aes->get();
                $get_type = 'Acute Encephalitis Syndrome';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $aes_count = $aes->count();

            $aes_update = $aes->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $ahf = Ahf::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($ahf->count() != 0) {
                $l = $ahf->get();
                $get_type = 'Acute Hemorrhagic Fever Syndrome';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $ahf_count = $ahf->count();

            $ahf_update = $ahf->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $hep = Hepatitis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($hep->count() != 0) {
                $l = $hep->get();
                $get_type = 'Acute Viral Hepatitis';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $hep_count = $hep->count();

            $hep_update = $hep->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $ame = Ames::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($ame->count() != 0) {
                $l = $ame->get();
                $get_type = 'AMES';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $ame_count = $ame->count();

            $ame_update = $ame->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $mgt = Meningitis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);
            
            if($mgt->count() != 0) {
                $l = $mgt->get();
                $get_type = 'Bacterial Meningitis';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $mgt_count = $mgt->count();

            $mgt_update = $mgt->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $chi = Chikv::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($chi->count() != 0) {
                $l = $chi->get();
                $get_type = 'Chikungunya';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $chi_count = $chi->count();

            $chi_update = $chi->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $cho = Cholera::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($cho->count() != 0) {
                $l = $cho->get();
                $get_type = 'Cholera';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $cho_count = $cho->count();

            $cho_update = $cho->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $den = Dengue::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($den->count() != 0) {
                $l = $den->get();
                $get_type = 'Dengue';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'id' => $i->id,
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                        'cc' => $i->getClassificationString(),
                    ]);
                }
            }

            $den_count = $den->count();

            $den_update = $den->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $dip = Diph::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($dip->count() != 0) {
                $l = $dip->get();
                $get_type = 'Diphtheria';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $dip_count = $dip->count();

            $dip_update = $dip->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $ili = Influenza::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($ili->count() != 0) {
                $l = $ili->get();
                $get_type = 'Influenza-like Illness';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $ili_count = $ili->count();

            $ili_update = $ili->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $lep = Leptospirosis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($lep->count() != 0) {
                $l = $lep->get();
                $get_type = 'Leptospirosis';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $lep_count = $lep->count();

            $lep_update = $lep->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $mal = Malaria::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($mal->count() != 0) {
                $l = $mal->get();
                $get_type = 'Malaria';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $mal_count = $mal->count();

            $mal_update = $mal->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $nnt = Nnt::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($nnt->count() != 0) {
                $l = $nnt->get();
                $get_type = 'Non-Neonatal Tetanus';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $nnt_count = $nnt->count();

            $nnt_update = $nnt->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $per = Pert::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($per->count() != 0) {
                $l = $per->get();
                $get_type = 'Pertussis';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $per_count = $per->count();

            $per_update = $per->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $rtv = Rotavirus::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($rtv->count() != 0) {
                $l = $rtv->get();
                $get_type = 'RotaVirus';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $rtv_count = $rtv->count();

            $rtv_update = $rtv->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $typ = Typhoid::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($typ->count() != 0) {
                $l = $typ->get();
                $get_type = 'Typhoid and Parathyphoid Fever';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $typ_count = $typ->count();

            $typ_update = $typ->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $hfm = Hfmd::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('MorbidityWeek', '<=', $sel_week);

            if($hfm->count() != 0) {
                $l = $hfm->get();
                $get_type = 'Hfmd';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
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
                    */

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->displayAgeStringToReport(),
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => (!is_null($i->Streetpurok)) ? mb_strtoupper($i->Streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->DateOfEntry,
                        'dru' => $i->NameOfDru,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $hfm_count = $hfm->count();

            $hfm_update = $hfm->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $sari = SevereAcuteRespiratoryInfection::where('province', 'CAVITE')
            ->where('muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where('enabled', 1)
            ->where('match_casedef', 1)
            ->where('Year', $sel_year)
            ->where('morbidity_week', '<=', $sel_week);

            if($sari->count() != 0) {
                $l = $sari->get();
                $get_type = 'Severe Acute Respiratory Infection';

                //$lab_array = [];

                foreach($l as $i) {
                    //Check Lab Details
                    $getLabDetails = PIDSRController::getLabDetails($i->epi_id, $i->edcs_caseid);

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
                        'name' => $i->getFullName(),
                        'age' => $i->age_years,
                        'sex' => substr($i->sex,0,1),
                        'brgy' => $i->barangay,
                        'address' => (!is_null($i->streetpurok)) ? mb_strtoupper($i->streetpurok) : 'NO ADDRESS ENCODED',
                        'doe' => $i->created_at,
                        'dru' => $i->facility_name,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'mobile' => $i->edcs_patientcontactnum,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $sari_count = $sari->count();

            $sari_update = $sari->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            $covid = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('systemsent', 0)
            ->where('Year', $sel_year)
            ->where('morb_week', '<=', $sel_week);

            if($covid->count() != 0) {
                $l = $covid->get();
                $get_type = 'COVID-19';

                //$lab_array = [];

                foreach($l as $i) {
                    /*
                    //Check Lab Details
                    $getLabDetails = PIDSRController::getLabDetails($i->epi_id, $i->edcs_caseid);

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

                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->records->getName(),
                        'age' => $i->age_years,
                        'sex' => substr($i->records->gender,0,1),
                        'brgy' => $i->records->address_brgy,
                        'address' => $i->records->getStreetPurok(),
                        'doe' => ($i->from_tkc == 1) ? $i->dateReported : $i->created_at,
                        'dru' => $i->drunit,
                        'early_sent' => ($i->notify_email_sent == 1) ? '(SENT EARLIER)' : '',
                        'cc' => $i->getClassificationString(),
                        'mobile' => $i->records->mobile,
                        //'lab_data' => $lab_array,
                    ]);
                }
            }

            $covid_count = $covid->count();

            $covid_update = $covid->update([
                'systemsent' => 1,
                'notify_email_sent' => 1,
                'notify_email_sent_datetime' => date('Y-m-d H:i:s'),
                'encoded_mw' => (date('W') - 1),
            ]);

            //Category 1
            /*
            $templateProcessor->setValue('afp', $afp_count);
            $templateProcessor->setValue('aef', $aefi_count);
            $templateProcessor->setValue('ant', $ant_count);
            $templateProcessor->setValue('inf', $hai_count); //0
            $templateProcessor->setValue('mea', $mea_count);
            $templateProcessor->setValue('mgc', $mgc_count);
            $templateProcessor->setValue('nt', $nt_count);
            $templateProcessor->setValue('psp', $psp_count);
            $templateProcessor->setValue('rab', $rab_count);
            $templateProcessor->setValue('sar', 0); //0
            $templateProcessor->setValue('hfm', $hfm_count);
            */

            //Category 2
            /*
            $templateProcessor->setValue('abd', $abd_count);
            $templateProcessor->setValue('aes', $aes_count);
            $templateProcessor->setValue('ahf', $ahf_count);
            $templateProcessor->setValue('hep', $hep_count);
            $templateProcessor->setValue('ame', $ame_count);
            $templateProcessor->setValue('mgt', $mgt_count);
            $templateProcessor->setValue('chi', $chi_count);
            $templateProcessor->setValue('cho', $cho_count);
            $templateProcessor->setValue('den', $den_count);
            $templateProcessor->setValue('dip', $dip_count);
            $templateProcessor->setValue('ili', $ili_count);
            $templateProcessor->setValue('lep', $lep_count);
            $templateProcessor->setValue('mal', $mal_count);
            $templateProcessor->setValue('nnt', $nnt_count);
            $templateProcessor->setValue('per', $per_count);
            $templateProcessor->setValue('rtv', $rtv_count);
            $templateProcessor->setValue('typ', $typ_count);

            $templateProcessor->saveAs(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.docx'));
            
            $phpWord = WordFactory::load(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.docx'));
            $xmlWriter = WordFactory::createWriter($phpWord, 'PDF');
            $xmlWriter->save(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.pdf'));
            */

            //Add new EDCS Excel
            $spreadsheet = ExcelFactory::load(storage_path('EDCS_SUMMARY.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A13', $afp_count);
            $sheet->setCellValue('A14', $dip_count);
            $sheet->setCellValue('A15', $mea_count);
            $sheet->setCellValue('A16', $nnt_count);
            $sheet->setCellValue('A17', $nt_count);
            $sheet->setCellValue('A18', $per_count);

            $sheet->setCellValue('S13', $chi_count);
            $sheet->setCellValue('S14', $den_count);
            $sheet->setCellValue('S15', $lep_count);
            $sheet->setCellValue('S16', $rab_count);

            $sheet->setCellValue('A23', $abd_count);
            $sheet->setCellValue('A24', $hep_count);
            $sheet->setCellValue('A25', $cho_count);
            $sheet->setCellValue('A26', $rtv_count);
            $sheet->setCellValue('A27', $typ_count);

            $sheet->setCellValue('S23', $ili_count);
            $sheet->setCellValue('S24', $ame_count);
            $sheet->setCellValue('S25', $hfm_count);
            $sheet->setCellValue('S26', $mgc_count);
            $sheet->setCellValue('S27', $sari_count);

            $sheet->setCellValue('F33', date('m/d/Y'));
            $sheet->setCellValue('Y6', date('F d, Y')." MW{$sel_week}");

            $writer = new Xlsx($spreadsheet);
            $writer->save(public_path("EDCS_SUMMARY_GENERALTRIASCITY_MW{$sel_week}_{$sel_year}.xlsx"));

            Mail::to(['cjh687332@gmail.com', 'cesu.gentrias@gmail.com', 'carlofloralde222@gmail.com',])->send(new PidsrWndrMail($list));

            //File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-2 Weeks')).'.pdf'));
            //File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-2 Weeks')).'.docx'));
            
            File::delete(public_path("EDCS_SUMMARY_GENERALTRIASCITY_MW{$previous_mw->mw}_{$previous_mw->year}.xlsx"));
        }
        else {
            $s->pidsr_early_sent = 0;
            if($s->isDirty()) {
                $s->save();
            }
        }
    }
}
