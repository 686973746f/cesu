<?php

namespace App\Imports;

use App\Models\DohFacility;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DohFacilityImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'MAIN' => new MainImport(),
        ];
    }
}

class MainImport implements ToModel, WithHeadingRow {
    public function model(array $r) {
        if(!(DohFacility::where('healthfacility_code', $r['health_facility_code'])->first())) {
            return new DohFacility([
                'healthfacility_code' => $r['health_facility_code'],
                'healthfacility_code_short' => $r['health_facility_code_short'],
                'facility_name' => $r['facility_name'],
                'facility_name_old1' => $r['old_health_facility_name_1'],
                'facility_name_old2' => $r['old_health_facility_name_2'],
                'facility_name_old3' => $r['old_health_facility_name_3'],
                'major_type' => $r['facility_major_type'],
                'facility_type' => $r['health_facility_type'],
                'ownership_type' => $r['ownership_major_classification'],
                'subclassification_government' => $r['ownership_sub_classification_for_government_facilities'],
                'subclassification_private' => $r['ownership_sub_classification_for_private_facilities'],
                'address_street' => $r['street_name_and'],
                'address_building' => $r['building_name_and'],
                'address_region' => $r['region_name'],
                'address_region_psgc' => $r['region_psgc'],
                'address_province' => $r['province_name'],
                'address_province_psgc' => $r['province_psgc'],
                'address_muncity' => $r['citymunicipality_name'],
                'address_muncity_psgc' => $r['citymunicipality_psgc'],
                'address_barangay' => $r['barangay_name'],
                'address_barangay_psgc' => $r['barangay_psgc'],
                'zip_code' => $r['zip_code'],
                'landline' => $r['landline_number'],
                'landline2' => $r['landline_number_2'],
                'fax' => $r['fax_number'],
                'email' => $r['email_address'],
                'email2' => $r['alternate_email_address'],
                'website' => $r['official_website'],
                'service_capability' => $r['service_capability'],
                'bed_capacity' => $r['bed_capacity'],
                'licensing_status' => $r['licensing_status'],
                'validity_date' => $r['license_validity_date'],
            ]);
        }
    }
}
