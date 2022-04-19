<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCompositeMeasure extends Mailable
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
        return $this->view('email.compositemeasure')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CESU Gen. Trias, Cavite - Composite Measure for '.date('F d, Y'))
        ->attach(public_path('GEN.TRIAS-DILG-CHO-REPORT-'.date('F-d-Y').'.xlsx'));
    }
}
