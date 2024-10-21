<?php

namespace App\Mail;

use App\Models\DohFacility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EdcsWeeklySubmissionChecker;
use Illuminate\Contracts\Queue\ShouldQueue;

class EdcsWeeklySubmissionSendEmailByFacility extends Mailable
{
    use Queueable, SerializesModels;

    protected $facility_id;
    protected $import_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($facility_id, $import_id)
    {
        $this->facility_id = $facility_id;
        $this->import_id = $import_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $f = DohFacility::findOrFail($this->facility_id);
        $d = EdcsWeeklySubmissionChecker::findOrFail($this->import_id);

        if(!is_null($d->excel_file)) {
            return $this->view('email.edcs_weeklysubmissionfacility')
            ->from('admin@cesugentri.com', $f->facility_name)
            ->subject('EDCS MW: '.$d->week.' - Year: '.$d->year.' Weekly Submission by '.$f->facility_name.' - General Trias CESU')
            ->attach(storage_path('app/edcs/weeklysubmission/'.$d->excel_file))
            ->with(['d' => $d, 'f' => $f]);
        }
        else {
            return $this->view('email.edcs_weeklysubmissionfacility')
            ->from('admin@cesugentri.com', $f->facility_name)
            ->subject('EDCS MW: '.$d->week.' - Year: '.$d->year.' Weekly Submission by '.$f->facility_name.' - General Trias CESU')
            ->with(['d' => $d, 'f' => $f]);
        }
    }
}
