<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\PregnancyTrackingForm;
use Illuminate\Http\Request;

class PregnancyTrackingController extends Controller
{
    public function index() {
        return view('pregnancytracking.index');
    }

    public function new() {
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();
        
        return view('pregnancytracking.new', [
            'brgy_list' => $brgy_list,
        ]);
    }

    public function store(Request $r) {
        if(!(PregnancyTrackingForm::ifDuplicateFound($r->lname, $r->fname, $r->mname))) {
            $create = $r->user()->pregnancytrackingform()->create([
                'catchment_brgy' => $r->catchment_brgy,
                'lname' => mb_strtoupper($r->lname),
                'fname' => mb_strtoupper($r->fname),
                'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,

                'age' => $r->age,
                'street_purok' => $r->street_purok,
                'lmp' => $r->lmp,
                'edc' => $r->edc,

                'pc_done1_check' => ($r->pc_done1_check) ? 'Y' : 'N',
                'pc_done2_check' => ($r->pc_done2_check) ? 'Y' : 'N',
                'pc_done3_check' => ($r->pc_done3_check) ? 'Y' : 'N',
                'pc_done4_check' => ($r->pc_done4_check) ? 'Y' : 'N',
                
                'pc_done1' => $r->pc_done1,
                'pc_done2' => $r->pc_done2,
                'pc_done3' => $r->pc_done3,
                'pc_done4' => $r->pc_done4,
                'outcome'  => $r->outcome,
                'accomplished_by' => $r->accomplished_by,
            ]);

            return redirect()->route('ptracking_index')
            ->with('msg', 'Patient '.$create->getNameFormal().' was successfully created.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Patient already exists in the Pregnancy Tracking Form Database. Please double check and try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function edit() {

    }

    public function update() {

    }

    public function monthlyreport1() {

    }
}
