<?php

namespace App\Http\Controllers;

use App\Models\Dengue;
use App\Models\Records;
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

    public function store_cif($record_id, Request $request) {

    }

    public function edit_cif(Dengue $f) {
        return view('dengue.cif_form', ['c' => $f]);
    }
}
