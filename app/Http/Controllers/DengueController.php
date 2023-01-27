<?php

namespace App\Http\Controllers;

use App\Models\Dengue;
use App\Models\Records;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DengueController extends Controller
{
    public function home() {
        return view('dengue.home');
    }

    public function cifhome() {

    }

    public function create_cif($record_id) {
        $id = $record_id;

        $d = Dengue::where('records_id', $id)->first();

        if($d) {
            return view('dengue.cif_exist', ['d' => $d]);
        }
        else {
            $r = Records::findOrFail($record_id);

            return $this->edit_cif(new Dengue())->with('d', $r);
        }
    }

    public function store_cif($record_id, Request $r) {
        $d = Records::findOrFail($record_id);

        $store = Dengue::create([
            'Region',
            'Province' => $d->address_province,
            'Muncity' => $d->address_city,
            'Streetpurok' => $d->address_houseno.', '.$d->address_street,
            'DateOfEntry' => $r->DateOfEntry,
            'DRU' => 'CHO',
            'PatientNum' => $r->PatientNum,
            'FirstName' => $d->fname,
            'FamilyName' => $d->lname,
            'FullName' => $d->lname.', '.$d->fname,
            'AgeYears' => $d->getAgeInt(),
            'AgeMons' => $d->getAgeMonths(),
            'AgeDays' => $d->getAgeDays(),
            'Sex' => $d->gender,
            'AddressOfDRU' => 'HOSPITAL ROAD, BRGY. PINAGTIPUNAN',
            'ProvOfDRU' => 'CAVITE',
            'MuncityOfDRU' => 'GENERAL TRIAS',
            'DOB' => $d->bdate,
            'Admitted' => $r->Admitted,
            'DAdmit' => $r->DAdmit,
            'DOnset' => $r->DOnset,
            'Type' => $r->Type,
            'LabTest' => $r->LabTest,
            'LabRes' => $r->LabRes,
            'ClinClass' => $r->ClinClass,
            'CaseClassification' => $r->CaseClassification,
            'Outcome' => $r->Outcome,
            'RegionOfDrU' => '04A',
            'EPIID' => Str::random(32),
            'DateDied' => $r->DateDied,
            'lcd10Code' => 'A90',
            'MorbidityMonth' => date('m'),
            'MorbidityWeek' => date('W'),
            'AdmitToEntry' => Carbon::parse($r->DateOfEntry)->diffInDays($r->DAdmit),
            'OnsetToAdmit' => Carbon::parse($r->DAdmit)->diffInDays($r->DOnset),
            'SentinelSite',
            'DeleteRecord' => NULL,
            'Year' => date('Y'),
            'Recstatus',
            'UniqueKey',
            'NameOfDru' => 'CITY HEALTH OFFICE OF GENERAL TRIAS',
            'ILHZ',
            'District',
            'Barangay' => $d->address_brgy,
            'TYPEHOSPITALCLINIC',
            'SENT' => 'N',
            'ip' => $d->isindg,
            'ipgroup' => ($d->isindg == 1) ? $d->indg_specify : NULL,
            'MiddleName' => $d->mname,
        ]);
    }

    public function edit_cif(Dengue $f) {
        return view('dengue.cif_form', ['c' => $f]);
    }
}
