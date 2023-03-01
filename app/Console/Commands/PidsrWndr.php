<?php

namespace App\Console\Commands;

use App\Models\Nt;
use App\Models\Abd;
use App\Models\Aes;
use App\Models\Afp;
use App\Models\Ahf;
use App\Models\Nnt;
use App\Models\Psp;
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
use App\Models\Leptospirosis;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Console\Command;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
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
    protected $signature = 'pisdrwndr:weekly';

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

        Settings::setPdfRendererName('MPDF');
        Settings::setPdfRendererPath(base_path() . '/vendor/mpdf/mpdf');
        
        $templateProcessor  = new TemplateProcessor(storage_path('WNDR.docx'));
        
        $templateProcessor->setValue('mweek', date('W'));
        $templateProcessor->setValue('myear', date('Y'));

        $templateProcessor->setValue('pdate', date('m/d/Y'));
        $templateProcessor->setValue('adate', date('m/d/Y'));
        $templateProcessor->setValue('sdate', date('m/d/Y'));

        $list = [];

        $afp = Afp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $afp = $afp->count();

        $aefi = 0;

        $ant = Anthrax::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $ant = $ant->count();

        $hai = 0; 

        $mea = Measles::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $mea = $mea->count();

        $mgc = Meningo::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $mgc = $mgc->count();

        $nt = Nt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $nt = $nt->count();

        $psp = Psp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $psp = $psp->count();

        $rab = Rabies::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $rab = $rab->count();

        $sar = 0;

        $abd = Abd::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $abd = $abd->count();

        $aes = Aes::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $aes = $aes->count();

        $ahf = Ahf::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $ahf = $ahf->count();

        $hep = Hepatitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $hep = $hep->count();

        $ame = Ames::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $ame = $ame->count();

        $mgt = Meningitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $mgt = $mgt->count();

        $chi = Chikv::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $chi = $chi->count();

        $cho = Cholera::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $cho = $cho->count();

        $den = Dengue::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $den = $den->count();

        $dip = Diph::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $dip = $dip->count();

        $ili = Influenza::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $ili = $ili->count();

        $lep = Leptospirosis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $lep = $lep->count();

        $mal = Malaria::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $mal = $mal->count();

        $nnt = Nnt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $nnt = $nnt->count();

        $per = Pert::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $per = $per->count();

        $rtv = Rotavirus::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $rtv = $rtv->count();

        $typ = Typhoid::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $typ = $typ->count();

        $hfm = Hfmd::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->where('Year', date('Y'))
                ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
                ->where('MorbidityWeek', date('W', strtotime('-1 Week')));
            })->orWhere(function ($r) {
                $r->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
                ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
                ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')));
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
                    'address' => $i->Barangay.', '.$i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                ]);
            }
        }

        $hfm = $hfm->count();

        //Category 1
        $templateProcessor->setValue('afp', $afp);
        $templateProcessor->setValue('aef', $aefi); //0
        $templateProcessor->setValue('ant', $ant);
        $templateProcessor->setValue('inf', $hai); //0
        $templateProcessor->setValue('mea', $mea);
        $templateProcessor->setValue('mgc', $mgc);
        $templateProcessor->setValue('nt', $nt);
        $templateProcessor->setValue('psp', $psp);
        $templateProcessor->setValue('rab', $rab);
        $templateProcessor->setValue('sar', 0); //0
        $templateProcessor->setValue('hfm', $hfm);

        //Category 2
        $templateProcessor->setValue('abd', $abd);
        $templateProcessor->setValue('aes', $aes);
        $templateProcessor->setValue('ahf', $ahf);
        $templateProcessor->setValue('hep', $hep);
        $templateProcessor->setValue('ame', $ame);
        $templateProcessor->setValue('mgt', $mgt);
        $templateProcessor->setValue('chi', $chi);
        $templateProcessor->setValue('cho', $cho);
        $templateProcessor->setValue('den', $den);
        $templateProcessor->setValue('dip', $dip);
        $templateProcessor->setValue('ili', $ili);
        $templateProcessor->setValue('lep', $lep);
        $templateProcessor->setValue('mal', $mal);
        $templateProcessor->setValue('nnt', $nnt);
        $templateProcessor->setValue('per', $per);
        $templateProcessor->setValue('rtv', $rtv);
        $templateProcessor->setValue('typ', $typ);


        $templateProcessor->saveAs(public_path('PIDSR_GenTrias_MW'.date('W').'.docx'));
        
        $phpWord = IOFactory::load(public_path('PIDSR_GenTrias_MW'.date('W').'.docx'));
        $xmlWriter = IOFactory::createWriter($phpWord, 'PDF');
        $xmlWriter->save(public_path('PIDSR_GenTrias_MW'.date('W').'.pdf'));

        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new PidsrWndrMail($list));

        File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.pdf'));
        File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.docx'));
    }
}
