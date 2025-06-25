<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\SheetCollection;

class EmailCovidCpdcTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emailcovid_cpdc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        //DB::setDefaultConnection('mysqlcesuexp');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('max_execution_time', 900);

        $list_query = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', ['2020-01-01', '2024-12-31']);
        
        function generator($getList) {
            foreach ($getList->cursor() as $user) {
                yield $user;
            }
        }

        $sheets = new SheetCollection([
            'Suspected' => generator($list_query),
        ]);

        $header_style = (new Style())->setFontBold();
        $rows_style = (new Style())->setShouldWrapText();

        $exp = (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->export(storage_path('app/GENTRI_COVID19_DATABASE_2020to2024.xlsx'), function ($form) {
            return [
                'AGE' => $form->records->getAgeInt(),
                'MALE' => ($form->records->gender == 'MALE') ? 'YES' : 'NO',
                'FEMALE' => ($form->records->gender == 'FEMALE') ? 'YES' : 'NO',
                'BARANGAY' => $form->records->address_brgy,
                'HOSPITALIZED' => ($form->dispoType == 1) ? 'YES' : 'NO',
                'HOME QUARANTINE' => ($form->dispoType == 3) ? 'YES' : 'NO',
                'DECEASED' => ($form->outcomeCondition == 'Died') ? 'YES' : 'NO',
                'MONTH-YEAR REPORTED' => Carbon::parse($form->morbidityMonth)->format('F Y'),
            ];
        });
    }
}
