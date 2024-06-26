<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ABTCUserSettingsController extends Controller
{
    public function save_settings(Request $request) {
        $u = User::findOrFail(auth()->user()->id);

        $u->abtc_default_vaccinationsite_id  = $request->default_vaccinationsite_id;

        if($u->isDirty()) {
            $u->save();
        }

        return redirect()->back()
        ->with('msg', 'User settings updated successfully.')
        ->with('msgtype', 'success');
    }
}
