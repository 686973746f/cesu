<?php

namespace App\Jobs;

use App\Models\ExportJobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CallTkcImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 90000;

    protected $job_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $d = ExportJobs::findOrFail($this->job_id);

        Excel::import(new TkcExcelImport(), $r->file('csv_file'));
    }
}
