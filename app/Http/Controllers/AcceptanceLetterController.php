<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcceptanceLetter;
use PhpOffice\PhpWord\TemplateProcessor;

class AcceptanceLetterController extends Controller
{
    public function index() {
        $list = AcceptanceLetter::orderBy('created_at', 'desc')->paginate(10);
        
        return view('acceptanceletter', [
            'list' => $list,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
            'suffix' => 'nullable|regex:/^[\pL\s\-]+$/u|max:4',
            'sex' => 'required',
            'address_region_code' => 'required',
            'address_province_code' => 'required',
            'address_muncity_code' => 'required',
            'address_brgy_text' => 'required',
            'address_houseno' => 'required',
            'travelto' => 'required',
        ]);

        $request->user()->acceptanceletter()->create([
            'lname' => mb_strtoupper($request->lname),
            'fname' => mb_strtoupper($request->fname),
            'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
            'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
            'sex' => $request->sex,
            'address_region_code' => $request->address_region_code,
            'address_region_text' => mb_strtoupper($request->address_region_text),
            'address_province_code' => $request->address_province_code,
            'address_province_text' => mb_strtoupper($request->address_province_text),
            'address_muncity_code' => $request->address_muncity_code,
            'address_muncity_text' => mb_strtoupper($request->address_muncity_text),
            'address_brgy_code' => mb_strtoupper($request->address_brgy_text),
            'address_brgy_text' => mb_strtoupper($request->address_brgy_text),
            'address_houseno' => mb_strtoupper($request->address_houseno),
            'travelto' => mb_strtoupper($request->travelto),
        ]);

        return redirect()->route('acceptance.index')
        ->with('msg', 'Acceptance Letter was successfully created.')
        ->with('msgType', 'success');
    }

    public function printview($id) {
        $data = AcceptanceLetter::findOrFail($id);

        $number = date('j', strtotime($data->created_at));

        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if (($number %100) >= 11 && ($number%100) <= 13) {
            $abbreviation = $number. 'th';
        }
        else {
            $abbreviation = $number. $ends[$number % 10];
        }

        $gcheck = ($data->sex == 'M') ? 'MR' : 'MS';
        $gcheck1 = ($data->sex == 'M') ? 'He' : 'She';

        return view('acceptance_print', [
            'data' => $data,
            'curr_date' => $abbreviation.' of '.date('F, Y', strtotime($data->created_at)),
            'gcheck' => $gcheck,
            'gcheck1' => $gcheck1,
        ]);
    }
}