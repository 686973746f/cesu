<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAyudaList extends Mailable
{
    use Queueable, SerializesModels;

    public $count;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($count)
    {
        $this->count = $count;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.ayudalist')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('COVID Ayuda List '.date('F d, Y'))
        ->attach(public_path('AyudaList_'.date('F_d_Y').'.xlsx'));
    }
}
