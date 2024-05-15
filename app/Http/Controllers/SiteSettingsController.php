<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use App\Models\BarangayHealthStation;

class SiteSettingsController extends Controller
{
    public function index() {
        $b = SiteSettings::find(1);

        return view('site_settings', [
            'b' => $b,
        ]);
    }

    public function update(Request $request) {
        $d = SiteSettings::updateOrCreate(['id' => 1], [
            'paswab_enabled' => $request->paswab_enabled,
            'paswab_antigen_enabled' => $request->paswab_antigen_enabled,
            'paswab_message_en' => $request->paswab_message_en,
            'paswab_message_fil' => $request->paswab_message_fil,
            'oniStartTime_pm' => $request->oniStartTime_pm,
            'oniStartTime_am' => $request->oniStartTime_am,
            'lockencode_enabled' => $request->lockencode_enabled,
            'lockencode_start_time' => $request->lockencode_start_time,
            'lockencode_end_time' => $request->lockencode_end_time,
            'lockencode_positive_enabled' => $request->lockencode_positive_enabled,
            'lockencode_positive_start_time' => $request->lockencode_positive_start_time,
            'lockencode_positive_end_time' => $request->lockencode_positive_end_time,
            'encodeActiveCasesCutoff' => $request->encodeActiveCasesCutoff,
            'listMobiles' => $request->listMobiles,
            'listTelephone' => $request->listTelephone,
            'listEmail' => $request->listEmail,
            'listLinkNames' => $request->listLinkNames,
            'listLinkURL' => $request->listLinkURL,
            'dilgCustomRespondentName' => $request->dilgCustomRespondentName,
            'dilgCustomOfficeName' => $request->dilgCustomOfficeName,
            'unvaccinated_days_of_recovery' => $request->unvaccinated_days_of_recovery,
            'partialvaccinated_days_of_recovery' => $request->partialvaccinated_days_of_recovery,
            'fullyvaccinated_days_of_recovery' => $request->fullyvaccinated_days_of_recovery,
            'booster_days_of_recovery' => $request->booster_days_of_recovery,
            'in_hospital_days_of_recovery' => $request->in_hospital_days_of_recovery,
            'severe_days_of_recovery' => $request->severe_days_of_recovery,
            'paswab_auto_schedule_if_symptomatic' => $request->paswab_auto_schedule_if_symptomatic,
            'cifpage_auto_schedule_if_symptomatic' => $request->cifpage_auto_schedule_if_symptomatic,
            'system_type' => $request->system_type,
            'default_dru_name' => $request->default_dru_name,
            'default_dru_region' => $request->address_region_text,
            'default_dru_region_json' => $request->address_region_code,
            'default_dru_province' => $request->address_province_text,
            'default_dru_province_json' => $request->address_province_code,
            'default_dru_citymun' => $request->address_muncity_text,
            'default_dru_citymun_json' => $request->address_muncity_code,
        ]);

        return back()
        ->withInput()
        ->with('msg', 'Saved.')
        ->with('msgType', 'success');
    }

    public function settingsHome() {
        return view('sitesettings.home');
    }

    public function generalSettings() {
        $d = SiteSettings::findOrFail(1);

        return view('sitesettings.general', [
            'd' => $d,
        ]);
    }

    public function generalSettingsUpdate(Request $r) {
        $d = SiteSettings::findOrFail(1);

        $d->default_holiday_dates = $r->default_holiday_dates;
        $d->custom_holiday_dates = $r->custom_holiday_dates;

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()->back()
        ->with('msg', 'Update was successful.')
        ->with('msgtype', 'success');
    }

    public function bhsPanel() {
        $list = BarangayHealthStation::orderBy('name', 'ASC')->get();

        return view('sitesettings.bhs', [
            'list' => $list,
        ]);
    }

    public function bhsView($id) {
        $d = BarangayHealthStation::findOrFail($id);

        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->get();

        return view('sitesettings.bhs_view', [
            'd' => $d,
            'brgy_list' => $brgy_list,
        ]);
    }

    public function bhsUpdate($id, Request $r) {
        $scode_check = BarangayHealthStation::where('id', '!=', $id)
        ->where('sys_code1', mb_strtoupper($r->sys_code1))
        ->first();

        if($scode_check) {
            return redirect()->back()
            ->with('msg', 'Error: New System Code already exists. Please change and try again.')
            ->with('msgtype', 'warning');
        }

        $d = BarangayHealthStation::findOrFail($id);

        if($r->filled('sys_coordinate_x')) {
            $r->validate([
                'sys_coordinate_x' => 'required',
                'sys_coordinate_y' => 'required',
            ]);
        }

        $d->brgy_id = $r->brgy_id;
        $d->name = mb_strtoupper($r->name);

        $d->assigned_personnel_name = mb_strtoupper($r->assigned_personnel_name);
        $d->assigned_personnel_position = mb_strtoupper($r->assigned_personnel_position);
        $d->assigned_personnel_contact_number = ($r->filled('assigned_personnel_contact_number')) ? $r->assigned_personnel_contact_number : NULL;

        $d->sys_code1 = mb_strtoupper($r->sys_code1);
        $d->sys_coordinate_x = $r->sys_coordinate_x;
        $d->sys_coordinate_y = $r->sys_coordinate_y;

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()->route('settings_bhs')
        ->with('msg', 'BHS Data was updated successfully.')
        ->with('msgtype', 'success');
    }
}
