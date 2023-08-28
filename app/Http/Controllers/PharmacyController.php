<?php

namespace App\Http\Controllers;

use App\Models\PharmacyStockCard;
use App\Models\PharmacyStockLog;
use App\Models\PharmacySupply;
use App\Models\PharmacySupplyStock;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function home() {
        return view('pharmacy.home');
    }

    public function addItem(Request $r) {
        $check = PharmacySupply::where('sku_code', mb_strtoupper($r->sku_code))
        ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
        ->first();

        if(!($check)) {
            $add = $r->user()->pharmacysupply()->create([
                'pharmacy_branch_id' => auth()->user()->pharmacy_branch_id,
                'name' => mb_strtoupper($r->name),
                'category' => $r->category,
    
                'sku_code' => mb_strtoupper($r->sku_code),
                'po_contract_number' => $r->po_contract_number,
                'supplier' => $r->supplier,
                'description' => $r->description,
                'dosage_form' => $r->dosage_form,
                'dosage_strength' => $r->dosage_strength,
                'unit_measure' => $r->unit_measure,
                'entity_name' => $r->entity_name,
                'source_of_funds' => $r->source_of_funds,
                'unit_cost' => $r->unit_cost,
                'mode_of_procurement' => $r->mode_of_procurement,
                'end_user' => $r->end_user,
    
                'config_piecePerBox' => $r->config_piecePerBox,
                'master_box_stock' => $r->supply_base_stock,
                'master_piece_stock' => ($r->filled('config_piecePerBox')) ? ($r->config_piecePerBox * $r->supply_base_stock) : NULL,
            ]);
    
            $add_stock = $r->user()->pharmacysupplystock()->create([
                'supply_id' => $add->id,
                'expiration_date' => $r->expiration_date,
    
                'current_box_stock' => $r->supply_base_stock,
                'current_piece_stock' => ($r->filled('config_piecePerBox')) ? ($r->config_piecePerBox * $r->supply_base_stock) : NULL,
            ]);

            $add_stock_card = $r->user()->pharmacystockcard()->create([
                'type' => 'ADD',
                'before_qty' => 0,
                'qty_to_process' => $r->supply_base_stock,
                'after_qty' => $r->supply_base_stock,
                'total_cost' => NULL,
                'drsi_number'=> NULL,

                'recipient' => NULL,
                'remarks'=> NULL,
            ]);

            return redirect()->route('pharmacy_itemlist')
            ->with('msg', 'Item ['.$r->name.'] was successfully added.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Item already exists in the database.')
            ->with('msgtype', 'warning');
        }
    }

    public function modifyStockView() {
        if(request()->input('code')) {
            $c = request()->input('code');

            $search = PharmacySupply::where('sku_code', $c)
            ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
            ->first();

            

            //$search->name = 'PARAZZZ';

            //dd($search->getOriginal('name'));

            if($search) {
                $sub_list = PharmacySupplyStock::where('supply_id', $search->id)
                ->where('current_box_stock', '>', 0)
                ->orderBy('expiration_date', 'ASC')
                ->get();

                return view('pharmacy.modify_stock', [
                    'd' => $search,
                    'sub_list' => $sub_list,
                ]);
            }
            else {
                return redirect()->back()
                ->with('msg', 'SKU Code does not exists or belongs to other branches.')
                ->with('msgtype', 'warning');
            }
        }
        else {
            return abort(401);
        }
    }

    public function modifyStockProcess($product_id, Request $r) {
        $c = $product_id;

        $search = PharmacySupply::where('sku_code', $c)
        ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
        ->first();

        if($search) {
            $sub_stock = PharmacySupplyStock::where('supply_id', $search->id)
            ->where('id', $r->select_sub_supply_id)
            ->first();

            if($r->type == 'ISSUED') {
                $search->master_box_stock = ($search->master_box_stock - $r->qty_to_process);
                $actiontxt = 'Issued';

                if($sub_stock->current_box_stock < $r->qty_to_process) {
                    
                    $qty_remaining = $r->qty_to_process - $sub_stock->current_box_stock;

                    $array_of_ids = [];
                    $array_of_ids[] = $r->select_sub_supply_id;

                    while($qty_remaining > 0) {
                        $loop_search = PharmacySupplyStock::whereNotIn('id', $array_of_ids)
                        ->where('current_box_stock', '>', 0)
                        ->orderBy('expiration_date', 'ASC')
                        ->first();

                        if($loop_search->current_box_stock < $qty_remaining) {
                            $loop_search->current_box_stock = 0;
                            $array_of_ids[] = $loop_search->id;
                        }
                        else {
                            $loop_search->current_box_stock = ($loop_search->current_box_stock - $qty_remaining);
                        }

                        $qty_remaining = ($qty_remaining - $loop_search->getOriginal('current_box_stock'));
                        
                        if($loop_search->isDirty()) {
                            $loop_search->save();
                        }
                    }

                    $sub_stock->current_box_stock = 0;
                }
                else {
                    $sub_stock->current_box_stock = $search->master_box_stock;
                }
            }
            else {
                $search->master_box_stock = ($search->master_box_stock + $r->qty_to_process);
                $actiontxt = 'Received';

                $sub_stock->current_box_stock = $search->master_box_stock;
            }

            $process = $r->user()->pharmacystockcard()->create([
                'supply_id' => $search->id,
                'type' => $r->type,
                'before_qty' => $search->getOriginal('master_box_stock'),
                'qty_to_process' => $r->qty_to_process,
                'after_qty' => $search->master_box_stock,
                'total_cost' => $r->total_cost,
                'drsi_number' => $r->drsi_number,

                'recipient' => $r->recipient,
                'remarks' => $r->remarks,
            ]);

            if($search->isDirty()) {
                $search->save();
                $sub_stock->save();
            }

            return redirect()->route('pharmacy_home')
            ->with('msg', 'Success: '.$actiontxt.' '.$r->qty_to_process.' pcs. of '.$search->name.' [SKU Code: '.$search->sku_code.']')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }
    
    public function viewItemList() {
        $list = PharmacySupply::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)->orderBy('name', 'ASC')->paginate(10);

        return view('pharmacy.itemlist', [
            'list' => $list,
        ]);
    }

    public function viewItem($item_id) {
        $item = PharmacySupply::findOrFail($item_id);

        $sub_list = PharmacySupplyStock::where('supply_id', $item->id)
        ->get();

        $scard = PharmacyStockCard::where('supply_id', $item->id)
        ->orderBy('created_at', 'DESC')
        ->get();

        if($item->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {
            return view('pharmacy.itemlist_viewitem', [
                'd' => $item,
                'sub_list' => $sub_list,
                'scard' => $scard,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function updateItem($item_id, Request $r) {

    }

    public function viewReport() {
        //get expiration within 3 months
        $expired_list = PharmacySupplyStock::whereBetween('expiration_date', [date('Y-m-d'), date('Y-m-t', strtotime('+3 Months'))])
        ->where('current_box_stock', '>', 0)
        ->orderBy('expiration_date', 'ASC')
        ->get();

        return view('pharmacy.report', [
            'expired_list' => $expired_list,
        ]);
    }
}
