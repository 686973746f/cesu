<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Patient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BakunaRecords;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index() {
        if(request()->input('q')) {
            $list = Patient::where(function ($q) {
                $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
                ->orWhere('id', request()->input('q'));
            })
            ->orderByRaw('lname ASC, fname ASC, mname ASC')
            ->paginate(10);
        }
        else {
            $list = Patient::orderBy('lname', 'ASC')->paginate(10);
        }
        
        return view('patientlist_index', [
            'list' => $list,
        ]);
    }

    public function create() {
        return view('patientlist_create');
    }

    public function store(Request $request) {
        $request->validate([

        ]);
        
        if(Patient::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->suffix, $request->bdate)) {
            return back()->with('msg', 'Unable to register new patient. Patient details already exists on the server.')
            ->with('msgtype', 'danger');
        }
        else {
            $foundunique = false;

            while(!$foundunique) {
                $for_qr = Str::random(20);
                
                $search = Patient::where('qr', $for_qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            if($request->has_bday == 'Yes') {
                $get_age = Carbon::parse($request->bdate)->age;
            }
            else {
                $get_age = $request->age;
            }

            $create = $request->user()->patient()->create([
                'lname' => mb_strtoupper($request->lname),
                'fname' => mb_strtoupper($request->fname),
                'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
                'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
                'bdate' => $request->bdate,
                'age' => $get_age,
                'gender' => $request->gender,
                'contact_number' => $request->contact_number,
                'address_region_code' => $request->address_region_code,
                'address_region_text' => $request->address_region_text,
                'address_province_code' => $request->address_province_code,
                'address_province_text' => $request->address_province_text,
                'address_muncity_code' => $request->address_muncity_code,
                'address_muncity_text' => $request->address_muncity_text,
                'address_brgy_code' => $request->address_brgy_text,
                'address_brgy_text' => $request->address_brgy_text,
                'address_street' => $request->address_street,
                'address_houseno' => $request->address_houseno,
    
                'qr' => $for_qr,
                'remarks' => ($request->filled('remarks')) ? $request->remarks : NULL,
                'ip' => request()->ip(),
            ]);
    
            return redirect()->route('patient_index')
            ->with('msg', 'Patient was added successfully.')
            ->with('pid', $create->id)
            ->with('msgtype', 'success');
        }
    }

    public function edit($id) {
        $data = Patient::findOrFail($id);

        $bcheck = BakunaRecords::where('patient_id', $data->id)->first();

        return view('patientlist_edit', [
            'd' => $data,
            'bcheck' => $bcheck,
        ]);
    }

    public function patient_viewbakunarecords($id) {
        $p = Patient::findOrFail($id);

        $list = BakunaRecords::where('patient_id', $p->id)->orderBy('created_at', 'DESC')->paginate(10);

        return view('patientlist_bakunarecords', [
            'p' => $p,
            'list' => $list,
        ]);
    }
    
    public function update($id, Request $request) {
        $request->validate([

        ]);

        $p = Patient::findOrFail($id);

        if(Patient::detectChangeName($request->lname, $request->fname, $request->mname, $request->suffix, $request->bdate, $p->id)) {
            return redirect()->back()
            ->with('msg', 'Unable to update. Patient already exists.')
            ->with('msgtype', 'warning');
        }
        else {
            if($request->has_bday == 'Yes') {
                $get_age = Carbon::parse($request->bdate)->age;
            }
            else {
                $get_age = $request->age;
            }

            $p->lname = mb_strtoupper($request->lname);
            $p->fname = mb_strtoupper($request->fname);
            $p->mname = ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL;
            $p->suffix = ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL;
            $p->bdate = $request->bdate;
            $p->age = $get_age;
            $p->gender = $request->gender;
            $p->contact_number = $request->contact_number;
            $p->address_region_code = $request->address_region_code;
            $p->address_region_text = $request->address_region_text;
            $p->address_province_code = $request->address_province_code;
            $p->address_province_text = $request->address_province_text;
            $p->address_muncity_code = $request->address_muncity_code;
            $p->address_muncity_text = $request->address_muncity_text;
            $p->address_brgy_code = $request->address_brgy_text;
            $p->address_brgy_text = $request->address_brgy_text;
            $p->address_street = $request->address_street;
            $p->address_houseno = $request->address_houseno;

            $p->remarks = ($request->filled('remarks')) ? $request->remarks : NULL;

            if($p->isDirty()) {
                $p->save();
            }

            return redirect()->route('patient_index')
            ->with('msg', 'Patient ['.$p->getName().' - #'.$p->id.'] was updated successfully.')
            ->with('msgtype', 'success');
        }
    }

    public function ajaxList(Request $request) {
        $list = [];
        if($request->has('q') && strlen($request->input('q')) > 1) {
            $search = mb_strtoupper($request->q);

            $data = Patient::where(function ($query) use ($search) {
                $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere('id', $search);
            })->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => $item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                ]);
            }
        }

        return response()->json($list);
    }
}
