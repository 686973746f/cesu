<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EdcsHourlyCaseCheckerMail extends Mailable
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
        return $this->view('email.edcs_hourlycasecheckerview')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CESU Gen. Trias AutoMailer - EDCS Case/s Detected on '.date('m/d/Y h:i A'));
    }
}
