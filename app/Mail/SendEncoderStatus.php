<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Forms;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
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
        $list = User::where('isAdmin', 2)
        ->where('enabled', 1)
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

            array_push($arr, [
                'name' => $item->name,
                'suspected_count' => $suspected_count,
                'confirmed_count' => $confirmed_count,
                'recovered_count' => $recovered_count,
                'negative_count' => $negative_count,
            ]);
        }
        
        return $this->markdown('email.encoder_stats', [
            'arr' => $arr,
        ])
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CESU Gen. Trias - Encoder Status for '.date(''));
    }
}
