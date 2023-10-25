<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbtcStockReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $arr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.abtcstockreportview')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('GenTrias Rabies Control Program - Stock Report '.date('m/d/Y'));
    }
}
