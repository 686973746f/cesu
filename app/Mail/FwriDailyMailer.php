<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FwriDailyMailer extends Mailable
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
        return $this->view('email.fwridailyview')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('General Trias CESU - FWRI Daily Report '.date('M. d, Y'))
        ->attach(storage_path('app/fwri/CESUGENTRIAS_APIR_LINELIST_'.date('mdY', strtotime('-1 Day')).'.xlsx'))
        ->attach(storage_path('app/fwri/CESUGENTRIAS_FWRI_LINELIST_'.date('mdY', strtotime('-1 Day')).'.xlsx'));
    }
}
