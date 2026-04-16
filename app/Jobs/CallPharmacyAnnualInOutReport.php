<?php

namespace App\Jobs;

use App\Models\ExportJobs;
use App\Models\PharmacyStockCard;
use App\Models\PharmacySupplySub;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CallPharmacyAnnualInOutReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 90000;

    protected $user_id;
    protected $task_id;
    protected $start_date;
    protected $end_date;
    protected $branch_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $task_id, $start_date, $end_date, $branch_id)
    {
        $this->user_id = $user_id;
        $this->task_id = $task_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->branch_id = $branch_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $branch_id = $this->branch_id;

        $cstart_date = Carbon::parse($start_date);
        $cyear = $cstart_date->year;
        $cend_date = Carbon::parse($end_date);
        
        $input_year = $cend_date->format('Y');
        
        $spreadsheet = IOFactory::load(storage_path('Pharmacy_Monthly_InOut_Report.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'YEAR '.$cend_date->format('Y'));

        $sRow = 4;

        $maxValMonth = ($input_year == date('Y')) 
        ? $cend_date->format('n') 
        : 12;

        $stockData = PharmacyStockCard::selectRaw("
            COALESCE(pharmacy_stock_cards.subsupply_id, substock.subsupply_id) as item_id,
            MONTH(pharmacy_stock_cards.created_at) as month,
            pharmacy_stock_cards.type,
            SUM(pharmacy_stock_cards.qty_to_process) as total
        ")
        ->leftJoin('pharmacy_stock_cards as substock', 'substock.id', '=', 'pharmacy_stock_cards.substock_id')
        ->whereBetween('pharmacy_stock_cards.created_at', [$start_date, $end_date])
        ->where('pharmacy_stock_cards.status', 'approved')
        ->groupBy('item_id', 'month', 'pharmacy_stock_cards.type')
        ->get();

        $grouped = [];

        foreach ($stockData as $row) {
            $grouped[$row->item_id][$row->month][$row->type] = $row->total;
        }

        //STOCKS MASTERLIST
        $list_subitem = PharmacySupplySub::where('pharmacy_branch_id', $branch_id)
        ->where('include_inreport', 'Y')
        ->get();

        $si_array = [];

        foreach($list_subitem as $key => $si) {
            $items_list[] = [
                'name' => $si->pharmacysupplymaster->name,
                'category' => $si->pharmacysupplymaster->category,
                'unit' => $si->pharmacysupplymaster->quantity_type,
                'current_stock' => $si->displayQty(),
                'yearend_stock' => $si->displayYearEndStock($input_year),
                'id' => $si->id,
            ];
        }

        foreach($items_list as $item) {
            $monthlyStocks = [];

            for($i = 1; $i <= $maxValMonth; $i++) {

                $issued_count = $grouped[$item['id']][$i]['ISSUED'] ?? 0;
                $received_count = $grouped[$item['id']][$i]['RECEIVED'] ?? 0;

                $issued_txt = $issued_count 
                    ? '- '.$issued_count.' '.Str::plural('PC', $issued_count) 
                    : '';

                $received_txt = $received_count 
                    ? '+ '.$received_count.' '.Str::plural('PC', $received_count) 
                    : '';

                $monthlyStocks[] = [
                    'month' => Carbon::create()->month($i)->format('F'),
                    'issued' => $issued_txt,
                    'received' => $received_txt,
                ];
            }

            $si_array[] = [
                'name' => $item['name'],
                'category' => $item['category'],
                'unit' => $item['unit'],
                'id' => $item['id'],
                'current_stock' => $item['current_stock'],
                'yearend_stock' => $item['yearend_stock'],
                'monthly_stocks' => $monthlyStocks,
            ];
        }

        foreach($si_array as $key => $si) {
            $sheet->setCellValue("A{$sRow}", $si['name']);
            $sheet->setCellValue("B{$sRow}", $si['unit']);
            $sheet->setCellValue("C{$sRow}", $si['current_stock']);

            $columnIndex = 4; // Start at 3 (corresponds to 'D')
            foreach($si['monthly_stocks'] as $ms) {
                //$columnLetter = chr(65 + $columnIndex); // 65 is ASCII for 'A'
                $columnLetter = Coordinate::stringFromColumnIndex($columnIndex);
                $sheet->setCellValue($columnLetter . $sRow, $ms['received']);

                $columnIndex++;
                $columnLetter = Coordinate::stringFromColumnIndex($columnIndex + 1);
                $sheet->setCellValue($columnLetter . $sRow, $ms['issued']);
                
                $columnIndex++;
            }

            $sheet->setCellValue('AB'.$sRow, $si['yearend_stock']);

            $sRow++;
        }
        
        $filename = 'Pharmacy_InOut_Report'.date('M_Y').'_'.Str::random(5).'.xlsx';
        $directory = storage_path('export_jobs');
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        //$filePath = storage_path('export_jobs/' . $filename);

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        $job_update = ExportJobs::where('id', $this->task_id)->update([
            'status' => 'completed',
            'filename' => $filename,
            'date_finished' => date('Y-m-d H:i:s'),
        ]);
    }
}