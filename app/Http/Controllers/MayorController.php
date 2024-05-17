<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PharmacyBranch;
use App\Models\PharmacyStockCard;
use App\Models\PharmacySupplySub;

class MayorController extends Controller
{
    public function mainMenu() {
        return view('mayor.main_menu');
    }

    public function pharmacyMainMenu() {
        $list_branches = PharmacyBranch::where('enabled', 1)->orderBy('name', 'ASC')->get();

        return view('mayor.pharmacy_home', [
            'list_branches' => $list_branches,
        ]);
    }

    public function pharmacyChangeBranch(Request $r) {
        $d = User::findOrfail(auth()->user()->id);

        $d->pharmacy_branch_id = $r->select_branch;

        if($d->isDirty()) {
            $d->save();
        }
        
        return redirect()->route('mayor_pharmacy_main_menu')
        ->with('msg', 'Successfully changed Pharmacy Branch.')
        ->with('msgtype', 'success');
    }

    public function monthlyStock() {
        $selected_branch = auth()->user()->pharmacy_branch_id;

        if(request()->input('year')) {
            $year = request()->input('year');

            if(request()->input('year') == date('Y')) {
                $currentMonth1 = date('m'); //with leading zeroes 04,05,06
                $currentMonth2 = date('n'); //without leading zeroes 4,5,6
            }
            else {
                $currentMonth1 = 12; //with leading zeroes 04,05,06
                $currentMonth2 = 12; //without leading zeroes 4,5,6
            }
        }
        else {
            $year = date('Y');

            $currentMonth1 = date('m'); //with leading zeroes 04,05,06
            $currentMonth2 = date('n'); //without leading zeroes 4,5,6
        }

        //STOCKS MASTERLIST
        $list_subitem = PharmacySupplySub::where('pharmacy_branch_id', $selected_branch)
        ->whereYear('created_at', '<=', $year)
        ->get();

        $si_array = [];

        foreach($list_subitem as $key => $si) {
            $items_list[] = [
                'name' => $si->pharmacysupplymaster->name,
                'category' => $si->pharmacysupplymaster->category,
                'unit' => $si->pharmacysupplymaster->quantity_type,
                'current_stock' => $si->displayQty(),
                'id' => $si->id,
            ];
        }

        

        foreach($items_list as $item) {
            $monthlyStocks = [];
            

            for($i=1;$i<=$currentMonth2;$i++) {
                $nomonth = Carbon::create()->month($i)->format('m');

                if($item['unit'] == 'BOX') {
                    $issued_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->where('qty_type', 'BOX')
                    ->sum('qty_to_process');

                    $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->where('qty_type', 'BOX')
                    ->sum('qty_to_process');

                    $issued_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->where('qty_type', 'PIECE')
                    ->sum('qty_to_process');

                    $received_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->where('qty_type', 'PIECE')
                    ->sum('qty_to_process');

                    if($issued_count == 0 && $issued_count_piece == 0) {
                        $issued_txt = '';
                        $received_txt = '';
                    }
                    else {
                        if($issued_count == 0) {
                            $issued_txt = '';

                            if($issued_count_piece != 0) {
                                $issued_txt = '- ';
                            }
                        }
                        else {
                            $issued_txt = '- '.$issued_count.' '.Str::plural('BOX', $issued_count);
                        }

                        if($received_count == 0) {
                            $received_txt = '';
                            
                            if($received_count_piece != 0) {
                                $received_txt = '+ ';
                            }
                        }
                        else {
                            $received_txt = '+ '.$received_count.' '.Str::plural('BOX', $received_count);
                        }

                        if($issued_count_piece != 0) {
                            $issued_txt = $issued_txt.' '.$issued_count_piece.' '.Str::plural('PC', $issued_count_piece);
                        }

                        if($received_count_piece != 0) {
                            $received_txt = $received_txt.' '.$received_count_piece.' '.Str::plural('PC', $received_count_piece);
                        }
                    }
                    
                }
                else {
                    $issued_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'ISSUED')
                    ->sum('qty_to_process');

                    $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $nomonth)
                    ->where('status', 'approved')
                    ->where('type', 'RECEIVED')
                    ->sum('qty_to_process');

                    if($issued_count == 0) {
                        $issued_txt = '';
                    }
                    else {
                        $issued_txt = '- '.$issued_count.' '.Str::plural('PC', $issued_count);
                    }

                    if($received_count == 0) {
                        $received_txt = '';
                    }
                    else {
                        $received_txt = '+ '.$received_count.' '.Str::plural('PC', $received_count);
                    }
                }
                
                $monthlyStocks[] = [
                    'month' => Carbon::create()->month($i)->format('F'),
                    'issued' => $issued_txt,
                    'received' => $received_txt,
                ];
            }

            $si_array[] = [
                'name' => $item['name'],
                'category' => $item['category'],
                'unit' => $item['unit'],
                'id' => $item['id'],
                'current_stock' => $item['current_stock'],
                'monthly_stocks' => $monthlyStocks,
            ];
        }

        return view('mayor.pharmacy_monthlystock', [
            'currentMonth1' => $currentMonth1,
            'currentMonth2' => $currentMonth2,
            'si_array' => $si_array,
            'year' => $year,
        ]);
    }

    public function viewDispensary() {
        if(request()->input('sdate')) {
            $sdate = Carbon::parse(request()->input('sdate'))->format('Y-m-d');
        }
        else {
            $sdate = '2024-05-01';
        }

        $default_branch = auth()->user()->pharmacy_branch_id;

        $list_query = PharmacyStockCard::whereDate('created_at', $sdate)
        ->whereNotNull('receiving_patient_id')
        ->where('type', 'ISSUED')
        ->where('sentby_branch_id', $default_branch)
        ->groupBy('patient_prescription_id')
        ->pluck('patient_prescription_id');

        $final_array = [];

        foreach($list_query as $l) {
            $d = PharmacyStockCard::where('patient_prescription_id', $l)->latest()->first();

            $final_array[] = [
                'datetime' => date('m/d/Y h:i A', strtotime($d->created_at)),
                'name' => $d->getReceivingPatient->getName(),
                'age' => $d->getReceivingPatient->getAge(),
                'sex' => $d->getReceivingPatient->sg(),
                'barangay' => $d->getReceivingPatient->address_brgy_text,
                'medicine_given' => implode(", ", $d->getMedicineIssuanceList()),
                'quantity' => implode(", ", $d->getQuantityIssuanceList()),
                'encoder' => $d->user->name,
            ];
        }

        return view('')
    }
}
