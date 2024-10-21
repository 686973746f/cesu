<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Mail\EdcsWeeklySubmissionSendEmailByFacility;
use App\Models\DohFacility;
use App\Models\EdcsWeeklySubmissionChecker;

class CallEdcsWeeklySubmissionSendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $facility_id;
    protected $import_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($facility_id, $import_id)
    {
        $this->facility_id = $facility_id;
        $this->import_id = $import_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $f = DohFacility::findOrFail($this->facility_id);
        
        $email_array = [
            'hihihisto@gmail.com',
            'cesu.gentrias@gmail.com',
            //'resu4a.edcs@gmail.com',
            'pesucavite@gmail.com',
            'macvillaviraydoh@gmail.com',
        ];

        if(!is_null($f->email)) {
            $email_array = $email_array + [
                $f->email_edcs,
            ];
        }

        Mail::to($email_array)->send(new EdcsWeeklySubmissionSendEmailByFacility($f->id, $this->import_id));
    }
}
