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

                // Load the JSON file contents into a string
                $regionString = file_get_contents(storage_path('json/refregion.json'));
                $provinceString = file_get_contents(storage_path('json/refprovince.json'));
                $cityString = file_get_contents(storage_path('json/refcitymun.json'));
                $brgyString = file_get_contents(storage_path('json/refbrgy.json'));

                // Decode the JSON string into a PHP array
                $data = json_decode($jsonString, true);

                // Search for the string 'BUENAVISTA II' in the array
                $key = array_search('BUENAVISTA II', array_map('strtoupper', array_column($data, 'brgyDesc')));

                if ($key !== false) {
                    // The string was found at index $key in the array
                    $brgyCode = $data[$key]['brgyCode'];
                    echo "String found at index $key. brgyCode: $brgyCode";
                } else {
                    // The string was not found in the array
                    echo "String not found.";
                }

                $sheet->setCellValue('A'.$ind, ''); //report_id
                $sheet->setCellValue('B'.$ind, date('n/d/Y', strtotime($d->created_at))); //created_at
                $sheet->setCellValue('C'.$ind, 'CHRISTIAN JAMES HISTORILLO');
                $sheet->setCellValue('D'.$ind, '1'); //case_tracking_status
                $sheet->setCellValue('E'.$ind, date('n/d/Y', strtotime($d->created_at))); //date_verified
                $sheet->setCellValue('F'.$ind, $d->records->philhealth);
                $sheet->setCellValue('G'.$ind, $d->records->fname);
                $sheet->setCellValue('H'.$ind, $d->records->mname);
                $sheet->setCellValue('I'.$ind, $d->records->lname);
                $sheet->setCellValue('J'.$ind, ''); //suffix
                $sheet->setCellValue('K'.$ind, date('n/d/Y', strtotime($d->records->bdate))); //
                $sheet->setCellValue('L'.$ind, $d->records->gender);
                $sheet->setCellValue('M'.$ind, 'Filipino');
                $sheet->setCellValue('N'.$ind, $d->records->cs);
                $sheet->setCellValue('O'.$ind, $d->records->phoneno);
                $sheet->setCellValue('P'.$ind, $d->records->mobile);
                $sheet->setCellValue('Q'.$ind, $d->records->email);
                $sheet->setCellValue('R'.$ind, '');
                $sheet->setCellValue('S'.$ind, '');
                $sheet->setCellValue('T'.$ind, $d->records->address_houseno);
                $sheet->setCellValue('U'.$ind, '');
                $sheet->setCellValue('V'.$ind, $d->records->address_street);
                $sheet->setCellValue('W'.$ind, '');
                $sheet->setCellValue('X'.$ind, '');
                $sheet->setCellValue('Y'.$ind, '');
                $sheet->setCellValue('Z'.$ind, '');
                $sheet->setCellValue('AA'.$ind, '');
                $sheet->setCellValue('AB'.$ind, $d->records->address_houseno);
                $sheet->setCellValue('AC'.$ind, '');
                $sheet->setCellValue('AD'.$ind, $d->records->address_street);
                $sheet->setCellValue('AE'.$ind, '');
                $sheet->setCellValue('AF'.$ind, '');
                $sheet->setCellValue('AG'.$ind, '');
                $sheet->setCellValue('AH'.$ind, '');
                $sheet->setCellValue('AI'.$ind, '');
                $sheet->setCellValue('AJ'.$ind, '');
            }
            
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter(','); // You can set your delimiter here
            $writer->setEnclosure('"'); // You can set your enclosure here

            $writer->save(storage_path('TESTTKC.csv'));
        }        
    }
}
