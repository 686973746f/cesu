<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Imports\CovidVaccineMasterlistImport;
use App\Jobs\ProcessCovidVaccineMasterlistLinelist;
use App\Models\CovidVaccinePatientMasterlist;

class CovidVaccineLinelistImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covidvaccinelinelistimporter:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import Masterlist Excel Every Monday, 9PM';

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
        //ini_set('max_execution_time', 999999999999999);

        $filenames = [
            storage_path('app/vaxcert/masterlist/1.xlsx'),
            storage_path('app/vaxcert/masterlist/2.xlsx'),
            storage_path('app/vaxcert/masterlist/3.xlsx'),
            storage_path('app/vaxcert/masterlist/4.xlsx'),
            storage_path('app/vaxcert/masterlist/5.xlsx'),
            storage_path('app/vaxcert/masterlist/6.xlsx'),
            storage_path('app/vaxcert/masterlist/7.xlsx'),
            storage_path('app/vaxcert/masterlist/8.xlsx'),
            storage_path('app/vaxcert/masterlist/9.xlsx'),
            storage_path('app/vaxcert/masterlist/10.xlsx'),
            storage_path('app/vaxcert/masterlist/11.xlsx'),
            storage_path('app/vaxcert/masterlist/12.xlsx'),
            storage_path('app/vaxcert/masterlist/13.xlsx'),
            storage_path('app/vaxcert/masterlist/14.xlsx'),
            storage_path('app/vaxcert/masterlist/15.xlsx'),
            storage_path('app/vaxcert/masterlist/16.xlsx'),
            storage_path('app/vaxcert/masterlist/17.xlsx'),
            storage_path('app/vaxcert/masterlist/18.xlsx'),
            storage_path('app/vaxcert/masterlist/19.xlsx'),
            storage_path('app/vaxcert/masterlist/20.xlsx'),
            storage_path('app/vaxcert/masterlist/21.xlsx'),
            storage_path('app/vaxcert/masterlist/22.xlsx'),
            storage_path('app/vaxcert/masterlist/23.xlsx'),
        ];

        /*
        MAATEXCEL TYPE
        foreach($filenames as $f) {
            if(File::exists($f)) {
                Excel::import(new CovidVaccineMasterlistImport(), $f);

                File::delete($f);

                sleep(180);
            }
        }
        */

        if (count(File::allFiles(storage_path('app/vaxcert/masterlist'))) != 0 && File::exists(storage_path('app/vaxcert/masterlist/1.xlsx'))) {
            CovidVaccinePatientMasterlist::truncate();
        }

        foreach($filenames as $f) {
            if(File::exists($f)) {
                ProcessCovidVaccineMasterlistLinelist::dispatch($f);
            }
        }
    }
}
