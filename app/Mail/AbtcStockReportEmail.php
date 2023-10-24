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
    public $wastage_count;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($arr, $wastage_count)
    {
        $this->arr = $arr;
        $this->wastage_count = $wastage_count;
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
