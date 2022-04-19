<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index() {
        Mail::to(['hihihisto@gmail.com'])->send(new TestMail());

        dd('EMAIL SENT '.date('Y-m-d H:i:s'));
    }
}
