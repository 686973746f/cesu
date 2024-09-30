<?php

namespace App\Console\Commands;

use App\Imports\EmployeesImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class CallEmployeesExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call_employeesexport';

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
        Excel::import(new EmployeesImport, storage_path('SERVICERECORD.xlsx'));
    }
}
