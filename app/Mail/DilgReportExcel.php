<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DilgReportExcel extends Mailable
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
        return $this->view('email.dilgreport')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CHO DILG '.date('F d, Y'))
        ->attach(public_path('GEN.TRIAS-DILG-CHO-REPORT-'.date('F-d-Y').'.xlsx'));
    }
}
