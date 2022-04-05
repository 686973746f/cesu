<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index() {
        foreach(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'] as $recipient) {
            Mail::to($recipient)->send(new TestMail());
        }
    }
}
