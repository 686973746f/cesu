<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SyndromicEmailSender extends Mailable
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
        return $this->view('email.syndromiclistsender')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('OPD Detection List - '.date('m/d/Y hA'));
    }
}
