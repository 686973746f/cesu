<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCTReport extends Mailable
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
        return $this->view('email.coviddb')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CT Report for '.date('F d, Y'))
        ->attach(public_path('CTREPORT_'.date('m_d_Y').'.xlsx'));
    }
}
