<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\CovidVaccinePatientMasterlist;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessCovidVaccineMasterlistLinelist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 90000;

    protected $f;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($f)
    {
        $this->f = $f;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $batchSize = 1000;
        $data = [];

        $collection = (new FastExcel)->import($this->f, function ($row) use (&$data, $batchSize) {
            //$search = CovidVaccinePatientMasterlist::where('row_hash', $row['ROW_HASH'])->first();

            return $data[] = [
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
                'row_hash' => $row['ROW_HASH'],
            ];
        });

        for($i = 0; $i < count($data); $i += $batchSize) {
            $batch = array_slice($data, $i, $batchSize);
            
            CovidVaccinePatientMasterlist::upsert($batch, ['row_hash'], [
                'category', 'comorbidity', 'unique_person_id', 'pwd', 'indigenous_member',
                'last_name', 'first_name', 'middle_name', 'suffix', 'contact_no', 'guardian_name',
                'region', 'province', 'muni_city', 'barangay', 'sex', 'birthdate', 'deferral', 'reason_for_deferral',
                'vaccination_date', 'vaccine_manufacturer_name', 'batch_number', 'lot_no', 'bakuna_center_cbcr_id',
                'vaccinator_name', 'first_dose', 'second_dose', 'additional_booster_dose',
                'second_additional_booster_dose', 'adverse_event', 'adverse_event_condition'
            ]);
        }

        File::delete($this->f);
    }
}
