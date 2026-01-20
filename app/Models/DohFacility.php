<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DohFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'healthfacility_code',
        'healthfacility_code_short',
        'facility_name',
        'facility_name_old1',
        'facility_name_old2',
        'facility_name_old3',
        'major_type',
        'facility_type',
        'ownership_type',
        'subclassification_government',
        'subclassification_private',
        'address_street',
        'address_building',
        'address_region',
        'address_region_psgc',
        'address_province',
        'address_province_psgc',
        'address_muncity',
        'address_muncity_psgc',
        'address_barangay',
        'address_barangay_psgc',
        'zip_code',
        'landline',
        'landline2',
        'fax',
        'email',
        'email2',
        'email_edcs',
        'is_weeklyreport_submitter',
        'edcs_defaultreporter_name',
        'edcs_defaultreporter_position',
        'edcs_defaultreporter_contactno',
        'edcs_shortname',
        'website',
        'service_capability',
        'bed_capacity',
        'licensing_status',
        'validity_date',

        'sys_code1',
        'sys_opdaccess_type',
        'pharmacy_branch_id',
        'logo_path',
        'letterhead_path',
        'sys_coordinate_x',
        'sys_coordinate_y',

        'edcs_region_code',
        'edcs_province_code',
        'edcs_muncity_code',
        'edcs_brgy_code',
        'edcs_service_capability',
        'edcs_region_name',
        'edcs_province_name',

        'abtc_facility_name',
        'abtc_facility_code',
        'abtc_address_houseno',
        'abtc_doh_certificate',
        'edcs_province_name',
        'abtc_professional1_id',
        'abtc_professional2_id',
        'abtc_professional3_id',
        'abtc_head_id',
        'abtc_accountant_name_position',

        'abtc_name',
        'abtc_code',
        'enable_customemr1',
    ];

    public function getRegionData() {
        $f = Regions::where('regionName', $this->address_region)->first();

        return $f;
    }

    public function getFacilityTypeShort() {
        //mostly being used for Mpox, for now
        if($this->facility_type == 'City Health Office') {
            return 'C/MHO';
        }
        else if($this->facility_type == 'Infirmary' && $this->ownership_type == 'Government') {
            return 'GOVT HOSPITAL';
        }
        else if($this->facility_type == 'Hospital' && $this->ownership_type == 'Private') {
            return 'PRIVATE LABORATORY';
        }
        else {
            return $this->facility_type;
        }
    }

    public function getAddress() {
        return 'BRGY. '.$this->address_barangay.', '.$this->address_muncity.', '.$this->address_province;
    }
}
