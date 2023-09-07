<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\PharmacyExcelImport;
use Maatwebsite\Excel\Facades\Excel;

class PharmacyImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pharmacyimport';

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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Excel::import(new PharmacyExcelImport(), storage_path('app/pharmacy/TEST.xlsx'));
    }
}
