<?php

namespace App\Http\Controllers;

use App\Models\Records;
use App\Models\MonkeyPox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonkeyPoxController extends Controller
{
    public function home() {
        return view('monkeypox.home');
    }

    public function ajaxlist(Request $request) {
        $list = [];

        if($request->has('q') && strlen($request->input('q')) > 1) {
            $search = mb_strtoupper($request->q);
            
            $search_rep = str_replace(',','', $search);

            $data = Records::where(function ($query) use ($search, $search_rep) {
                $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%$search_rep%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%$search_rep%")
                ->orWhere('id', $search);
            })->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => '#'.$item->id.' - '.$item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                    'class' => 'cif',
                ]);
            }
        }

        return response()->json($list);
    }

    public function view_report() {

    }

    public function export_report() {

    }

    public function view_records() {

    }

    public function view_cif() {

    }

    public function create_cif($record_id) {
        $id = $record_id;

        $d = MonkeyPox::where('records_id', $id)->first();

        if($d) {

        }
        else {
            $r = Records::findOrFail($record_id);

            return view('monkeypox.cif_create', ['d' => $r]);
        }
    }

    public function store_cif($record_id, Request $request) {

    }

    public function edit_cif($cif_id) {

    }

    public function update_cif($cif_id, Request $request) {
        
    }
}
