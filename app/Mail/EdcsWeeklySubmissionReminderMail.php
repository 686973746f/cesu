<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EdcsWeeklySubmissionReminderMail extends Mailable
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
        if(date('W') == 02) {
            $year = date('Y', strtotime('-1 Day'));
        }
        else {
            $year = date('Y', strtotime('-1 Week'));
        }

        return $this->view('email.edcs_weeklysubmission_reminder')
        ->from('admin@cesugentri.com', 'CESU General Trias')
        ->subject('Reminder of Encoding and Submitting MW'.date('W', strtotime('-1 Week')).' - Year '.$year.' EDCS Weekly Report');
    }
}
