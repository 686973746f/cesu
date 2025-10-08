<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\ExportJobs;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\PharmacyStockCard;
use App\Models\PharmacySupplySub;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Queue\InteractsWithQueue;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class CallPharmacyAnnualInOutReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 90000;

    protected $user_id;
    protected $task_id;
    protected $year;
    protected $branch_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $task_id, $year, $branch_id)
    {
        $this->user_id = $user_id;
        $this->task_id = $task_id;
        $this->year = $year;
        $this->branch_id = $branch_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $input_year = $this->year;
        $branch_id = $this->branch_id;

        // Starting point for values
        $startRow = 4;
        $startCol = 'C'; // Column C

        $subs = PharmacySupplySub::with(['substock.stockcards' => function ($q) use ($input_year) {
            $q->whereYear('created_at', $input_year)
            ->selectRaw('
                subsupply_id,
                MONTH(created_at) as month,
                SUM(CASE WHEN type = "RECEIVED" THEN qty_to_process ELSE 0 END) as total_in,
                SUM(CASE WHEN type = "ISSUED" THEN qty_to_process ELSE 0 END) as total_out
            ')
            ->groupBy('subsupply_id', 'month');
        }])
        ->where('include_inreport', 'Y')
        ->where('pharmacy_branch_id', $branch_id)
        ->get();

        $row = $startRow;

        $spreadsheet = IOFactory::load(storage_path('Pharmacy_Monthly_InOut_Report.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'YEAR '.$input_year);

        foreach ($subs as $sub) {
            $col = $startCol;

            // Medicine name (col C)
            $sheet->setCellValue($col++ . $row, $sub->name);

            // UOM (col D)
            $sheet->setCellValue($col++ . $row, 'TEST');

            // Prepare monthly data (Jan–Dec)
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$i] = ['in' => 0, 'out' => 0];
            }

            foreach ($sub->substock->stockcards as $stockcard) {
                $month = (int)$stockcard->month;
                $monthlyData[$month]['in'] = $stockcard->total_in;
                $monthlyData[$month]['out'] = $stockcard->total_out;
            }

            // Write IN and OUT values per month
            for ($i = 1; $i <= 12; $i++) {
                $sheet->setCellValue($col++ . $row, $monthlyData[$i]['in']);
                $sheet->setCellValue($col++ . $row, $monthlyData[$i]['out']);
            }

            $row++;
        }

        $filename = 'Pharmacy_InOut_Report'.date('M_Y').'_'.Str::random(5).'.xlsx';
        $filePath = storage_path('export_jobs/' . $filename);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        $job_update = ExportJobs::where('id', $this->task_id)->update([
            'status' => 'completed',
            'filename' => $filename,
            'date_finished' => date('Y-m-d H:i:s'),
        ]);
    }
}
