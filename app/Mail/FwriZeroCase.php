<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FwriZeroCase extends Mailable
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
        return $this->view('email.fwrizerocase')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('FWRI Daily Report - ZERO CASE - CESU General Trias '.date('m/d/Y'));
    }
}
