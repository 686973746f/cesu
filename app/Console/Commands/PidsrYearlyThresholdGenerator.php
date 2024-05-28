<?php

namespace App\Console\Commands;

use App\Models\PidsrThreshold;
use Illuminate\Console\Command;

class PidsrYearlyThresholdGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pidsrgeneratethreshold:yearly';

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
        /**'Abd',
        'Aefi',
        'Aes',
        'Afp',
        'Ahf',
        'Ames',
        'Anthrax',
        'Chikv',
        'Cholera',
        'Dengue',
        'Diph',
        'Hepatitis',
        'Hfmd',
        'Influenza',
        'Leptospirosis',
        'Malaria',
        'Measles',
        'Meningitis',
        'Meningo',
        'Nnt',
        'Nt',
        'Pert',
        'Psp',
        'Rabies',
        'Rotavirus',
        'Typhoid',*/

        $diseases = [
            'COVID',
        ];

        foreach($diseases as $d) {
            if($d == 'COVID') {
                $modelClass = "App\\Models\\Forms";
            }
            else {
                $modelClass = "App\\Models\\$d";
            }
            
            $y = date('Y', strtotime('-1 Year'));

            //Create Row First if Not Exist
            $s = PidsrThreshold::where('disease', $d)
            ->where('year', $y)
            ->first();

            if(!$s) {
                $create_row = PidsrThreshold::create([
                    'disease' => mb_strtoupper($d),
                    'year' => $y,
                ]);
            }

            for($i=1;$i<=53;$i++) {
                if($d == 'COVID') {
                    $cond = $modelClass::with('records')
                    ->whereHas('records', function ($q) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS');
                    })
                    ->where('status', 'approved')
                    ->where('caseClassification', 'Confirmed')
                    ->whereYear('morbidityMonth', $y)
                    ->whereRaw('WEEK(morbidityMonth, 1) = ?', [52])
                    ->count();
                }
                else {
                    $cond = $modelClass::where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->where('Year', $y)
                    ->where('MorbidityWeek', $i)
                    ->count();
                }

                $update = PidsrThreshold::where('year', $y)
                ->where('disease', $d)
                ->update([
                    'mw'.$i => $cond,
                ]);
            }
        }
    }
}
