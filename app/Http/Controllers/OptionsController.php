<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function index() {
        return view('options');
    }

    public function submit(Request $request) {
        $usr = User::findOrFail(auth()->user()->id);

        $usr->option_enableAutoRedirectToCif = ($request->option_enableAutoRedirectToCif) ? 1 : 0;

        $usr->save();

        return redirect()->route('options.index')
        ->with('msg', 'Settings has been updated successfully.')
        ->with('msgtype', 'success');
    }
}
