<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PidsrWndrMail extends Mailable
{
    use Queueable, SerializesModels;

    public $list;
    public $sel_year;
    public $sel_week;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($list, $sel_year, $sel_week)
    {
        $this->list = $list;
        $this->sel_year = $sel_year;
        $this->sel_week = $sel_week;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /*
        return $this->view('email.pidsrwndr')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CHO GENERAL TRIAS PIDSR Weekly Notifiable Diseases Report MW'.date('W', strtotime('-1 Week')))
        ->attach(public_path('PIDSR_GenTrias_MW'.date('W', strtotime('-1 Week')).'.pdf'))
        ->attach(public_path('EDCS_SUMMARY_GENERALTRIASCITY_MW'.date('W').'.xlsx'));
        */

        return $this->view('email.pidsrwndr')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject("CESU GEN. TRIAS AutoMailer - EDCS WEEKLY Report for MW{$this->sel_week}, Year {$this->sel_year}")
        ->attach(public_path("EDCS_SUMMARY_GENERALTRIASCITY_MW{$this->sel_week}_{$this->sel_year}.xlsx"));
    }
}
