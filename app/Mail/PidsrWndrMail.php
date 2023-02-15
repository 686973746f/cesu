<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PidsrWndrMail extends Mailable
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
        return $this->view('email.pidsrwndr')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CHO GENERAL TRIAS PIDSR Weekly Notifiable Diseases Report MW'.date('W'))
        ->attach(public_path('PIDSR_GenTrias_MW'.date('W').'.pdf'));
    }
}
