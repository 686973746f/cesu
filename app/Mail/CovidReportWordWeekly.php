<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CovidReportWordWeekly extends Mailable
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
        return $this->view('email.covidreportweekly')
        ->from('admin@cesugentri.com', 'Christian James Historillo')
        ->subject('COVID GenTri Weekly ('.date('m/d', strtotime('-6 Days')).' - '.date('m/d, Y').')')
        ->attach(storage_path('CITY-OF-GENERAL-TRIAS-WEEKLY-'.date('F-d-Y').'.docx'));
    }
}
