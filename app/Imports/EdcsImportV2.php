<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Dengue;
use App\Models\DohFacility;
use Illuminate\Support\Str;
use App\Models\SyndromicRecords;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EdcsImportV2 implements ToModel, WithHeadingRow
{
    protected $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id; // Store the string in a property
    }

    public static function tDate($date) {
        if(!is_null($date) && $date != "" && $date != 'N/A') {
            if(Carbon::hasFormat($date, 'Y-m-d') || Carbon::hasFormat($date, 'm/d/Y')) {
                return date('Y-m-d', strtotime($date));
            }
            else {
                return Carbon::parse(Date::excelToDateTimeObject($date))->format('Y-m-d');
            }
        }
        else {
            return NULL;
        }
    }

    public static function getMorbMonth($timestamp) {
        return Carbon::parse($timestamp)->format('n');
    }

    public static function brgySetter($brgy) {
        //BARANGAY SETTER (BECAUSE OF THE POB.)
        if(!is_null($brgy) && !in_array($brgy, ['', 'N/A', 'NONE'])) {
            if(Str::contains($brgy, 'Pob.')) {
                if($brgy == 'Arnaldo Pob.') {
                    $get_brgy = 'ARNALDO POB. (BGY. 7)';
                }
                else if($brgy == 'Bagumbayan Pob.') {
                    $get_brgy = 'BAGUMBAYAN POB. (BGY. 5)';
                }
                else if($brgy == 'Corregidor Pob.') {
                    $get_brgy = 'CORREGIDOR POB. (BGY. 10)';
                }
                else if($brgy == 'Dulong Bayan Pob.') {
                    $get_brgy = 'DULONG BAYAN POB. (BGY. 3)';
                }
                else if($brgy == 'Gov. Ferrer Pob.') {
                    $get_brgy = 'GOV. FERRER POB. (BGY. 1)';
                }
                else if($brgy == 'Ninety Sixth Pob.') {
                    $get_brgy = 'NINETY SIXTH POB. (BGY. 8)';
                }
                else if($brgy == 'Prinza Pob.') {
                    $get_brgy = 'PRINZA POB. (BGY. 9)';
                }
                else if($brgy == 'Sampalucan Pob.') {
                    $get_brgy = 'SAMPALUCAN POB. (BGY. 2)';
                }
                else if($brgy == 'San Gabriel Pob.') {
                    $get_brgy = 'SAN GABRIEL POB. (BGY. 4)';
                }
                else if($brgy == 'Vibora Pob.') {
                    $get_brgy = 'VIBORA POB. (BGY. 6)';
                }
                else {
                    $get_brgy = mb_strtoupper($brgy);
                }
            }
            else {
                $get_brgy = mb_strtoupper($brgy);
            }
        }
        else {
            $get_brgy = NULL;
        }

        return $get_brgy;
    }

    public static function autoMateSubdivision($brgy) {
        if($brgy == 'Arnaldo Pob.') {
            $subd_id = 298;
        }
        else if($brgy == 'Bagumbayan Pob.') {
            $subd_id = 299;
        }
        else if($brgy == 'Corregidor Pob.') {
            $subd_id = 300;
        }
        else if($brgy == 'Dulong Bayan Pob.') {
            $subd_id = 301;
        }
        else if($brgy == 'Gov. Ferrer Pob.') {
            $subd_id = 302;
        }
        else if($brgy == 'Ninety Sixth Pob.') {
            $subd_id = 303;
        }
        else if($brgy == 'Prinza Pob.') {
            $subd_id = 304;
        }
        else if($brgy == 'Sampalucan Pob.') {
            $subd_id = 305;
        }
        else if($brgy == 'San Gabriel Pob.') {
            $subd_id = 306;
        }
        else if($brgy == 'Vibora Pob.') {
            $subd_id = 307;
        }
        else {
            $subd_id = NULL;
        }

        return $subd_id;
    }

    public static function getEdcsFacilityDetails($code, $fname) {
        $s = DohFacility::where('healthfacility_code', $code)
        ->orWhere('facility_name', $fname)
        ->first();

        if($s) {
            return $s;
        }
        else {
            return NULL;
        }
    }

    public function model(array $row) {
        $casecode = $row['casecode'];

        if($casecode == 'DENGUE') {
            if($row['permanentaddresscitymunicipality'] == 'City of General Trias' && $row['permanentaddressprovince'] == 'Cavite' && $row['epiid']) {
                $birthdate = Carbon::parse(EdcsImportV2::tDate($row['dob']));
                $currentDate = Carbon::parse(EdcsImportV2::tDate($row['timestamp_disease']));
    
                $getAgeMonths = $birthdate->diffInMonths($currentDate);
                $getAgeDays = $birthdate->diffInDays($currentDate);
    
                $hfcode = $row['health_facility_code'];
                $fac_name = $row['nameofdru'];
    
                //GET FULL NAME
                $getFullName = $row['familyname'].', '.$row['firstname'];
    
                if(!is_null($row['middlename']) && $row['middlename'] != "" && $row['middlename'] != 'N/A' && $row['middlename'] != "NONE") {
                    $getFullName = $getFullName.' '.$row['middlename'];
                }
    
                if(!is_null($row['suffixname']) && $row['suffixname'] != "" && $row['suffixname'] != 'N/A' && $row['suffixname'] != "NONE") {
                    $getFullName = $getFullName.' '.$row['suffixname'];
                }
    
                //CLASSIFICATION FIX
                if($row['clinclass'] == 'Dengue Without Warning Signs') {
                    $get_classi = 'NO WARNING SIGNS';
                }
                else if($row['clinclass'] == 'Dengue With Warning Signs' || $row['clinclass'] == 'Dengue With Warning ') {
                    $get_classi = 'WITH WARNING SIGNS';
                }
                else {
                    $get_classi = mb_strtoupper($row['clinclass']);
                }
    
                if(is_null($row['health_facility_code']) || is_null(EdcsImportV2::getEdcsFacilityDetails($hfcode, $fac_name))) {
                    $getDruRegionText = NULL;
                    $getDruProvinceText = NULL;
                    $getDruMuncityText = NULL;
    
                    $getDruFacilityTypeText = NULL;
                }
                else {
                    $getDruRegionText = (!is_null(EdcsImportV2::getEdcsFacilityDetails($hfcode, $fac_name))) ? EdcsImportV2::getEdcsFacilityDetails($hfcode, $fac_name)->getRegionData()->short_name1 : NULL;
                    $getDruProvinceText = EdcsImportV2::getEdcsFacilityDetails($hfcode, $fac_name)->address_province;
                    $getDruMuncityText = EdcsImportV2::getEdcsFacilityDetails($hfcode, $fac_name)->address_muncity;
    
                    $getDruFacilityTypeText = EdcsImportV2::getEdcsFacilityDetails($hfcode, $fac_name)->facility_type;
                }
    
                $table_params = [
                    'Icd10Code' => 'A90',
                    'RegionOFDrU' => $getDruRegionText,
                    'ProvOfDRU' => $getDruProvinceText,
                    'MuncityOfDRU' => $getDruMuncityText,
                    'DRU' => $getDruFacilityTypeText,
                    'NameOfDru' => $row['nameofdru'],
                    'AddressOfDRU' => NULL,
                    
                    'Region' => '04A',
                    'Province' => 'CAVITE',
                    'Muncity' => 'GENERAL TRIAS',
                    'DateOfEntry' => EdcsImportV2::tDate($row['timestamp_disease']),
                    
                    'PatientNumber' => $row['patientnumber'],
                    'FirstName' => $row['firstname'],
                    'FamilyName' => $row['familyname'],
                    'FullName' => $getFullName,
                    'AgeYears' => $row['ageyears'],
                    'AgeMons' => $getAgeMonths,
                    'AgeDays' => $getAgeDays,
                    'Sex' => $row['sex'],
                    'DOB' => EdcsImportV2::tDate($row['dob']),
                    'Admitted' => ($row['admitted'] == 'Y') ? 1 : 0,
                    'DAdmit' => EdcsImportV2::tDate($row['dadmit']),
                    'DOnset' => EdcsImportV2::tDate($row['donset']),
                    'Type' => 'DF',
                    'LabTest' => NULL,
                    'LabRes' => NULL,
                    'ClinClass' => $get_classi,
                    'Outcome' => mb_strtoupper(substr($row['outcome'],0,1)),
                    'EPIID' => $row['epiid'],
                    'DateDied' => EdcsImportV2::tDate($row['datedied']),
                    
                    'MorbidityMonth' => $currentDate->format('n'),
                    'MorbidityWeek' => $currentDate->format('W'),
                    'AdmitToEntry' => $row['admittoentry'],
                    'OnsetToAdmit' => $row['timelapse_dateonsettodateencode'],
                    'SentinelSite' => NULL,
                    'DeleteRecord' => NULL,
                    'Year' => $currentDate->format('Y'),
                    'Recstatus' => NULL,
                    'UniqueKey' => Dengue::orderBy('UniqueKey', 'DESC')->pluck('UniqueKey')->first() + 1,
                    
                    'ILHZ' => 'GENTAMAR',
                    'District' => 6,
                    
                    //'TYPEHOSPITALCLINIC' => $row['verification_level'],
                    'SENT' => 'Y',
                    'ip' => 'N',
                    'ipgroup' => NULL,
    
                    'middle_name' => ($row['middlename'] != '' && !is_null($row['middlename'] && $row['middlename'] != 'NONE' && $row['middlename'] != 'N/A') && $row['middlename'] != 'N/A') ? $row['middlename'] : NULL,
                    'suffix' => ($row['suffixname'] != '' && !is_null($row['suffixname']) && $row['suffixname'] != 'N/A' && $row['suffixname'] != 'NONE') ? $row['suffixname'] : NULL,
                    'edcs_caseid' => $row['case_id'],
                    'edcs_healthFacilityCode' => $row['health_facility_code'],
                    //'edcs_verificationLevel' => $row['verification_level'],
                    'edcs_investigatorName' => isset($row['name_of_investigator']) ? $row['name_of_investigator'] : NULL,
                    'edcs_contactNo' => isset($row['contact_no']) ? $row['contact_no'] : NULL,
                    'edcs_ageGroup' => isset($row['age_group']) ? $row['age_group'] : NULL,
                    'from_edcs' => 1,
    
                    'edcs_userid' => $row['userid'],
                    'edcs_last_modifiedby' => $row['lastmodifiedby'],
                    'edcs_last_modified_date' => EdcsImportV2::tDate($row['lastmodifieddate']),
                    'system_subdivision_id' => EdcsImportV2::autoMateSubdivision($row['permanentaddressbarangay']),
                ];
    
                $exist_check = Dengue::where('EPIID', $row['epiid'])->first();
    
                if($exist_check) {
                    $old_modified_date = $exist_check->edcs_last_modified_date;
                    $new_modified_date = EdcsImportV2::tDate($row['lastmodifieddate']);
                    if(is_null($exist_check->edcs_last_modified_date) || Carbon::parse($new_modified_date)->gte(Carbon::parse($old_modified_date))) {
                        $model = $exist_check->update($table_params);
                    }
    
                    $model = $exist_check;
                }
                else {
                    $table_params = $table_params + [
                        'CaseClassification' => mb_strtoupper(substr($row['caseclassification'],0,1)),
                        'Streetpurok' => ($row['permanentaddresssitiopurokstreetname'] != '' && !is_null($row['permanentaddresssitiopurokstreetname']) && mb_strtoupper($row['permanentaddresssitiopurokstreetname']) != 'N/A') ? $row['permanentaddresssitiopurokstreetname'] : NULL,
                        'Barangay' => EdcsImportV2::brgySetter($row['permanentaddressbarangay']),
                        'created_by' => auth()->user()->id,
                    ];
    
                    $model = Dengue::create($table_params);
                }
    
                //Update Syndromic Record - Mark as Received
                if(Str::startsWith($row['patientnumber'], 'MPSS_')) { //Mark na galing sa OPD System yung Record
                    $search_id = str_replace('MPSS_', '', $row['patientnumber']);
    
                    $synd_record = SyndromicRecords::where('id', $search_id)->first();
                    if($synd_record) {
                        $synd_record->addToReceivedEdcsTag('DENGUE');
                    }
                }
    
                return $model;
            }
        }
    }
}
