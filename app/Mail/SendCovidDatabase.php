<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCovidDatabase extends Mailable
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
        ->subject('CHO General Trias - COVID-19 Database for '.date('F d, Y'))
        ->attach(public_path('GENTRI_COVID19_DATABASE_'.date('m_d_Y').'.xlsx'));
    }
}
