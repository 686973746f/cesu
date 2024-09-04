<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Queue\InteractsWithQueue;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CallOpdErExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $spreadsheet = IOFactory::load(storage_path('OPDSUMMARYREPORT_TEMPLATE.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        // Append row after row 2
        $sheet->insertNewRowBefore(4, 2); // Insert one new row before row 2

        $sheet->setCellValue('A4', 'BILAT');

        $fileName = 'OPDTEST_fin.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('export_jobs/OPDTEST_modified.xlsx'));
    }
}
