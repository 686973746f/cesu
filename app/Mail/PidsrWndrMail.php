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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($list)
    {
        $this->list = $list;
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
        ->subject('CESU GEN. TRIAS AutoMailer - EDCS Weekly Report for MW'.date('W', strtotime('-1 Week')))
        ->attach(public_path('EDCS_SUMMARY_GENERALTRIASCITY_MW'.date('W').'.xlsx'));
    }
}
