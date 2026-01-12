<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EdcsWeeklySubmissionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public int $year;
    public int $week;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $year, int $week)
    {
        $this->year = $year;
        $this->week = $week;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.edcs_weeklysubmission_reminder')
        ->from('admin@cesugentri.com', 'CESU General Trias')
        ->subject("Reminder of Encoding and Submitting MW{$this->week} - Year {$this->year} EDCS Weekly Report");
    }
}
