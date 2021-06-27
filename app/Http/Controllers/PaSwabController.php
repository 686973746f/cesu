<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FormValidationRequest;

class PaSwabController extends Controller
{
    public function index() {
        return view('paswab_index');
    }

    public function store(FormValidationRequest $request) {
        
    }

    public function complete() {

    }
}
