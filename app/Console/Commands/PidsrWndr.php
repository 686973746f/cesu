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

        $afp = Afp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $afp += Afp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $aefi = 0;

        $ant = Anthrax::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $ant += Anthrax::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $hai = 0; 

        $mea = Measles::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $mea += Measles::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $mgc = Meningo::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $mgc += Meningo::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $nt = Nt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $nt += Nt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $psp = Psp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $psp += Psp::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $rab = Rabies::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $rab += Rabies::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $sar = 0;

        $abd = Abd::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $abd += Abd::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $aes = Aes::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $aes += Aes::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $ahf = Ahf::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $ahf += Ahf::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $hep = Hepatitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $hep += Hepatitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $ame = Ames::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $ame += Ames::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $mgt = Meningitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $mgt += Meningitis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $chi = Chikv::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $chi += Chikv::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $cho = Cholera::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $cho += Cholera::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $den = Dengue::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $den += Dengue::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $dip = Diph::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $dip += Diph::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $ili = Influenza::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $ili += Influenza::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $lep = Leptospirosis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $lep += Leptospirosis::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $mal = Malaria::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $mal += Malaria::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $nnt = Nnt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $nnt += Nnt::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $per = Pert::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $per += Pert::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $rtv = Rotavirus::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $rtv += Rotavirus::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $typ = Typhoid::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $typ += Typhoid::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

        $hfm = Hfmd::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('Year', date('Y'))
        ->where('MorbidityMonth', date('n', strtotime('-1 Week')))
        ->where('MorbidityWeek', date('W', strtotime('-1 Week')))
        ->count();

        $hfm += Hfmd::where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS')
        ->where('MorbidityMonth', '<=', date('n', strtotime('-2 Weeks')))
        ->where('MorbidityWeek', '<=', date('W', strtotime('-2 Weeks')))
        ->whereRaw('WEEK(created_at) >= ' . date('W', strtotime('-1 Week')))
        ->count();

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

        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new PidsrWndrMail());

        File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.pdf'));
        File::delete(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.docx'));
    }
}
