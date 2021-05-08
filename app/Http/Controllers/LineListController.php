<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;

class LineListController extends Controller
{
    public function index() {
        return view('linelist_index');
    }

    public function createoni() {

        $list = Forms::where('testDateCollected1', date('Y-m-d'))->get();

        $list = json_encode($list);

        return view('linelist_createoni', ['list' => $list]);
    }
}
