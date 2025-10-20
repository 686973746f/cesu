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
    public function handle()
    {
        /*
        //STOCKS MASTERLIST
        $list_subitem = PharmacySupplySub::where('pharmacy_branch_id', $selected_branch)
        ->get();

        $si_array = [];

        foreach($list_subitem as $key => $si) {
            $items_list[] = [
                'name' => $si->pharmacysupplymaster->name,
                'category' => $si->pharmacysupplymaster->category,
                'unit' => $si->pharmacysupplymaster->quantity_type,
                'current_stock' => $si->displayQty(),
                'id' => $si->id,
            ];
        }

        foreach($items_list as $item) {
            $monthlyStocks = [];

            for($i=1;$i<=12;$i++) {
                $nomonth = Carbon::create()->month($i)->format('m');

                if($item['unit'] == 'BOX') {
                    $issued_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->where('qty_type', 'BOX')
                    ->sum('qty_to_process');

                    $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->where('qty_type', 'BOX')
                    ->sum('qty_to_process');

                    $issued_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->where('qty_type', 'PIECE')
                    ->sum('qty_to_process');

                    $received_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->where('qty_type', 'PIECE')
                    ->sum('qty_to_process');

                    if($issued_count == 0 && $issued_count_piece == 0) {
                        $issued_txt = '';
                        $received_txt = '';
                    }
                    else {
                        if($issued_count == 0) {
                            $issued_txt = '';

                            if($issued_count_piece != 0) {
                                $issued_txt = '- ';
                            }
                        }
                        else {
                            $issued_txt = '- '.$issued_count.' '.Str::plural('BOX', $issued_count);
                        }

                        if($received_count == 0) {
                            $received_txt = '';
                            
                            if($received_count_piece != 0) {
                                $received_txt = '+ ';
                            }
                        }
                        else {
                            $received_txt = '+ '.$received_count.' '.Str::plural('BOX', $received_count);
                        }

                        if($issued_count_piece != 0) {
                            $issued_txt = $issued_txt.' '.$issued_count_piece.' '.Str::plural('PC', $issued_count_piece);
                        }

                        if($received_count_piece != 0) {
                            $received_txt = $received_txt.' '.$received_count_piece.' '.Str::plural('PC', $received_count_piece);
                        }
                    }
                    
                }
                else {
                    $issued_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->sum('qty_to_process');

                    $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->sum('qty_to_process');

                    if($issued_count == 0) {
                        $issued_txt = '';
                    }
                    else {
                        $issued_txt = '- '.$issued_count.' '.Str::plural('PC', $issued_count);
                    }

                    if($received_count == 0) {
                        $received_txt = '';
                    }
                    else {
                        $received_txt = '+ '.$received_count.' '.Str::plural('PC', $received_count);
                    }
                }
                
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
                'monthly_stocks' => $monthlyStocks,
            ];
        }

        $filename = 'FHSIS_IMPORT_M2 BHS_'.$start->format('M_Y').'_'.Str::random(5).'.xlsx';

        $exp = (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->export(storage_path('export_jobs/'.$filename));
        */

        $input_year = $this->year;
        $branch_id = $this->branch_id;

        $spreadsheet = IOFactory::load(storage_path('Pharmacy_Monthly_InOut_Report.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'YEAR '.$input_year);

        $sRow = 4;

        if($input_year == date('Y')) {
            if(date('n') == 2) {
                $maxValMonth = 1;
            }
            else {
                $maxValMonth = date('n');
            }
        }
        else {
            $maxValMonth = 12;
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
                $nomonth = Carbon::create()->month($i)->format('n');

                if($item['unit'] == 'BOX') {
                    $issued_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->where('qty_type', 'BOX')
                    ->sum('qty_to_process');

                    $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->where('qty_type', 'BOX')
                    ->sum('qty_to_process');

                    $issued_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->where('qty_type', 'PIECE')
                    ->sum('qty_to_process');

                    $received_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->where('qty_type', 'PIECE')
                    ->sum('qty_to_process');

                    if($issued_count == 0 && $issued_count_piece == 0) {
                        $issued_txt = '';
                        $received_txt = '';
                    }
                    else {
                        if($issued_count == 0) {
                            $issued_txt = '';

                            if($issued_count_piece != 0) {
                                $issued_txt = '- ';
                            }
                        }
                        else {
                            $issued_txt = '- '.$issued_count.' '.Str::plural('BOX', $issued_count);
                        }

                        if($received_count == 0) {
                            $received_txt = '';
                            
                            if($received_count_piece != 0) {
                                $received_txt = '+ ';
                            }
                        }
                        else {
                            $received_txt = '+ '.$received_count.' '.Str::plural('BOX', $received_count);
                        }

                        if($issued_count_piece != 0) {
                            $issued_txt = $issued_txt.' '.$issued_count_piece.' '.Str::plural('PC', $issued_count_piece);
                        }

                        if($received_count_piece != 0) {
                            $received_txt = $received_txt.' '.$received_count_piece.' '.Str::plural('PC', $received_count_piece);
                        }
                    }
                    
                }
                else {
                    $issued_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->sum('qty_to_process');

                    $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $input_year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->sum('qty_to_process');

                    if($issued_count == 0) {
                        $issued_txt = '';
                    }
                    else {
                        $issued_txt = '- '.$issued_count.' '.Str::plural('PC', $issued_count);
                    }

                    if($received_count == 0) {
                        $received_txt = '';
                    }
                    else {
                        $received_txt = '+ '.$received_count.' '.Str::plural('PC', $received_count);
                    }
                }
                
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
            $sheet->setCellValue('A'.$sRow, $si['name']);
            $sheet->setCellValue('B'.$sRow, $si['unit']);

            $columnIndex = 2; // Start at 0 (corresponds to 'A')
            foreach($si['monthly_stocks'] as $ms) {
                $columnLetter = chr(65 + $columnIndex); // 65 is ASCII for 'A'
                $sheet->setCellValue($columnLetter . $sRow, $ms['received']);

                $columnIndex++;
                $columnLetter = chr(65 + $columnIndex);
                $sheet->setCellValue($columnLetter . $sRow, $ms['issued']);
                
                $columnIndex++;
            }

            $sheet->setCellValue('AA'.$sRow, $si['yearend_stock']);

            $sRow++;
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