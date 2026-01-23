<?php

namespace App\Http\Controllers;

use App\Models\InhouseChildCare;
use App\Models\InhouseMaternalCare;
use App\Models\SyndromicPatient;
use Illuminate\Http\Request;

class ElectronicTclController extends Controller
{
    public function newMaternalCare($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        return $this->newOrEditMaternalCare(new InhouseMaternalCare(), 'NEW', $d->id);
    }

    public function storeMaternalCare(Request $r, $patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $c = InhouseChildCare::create([

        ]);
    }

    public function editMaternalCare($id) {
        $d = InhouseMaternalCare::findOrFail($id);
        
        return $this->newOrEditMaternalCare($d, 'EDIT');
    }

    public function newOrEditMaternalCare(InhouseMaternalCare $record, $mode, $patient_id = null) {
        if($patient_id != null) {
            $patient = SyndromicPatient::findOrFail($patient_id);
        }

        return view('efhsis.etcl.maternalcare_encode', [
            'd' => $record,
            'mode' => $mode,
            'patient' => $patient,
        ]);
    }

    public function updateMaternalCare(Request $r, $id) {
        $d = InhouseMaternalCare::findOrFail($id);

        
    }

    public function newChildCare($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        return view('efhsis.etcl.childcare_encode', compact('d'));
    }

    public function storeChildCare(Request $r, $patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $c = InhouseChildCare::create([

        ]);
    }

    public function generateM1(Request $r,) {

    }
}
