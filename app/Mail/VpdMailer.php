<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VpdMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $diph_array;
    public $measles_array;
    public $afp_array;
    public $pert_array;
    public $nnt_array;
    public $nt_array;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($diph_array, $measles_array, $afp_array, $pert_array, $nnt_array, $nt_array)
    {
        $this->diph_array = $diph_array;
        $this->measles_array = $measles_array;
        $this->afp_array = $afp_array;
        $this->pert_array = $pert_array;
        $this->nnt_array = $nnt_array;
        $this->nt_array = $nt_array;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.vpdsender')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('CESU Gen. Trias - VPD Cases Detected ('.date('H').')');
    }
}
