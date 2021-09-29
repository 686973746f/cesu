<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\City;
use App\Models\Forms;
use App\Exports\DOHExport;
use App\Exports\FormsExport;
use Illuminate\Http\Request;
use App\Exports\SitReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

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

    public function dohExportAll() {
        function suspectedGenerator() {
            foreach (Forms::where('status', 'approved')
            ->where('caseClassification', 'Suspect')
            ->where('outcomeCondition', 'Active')
            ->where(function ($q) {
                $q->whereDate('testDateCollected1', '<=', date('Y-m-d'))
                ->orWhereDate('testDateCollected2', '<=', date('Y-m-d'));
            })
            ->orderby('created_at', 'asc')
            ->cursor() as $user) {
                yield $user;
            }
        }

        function probableGenerator() {
            foreach (Forms::where('status', 'approved')
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->orderby('created_at', 'asc')->cursor() as $user) {
                yield $user;
            }
        }

        function confirmedGenerator() {
            foreach (Forms::where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', ['Active','Recovered', 'Died'])
            ->orderby('created_at', 'asc')->cursor() as $user) {
                yield $user;
            }
        }

        function negativeGenerator() {
            foreach (Forms::where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->orderby('created_at', 'asc')->cursor() as $user) {
                yield $user;
            }
        }

        $sheets = new SheetCollection([
            'Suspected' => suspectedGenerator(),
            'Probable' => probableGenerator(),
            'Confirmed' => confirmedGenerator(),
        ]);

        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())->setShouldWrapText()->build();

        return (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->download('GENTRI_COVID19_DATABASE_'.date('Ymd').'.xlsx', function ($form) {
            $arr_sas = explode(",", $form->SAS);
            $arr_othersas = explode(",", $form->SASOtherRemarks);
            $arr_como = explode(",", $form->COMO);

            if(is_null($form->testType2)) {
                $testType = $form->testType1;
                $testDate = date('m/d/Y', strtotime($form->testDateCollected1));
                $testReleased = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
                $testResult = $form->testResult1;
            }
            else {
                //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
                $testType = $form->testType2;
                $testDate = date('m/d/Y', strtotime($form->testDateCollected2));
                $testReleased = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
                $testResult = $form->testResult2;
            }

            if($form->dispoType == 1) {
                $dispo = 'ADMITTED AT HOSPITAL';
                $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 2) {
                $dispo = 'ADMITTED AT ISOLATION FACILITY';
                $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 3) {
                $dispo = 'HOME QUARANTINE';
                $dispoName = "N/A";
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 4) {
                $dispo = 'DISCHARGED';
                $dispoName = "N/A";
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 5) {
                $dispo = 'OTHERS';
                $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }

            return [
                'CIF Patient ID' => $form->id,
                'MM (Morbidity Month)' => date('m/d/Y', strtotime($form->created_at)),
                'MW (Morbidity Week' => Carbon::parse($form->created_at)->format('W'),
                'DATE REPORTED' => date('m/d/Y', strtotime($form->dateReported)),
                'DRU' => $form->drunit,
                'REGION OF DRU' => $form->drregion,
                'MUNCITY OF DRU' => $form->drprovince,
                'LAST NAME' => $form->records->lname,
                'FIRST NAME' => $form->records->fname,
                'MIDDLE NAME' => (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
                'DOB' => date('m/d/Y', strtotime($form->records->bdate)),
                'AGE (AGE IN YEARS)' => $form->records->getAge(),
                'SEX(M/F)' => substr($form->records->gender,0,1),
                'NATIONALITY' => $form->records->nationality,
                'REGION' => 'IV A',
                'PROVINCE/HUC' => $form->records->permaaddress_province,
                'MUNICIPALITY/CITY' => $form->records->permaaddress_city,
                'BARANGAY' => $form->records->permaaddress_brgy,
                'HOUSE N. AND STREET OR NEAREST LANDMARK' => $form->records->permaaddress_houseno.', '.$form->records->permaaddress_street,
                'CONTACT N.' => $form->records->mobile,
                'OCCUPATION' => (!is_null($form->records->occupation)) ? $form->records->occupation : "N/A",
                'HEALTHCARE WORKER(Y/N)' => ($form->isHealthCareWorker == 1) ? 'Y' : 'N',
                'PLACE OF WORK' => ($form->isHealthCareWorker == 1) ? $form->healthCareCompanyLocation : 'N/A',
                'SEVERITY OF THE CASE (ASYMTOMATIC,MILD,MODERATE,SEVERE,CRITICAL)' => $form->healthStatus,
                'PREGNANT (Y/N)' => ($form->records->isPregnant == 1) ? 'Y' : 'N',
                'ONSET OF ILLNESS' => (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
                'FEVER(Y/N)' => (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
                'COUGH (Y/N)' => (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
                'COLDS (Y/N)' => (in_array('COLDS', $arr_othersas) || in_array('COLD', $arr_othersas)) ? 'Y' : 'N',
                'DOB (Y/N)' => (in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
                'LOSS OF SMELL (Y/N)' => (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'Y' : 'N',
                'LOSS OF TASTE (Y/N)' => (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'Y' : 'N',
                'SORETHROAT (Y/N)' => (in_array('Sore throat', $arr_sas)) ? 'Y' : 'N',
                'DIARRHEA (Y/N)' => (in_array('Diarrhea', $arr_sas)) ? 'Y' : 'N',
                'OTHER SYMPTOMS' => (!is_null($form->SASOtherRemarks)) ? mb_strtoupper($form->SASOtherRemarks) : 'N/A',
                'W. COMORBIDITY (Y/N)' => ($form->COMO != 'None') ? 'Y' : 'N',
                'COMORBIDITY (HYPERTENSIVE, DIABETIC, WITH HEART PROBLEM, AND OTHERS)' => ($form->COMO != 'None') ? $form->COMO : 'N/A',
                'DATE OF SPECIMEN COLLECTION' => $testDate,
                'ANTIGEN (POSITIVE/NEGATIVE)' => ($testType == 'ANTIGEN') ? $testResult : 'N/A',
                'PCR(POSITIVE/NEGATIVE)' => ($testType == 'OPS' || $testType == 'NPS' || $testType == 'OPS AND NPS') ? $testResult : 'N/A',
                'RDT(+IGG, +IGM,NEGATIVE)' => ($testType == 'ANTIBODY') ? $testResult : 'N/A',
                'CLASSIFICATION (CONFIRMED,SUSPECTED,PROBABLE,FOR VALIDATION)' => $form->caseClassification,
                'QUARANTINE STATUS (ADMITTED,HOME QUARANTINE,TTMF,CLEARED,DISCHARGED)' => $dispo,
                'NAME OF FACILITY (FOR FACILITY QUARANTINE AND ADMITTED)' => $dispoName,
                'DATE START OF QUARANTINE' => $dispoDate,
                'DATE COMPLETED QUARANTINE (FOR HOME AND FACILITY QUARANTINE)' => ($form->dispoType == 4) ? $dispoDate : 'N/A',
                'OUTCOME(ALIVE/RECOVERED/DIED)' => $form->outcomeCondition,
                'DATE RECOVERED' => ($form->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($form->outcomeRecovDate)) : 'N/A',
                'DATE DIED' => ($form->outcomeCondition == 'Died') ? date('m/d/Y', strtotime($form->outcomeDeathDate)) : 'N/A',
                'CAUSE OF DEATH' => ($form->outcomeCondition == 'Died') ? mb_strtoupper($form->deathImmeCause) : 'N/A',
                'W. TRAVEL HISTORY(Y/N)' => ($form->expoitem2 == 1) ? 'Y' : 'N',
                'PLACE OF TRAVEL' => (!is_null($form->placevisited)) ? $form->placevisited : 'N/A',
                'DATE OF TRAVEL' => (!is_null($form->localDateDepart1)) ? date('m/d/Y', strtotime($form->localDateDepart1)) : 'N/A',
                'LSI (Y/N)' => ($form->isLSI == 1) ? 'Y' : 'N',
                'ADDRESS(LSI)' => ($form->isLSI == 1) ? $form->LSICity : 'N/A',
                'OFW(Y/N)' => ($form->isOFW == 1 && $form->ofwType == 1) ? 'Y': 'N',
                'PLACE OF ORIGIN (OFW)' => ($form->isOFW == 1 && $form->ofwType == 1) ? $form->OFWCountyOfOrigin : 'N/A',
                'DATE OF ARRIVAL (OFW)' => "N/A", //OFW DATE OF ARRIVAL, WALA NAMANG GANITO SA CIF DATABASE ROWS,
                'AUTHORIZED PERSON OUTSIDE RESIDENCE (Y/N)' => ($form->isLSI == 1 && $form->lsiType == 0) ? 'Y' : 'N',
                'LOCAL/IMPORTED CASE' => "UNKNOWN",
                'RETURNING OVERSEAS FILIPINO (Y/N)' => ($form->isOFW == 1 && $form->ofwType == 2) ? 'Y': 'N',
                'REMARKS' => (!is_null($form->remarks)) ? $form->remarks : 'N/A',
            ];
        });
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
