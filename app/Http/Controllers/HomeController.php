<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentWeek = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->format('W');
        return view('home', ['currentWeek' => $currentWeek]);
    }

    public function viewphp() {
        return phpinfo();
    }
}
