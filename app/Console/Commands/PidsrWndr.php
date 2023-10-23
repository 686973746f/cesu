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
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\TemplateProcessor;

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

        if($s->pidsr_early_sent == 0) {
            Settings::setPdfRendererName('MPDF');
            Settings::setPdfRendererPath(base_path() . '/vendor/mpdf/mpdf');
            
            $templateProcessor  = new TemplateProcessor(storage_path('WNDR.docx'));
            
            $templateProcessor->setValue('mweek', date('W', strtotime('-1 Week')));
            $templateProcessor->setValue('myear', date('Y', strtotime('-1 Week')));

            $templateProcessor->setValue('pdate', date('m/d/Y'));
            $templateProcessor->setValue('adate', date('m/d/Y'));
            $templateProcessor->setValue('sdate', date('m/d/Y'));

            $list = [];

            $afp = Afp::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($afp->count() != 0) {
                $l = $afp->get();
                $get_type = 'Acute Flaccid Paralysis';

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
            }

            $afp_count = $afp->count();

            $afp_update = $afp->update(['systemsent' => 1]);

            //$aefi_count = 0;

            $aefi = Aefi::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
            ->where(function ($q) {
                $q->where(function ($r) {
                    $r->whereYear('DAdmit', date('Y', strtotime('-1 Week')))
                    ->whereMonth('DAdmit', date('n', strtotime('-1 Week')))
                    ->where(DB::raw("WEEKOFYEAR(DAdmit)"), date('W', strtotime('-1 Week')));
                })->orWhere(function ($r) {
                    $r->whereYear('DAdmit', '<=', date('Y', strtotime('-2 Weeks')))
                    ->whereMonth('DAdmit', '<=', date('n', strtotime('-2 Weeks')))
                    ->where(DB::raw("WEEKOFYEAR(DAdmit)"), '<=', date('W', strtotime('-2 Weeks')))
                    ->where('created_at', '>=', Carbon::now()->previous(Carbon::TUESDAY)->setTime(11,0,0)->toDateString());
                });
            });

            if($aefi->count() != 0) {
                $l = $aefi->get();
                $get_type = 'AEFI';

                foreach($l as $i) {
                    array_push($list, [
                        'type' => $get_type,
                        'name' => $i->FullName,
                        'age' => $i->AgeYears,
                        'sex' => $i->Sex,
                        'brgy' => $i->Barangay,
                        'address' => $i->Streetpurok,
                        'doe' => $i->DAdmit,
                        'aefi_type' => $i->Kaso,
                        'dru' => $i->NameOfDru,
                    ]);
                }
            }

            $aefi_count = $aefi->count();

            $aefi_update = $aefi->update(['systemsent' => 1]);
            

            $ant = Anthrax::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($ant->count() != 0) {
                $l = $ant->get();
                $get_type = 'Anthrax';

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
            }

            $ant_count = $ant->count();

            $ant_update = $ant->update(['systemsent' => 1]);

            $hai_count = 0; 

            $mea = Measles::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($mea->count() != 0) {
                $l = $mea->get();
                $get_type = 'Measles';

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
            }

            $mea_count = $mea->count();

            $mea_update = $mea->update(['systemsent' => 1]);

            $mgc = Meningo::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($mgc->count() != 0) {
                $l = $mgc->get();
                $get_type = 'Meningococcal Disease';

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
            }

            $mgc_count = $mgc->count();

            $mgc_update = $mgc->update(['systemsent' => 1]);

            $nt = Nt::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($nt->count() != 0) {
                $l = $nt->get();
                $get_type = 'Neonatal Tetanus';

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
            }

            $nt_count = $nt->count();

            $nt_update = $nt->update(['systemsent' => 1]);

            $psp = Psp::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($psp->count() != 0) {
                $l = $psp->get();
                $get_type = 'Paralytic Shellfish Poisoning';

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
            }

            $psp_count = $psp->count();

            $psp_update = $psp->update(['systemsent' => 1]);

            $rab = Rabies::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($rab->count() != 0) {
                $l = $rab->get();
                $get_type = 'Rabies';

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
            }

            $rab_count = $rab->count();
            
            $rab_update = $rab->update(['systemsent' => 1]);
            
            $sar_count = 0;

            $abd = Abd::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($abd->count() != 0) {
                $l = $abd->get();
                $get_type = 'Acute Bloody Diarrhea';

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
            }

            $abd_count = $abd->count();

            $abd_update = $abd->update(['systemsent' => 1]);

            $aes = Aes::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($aes->count() != 0) {
                $l = $aes->get();
                $get_type = 'Acute Encephalitis Syndrome';

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
            }

            $aes_count = $aes->count();

            $aes_update = $aes->update(['systemsent' => 1]);

            $ahf = Ahf::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($ahf->count() != 0) {
                $l = $ahf->get();
                $get_type = 'Acute Hemorrhagic Fever Syndrome';

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
            }

            $ahf_count = $ahf->count();

            $ahf_update = $ahf->update(['systemsent' => 1]);

            $hep = Hepatitis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($hep->count() != 0) {
                $l = $hep->get();
                $get_type = 'Acute Viral Hepatitis';

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
            }

            $hep_count = $hep->count();

            $hep_update = $hep->update(['systemsent' => 1]);

            $ame = Ames::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($ame->count() != 0) {
                $l = $ame->get();
                $get_type = 'AMES';

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
            }

            $ame_count = $ame->count();

            $ame_update = $ame->update(['systemsent' => 1]);

            $mgt = Meningitis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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
            
            if($mgt->count() != 0) {
                $l = $mgt->get();
                $get_type = 'Bacterial Meningitis';

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
            }

            $mgt_count = $mgt->count();

            $mgt_update = $mgt->update(['systemsent' => 1]);

            $chi = Chikv::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($chi->count() != 0) {
                $l = $chi->get();
                $get_type = 'Chikungunya';

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
            }

            $chi_count = $chi->count();

            $chi_update = $chi->update(['systemsent' => 1]);

            $cho = Cholera::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($cho->count() != 0) {
                $l = $cho->get();
                $get_type = 'Cholera';

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
            }

            $cho_count = $cho->count();

            $cho_update = $cho->update(['systemsent' => 1]);

            $den = Dengue::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($den->count() != 0) {
                $l = $den->get();
                $get_type = 'Dengue';

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
            }

            $den_count = $den->count();

            $den_update = $den->update(['systemsent' => 1]);

            $dip = Diph::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($dip->count() != 0) {
                $l = $dip->get();
                $get_type = 'Diphtheria';

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
            }

            $dip_count = $dip->count();

            $dip_update = $dip->update(['systemsent' => 1]);

            $ili = Influenza::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($ili->count() != 0) {
                $l = $ili->get();
                $get_type = 'Influenza-like Illness';

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
            }

            $ili_count = $ili->count();

            $ili_update = $ili->update(['systemsent' => 1]);

            $lep = Leptospirosis::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($lep->count() != 0) {
                $l = $lep->get();
                $get_type = 'Leptospirosis';

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
            }

            $lep_count = $lep->count();

            $lep_update = $lep->update(['systemsent' => 1]);

            $mal = Malaria::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($mal->count() != 0) {
                $l = $mal->get();
                $get_type = 'Malaria';

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
            }

            $mal_count = $mal->count();

            $mal_update = $mal->update(['systemsent' => 1]);

            $nnt = Nnt::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($nnt->count() != 0) {
                $l = $nnt->get();
                $get_type = 'Non-Neonatal Tetanus';

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
            }

            $nnt_count = $nnt->count();

            $nnt_update = $nnt->update(['systemsent' => 1]);

            $per = Pert::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($per->count() != 0) {
                $l = $per->get();
                $get_type = 'Pertussis';

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
            }

            $per_count = $per->count();

            $per_update = $per->update(['systemsent' => 1]);

            $rtv = Rotavirus::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($rtv->count() != 0) {
                $l = $rtv->get();
                $get_type = 'RotaVirus';

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
            }

            $rtv_count = $rtv->count();

            $rtv_update = $rtv->update(['systemsent' => 1]);

            $typ = Typhoid::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($typ->count() != 0) {
                $l = $typ->get();
                $get_type = 'Typhoid and Parathyphoid Fever';

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
            }

            $typ_count = $typ->count();

            $typ_update = $typ->update(['systemsent' => 1]);

            $hfm = Hfmd::where('Province', 'CAVITE')
            ->where('Muncity', 'GENERAL TRIAS')
            ->where('systemsent', 0)
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

            if($hfm->count() != 0) {
                $l = $hfm->get();
                $get_type = 'Hfmd';

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
            }

            $hfm_count = $hfm->count();

            $hfm_update = $hfm->update(['systemsent' => 1]);

            //Category 1
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

            //Category 2
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
            
            $phpWord = IOFactory::load(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.docx'));
            $xmlWriter = IOFactory::createWriter($phpWord, 'PDF');
            $xmlWriter->save(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.pdf'));

            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new PidsrWndrMail($list));

            File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-2 Weeks')).'.pdf'));
            File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-2 Weeks')).'.docx'));
        }
        else {
            $s->pidsr_early_sent = 0;
            if($s->isDirty()) {
                $s->save();
            }
        }
    }
}
