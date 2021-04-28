<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LineListController extends Controller
{
    public function index() {
        return view('linelist_index');
    }

    public function createoni() {
        return view('linelist_createoni');
    }
}
