<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Forms;
use App\Models\WorkTask;
use App\Models\LiveBirth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\VaxcertConcern;
use App\Models\SyndromicRecords;
use App\Models\AbtcBakunaRecords;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\PIDSRController;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEncoderStatus extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $list = User::where('encoder_stats_visible', 1)
        ->where('enabled', 1)
        ->orderBy('name', 'ASC')
        ->get();
        
        $arr = [];
        foreach($list as $item) {
            /*
            $suspected_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->where(function ($q) {
                $q->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
            })
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->count();
            */

            /*
            $confirmed_count = Forms::where(function ($q) use ($item) {
                $q->where(function ($r) use ($item) {
                    $r->where('user_id', $item->id)
                    ->where('updated_by', '!=', $item->id);
                })
                ->orWhere(function ($s) use ($item) {
                    $s->where('user_id', '!=', $item->id)
                    ->where('updated_by', $item->id);
                })
                ->orWhere(function ($t) use ($item) {
                    $t->where('user_id', $item->id)
                    ->where('updated_by', $item->id);
                });
            })
            ->whereDate('morbidityMonth', date('Y-m-d'))
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->count();
            */

            /*
            $confirmed_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->where(function ($q) {
                $q->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
            })
            ->whereDate('morbidityMonth', date('Y-m-d'))
            ->where('caseClassification', 'Confirmed')
            ->count();
            */

            $suspected_count = Forms::where('user_id', $item->id)
            ->where(function($q) {
                $q->whereDate('created_at', date('Y-m-d'));
            })
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->count();

            $confirmed_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->whereDate('morbidityMonth', date('Y-m-d'))
            ->where('caseClassification', 'Confirmed')
            ->count();

            $recovered_count = Forms::where(function ($q) use ($item) {
                $q->where(function ($r) use ($item) {
                    $r->where('user_id', $item->id)
                    ->where('updated_by', '!=', $item->id);
                })
                ->orWhere(function ($s) use ($item) {
                    $s->where('user_id', '!=', $item->id)
                    ->where('updated_by', $item->id);
                })
                ->orWhere(function ($t) use ($item) {
                    $t->where('user_id', $item->id)
                    ->where('updated_by', $item->id);
                });
            })
            ->whereDate('morbidityMonth', date('Y-m-d'))
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Recovered')
            ->count();
            
            $negative_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->where(function ($q) {
                $q->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
            })
            ->where('caseClassification', 'Non-COVID-19 Case')
            ->count();

            $covid_count_final = $suspected_count + $confirmed_count + $recovered_count + $negative_count;

            $abtc_count = AbtcBakunaRecords::where('d0_done_by', $item->id)
            ->whereDate('d0_done_date', date('Y-m-d'))
            ->count();

            $abtc_count_ff1 = AbtcBakunaRecords::where('d3_done_by', $item->id)
            ->whereDate('d3_done_date', date('Y-m-d'))
            ->count();

            $abtc_count_ff2 = AbtcBakunaRecords::where('d7_done_by', $item->id)
            ->whereDate('d7_done_date', date('Y-m-d'))
            ->count();

            $abtc_count_ff3 = AbtcBakunaRecords::where('d14_done_by', $item->id)
            ->whereDate('d14_done_date', date('Y-m-d'))
            ->count();

            $abtc_count_ff4 = AbtcBakunaRecords::where('d28_done_by', $item->id)
            ->whereDate('d28_done_date', date('Y-m-d'))
            ->count();

            $abtc_ffup_gtotal = $abtc_count_ff1 + $abtc_count_ff2 + $abtc_count_ff3 + $abtc_count_ff4;

            $vaxcert_count = VaxcertConcern::where('processed_by', $item->id)
            ->whereDate('updated_at', date('Y-m-d'))
            ->count();

            $opd_count = SyndromicRecords::where('created_by', $item->id)
            ->whereDate('created_at', date('Y-m-d'))
            ->count();

            $lcr_livebirth = LiveBirth::whereDate('created_at', date('Y-m-d'))
            ->where('created_by', $item->id)
            ->count();

            $disease_list = PIDSRController::listDiseasesTables();

            //Add Laboratory data table for counting
            $disease_list = $disease_list + [
                'EdcsLaboratoryData',
            ];

            $edcs_count = 0;

            foreach($disease_list as $d) {
                $modelClass = "App\\Models\\$d";

                $model_count = $modelClass::where('created_by', $item->id)
                ->whereDate('created_at', date('Y-m-d'))
                ->count();

                $edcs_count += $model_count;
            }

            $death_count = WorkTask::where('name', 'DAILY ENCODE OF DEATH CERTIFICATES TO FHSIS')
            ->where('finished_by', $item->id)
            ->whereDate('finished_date', date('Y-m-d'))
            ->first();

            if($death_count) {
                $death_count = $death_count->encodedcount ?: 0;
            }
            else {
                $death_count = 0;
            }

            $opdtoics_count = SyndromicRecords::where('ics_finishedby', $item->id)
            ->whereDate('ics_finished_date', date('Y-m-d'))
            ->count();

            $abtctoics_count = AbtcBakunaRecords::where('ics_finishedby', $item->id)
            ->whereDate('ics_finished_date', date('Y-m-d'))
            ->count();

            array_push($arr, [
                'name' => $item->name,
                'covid_count_final' => $covid_count_final,
                'abtc_count' => $abtc_count,
                'abtc_ffup_gtotal' => $abtc_ffup_gtotal,
                'vaxcert_count' => $vaxcert_count,
                'opd_count' => $opd_count,
                'lcr_livebirth' => $lcr_livebirth,
                'edcs_count' => $edcs_count,

                'death_count' => $death_count,
                'opdtoics_count' => $opdtoics_count,
                'abtctoics_count' => $abtctoics_count,
            ]);
        }
        
        return $this->markdown('email.encoder_stats', [
            'arr' => $arr,
        ])
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CESU Gen. Trias - Encoder Status for '.date('F d, Y'));
    }
}
