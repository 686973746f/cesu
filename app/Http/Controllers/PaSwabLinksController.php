<?php

namespace App\Http\Controllers;

use App\Models\PaSwabLinks;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use IlluminateAgnostic\Collection\Support\Str;

class PaSwabLinksController extends Controller
{
    public function index() {
        if(request()->input('q')) {
            $search = request()->input('q');

            $data = PaSwabLinks::where('code', 'LIKE', '%'.$search.'%')
            ->orWhere('id', $search)
            ->paginate(10);
        }
        else {
            $data = PaSwabLinks::orderBy('created_at', 'desc')->paginate(10);
        }
        
        $interviewers = Interviewers::orderBy('lname', 'asc')->get();
        return view('paswablinks_index', ['data' => $data, 'interviewers' => $interviewers]);
    }

    public function store(Request $request) {
        $request->validate([
            'code' => 'required|string|max:20',
            'interviewer_id' => 'required|numeric',
            'is_lock_code' => 'required|in:Yes,No',
            'address_cityjson' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'address_provincejson' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'address_province' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'address_city' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'saddress_province' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'saddress_city' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'address_brgy' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'taddress_brgy' => ($request->is_lock_code == 'Yes') ? 'required' : 'nullable',
            'lock_subd_array' => 'nullable',
        ]);

        $check = PaSwabLinks::where('code', mb_strtoupper($request->code))->first();

        if(!$check) {
            $request->user()->paSwabLink()->create([
                'code' => mb_strtoupper($request->code),
                'secondary_code' => mb_strtoupper(Str::random(6)),
                'interviewer_id' => $request->interviewer_id,
                'enableLockAddress' => ($request->is_lock_code == 'Yes') ? 1 : 0,
                'lock_brgy' => ($request->is_lock_code == 'Yes') ? mb_strtoupper($request->taddress_brgy) : NULL,
                'lock_city' => ($request->is_lock_code == 'Yes') ? $request->address_cityjson : NULL,
                'lock_city_text' => ($request->is_lock_code == 'Yes') ? mb_strtoupper($request->address_city) : NULL,
                'lock_province' => ($request->is_lock_code == 'Yes') ? $request->address_provincejson : NULL,
                'lock_province_text' => ($request->is_lock_code == 'Yes') ? mb_strtoupper($request->address_province) : NULL,
                'lock_subd_array' => ($request->filled('lock_subd_array')) ? mb_strtoupper($request->lock_subd_array) : NULL,
            ]);

            return redirect()->action([PaSwabLinksController::class, 'index'])
            ->with('msg', 'Pa-Swab Link Code has been created successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->action([PaSwabLinksController::class, 'index'])
            ->with('msg', 'There was an error processing your request. Pa-Swab Link Code already exists in the sytem. Please input another code and then try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function linkInit($id, Request $request) {
        $item = PaSwabLinks::findOrFail($id);

        if($request->submit == 'activeInit') {
            if($item->active == 1) {
                $update = PaSwabLinks::where('id', $id)->update([
                    'active' => 0,
                ]);
            }
            else {
                $update = PaSwabLinks::where('id', $id)->update([
                    'active' => 1,
                ]);
            }

            return redirect()->action([PaSwabLinksController::class, 'index'])
            ->with('msg', 'Pa-Swab Link Code status has been updated successfully.')
            ->with('msgtype', 'success');
        }
        else if($request->submit == 'changeSecondaryCode') {
            $update = PaSwabLinks::where('id', $id)->update([
                'secondary_code' => mb_strtoupper(Str::random(6)),
            ]);

            return redirect()->action([PaSwabLinksController::class, 'index'])
            ->with('msg', 'Pa-Swab Secondary Link Code for ('.$item->code.') has been updated successfully.')
            ->with('msgtype', 'success');
        }
    }
}
