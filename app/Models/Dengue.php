<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dengue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'Region',
        'Province',
        'Muncity',
        'Streetpurok',
        'DateOfEntry',
        'DRU',
        'PatientNum',
        'FirstName',
        'FamilyName',
        'FullName',
        'AgeYears',
        'AgeMons',
        'AgeDays',
        'Sex',
        'AddressOfDRU',
        'ProvOfDRU',
        'MuncityOfDRU',
        'DOB',
        'Admitted',
        'DAdmit',
        'DOnset',
        'Type',
        'LabTest',
        'LabRes',
        'ClinClass',
        'CaseClassification',
        'Outcome',
        'RegionOfDrU',
        'EPIID',
        'DateDied',
        'lcd10Code',
        'MorbidityMonth',
        'MorbidityWeek',
        'AdmitToEntry',
        'OnsetToAdmit',
        'SentinelSite',
        'DeleteRecord',
        'Year',
        'Recstatus',
        'UniqueKey',
        'NameOfDru',
        'ILHZ',
        'District',
        'Barangay',
        'TYPEHOSPITALCLINIC',
        'SENT',
        'ip',
        'ipgroup',
        'MiddleName',
    ];

    public function records() {
        return $this->belongsTo(Records::class);
    }
}
