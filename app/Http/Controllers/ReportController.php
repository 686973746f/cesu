<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\City;
use App\Models\Forms;
use App\Exports\DOHExport;
use App\Exports\FormsExport;
use Illuminate\Http\Request;
use App\Exports\DOHExportAll;
use App\Exports\SitReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index() {
        if(auth()->user()->isCesuAccount()) {
            return view('report_select');
        }
        else {
            if(auth()->user()->isBrgyAccount()) {

            }
            else if(auth()->user()->isCompanyAccount()) {
                
            }
        }
    }

    public function viewDaily() {
        $list = Forms::all();
        $brgy = Brgy::all();

        $listToday = Forms::where(function ($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->get();

        $notPresent = Forms::where(function ($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->where(function ($query) {
            $query->where('isPresentOnSwabDay', 0)
            ->orWhereNull('isPresentOnSwabDay');
        })->get();

        return view('report_daily', [
            'listToday' => $listToday,
            'notPresent' => $notPresent,
            'list' => $list,
            'brgy_list' => $brgy
        ]);
    }

    public function viewClustering($city_id, $brgy_id) {
        $city_data = City::findOrFail($city_id);
        $brgy_data = Brgy::findOrFail($brgy_id);

        $clustered_forms = Forms::where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereHas('records', function ($query) use ($brgy_data, $city_data){
            $query->where('records.address_brgy', $brgy_data->brgyName)
            ->where('records.address_city', $city_data->cityName);
        })->get();

        return view('report_clustering', [
            'clustered_forms' => $clustered_forms,
            'brgy_name' => $brgy_data->brgyName,
        ]);
    }

    public function viewSituational() {
        $forms = Forms::all();
        $brgy = Brgy::all();

        $formstotal = $forms->count();
        $formsActiveTotal = $forms->where('outcomeCondition', 'Active')->count();
        $formsConfirmedTotal = $forms->where('caseClassification', 'Confirmed')->count();
        $formsActiveConfirmedTotal = Forms::where('outcomeCondition', 'Active')->where('caseClassification', 'Confirmed')->count();
        $recoveryCount = $forms->where('outcomeCondition', 'Recovered')->count();
        $fatalityCount = $forms->where('outcomeCondition', 'Died')->count();
        $positiveCount = $forms->where('caseClassification', 'Confirmed')->count();
        $hqCount = $forms->where('dispositionType', 3)->where('outcomeCondition', 'Active')->where('caseClassification', 'Confirmed')->count();

        return view('report_situational', [
            'list' => $forms,
            'brgy_list' => $brgy,
            'formstotal' => $formstotal,
            'formsActiveTotal' => $formsActiveTotal,
            'formsConfirmedTotal' => $formsConfirmedTotal,
            'formsActiveConfirmedTotal' => $formsActiveConfirmedTotal,
            'recoveryCount' => $recoveryCount,
            'fatalityCount' => $fatalityCount,
            'positiveCount' => $positiveCount,
            'hqCount' => $hqCount,
            'recRate' => round(($recoveryCount / $formsActiveTotal) * 100, 2),
            'fatRate' => round(($fatalityCount / $formsActiveTotal) * 100, 2),
            'posRate' => round(($positiveCount / $formstotal) * 100, 2),
            'hqRate' => round(($hqCount / $formsActiveConfirmedTotal) * 100, 2),
        ]);
    }

    public function viewSituationalv2() {
        $brgyList = Brgy::where('city_id', 1) //dapat mai-automate ang city id soon base sa system settings
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        $formsList = Forms::with('records')->get();

        return view('situationalv2_index', [
            'brgyList' => $brgyList,
            'formsList' => $formsList,
        ]);
    }

    public function printSituationalv2() {
        return (new SitReportExport)->download('invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function makeAllSuspected() {
        $query = Forms::where(function($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->where('isPresentOnSwabDay', 0)
        ->update(['caseClassification' => 'Suspect']);

        return redirect()->action([ReportController::class, 'index'])->with('status', 'All patients who were absent for today were moved in SUSPECTED Case.')->with('statustype', 'success');
    }

    public function DOHExportAll() {
        return Excel::download(new DOHExportAll(), 'DOH_Excel_'.date('m_d_Y').'.xlsx');
    }

    public function reportExport(Request $request) {
        $request->validate([
            'eStartDate' => 'required|date|before:tomorrow',
            'eEndDate' => 'required|date|before:tomorrow',
            'rType' => 'required',
        ]);

        if($request->rType == "DOH") {
            $query = Forms::where(function ($q) use ($request) {
                $q->whereBetween('testDateCollected1', [$request->eStartDate, $request->eEndDate])
                ->orWhereBetween('testDateCollected2', [$request->eStartDate, $request->eEndDate]);
            })
            ->orderBy('testDateCollected1', 'ASC')
            ->orderBy('testDateCollected2', 'ASC')
            ->pluck('id')->toArray();

            return Excel::download(new DOHExport($query), 'DOH_Excel_'.date('m_d_Y').'.xlsx');
        }
        else {
            $query = Forms::where(function ($q) use ($request) {
                $q->whereBetween('testDateCollected1', [$request->eStartDate, $request->eEndDate])
                ->orWhereBetween('testDateCollected2', [$request->eStartDate, $request->eEndDate]);
            })
            ->orderBy('testDateCollected1', 'ASC')
            ->orderBy('testDateCollected2', 'ASC')
            ->pluck('id')->toArray();

            return Excel::download(new FormsExport($query), 'CIF_ALL_'.date("m_d_Y").'.xlsx');
        }
    }
}
