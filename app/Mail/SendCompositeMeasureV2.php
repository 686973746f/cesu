<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCompositeMeasureV2 extends Mailable
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
        if(date('d') <= 15) { 
            $period = date('F 01, Y').' - '.date('F 15, Y');
        }
        else if(date('d') >= 16) {
            $period = date('F 16, Y').' - '.date('F t, Y');
        }
        
        return $this->view('email.compositemeasurev2')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('Composite Measure for '.$period.' - City of General Trias, Cavite')
        ->attach(storage_path('COMPOSITE_MEASURE_'.date('F_d_Y').'.docx'));
    }
}
