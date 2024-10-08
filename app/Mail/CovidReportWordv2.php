<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CovidReportWordv2 extends Mailable
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
        return $this->view('email.covidreport')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('COVID Gentri v2 '.date('F d, Y'))
        ->attach(public_path('CITY-OF-GENERAL-TRIAS-NEW-'.date('F-d-Y').'.docx'));
    }
}
