<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\MonitoringSheetMaster;
use App\Models\MonitoringSheetSub;
use IlluminateAgnostic\Collection\Support\Str;

class MonitoringSheetController extends Controller
{
    public function create($id) {
        $data = Forms::findOrFail($id);

        $search = MonitoringSheetMaster::where('forms_id', $data->id)->first();

        if(!$search) {
            $create = new MonitoringSheetMaster;

            $create->forms_id = $data->id;
            $create->region = '4A';
            $create->date_lastexposure = (!is_null($data->expoDateLastCont)) ? $data->expoDateLastCont : NULL;
            $create->date_endquarantine = Carbon::parse($data->getLatestTestDate())->addDays(13)->format('Y-m-d');
            $create->magicURL = Str::random(30);

            $create->save();

            return redirect()->route('msheet.view', ['id' => $create->id])
            ->with('msg', 'Monitoring Sheet has been created successfully. You can now proceed.')
            ->with('msgtype', 'success');
        }
        else {
            return 'You are not allowed to do that';
        }
    }
    
    public function view($id) {
        $data = MonitoringSheetMaster::findOrFail($id);
        $subdata = MonitoringSheetSub::where('monitoring_sheet_masters_id', $data->id)->get();

        $period = CarbonPeriod::create(date('Y-m-d', strtotime($data->date_endquarantine.' -13 Days')), $data->date_endquarantine);

        return view('msheet_view', ['data' => $data, 'period' => $period, 'subdata' => $subdata]);
    }

    public function viewguest($magicurl) {
        
    }

    public function updatemonitoring(Request $request, $id, $date, $mer) {
        $master = MonitoringSheetMaster::findOrFail($id);

        $period = CarbonPeriod::create(date('Y-m-d', strtotime($master->date_endquarantine.' -13 Days')), $master->date_endquarantine)->toArray();
        $dateArr = [];
        foreach($period as $ind) {
            array_push($dateArr, $ind->format('Y-m-d'));
        }

        if(in_array($date, $dateArr)) {
            $sub = MonitoringSheetSub::updateOrCreate(['monitoring_sheet_masters_id' => $master->id, 'forDate' => $date, 'forMeridian' => $mer],
        [
            'fever' => !is_null($request->fevertemp) ? $request->fevertemp : NULL,
            'cough' => ($request->cough) ? 1 : 0,
            'sorethroat' => ($request->sorethroat) ? 1 : 0,
            'dob' => ($request->dob) ? 1 : 0,
            'colds' => ($request->colds) ? 1 : 0,
            'diarrhea' => ($request->diarrhea) ? 1 : 0,
            'os1' => !is_null($request->os1) ? mb_strtoupper($request->os1) : NULL,
            'os2' => !is_null($request->os2) ? mb_strtoupper($request->os2) : NULL,
            'os3' => !is_null($request->os3) ? mb_strtoupper($request->os3) : NULL,
        ]);

        return redirect()->route('msheet.view', ['id' => $master->id])
            ->with('msg', 'Status for ('.$date.' - '.$mer.') has been updated successfully.')
            ->with('msgtype', 'success');
        }
    }
}
