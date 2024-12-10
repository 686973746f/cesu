<?php

namespace App\Jobs;

use App\Imports\EdcsImportV2;
use App\Models\ExportJobs;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CallEdcsImportJobV2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $folderName;
    protected $job_id;
    protected $submit_type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($folderName, $job_id, $submit_type)
    {
        $this->folderName = $folderName;
        $this->job_id = $job_id;
        $this->submit_type = $submit_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $j = ExportJobs::findOrFail($this->job_id);

        $files = File::files(storage_path('app/edcs/uploads/').$this->folderName);

        foreach ($files as $file) {
            $filePath = $file->getPathname();

            try {
                // Log or handle specific file processing
                //\Log::info("Processing file: {$filePath}");

                // Import each file using Laravel Excel
                Excel::import(new EdcsImportV2($j->created_by), $filePath);
            } catch (\Exception $e) {
                // Log or handle errors during file import
                //\Log::error("Error processing file: {$filePath} - {$e->getMessage()}");
            }
        }
    }
}
