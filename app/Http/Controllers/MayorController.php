<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PharmacyBranch;
use App\Models\PharmacyStockCard;
use App\Models\PharmacySupplySub;
use App\Models\PharmacyPrescription;
use App\Models\PharmacySupplyMaster;

class MayorController extends Controller
{
    public function mainMenu() {
        return view('mayor.main_menu');
    }

    public function pharmacyMainMenu() {
        $list_branches = PharmacyBranch::where('enabled', 1)
        ->whereIn('id', [1,2,66])
        ->orderBy('name', 'ASC')
        ->get();

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

    public function pharmacyReport() {
        $currentDate = Carbon::now();
        $currentMonth0 = date('n');
        $currentQuarter = ceil($currentDate->month / 3);

        if(request()->input('type') && request()->input('year')) {
            $type = request()->input('type');
            $year = request()->input('year');
            $quarter = request()->input('quarter');

            $currentMonth1 = Carbon::create()->month(request()->input('month'))->format('m'); //with leading zeroes 04,05,06
            $currentMonth2 = Carbon::create()->month(request()->input('month'))->format('n'); //without leading zeroes 4,5,6
            $currentMonth3 = Carbon::create()->month(request()->input('month'))->format('M');

            if($year == date('Y')) {
                if($type == 'QUARTERLY') {
                    if($currentQuarter != $quarter) {
                        return redirect()->route('mayor_pharmacy_main_menu')
                        ->with('msg', 'You are not allowed to do that.')
                        ->with('msgtype', 'warning');
                    }
                }
                else if($type == 'MONTHLY') {
                    if($currentMonth0 < $currentMonth2) {
                        return redirect()->route('mayor_pharmacy_main_menu')
                        ->with('msg', 'You are not allowed to do that.')
                        ->with('msgtype', 'warning');
                    }
                }
            }
            //$week = request()->input('month');
        }
        else {
            $type = 'MONTHLY';
            $year = date('Y');
            $quarter = ceil($currentDate->month / 3);
            
            $currentMonth1 = date('m'); //with leading zeroes 04,05,06
            $currentMonth2 = date('n'); //without leading zeroes 4,5,6
            $currentMonth3 = date('M');
        }

        $user_pharmacy_id = auth()->user()->pharmacy_branch_id;

        //TOP FAST MOVING MEDS
        $fast_moving = PharmacyStockCard::where('type', 'ISSUED')
        ->whereHas('pharmacysub', function ($q) use ($user_pharmacy_id) {
            $q->where('pharmacy_branch_id', $user_pharmacy_id);
        });

        if($type == 'YEARLY') {
            $fast_moving = $fast_moving->whereYear('created_at', $year)->get();

            $display_flavor = '('.auth()->user()->pharmacybranch->name.' YEAR: '.$year.')';
        }
        else if($type == 'QUARTERLY') {
            if($quarter == 1) {
                $start = Carbon::create($year, 1, 1); // January 1, 2024
                $end = Carbon::create($year, 3, 31)->endOfDay(); // March 31, 2024

                $quarter_flavor = '1ST QUARTER';
            }
            else if($quarter == 2) {
                $start = Carbon::create($year, 4, 1); // January 1, 2024
                $end = Carbon::create($year, 6, 30)->endOfDay(); // March 31, 2024

                $quarter_flavor = '2ND QUARTER';
            }
            else if($quarter == 3) {
                $start = Carbon::create($year, 7, 1); // January 1, 2024
                $end = Carbon::create($year, 9, 30)->endOfDay(); // March 31, 2024
                
                $quarter_flavor = '3RD QUARTER';
            }
            else if($quarter == 4) {
                $start = Carbon::create($year, 10, 1); // January 1, 2024
                $end = Carbon::create($year, 12, 31)->endOfDay(); // March 31, 2024

                $quarter_flavor = '4TH QUARTER';
            }

            $fast_moving = $fast_moving->whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])->get();

            $display_flavor = '('.auth()->user()->pharmacybranch->name.' YEAR: '.$year.' '.$quarter_flavor.')';
        }
        else if($type == 'MONTHLY') {
            $fast_moving = $fast_moving->whereYear('created_at', $year)
            ->whereMonth('created_at', $currentMonth2)
            ->get();

            $display_flavor = '('.auth()->user()->pharmacybranch->name.' YEAR: '.$year.' MONTH OF: '.$currentMonth3.')';
        }

        $fast_moving_group = $fast_moving->groupBy(function ($stockCard) {
            return $stockCard->pharmacysub->pharmacysupplymaster->id;
        });

        $fm_array = [];

        foreach($fast_moving_group as $id => $fm) {
            $qty_total = $fm->where('qty_type', 'PIECE')->sum('qty_to_process') + ($fm->where('qty_type', 'BOX')->sum('qty_to_process') * PharmacySupplyMaster::find($id)->config_piecePerBox);

            if($qty_total != 1) {
                $fm_array[] = [
                    'master_id' => $id,
                    'name' => PharmacySupplyMaster::find($id)->name,
                    'qty_total' => $qty_total,
                ];
            }
        }

        usort($fm_array, function ($a, $b) {
            return $b['qty_total'] - $a['qty_total'];
        });

        //TOP BRGY ISSUANCE
        $list_entities = PharmacyBranch::where('id', '!=', auth()->user()->pharmacy_branch_id)->get();

        foreach($list_entities as $e) {
            //CHECK IF HAS ISSUANCE RECORD FIRST
            $check = PharmacyStockCard::where('receiving_branch_id', $e->id)
            ->where('status', 'approved')
            ->where('type', 'ISSUED');

            $issue_box_qry = PharmacyStockCard::where('receiving_branch_id', $e->id)
            ->where('status', 'approved')
            ->where('type', 'ISSUED')
            ->where('qty_type', 'BOX');

            $issue_piece_qry = PharmacyStockCard::where('receiving_branch_id', $e->id)
            ->where('status', 'approved')
            ->where('type', 'ISSUED')
            ->where('qty_type', 'PIECE');

            if($type == 'YEARLY') {
                $check = $check->whereYear('created_at', $year)->first();

                if($check) {
                    $issue_box_qry = $issue_box_qry->whereYear('created_at', $year);
                    $issue_piece_qry = $issue_piece_qry->whereYear('created_at', $year);

                    $issued_box_count = ($issue_box_qry->sum('qty_to_process') * $check->pharmacysub->pharmacysupplymaster->config_piecePerBox);
                    //$issued_box_piece_count = $issue_piece_qry->sum('qty_to_process');
                }
            }
            else if($type == 'QUARTERLY') {
                $check = $check->whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])->first();

                if($check) {
                    $issue_box_qry = $issue_box_qry->whereBetween('created_at', [$start, $end]);
                    $issue_piece_qry = $issue_piece_qry->whereBetween('created_at', [$start, $end]);

                    $issued_box_count = ($issue_box_qry->sum('qty_to_process') * $check->pharmacysub->pharmacysupplymaster->config_piecePerBox);
                    //$issued_box_piece_count = $issue_piece_qry->sum('qty_to_process');
                }
                
            }
            else if($type == 'MONTHLY') {
                $check = $check->whereMonth('created_at', $currentMonth2)->first();

                if($check) {
                    $issue_box_qry = $issue_box_qry->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth2);
                    $issue_piece_qry = $issue_piece_qry->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth2);

                    $issued_box_count = ($issue_box_qry->sum('qty_to_process') * $check->pharmacysub->pharmacysupplymaster->config_piecePerBox);
                    //$issued_box_piece_count = $issue_piece_qry->sum('qty_to_process');
                }
                
            }

            $issued_box_piece_count = $issue_piece_qry->sum('qty_to_process');
            
            if($check) {
                $entities_arr[] = [
                    'id' => $e->id,
                    'name' => $e->name,
                    'issued_qty_total' => $issued_box_count + $issued_box_piece_count,
                ];
            }
        }

        usort($entities_arr, function ($a, $b) {
            return $b['issued_qty_total'] - $a['issued_qty_total'];
        });

        //AGE GROUP
        $age_group_set_male = [];
        $age_group_set_female = [];

        $age_group_conditions = [
            ['operator' => '<=', 'values' => 10],
            ['operator' => 'BETWEEN', 'values' => [11,20]],
            ['operator' => 'BETWEEN', 'values' => [21,30]],
            ['operator' => 'BETWEEN', 'values' => [31,40]],
            ['operator' => 'BETWEEN', 'values' => [41,50]],
            ['operator' => 'BETWEEN', 'values' => [51,60]],
            ['operator' => '>', 'values' => 60],
        ];

        foreach($age_group_conditions as $ag) {
            $qry_male = PharmacyStockCard::whereNotNull('receiving_patient_id')
            ->whereHas('getReceivingPatient', function ($q) {
                $q->where('gender', 'MALE');
            });

            $qry_female = PharmacyStockCard::whereNotNull('receiving_patient_id')
            ->whereHas('getReceivingPatient', function ($q) {
                $q->where('gender', 'FEMALE');
            });

            if($ag['operator'] != 'BETWEEN') {
                $qry_male = $qry_male->where('patient_age_years', $ag['operator'], $ag['values']);
                $qry_female = $qry_female->where('patient_age_years', $ag['operator'], $ag['values']);
            }
            else {
                $qry_male = $qry_male->whereBetween('patient_age_years', $ag['values']);
                $qry_female = $qry_female->whereBetween('patient_age_years', $ag['values']);
            }

            if($type == 'YEARLY') {
                $qry_male = $qry_male->whereYear('created_at', $year);
                $qry_female = $qry_female->whereYear('created_at', $year);
            }
            else if($type == 'QUARTERLY') {
                $qry_male = $qry_male->whereBetween('created_at', [$start, $end]);
                $qry_female = $qry_female->whereBetween('created_at', [$start, $end]);
            }
            else if($type == 'MONTHLY') {
                $qry_male = $qry_male->whereYear('created_at', $year)
                ->whereMonth('created_at', $currentMonth2);
                $qry_female = $qry_female->whereYear('created_at', $year)
                ->whereMonth('created_at', $currentMonth2);
            }
            
            $age_group_set_male[] = $qry_male->groupBy('receiving_patient_id')
            ->pluck('receiving_patient_id')
            ->count();

            $age_group_set_female[] = $qry_female->groupBy('receiving_patient_id')
            ->pluck('receiving_patient_id')
            ->count();
        }

        //GET PATIENT TOP REASON FOR MEDS
        $get_grouped_prescription = PharmacyStockCard::where('type', 'ISSUED')
        ->whereNotNull('receiving_patient_id');

        if($type == 'YEARLY') {
            $get_grouped_prescription = $get_grouped_prescription->whereYear('created_at', $year);
        }
        else if($type == 'QUARTERLY') {
            $get_grouped_prescription = $get_grouped_prescription->whereBetween('created_at', [$start, $end]);
        }
        else if($type == 'MONTHLY') {
            $get_grouped_prescription = $get_grouped_prescription->whereYear('created_at', $year)
            ->whereMonth('created_at', $currentMonth2);
        }

        $get_grouped_prescription = $get_grouped_prescription->groupBy('patient_prescription_id')
        ->pluck('patient_prescription_id');

        $reason_array = [];

        foreach($get_grouped_prescription as $p) {
            //get latest prescription
            $sp = PharmacyPrescription::find($p);

            $exploded_reasons = explode(',', $sp->concerns_list);

            foreach($exploded_reasons as $er) {
                $found = false;

                foreach ($reason_array as &$reason) {
                    if ($reason['name'] === $er) {
                        // Increment the count if 'name' exists
                        $reason['count']++;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $reason_array[] = [
                        'name' => $er,
                        'count' => 1,
                    ];
                }
            }
        }

        // Filter out reasons with a count less than 10
        $reason_array = array_filter($reason_array, function($reason) {
            return $reason['count'] >= 10;
        });

        /*
        if($type == 'YEARLY') {

        }
        else if($type == 'QUARTERLY') {

        }
        else if($type == 'MONTHLY') {
            
        }
        */

        return view('mayor.pharmacy_report', [
            'fm_array' => $fm_array,
            'entities_arr' => $entities_arr,
            'age_group_set_male' => $age_group_set_male,
            'age_group_set_female' => $age_group_set_female,
            'reason_array' => $reason_array,
            'display_flavor' => $display_flavor,
            'selected_type' => $type,
            'selected_year' => $year,
            'selected_month' => $currentMonth2,
            'selected_quarter' => $quarter,
        ]);
    }

    public function pharmacyInventory() {
        $list = PharmacySupplySub::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
        ->get();

        return view('mayor.pharmacy_inventory', [
            'list' => $list,
        ]);
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
            $sdate = date('Y-m-d');
        }

        return view('mayor.pharmacy_dispensary', [
            'sdate' => $sdate,
        ]);
    }
}
