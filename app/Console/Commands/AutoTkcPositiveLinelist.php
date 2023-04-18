<?php

namespace App\Console\Commands;

use App\Models\Forms;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class AutoTkcPositiveLinelist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autotkc:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AutoTCK Send Linelist Daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where('sent', 0)
        ->get();

        if($query->count() != 0) {
            $spreadsheet = IOFactory::load(storage_path('tkc_linelist_template.csv'));
            $sheet = $spreadsheet->getActiveSheet();

            foreach($query as $ind => $d) {
                $ind = $ind + 2;

                $sheet->setCellValue('A2', ''); //report_id
                $sheet->setCellValue('B2', date('')); //report_id
            }
            
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter(','); // You can set your delimiter here
            $writer->setEnclosure('"'); // You can set your enclosure here
            $writer->save(storage_path('TESTTKC.csv'));
        }        
    }
}
