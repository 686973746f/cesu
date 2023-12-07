<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\Records;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TkcExcelImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if($row['hasbeenpositive'] != "FALSE") {
            dd($row);
            
            //Check if TKC ID exists
            $check1 = Forms::where('tkc_id', $row['tkc_id'])
            ->first();

            if(!($check1)) {
                if($row['last_name'] != "" && $row['first_name'] != "") {
                    $lname = mb_strtoupper(str_replace([' ','-'], '', $row['last_name']));
                    $fname = mb_strtoupper(str_replace([' ','-'], '', $row['first_name']));
                    $mname = ($row['middle_name'] != "") ? mb_strtoupper(str_replace([' ','-'], '', $row['middle_name'])) : NULL;
                    $suffix = ($row['suffix'] != "") ? mb_strtoupper(str_replace([' ','-','.'], '', $row['suffix'])) : NULL;
                    $bdate = ($row['birthdate'] != "") ? Carbon::parse($row['birthdate'])->format('Y-m-d') : '1900-01-01';
                    
                    //Check if existing name and birthday exist
                    $check = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
                    ->where(function($q) use ($fname) {
                        $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
                        ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
                    })
                    ->whereDate('bdate', $bdate);

                    if(!($check->first())) {
                        if(!is_null($mname)) {
                            $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname);
                        }
                
                        if(!is_null($suffix)) {
                            $final_fname = $fname.' '.$suffix;

                            $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(suffix,'.',''),'-',''),' ','')"), $suffix)->first();
                        }
                        else {
                            $final_fname = $fname;

                            $check = $check->first();
                        }
                
                        if($check) {
                            $newRecord = false;
                            $frecord = $check;
                        }
                        else {
                            $newRecord = true;
                        }
                    }
                    else {
                        $newRecord = false;
                        $frecord = $check;
                    }

                    if($newRecord) {
                        //CREATE NEW RECORD

                        $final_houseno = '';

                        if($row['house_lot_number'] != "") {
                            $final_houseno = $row['house_lot_number'];
                        }

                        if($row['house_village_name'] != "") {
                            $final_houseno = $final_houseno.' '.$row['house_village_name'];
                        }

                        if($row['apartment_unit_number'] != "") {
                            $final_houseno = $final_houseno.' '.$row['apartment_unit_number'];
                        }

                        if($row['apartment_floor_number'] != "") {
                            $final_houseno = $final_houseno.' '.$row['apartment_floor_number'];
                        }

                        if($row['apartment_building_name'] != "") {
                            $final_houseno = $final_houseno.' '.$row['apartment_building_name'];
                        }

                        if($row['current_address_house_number'] != "") {
                            $final_houseno = $final_houseno.' '.$row['current_address_house_number'];
                        }

                        //42 = CESU BOT
                        $create_record = Records::create([
                            'is_confidential' => 0,
                            'user_id' => 42,
                            'status' => 'pending',
                            'lname' => mb_strtoupper(str_replace(['.',':'], '', $row['last_name'])),
                            'fname' => str_replace(['.',':'], '', $final_fname),
                            'mname' => (!is_null($mname)) ? mb_strtoupper($row['middle_name']) : NULL,
                            'gender' => mb_strtoupper($row['sex']),
                            'isPregnant' => ($row['illness_pregnancy'] == 'TRUE') ? 1 : 0,
                            'cs' => ($row['civil_status'] != '') ? mb_strtoupper($row['civil_status']) : 'SINGLE',
                            'nationality' => ($row['nationality'] != '') ? mb_strtoupper($row['nationality']) : 'FILIPINO',
                            'bdate' => $bdate,
                            'mobile' => $row['cellphone_number'],
                            'phoneno' => ($row['telephone_number'] != "") ? $row['telephone_number'] : NULL,
                            'email' => ($row['email_address'] != "") ? $row['email_address'] : NULL,
                            'philhealth' => ($row['philhealth_number'] != "") ? $row['philhealth_number'] : NULL,
                            'address_houseno' => mb_strtoupper($final_houseno),
                            'address_street' => mb_strtoupper($row['current_address_street']),
                            'address_brgy' => mb_strtoupper($row['current_address_barangay']),
                            'address_city' => 'GENERAL TRIAS',
                            'address_cityjson' => '042108',
                            'address_province' => 'CAVITE',
                            'address_provincejson' => '0421',
                            'permaaddressDifferent' => 0,
                            'permaaddress_houseno' => mb_strtoupper($final_houseno),
                            'permaaddress_street' => mb_strtoupper($row['current_address_street']),
                            'permaaddress_brgy' => mb_strtoupper($row['current_address_barangay']),
                            'permaaddress_city' => 'GENERAL TRIAS',
                            'permaaddress_cityjson' => '042108',
                            'permaaddress_province' => 'CAVITE',
                            'permaaddress_provincejson' => '0421',
                            'permamobile' => $row['cellphone_number'],
                            'permaphoneno' => ($row['telephone_number'] != "") ? $row['telephone_number'] : NULL,
                            'permaemail' => ($row['email_address'] != "") ? $row['email_address'] : NULL,
                            'hasOccupation' => ($row['occupation'] != "") ? 1 : 0,
                            'occupation' => ($row['occupation'] != "") ? mb_strtoupper($row['occupation']) : NULL,
                            'worksInClosedSetting' => 0,
                            'occupation_lotbldg' => NULL,
                            'occupation_street' => ($row['work_address'] != "") ? mb_strtoupper($row['work_address']) : NULL,
                            'occupation_brgy' => NULL,
                            'occupation_city' => NULL,
                            'occupation_cityjson' => NULL,
                            'occupation_province' => NULL,
                            'occupation_provincejson' => NULL,
                            'occupation_name' => ($row['employer_name'] != "") ? mb_strtoupper($row['employer_name']) : NULL,
                            'occupation_mobile' => ($row['office_phone'] != "") ? mb_strtoupper($row['office_phone']) : NULL,
                            'occupation_email' => NULL,
                            
                            'natureOfWork' => NULL,
                            'natureOfWorkIfOthers' => NULL,

                            'vaccinationDate1',
                            'vaccinationName1',
                            'vaccinationNoOfDose1',
                            'vaccinationFacility1',
                            'vaccinationRegion1',
                            'haveAdverseEvents1',

                            'vaccinationDate2',
                            'vaccinationName2',
                            'vaccinationNoOfDose2',
                            'vaccinationFacility2',
                            'vaccinationRegion2',
                            'haveAdverseEvents2',

                            'vaccinationDate3',
                            'vaccinationName3',
                            'vaccinationNoOfDose3',
                            'vaccinationFacility3',
                            'vaccinationRegion3',
                            'haveAdverseEvents3',

                            'vaccinationDate4',
                            'vaccinationName4',
                            'vaccinationNoOfDose4',
                            'vaccinationFacility4',
                            'vaccinationRegion4',
                            'haveAdverseEvents4',

                            'remarks' => NULL,
                            'sharedOnId' => NULL,

                            'isHCW' => ($row['sp_hcw'] == 'TRUE') ? 1 : 0,
                            'isPriority' => 0,
                            'isindg' => 0,
                            'indg_specify' => NULL,

                            'address_lat' => $row['current_address_lat'],
                            'address_lng' => $row['current_address_lng'],
                            'address_region_psgc' => $row['current_address_region_psgc'],
                            'address_province_psgc' => $row['current_address_province_psgc'],
                            'address_muncity_psgc' => $row['current_address_city_psgc'],
                            'address_brgy_psgc' => $row['current_address_barangay_psgc'],

                            'perma_address_lat' => NULL,
                            'perma_address_lng' => NULL,
                            'perma_address_region_psgc' => NULL,
                            'perma_address_province_psgc' => NULL,
                            'perma_address_muncity_psgc' => NULL,
                            'perma_address_brgy_psgc' => NULL,
                            
                            'occupation_address_lat' => $row['work_address_lat'],
                            'occupation_address_lng' => $row['work_address_lng'],
                            'occupation_address_region_psgc' => NULL,
                            'occupation_address_province_psgc' => NULL,
                            'occupation_address_muncity_psgc' => NULL,
                            'occupation_address_brgy_psgc' => NULL,

                            'from_tkc' => 1,
                        ]);

                        $get_patient_id = $create_record->id;
                    }
                    else {
                        //FETCH PATIENT ID
                        $get_patient_id = $frecord->id;
                    }

                    //CREATE NEW FORM
                    $create_form = Forms::create([
                        'from_tkc' => 1,
                        'system_isverified' => 0,

                        
                    ]);
                }
            }
        }
    }
}
