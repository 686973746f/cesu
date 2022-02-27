<?php

namespace App\Http\Controllers;

use App\Models\Antigen;
use Illuminate\Http\Request;

class AntigenController extends Controller
{
    public function index() {
        $list = Antigen::orderBy('antigenKitName', 'ASC')->get();

        return view('antigen_index', [
            'list' => $list,
        ]);
    }

    public function create() {
        
    }

    public function store(Request $request) {
        
    }

    public function edit($id) {

    }

    public function update(Request $request, $id) {
        
    }
}
