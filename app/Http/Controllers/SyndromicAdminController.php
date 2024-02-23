<?php

namespace App\Http\Controllers;

use App\Models\DohFacility;
use App\Models\SyndromicDoctor;
use Illuminate\Http\Request;

class SyndromicAdminController extends Controller
{
    public function doctors_index() {
        $list = SyndromicDoctor::orderBy('created_at', 'DESC')
        ->paginate(10);

        $facility_list = DohFacility::where('address_muncity', 'CITY OF GENERAL TRIAS')
        ->orderBy('facility_name', 'ASC')
        ->get();

        return view('syndromic.admin.doctors_index', [
            'list' => $list,
            'facility_list' => $facility_list,
        ]);
    }

    public function doctors_store(Request $r) {
        $s = SyndromicDoctor::where('doctor_name', mb_strtoupper($r->doctor_name))->first();

        if($s) {
            return redirect()->back()
            ->with('msg', 'Error: Doctor name already exists.')
            ->with('msgtype', 'danger');
        }

        $getFacility = DohFacility::findOrFail($r->facility_id);

        $c = SyndromicDoctor::create([
            'facility_id' => $r->facility_id,
            'doctor_name' => mb_strtoupper($r->doctor_name),
            'gender' => $r->gender,
            'position' => mb_strtoupper($r->position),
            'dru_name' => $getFacility->facility_name,
            'reg_no' => $r->reg_no,
        ]);

        return redirect()->route('syndromic_admin_doctors_index')
        ->with('msg', 'Doctor data successfully added.')
        ->with('msgtype', 'success');
    }

    public function doctors_edit($id) {
        $d = SyndromicDoctor::findOrFail($id);

        return view('syndromic.admin.doctors_view', [
            'd' => $d,
        ]);
    }

    public function doctors_update($id, Request $r) {

    }
}
