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

        $afp = Afp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $ant = Anthrax::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $inf = Influenza::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $mea = Measles::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $mgc = Meningo::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $nt = Nt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $psp = Psp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $rab = Rabies::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $sar = Rabies::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $abd = Abd::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $aes = Aes::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $ahf = Ahf::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $hep = Hepatitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $ame = Ames::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $mgt = Meningitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $chi = Chikv::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $cho = Cholera::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $den = Dengue::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $dip = Diph::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $ili = 0;

        $lep = Leptospirosis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $mal = Malaria::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $nnt = Nnt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $per = Pert::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $rtv = Rotavirus::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        $typ = Typhoid::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n'))
        ->where('MorbidityWeek', date('W'))
        ->count();

        //Category 1
        $templateProcessor->setValue('afp', $afp);
        $templateProcessor->setValue('aef', 0);
        $templateProcessor->setValue('ant', $ant);
        $templateProcessor->setValue('inf', $inf);
        $templateProcessor->setValue('mea', $mea);
        $templateProcessor->setValue('mgc', $mgc);
        $templateProcessor->setValue('nt', $nt);
        $templateProcessor->setValue('psp', $psp);
        $templateProcessor->setValue('rab', $rab);
        $templateProcessor->setValue('sar', 0);

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


        $templateProcessor->saveAs(public_path('PIDSR_GenTrias_'.date('Y_m_d').'.docx'));
        
        $phpWord = IOFactory::load(public_path('PIDSR_GenTrias_'.date('Y_m_d').'.docx'));
        $xmlWriter = IOFactory::createWriter($phpWord, 'PDF');
        $xmlWriter->save(public_path('PIDSR_GenTrias_'.date('Y_m_d').'.pdf'));

        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new PidsrWndrMail());

        File::delete(public_path('PIDSR_GenTrias_'.date('Y_m_d', strtotime('-1 Day')).'.pdf'));
        File::delete(public_path('PIDSR_GenTrias_'.date('Y_m_d', strtotime('-1 Day')).'.docx'));
    }
}
