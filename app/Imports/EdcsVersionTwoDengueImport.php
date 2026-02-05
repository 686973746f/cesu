<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Dengue;
use App\Imports\EdcsImport;
use App\Models\DohFacility;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;

class EdcsVersionTwoDengueImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected ?int $userId;
    protected ?string $sourceZip;

    public function __construct(?int $userId = null, ?string $sourceZip = null)
    {
        $this->userId = $userId;
        $this->sourceZip = $sourceZip;
    }

    /**
     * If your CSV has headings on row 2 or 3, set it here.
     * Default is 1.
     */
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row) {
        if($row['current_address_city_municipality'] == 'City of General Trias' && $row['current_address_province'] == 'Cavite' && $row['epi_id']) {
            $birthdate = Carbon::parse(EdcsImport::tDate($row['date_of_birth']));
            $currentDate = Carbon::parse(EdcsImport::tDate($row['timestamp']));

            $getAgeMonths = $birthdate->diffInMonths($currentDate);
            $getAgeDays = $birthdate->diffInDays($currentDate);

            $hfcode = $row['health_facility_code'];
            $fac_name = $row['facilityname'];

            //GET FULL NAME
            $getFullName = $row['last_name'].', '.$row['first_name'];

            if(!is_null($row['middle_name']) && $row['middle_name'] != "" && $row['middle_name'] != 'N/A' && $row['middle_name'] != "NONE") {
                $getFullName = $getFullName.' '.$row['middle_name'];
            }

            if(!is_null($row['suffix_name']) && $row['suffix_name'] != "" && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != "NONE") {
                $getFullName = $getFullName.' '.$row['suffix_name'];
            }

            //CLASSIFICATION FIX
            if($row['clinical_classification'] == 'Dengue Without Warning Signs') {
                $get_classi = 'NO WARNING SIGNS';
            }
            else if($row['clinical_classification'] == 'Dengue With Warning Signs' || $row['clinical_classification'] == 'Dengue With Warning ') {
                $get_classi = 'WITH WARNING SIGNS';
            }
            else {
                $get_classi = mb_strtoupper($row['clinical_classification']);
            }

            //Check Facility Code if Existing in the DOH Facilities Database
            $hf_check = DohFacility::where('healthfacility_code', $hfcode)->first();

            if(!$hf_check) {
                EdcsImport::createDohFacility($fac_name, $hfcode, $row['region_dru'], $row['province_dru'], $row['muncity_dru']);
            }

            if(is_null($row['health_facility_code']) || is_null(EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name))) {
                $getDruRegionText = NULL;
                $getDruProvinceText = NULL;
                $getDruMuncityText = NULL;

                $getDruFacilityTypeText = NULL;
            }
            else {
                $getDruRegionText = (!is_null(EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name))) ? EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1 : NULL;
                $getDruProvinceText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_province;
                $getDruMuncityText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity;

                $getDruFacilityTypeText = EdcsImport::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type;
            }

            if(Str::startsWith($row['patient_no'], 'MPSS_') && Str::endsWith($row['patient_no'], 'E')) {
                preg_match('/\d+/', $row['patient_no'], $matches);

                $extracted_id = $matches[0];

                /*
                $model = Dengue::where('id', $extracted_id)
                ->where('from_edcs', 0)
                ->where('from_inhouse', 1)
                ->update([
                    'from_edcs' => 1,
                    'EPIID' => $row['epi_id'],
                ]);
                */

                $model = Dengue::where('id', $extracted_id)->first();

                if($model) {
                    if($model->from_edcs == 0) {
                        $model->from_edcs = 1;
                        $model->EPIID = $row['epi_id'];
                        $model->edcs_caseid = $row['case_id'];
    
                        if($model->isDirty()) {
                            $model->save();
                        }
                    }
                }
            }
            else {
                //Get Morb Month
                if(!is_null($row['date_on_set_of_illness_first_symptoms'])) {
                    $getMorbidityMonth = EdcsImport::getMorbMonth(EdcsImport::tDate($row['date_on_set_of_illness_first_symptoms']));
                }
                else {
                    $getMorbidityMonth = EdcsImport::getMorbMonth($currentDate->format('Y-m-d'));
                }

                $table_params = [
                    'Icd10Code' => 'A90',
                    'RegionOFDrU' => $getDruRegionText,
                    'ProvOfDRU' => $getDruProvinceText,
                    'MuncityOfDRU' => $getDruMuncityText,
                    'DRU' => $getDruFacilityTypeText,
                    'NameOfDru' => $row['facilityname'],
                    'AddressOfDRU' => NULL,
                    
                    'Region' => '04A',
                    'Province' => 'CAVITE',
                    'Muncity' => 'GENERAL TRIAS',
                    'DateOfEntry' => EdcsImport::tDate($row['timestamp']),
                    
                    'PatientNumber' => $row['patient_no'],
                    'FirstName' => $row['first_name'],
                    'FamilyName' => $row['last_name'],
                    'FullName' => $getFullName,
                    'AgeYears' => $row['age_in_years'],
                    'AgeMons' => $getAgeMonths,
                    'AgeDays' => $getAgeDays,
                    'Sex' => strtoupper(substr($row['sex'],0,1)),
                    'DOB' => EdcsImport::tDate($row['date_of_birth']),
                    'Admitted' => ($row['patient_admitted'] == 'Y') ? 1 : 0,
                    'DAdmit' => EdcsImport::tDate($row['date_admitted_seen']),
                    'DOnset' => EdcsImport::tDate($row['date_on_set_of_illness_first_symptoms']),
                    'Type' => 'DF',
                    'LabTest' => NULL,
                    'LabRes' => NULL,
                    'ClinClass' => $get_classi,
                    'Outcome' => (isset($row['outcome'])) ? mb_strtoupper(substr($row['outcome'],0,1)) : mb_strtoupper(substr($row['outcome2'],0,1)),
                    'EPIID' => $row['epi_id'],
                    'DateDied' => EdcsImport::tDate($row['date_died']),
                    
                    'MorbidityMonth' => $getMorbidityMonth,
                    'MorbidityWeek' => $row['morbidity_week'],
                    'Year' => $row['year'],
                    'AdmitToEntry' => preg_replace('/[^0-9]/', '', $row['timelapse_dateadmittodateencode']),
                    'OnsetToAdmit' => preg_replace('/[^0-9]/', '', $row['timelapse_dateonsettodateencode']),
                    'SentinelSite' => NULL,
                    'DeleteRecord' => NULL,
                    'Recstatus' => NULL,
                    'UniqueKey' => Dengue::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                    
                    'ILHZ' => 'GENTAMAR',
                    'District' => 6,
                    
                    'TYPEHOSPITALCLINIC' => $row['verification_level'],
                    'SENT' => 'Y',
                    'ip' => 'N',
                    'ipgroup' => NULL,
    
                    'middle_name' => ($row['middle_name'] != '' && !is_null($row['middle_name'] && $row['middle_name'] != 'NONE' && $row['middle_name'] != 'N/A') && $row['middle_name'] != 'N/A') ? $row['middle_name'] : NULL,
                    'suffix' => ($row['suffix_name'] != '' && !is_null($row['suffix_name']) && $row['suffix_name'] != 'N/A' && $row['suffix_name'] != 'NONE') ? $row['suffix_name'] : NULL,
                    'edcs_caseid' => (isset($row['case_id'])) ? $row['case_id'] : $row['21'],
                    'edcs_healthFacilityCode' => $row['health_facility_code'],
                    'edcs_verificationLevel' => $row['verification_level'],
                    'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                    'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                    'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                    'from_edcs' => 1,
    
                    'edcs_userid' => $row['user_id'],
                    'edcs_last_modifiedby' => $row['last_modified_by'],
                    'edcs_last_modified_date' => EdcsImport::tDate($row['last_modified_date']),
                    'system_subdivision_id' => EdcsImport::autoMateSubdivision($row['current_address_barangay']),

                    'dru_reg_code' => $row['region_dru'],
                    'dru_pro_code' => $row['province_dru'],
                    'dru_mun_code' => $row['muncity_dru'],
                ];

                $exist_check = Dengue::where('EPIID', $row['epi_id'])->first();

                if($exist_check) {
                    $old_modified_date = $exist_check->edcs_last_modified_date;
                    $new_modified_date = EdcsImport::tDate($row['last_modified_date']);
                    if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                        $model = $exist_check->update($table_params);
                    }

                    $model = $exist_check;
                }
                else {
                    $table_params = $table_params + [
                        'CaseClassification' => mb_strtoupper(substr($row['case_classification'],0,1)),
                        'Streetpurok' => ($row['current_address_sitio_purok_street_name'] != '' && !is_null($row['current_address_sitio_purok_street_name']) && mb_strtoupper($row['current_address_sitio_purok_street_name']) != 'N/A') ? $row['current_address_sitio_purok_street_name'] : NULL,
                        'Barangay' => EdcsImport::brgySetter($row['current_address_barangay']),
                        'created_by' => auth()->user()->id,
                    ];

                    $model = Dengue::create($table_params);
                }
            }

            /*
            //Update Syndromic Record - Mark as Received
            if(Str::startsWith($row['patient_no'], 'MPSS_')) { //Mark na galing sa OPD System yung Record
                $search_id = str_replace('MPSS_', '', $row['patient_no']);

                $synd_record = SyndromicRecords::where('id', $search_id)->first();
                if($synd_record) {
                    $synd_record->addToReceivedEdcsTag('DENGUE');
                }
            }
            */

            return $model;
        }
    }
}
