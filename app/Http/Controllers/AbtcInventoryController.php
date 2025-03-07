<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbtcInventoryMaster;
use App\Models\AbtcInventoryStock;
use Illuminate\Support\Facades\Auth;
use App\Models\AbtcInventorySubMaster;
use App\Models\AbtcInventoryTransaction;
use App\Models\AbtcVaccinationSite;

class AbtcInventoryController extends Controller
{
    public function home() {
        //Viewing Recent Transactions on Branch

        /*
        if(auth()->user()->isGlobalAdmin()) {
            $list = AbtcInventoryTransaction::orderBy('created_at', 'DESC')
            ->paginate(10);
        }
        else {
            $list = AbtcInventoryTransaction::whereHas('stock.submaster', function ($q) {
                $q->where('abtc_facility_id', auth()->user()->abtc_default_vaccinationsite_id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        }
        */

        $list = AbtcInventoryTransaction::whereHas('stock.submaster', function ($q) {
            $q->where('abtc_facility_id', auth()->user()->abtc_default_vaccinationsite_id);
        })
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        $qt_list = AbtcInventorySubMaster::where('abtc_facility_id', auth()->user()->abtc_default_vaccinationsite_id)->get();

        $transfer_branches_list = AbtcVaccinationSite::where('id', '!=', auth()->user()->abtc_default_vaccinationsite_id)->get();

        return view('abtc.inventory.home', [
            'list' => $list,
            'qt_list' => $qt_list,
            'transfer_branches_list' => $transfer_branches_list,
        ]);
    }

    public function masterInventoryHome() {
        $list = AbtcInventoryMaster::orderBy('name', 'ASC')->get();

        return view('abtc.inventory.masterlist_home', [
            'list' => $list,
        ]);
    }

    public function storeMaster(Request $r) {
        $name = mb_strtoupper($r->name);

        $check = AbtcInventoryMaster::where('name', $name)->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'ERROR: Item already exists in the database. Kindly check and try again.')
            ->with('msgtype', 'warning');
        }

        $c = AbtcInventoryMaster::create([
            'name' => $name,
            'description' => ($r->description) ? mb_strtoupper($r->description) : NULL,
            'uom' => $r->uom,
            'created_by' => Auth::id(),
        ]);

        //Create SubMaster on Every Branch
        $branch_list = AbtcVaccinationSite::get();

        foreach($branch_list as $b) {
            $cb = AbtcInventorySubMaster::create([
                'master_id' => $c->id,
                'abtc_facility_id' => $b->id,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->back()
        ->with('msg', 'Item was added to the masterlist successfully.')
        ->with('msgtype', 'success');
    }

    public function updateMaster($master_id, Request $r) {
        $d = AbtcInventoryMaster::findOrFail($master_id);

        $d->enabled = $r->enabled;
        $d->name = $r->name;
        $d->description = $r->description;
        $d->uom = $r->uom;

        if($d->isDirty()) {
            $d->updated_by = Auth::id();
            $d->save();
        }

        return redirect()->back()
        ->with('msg', 'Masterlist ID: '.$d->id.' ('.$d->name.') was updated successfully.')
        ->with('msgtype', 'success');
    }

    public function branchInventoryHome() {
        $list = AbtcInventorySubMaster::where('abtc_facility_id', auth()->user()->abtc_default_vaccinationsite_id)->get();

        return view('abtc.inventory.branch_itemlist', [
            'list' => $list,
        ]);
    }

    public function viewBranchInventoryItem($sub_id) {
        $d = AbtcInventorySubMaster::findOrFail($sub_id);

        if(!auth()->user()->isGlobalAdmin()) {
            if($d->abtc_facility_id != auth()->user()->abtc_default_vaccinationsite_id) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        //List Stocks
        $stock_list = AbtcInventoryStock::where('sub_id', $d->id)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        //List Recent Transactions
        $transaction_list = AbtcInventoryTransaction::whereHas('stock', function ($q) use ($d) {
            $q->where('sub_id', $d->id);
        })->orderBy('created_at', 'DESC')
        ->paginate(30);

        return view('abtc.inventory.branch_viewitem', [
            'd' => $d,
            'stock_list' => $stock_list,
            'transaction_list' => $transaction_list,
        ]);
    }

    public function viewMoreStocks($sub_id) {
        $d = AbtcInventorySubMaster::findOrFail($sub_id);

        if(!auth()->user()->isGlobalAdmin()) {
            if($d->abtc_facility_id != auth()->user()->abtc_default_vaccinationsite_id) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        //List Stocks
        $list = AbtcInventoryStock::where('sub_id', $d->id)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        return view('abtc.inventory.branch_viewitem', [
            'd' => $d,
            'list' => $list,
        ]);
    }

    public function viewMoreTransactions($sub_id) {
        $d = AbtcInventorySubMaster::findOrFail($sub_id);

        if(!auth()->user()->isGlobalAdmin()) {
            if($d->abtc_facility_id != auth()->user()->abtc_default_vaccinationsite_id) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        $list = AbtcInventoryTransaction::whereHas('stock', function ($q) use ($d) {
            $q->where('sub_id', $d->id);
        })->orderBy('created_at', 'DESC')
        ->paginate(30);
        
        return view('abtc.inventory.branch_more_transactions', [
            'd' => $d,
            'list' => $list,
        ]);
    }

    public function updateBranchInventoryItem($sub_id, Request $r) {

    }

    public function processTransaction(Request $r) {
        $d = AbtcInventorySubMaster::findOrFail($r->sub_id);

        //Check Permission
        if(!auth()->user()->isGlobalAdmin()) {
            if($d->abtc_facility_id != auth()->user()->abtc_default_vaccinationsite_id) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        if($r->transaction_type == 'ISSUED') {
            $s = AbtcInventoryStock::findOrFail($r->stock_id);

            if($s->sub_id != $d->id) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }

            if($r->qty_to_process > $s->current_qty) {
                return redirect()->back()
                ->with('msg', 'Error: Quantity to Process is greater than the Available Quantity. Kindly double check and try again.')
                ->with('msgtype', 'warning');
            }
            else {
                $before_qty = $s->current_qty;
                $s->current_qty = $s->current_qty - $r->qty_to_process;
                $after_qty = $s->current_qty;

                if($s->isDirty()) {
                    $s->save();
                }

                $d = AbtcInventoryTransaction::create([
                    'transaction_date' => $r->transaction_date,
                    'stock_id' => $s->id,
                    'type' => 'ISSUED',
                    'process_qty' => $r->qty_to_process,
                    'before_qty' => $before_qty,
                    'after_qty' => $after_qty,
                    'po_number', ($r->po_number) ? mb_strtoupper($r->po_number) : NULL,
                    'unit_price' => $r->unit_price,
                    'unit_price_amount' => ($r->current_qty * $r->unit_price),
                    'remarks' => ($r->remarks) ? mb_strtoupper($r->remarks) : NULL,
                    'created_by' => Auth::id(),
                ]);
            }
        }
        else if($r->transaction_type == 'RECEIVED') {
            $batch_no = mb_strtoupper($r->batch_no);
            
            $check = AbtcInventoryStock::where('sub_id', $d->id)
            ->where('batch_no', $batch_no)
            ->first();

            if($check) {
                /*
                return redirect()->back()
                ->with('msg', 'Error: Same Batch Number already exists in the database. Kindly double check and try again.')
                ->with('msgtype', 'warning');
                */

                //Add to Existing Batch the Quantity

                $before_qty = $check->current_qty;
                $check->current_qty = $check->current_qty + $r->current_qty;
                $after_qty = $check->current_qty;

                if($check->isDirty()) {
                    $check->save();
                }

                $stock_id = $check->id;
            }
            else {
                $c = AbtcInventoryStock::create([
                    'sub_id' => $d->id,
                    'batch_no' => $batch_no,
                    'expiry_date' => $r->expiry_date,
                    'source' => $r->source,
    
                    'current_qty' => $r->current_qty,
                    'created_by' => Auth::id(),
                ]);
                $stock_id = $c->id;
                $before_qty = 0;
                $after_qty = $r->current_qty;
            }

            $d = AbtcInventoryTransaction::create([
                'transaction_date' => date('Y-m-d'),
                'stock_id' => $stock_id,
                'type' => 'RECEIVED',
                'process_qty' => $r->current_qty,
                'before_qty' => $before_qty,
                'after_qty' => $after_qty,
                //'po_number',
                'unit_price' => $r->unit_price,
                'unit_price_amount' => ($r->current_qty * $r->unit_price),
                'remarks' => ($r->remarks) ? mb_strtoupper($r->remarks) : NULL,
                'created_by' => Auth::id(),
            ]);
        }
        else if($r->transaction_type == 'TRANSFERRED') {
            $s = AbtcInventoryStock::findOrFail($r->stock_id);

            if($s->sub_id != $d->id) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }

            if($r->qty_to_process > $s->current_qty) {
                return redirect()->back()
                ->with('msg', 'Error: Quantity to Process is greater than the Available Quantity. Kindly double check and try again.')
                ->with('msgtype', 'warning');
            }
            else {
                //Create Transfer Transaction
                $before_qty = $s->current_qty;
                $s->current_qty = $s->current_qty - $r->qty_to_process;
                $after_qty = $s->current_qty;

                if($s->isDirty()) {
                    $s->save();
                }

                $d = AbtcInventoryTransaction::create([
                    'transaction_date' => $r->transaction_date,
                    'stock_id' => $s->id,
                    'type' => 'TRANSFERRED',
                    'transferto_facility' => $r->transferto_facility,
                    'process_qty' => $r->qty_to_process,
                    'before_qty' => $before_qty,
                    'after_qty' => $after_qty,
                    'po_number', ($r->po_number) ? mb_strtoupper($r->po_number) : NULL,
                    'unit_price' => $r->unit_price,
                    'unit_price_amount' => ($r->current_qty * $r->unit_price),
                    'remarks' => ($r->remarks) ? mb_strtoupper($r->remarks) : NULL,
                    'created_by' => Auth::id(),
                ]);

                //Create Received Transaction
                //Search Transfer Branch Stock ID
                $branch_submaster = AbtcInventorySubMaster::where('master_id', $s->submaster->master->id)
                ->where('abtc_facility_id', $r->transferto_facility)
                ->first();

                $branch_stock = AbtcInventoryStock::where('sub_id', $branch_submaster->id)
                ->where('batch_no', $s->batch_no)
                ->first();

                if($branch_stock) {
                    $bs_before = $branch_stock->current_qty;
                    $branch_stock->current_qty = $branch_stock->current_qty + $r->qty_to_process;
                    $bs_after = $branch_stock->current_qty;

                    if($branch_stock->isDirty()) {
                        $d->updated_by = Auth::id();
                        $d->save();
                    }
                }
                else {
                    $bs_before = 0;
                    $bs_after = $r->qty_to_process;

                    $branch_stock = AbtcInventoryStock::create([
                        'sub_id' => $branch_submaster->id,
                        'batch_no' => $s->batch_no,
                        'expiry_date' => $s->expiry_date,
                        'source' => $s->source,
                        'current_qty' => $r->qty_to_process,
                        'created_by' => Auth::id(),
                    ]);
                }

                $d1 = AbtcInventoryTransaction::create([
                    'transaction_date' => $r->transaction_date,
                    'stock_id' => $branch_stock->id,
                    'type' => 'RECEIVED',
                    'process_qty' => $r->qty_to_process,
                    'before_qty' => $bs_before,
                    'after_qty' => $bs_after,
                    //'po_number',
                    'unit_price' => $s->getOriginTransaction()->unit_price,
                    'unit_price_amount' => ($r->qty_to_process * $s->getOriginTransaction()->unit_price),
                    //'remarks' => ($r->remarks) ? mb_strtoupper($r->remarks) : NULL,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->back()
        ->with('msg', 'Transaction was processed successfully.')
        ->with('msgtype', 'success');
    }

    public function getInventoryStocks($sub_id) {
        $list = [];

        $data = AbtcInventoryStock::where('enabled', 'Y')
        ->where('sub_id', $sub_id)
        ->where('current_qty', '>', 0)
        ->whereDate('expiry_date', '>=', date('Y-m-d'))
        ->get();
        
        foreach($data as $item) {
            array_push($list, [
                'id' => $item->id,
                'text' => 'Batch No. '.$item->batch_no.' - Current Qty: '.$item->current_qty.' '.$item->submaster->master->uom,
            ]);
        }

        return response()->json($list);
    }

    public function monthlyStockReport() {
        //For Pharmacy Use

    }
}
