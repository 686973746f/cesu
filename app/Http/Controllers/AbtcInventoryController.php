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

        return view('abtc.inventory.home', [
            'list' => $list,
            'qt_list' => $qt_list,
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
            $check = AbtcInventoryStock::where('batch_no', $batch_no)->first();

            if($check) {
                return redirect()->back()
                ->with('msg', 'Error: Same Batch Number already exists in the database. Kindly double check and try again.')
                ->with('msgtype', 'warning');
            }

            $c = AbtcInventoryStock::create([
                'sub_id' => $d->id,
                'batch_no' => $batch_no,
                'expiry_date' => $r->expiry_date,
                'source' => $r->source,

                'current_qty' => $r->current_qty,
                'created_by' => Auth::id(),
            ]);

            $d = AbtcInventoryTransaction::create([
                'transaction_date' => date('Y-m-d'),
                'stock_id' => $c->id,
                'type' => 'RECEIVED',
                'process_qty' => $r->current_qty,
                'before_qty' => 0,
                'after_qty' => $r->current_qty,
                //'po_number',
                'unit_price' => $r->unit_price,
                'unit_price_amount' => ($r->current_qty * $r->unit_price),
                'remarks' => ($r->remarks) ? mb_strtoupper($r->remarks) : NULL,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->back()
        ->with('msg', 'Transaction was processed successfully.')
        ->with('msgtype', 'success');
    }

    public function getInventoryStocks($sub_id) {
        $list = [];
        $data = AbtcInventoryStock::where('sub_id', $sub_id)
        ->where('current_qty', '>', 0)
        ->get();
        
        foreach($data as $item) {
            array_push($list, [
                'id' => $item->id,
                'text' => 'Batch No. '.$item->batch_no.' - Current Qty: '.$item->current_qty.' '.$item->submaster->master->uom,
            ]);
        }

        return response()->json($list);
    }
}
