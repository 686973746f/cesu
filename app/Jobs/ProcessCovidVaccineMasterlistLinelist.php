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
        $create = (new FastExcel)->import($this->f, function ($row) {
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

        File::delete($this->f);
    }
}
