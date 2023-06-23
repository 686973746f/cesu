<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFhsisM2 extends Mailable
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
        return $this->view('email.fhsism2');
        
        return $this->view('email.fhsism2')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('eFHSIS M2 Monthy Report for '.date('F, Y'))
        ->attach(public_path('FHSIS_M2_REPORT_'.date('F_Y').'.xlsx'));
    }
}
