<?php

namespace App\Console\Commands;

use App\Models\Nt;
use App\Models\Afp;
use App\Models\Nnt;
use App\Models\Diph;
use App\Models\Pert;
use App\Mail\VpdMailer;
use App\Models\Measles;
use Illuminate\Console\Command;
use App\Models\PidsrNotifications;
use Illuminate\Support\Facades\Mail;

class VpdNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vpdnotifier:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vaccine Preventable Diseases Notifier';

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
        AFP
        DIPH
        MEASLES
        NT
        NNT
        PERT
        */

        $diph_array = [];
        $measles_array = [];
        $afp_array = [];
        $pert_array = [];
        $nnt_array = [];
        $nt_array = [];

        $diph_search = Diph::where('enabled', 1)
        ->where('match_casedef', 1)
        ->whereDate('created_at', date('Y-m-d'))
        ->where('system_notified', 0)
        ->where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS');
        
        if($diph_search->count() != 0) {
            $l = $diph_search->get();

            foreach($l as $i) {
                array_push($diph_array, [
                    'name' => $i->FullName,
                    'age' => $i->AgeYears,
                    'sex' => $i->Sex,
                    'brgy' => $i->Barangay,
                    'address' => $i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                    'dru' => $i->NameOfDru,
                ]);

                PidsrNotifications::create([
                    'disease' => 'DIPHTHERIA',
                    'disease_id' => $l->EPIID,
                    'message' => 'CASE DETECTED',
                ]);
            }
        }

        $diph_update = $diph_search->update([
            'system_notified' => 1,
        ]);
        
        //

        $measles_search = Measles::where('enabled', 1)
        ->where('match_casedef', 1)
        ->whereDate('created_at', date('Y-m-d'))
        ->where('system_notified', 0)
        ->where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS');
        
        if($measles_search->count() != 0) {
            $l = $measles_search->get();

            foreach($l as $i) {
                array_push($measles_array, [
                    'name' => $i->FullName,
                    'age' => $i->AgeYears,
                    'sex' => $i->Sex,
                    'brgy' => $i->Barangay,
                    'address' => $i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                    'dru' => $i->NameOfDru,
                ]);

                PidsrNotifications::create([
                    'disease' => 'MEASLES',
                    'disease_id' => $l->EPIID,
                    'message' => 'CASE DETECTED',
                ]);
            }
        }

        $measles_update = $measles_search->update([
            'system_notified' => 1,
        ]);
        
        //

        $afp_search = Afp::where('enabled', 1)
        ->where('match_casedef', 1)
        ->whereDate('created_at', date('Y-m-d'))
        ->where('system_notified', 0)
        ->where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS');
        
        if($afp_search->count() != 0) {
            $l = $afp_search->get();

            foreach($l as $i) {
                array_push($afp_array, [
                    'name' => $i->FullName,
                    'age' => $i->AgeYears,
                    'sex' => $i->Sex,
                    'brgy' => $i->Barangay,
                    'address' => $i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                    'dru' => $i->NameOfDru,
                ]);

                PidsrNotifications::create([
                    'disease' => 'AFP',
                    'disease_id' => $l->EPIID,
                    'message' => 'CASE DETECTED',
                ]);
            }
        }

        $afp_update = $afp_search->update([
            'system_notified' => 1,
        ]);
        
        //

        $pert_search = Pert::where('enabled', 1)
        ->where('match_casedef', 1)
        ->whereDate('created_at', date('Y-m-d'))
        ->where('system_notified', 0)
        ->where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS');
        
        if($pert_search->count() != 0) {
            $l = $pert_search->get();

            foreach($l as $i) {
                array_push($pert_array, [
                    'name' => $i->FullName,
                    'age' => $i->AgeYears,
                    'sex' => $i->Sex,
                    'brgy' => $i->Barangay,
                    'address' => $i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                    'dru' => $i->NameOfDru,
                ]);

                PidsrNotifications::create([
                    'disease' => 'PERTUSSIS',
                    'disease_id' => $l->EPIID,
                    'message' => 'CASE DETECTED',
                ]);
            }
        }

        $pert_update = $pert_search->update([
            'system_notified' => 1,
        ]);
        
        //

        $nnt_search = Nnt::where('enabled', 1)
        ->where('match_casedef', 1)
        ->whereDate('created_at', date('Y-m-d'))
        ->where('system_notified', 0)
        ->where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS');
        
        if($nnt_search->count() != 0) {
            $l = $nnt_search->get();

            foreach($l as $i) {
                array_push($nnt_array, [
                    'name' => $i->FullName,
                    'age' => $i->AgeYears,
                    'sex' => $i->Sex,
                    'brgy' => $i->Barangay,
                    'address' => $i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                    'dru' => $i->NameOfDru,
                ]);

                PidsrNotifications::create([
                    'disease' => 'NNT',
                    'disease_id' => $l->EPIID,
                    'message' => 'CASE DETECTED',
                ]);
            }
        }

        $nnt_update = $nnt_search->update([
            'system_notified' => 1,
        ]);
        
        //

        $nt_search = Nt::where('enabled', 1)
        ->where('match_casedef', 1)
        ->whereDate('created_at', date('Y-m-d'))
        ->where('system_notified', 0)
        ->where('Province', 'CAVITE')
        ->where('Muncity', 'GENERAL TRIAS');
        
        if($nt_search->count() != 0) {
            $l = $nt_search->get();

            foreach($l as $i) {
                array_push($nt_array, [
                    'name' => $i->FullName,
                    'age' => $i->AgeYears,
                    'sex' => $i->Sex,
                    'brgy' => $i->Barangay,
                    'address' => $i->Streetpurok,
                    'doe' => $i->DateOfEntry,
                    'dru' => $i->NameOfDru,
                ]);

                PidsrNotifications::create([
                    'disease' => 'NT',
                    'disease_id' => $l->EPIID,
                    'message' => 'CASE DETECTED',
                ]);
            }
        }

        $nt_update = $nt_search->update([
            'system_notified' => 1,
        ]);

        if(!empty($diph_array) || !empty($measles_array) || !empty($afp_array) || !empty($pert_array) || !empty($nnt_array) || !empty($nt_array)) {
            Mail::to(['cjh687332@gmail.com', 'cesu.gentrias@gmail.com'])->send(new VpdMailer($diph_array, $measles_array, $afp_array, $pert_array, $nnt_array, $nt_array));
        }
    }
}
