<?php

namespace App\Console\Commands;

use App\Models\CovidVaccinePatientMasterlist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\FastExcel\FastExcel;

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
        ini_set('max_execution_time', 999999);

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

        foreach($filenames as $f) {
            if(File::exists($f)) {
                $create = (new FastExcel)->import($f, function ($row) {
                    //$search = CovidVaccinePatientMasterlist::where('row_hash', $row['ROW_HASH'])->first();

                    return CovidVaccinePatientMasterlist::updateOrCreate([
                        'row_hash' => $row['ROW_HASH'],
                    ], [
                        'category' => $row['CATEGORY'],
                        'comorbidity' => ($row['COMORBIDITY'] != '') ? $row['COMORBIDITY'] : NULL,
                        'unique_person_id' => $row['UNIQUE_PERSON_ID'],
                        'pwd' => $row['PWD'],
                        'indigenous_member' => $row['INDIGENOUS_MEMBER'],
                        'last_name' => $row['LAST_NAME'],
                        'first_name' => $row['FIRST_NAME'],
                        'middle_name' => ($row['MIDDLE_NAME'] == '') ? NULL : $row['MIDDLE_NAME'],
                        'suffix' => ($row['SUFFIX'] != '') ? $row['SUFFIX'] : NULL,
                        'contact_no' => $row['CONTACT_NO'],
                        'guardian_name' => ($row['GUARDIAN_NAME'] != '') ? $row['GUARDIAN_NAME'] : NULL,
                        'region' => $row['REGION'],
                        'province' => $row['PROVINCE'],
                        'muni_city' => $row['MUNI_CITY'],
                        'barangay' => $row['BARANGAY'],
                        'sex' => $row['SEX'],
                        'birthdate' => date('Y-m-d', strtotime($row['BIRTHDATE'])),
                        'deferral' => 'N',
                        'reason_for_deferral' => NULL,
                        'vaccination_date' => date('Y-m-d', strtotime($row['VACCINATION_DATE'])),
                        'vaccine_manufacturer_name' => $row['VACCINE_MANUFACTURER_NAME'],
                        'batch_number' => $row['BATCH_NUMBER'],
                        'lot_no' => $row['LOT_NO'],
                        'bakuna_center_cbcr_id' => $row['BAKUNA_CENTER_CBCR_ID'],
                        'vaccinator_name' => $row['VACCINATOR_NAME'],
                        'first_dose' => $row['FIRST_DOSE'],
                        'second_dose' => $row['SECOND_DOSE'],
                        'additional_booster_dose' => $row['ADDITIONAL_BOOSTER_DOSE'],
                        'second_additional_booster_dose' => $row['SECOND_ADDITIONAL_BOOSTER_DOSE'],
                        'adverse_event' => $row['ADVERSE_EVENT'],
                        'adverse_event_condition' => ($row['ADVERSE_EVENT'] != 'N') ? $row['ADVERSE_EVENT_CONDITION'] : NULL,
                        'row_hash' => $row['ROW_HASH']
                    ]);
                });

                File::delete($f);
            }
        }
    }
}
