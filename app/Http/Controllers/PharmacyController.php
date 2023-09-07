<?php

namespace App\Http\Controllers;

use App\Models\PharmacyBranch;
use App\Models\PharmacyPatient;
use App\Models\PharmacyStockCard;
use App\Models\PharmacyStockLog;
use App\Models\PharmacySupply;
use App\Models\PharmacySupplyMaster;
use App\Models\PharmacySupplyStock;
use App\Models\PharmacySupplySub;
use App\Models\PharmacySupplySubStock;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function home() {
        return view('pharmacy.home');
    }

    public function addMasterItem(Request $r) {
        $check = PharmacySupplyMaster::where('sku_code', mb_strtoupper($r->sku_code))
        ->orWhere('name', mb_strtoupper($r->name))
        ->first();

        if(!($check)) {
            $c = $r->user()->pharmacysupplymaster()->create([
                'name' => mb_strtoupper($r->name),
                'sku_code' => mb_strtoupper($r->sku_code),
                'category' => $r->category,
                'description' => $r->description,
                'quantity_type' => $r->quantity_type,
                'config_piecePerBox' => ($r->quantity_type == 'BOX') ? $r->config_piecePerBox : NULL, 
            ]);

            if($r->master_box_stock != 0) {
                $d = $r->user()->pharmacysupplysub()->create([
                    'supply_master_id' => $c->id,
                    'pharmacy_branch_id' => auth()->user()->pharmacy_branch_id,
                    'self_sku_code' => NULL,
                    'self_description' => NULL,
    
                    'po_contract_number' => $r->po_contract_number,
                    'supplier' => $r->supplier,
                    'dosage_form' => $r->dosage_form,
                    'dosage_strength' => $r->dosage_strength,
                    'unit_measure' => $r->unit_measure,
                    'entity_name' => $r->entity_name,
                    'source_of_funds' => $r->source_of_funds,
                    'unit_cost' => $r->unit_cost,
                    'mode_of_procurement' => $r->mode_of_procurement,
                    'end_user' => $r->end_user,
                    'default_issuance_per_box' => NULL,
                    'default_issuance_per_piece' => NULL,
    
                    'master_box_stock' => $r->master_box_stock,
                    'master_piece_stock' => ($r->quantity_type == 'BOX') ? ($r->config_piecePerBox * $r->master_box_stock) : NULL, 
                ]);
    
                $e = $r->user()->pharmacysupplysubstock()->create([
                    'subsupply_id' => $d->id,
                    'expiration_date' => $r->expiration_date,
                    'current_box_stock' => $r->master_box_stock,
                    'current_piece_stock' => ($r->quantity_type == 'BOX') ? ($r->config_piecePerBox * $r->master_box_stock) : NULL, 
                ]);
    
                $f = $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $d->id,
    
                    'type' => 'RECEIVED',
                    'before_qty' => 0,
                    'qty_to_process' => $r->master_box_stock,
                    'after_qty' => $r->master_box_stock,
                    'total_cost' => NULL,
                    'drsi_number' => NULL,
    
                    'recipient' => NULL,
                    'remarks' => 'INITIAL ENCODING',
                ]);
            }

            return redirect()->back()
            ->with('msg', 'Master Item was added successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Master Item already exists in the database. Please double check the Item Name and SKU Code.')
            ->with('msgtype', 'warning');
        }   
    }

    public function addSubItem($master_id, Request $r) {

        /*
        $check = PharmacySupply::where('sku_code', mb_strtoupper($r->sku_code))
        ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
        ->first();

        if(!($check)) {
            $add = $r->user()->pharmacysupply()->create([
                'pharmacy_branch_id' => auth()->user()->pharmacy_branch_id,
                'name' => mb_strtoupper($r->name),
                'category' => $r->category,
                'quantity_type' => $r->quantity_type,
    
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
                'supply_id' => $add->id,
                'type' => 'RECEIVED',
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
        */
    }

    public function modifyStockQr() {
        if(request()->input('code')) {
            $code = request()->input('code');

            $s = PharmacySupplyMaster::where('sku_code', $code)->first();

            if($s) {
                //find if sub supply exists
                $t = PharmacySupplySubStock::where('subsupply_id', $s->id)
                ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
                ->first();

                if($t) {
                    return redirect()->route('pharmacy_modify_view', $t->id);
                }
            }
            else {
                $s1 = PharmacyPatient::where('id', $code)->first();

                if($s1) {

                }
                else {
                    return redirect()->back()
                ->with('msg', 'Error: SKU Code or Patient ID does not exists in the database.')
                ->with('msgtype', 'warning');
                }
            }
        }
        else {
            return abort(401);
        }
    }

    public function modifyStockView($subsupply_id) {
        $d = PharmacySupplySub::findOrFail($subsupply_id);

        $sub_list = PharmacySupplySubStock::where('subsupply_id', $d->id)
        ->get();

        $branch_list = PharmacyBranch::where('id', '!=', auth()->user()->pharmacy_branch_id)
        ->orderBy('name', 'ASC')
        ->get();

        return view('pharmacy.modify_stock', [
            'd' => $d,
            'sub_list' => $sub_list,
            'branch_list' => $branch_list,
        ]);
    }

    public function modifyStockProcess($subsupply_id, Request $r) {
        $d = PharmacySupplySub::findOrFail($subsupply_id);
        
        if($r->type == 'ISSUED') {
            if($d->pharmacysupplymaster->quantity_type == 'BOX') {
                if($r->qty_type == 'BOX') {
                    //BOX TYPE QTY ISSUANCE
                    //double check logic - if has enough stock
                    if($d->master_box_stock >= $r->qty_to_process) {
                        $stock = PharmacySupplySubStock::findOrFail($r->select_sub_stock_id);
    
                        $d->master_box_stock = $d->master_box_stock - $r->qty_to_process;
                        
                        if($stock->current_box_stock < $r->qty_to_process) {
                            $qty_remaining = $r->qty_to_process - $stock->current_box_stock;
                            $stock->current_box_stock = 0;
                            $stock->current_piece_stock = $stock->current_piece_stock - ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);
    
                            $array_of_ids = [];
                            $array_of_ids[] = $r->select_sub_supply_id;
    
                            while($qty_remaining > 0) {
                                $loop_search = PharmacySupplySubStock::whereNotIn('id', $array_of_ids)
                                ->where('subsupply_id', $d->id)
                                ->where('current_box_stock', '>', 0)
                                ->orderBy('expiration_date', 'ASC')
                                ->first();
    
                                if($loop_search->current_box_stock < $qty_remaining) {
                                    $loop_search->current_box_stock = 0;
                                    $loop_search->current_piece_stock = 0;
                                    $array_of_ids[] = $loop_search->id;
                                }
                                else {
                                    $loop_search->current_box_stock = ($loop_search->current_box_stock - $qty_remaining);
                                    $loop_search->current_piece_stock = $loop_search->current_piece_stock - ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);
                                }
            
                                $qty_remaining = ($qty_remaining - $loop_search->getOriginal('current_box_stock'));
                                
                                if($loop_search->isDirty()) {
                                    $loop_search->save();
                                }
                            }
                        }
                        else {
                            $stock->current_box_stock = $stock->current_box_stock - $r->qty_to_process;
                            $stock->current_piece_stock = $stock->current_piece_stock - ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);
                        }

                        //PROCESS STOCK CARD
                        $create_sc = $r->user()->pharmacystockcard()->create([
                            'subsupply_id' => $d->id,
                            'type' => 'ISSUED',
                            'before_qty_box' => $d->getOriginal('master_box_stock'),
                            'before_qty_piece' => $d->getOriginal('master_piece_stock'),
                            'qty_to_process' => $r->qty_to_process,
                            'qty_type' => 'BOX',
                            'after_qty_box' => $d->master_box_stock,
                            'after_qty_piece' => $d->master_piece_stock,
                            'total_cost' => $r->total_cost,
                            'drsi_number' => $r->drsi_number,

                            'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                            'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $r->receiving_patient_id : NULL,
                            'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,

                            'remarks' => $r->remarks,
                        ]);
    
                        if($d->isDirty()) {
                            $d->save();
                        }
    
                        if($stock->isDirty()) {
                            $stock->save();
                        }

                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Stock was changed upon processing. Please try again.')
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    //PIECE TYPE QTY ISSUANCE
                    if($d->master_piece_stock >= $r->qty_to_process) {
                        $d->master_piece_stock = $d->master_piece_stock - $r->qty_to_process;
                    
                        //Check how many box can exist with the updated pieces
                        while(($d->master_box_stock * $d->pharmacysupplymaster->config_piecePerBox) > $d->master_piece_stock) {
                            $d->master_box_stock = $d->master_box_stock - 1;
                        }
    
                        //proceed with deducting the sub stock
                        $stock = PharmacySupplySubStock::findOrFail($r->select_sub_stock_id);
                        if($stock->current_piece_stock >= $r->qty_to_process) {
                            $stock->current_piece_stock = $stock->current_piece_stock - $r->qty_to_process;
                            
                            while(($stock->current_box_stock * $d->pharmacysupplymaster->config_piecePerBox) > $stock->current_piece_stock) {
                                $stock->current_box_stock = $stock->current_box_stock - 1;
                            }
                        }
                        else {
                            $qty_remaining = $r->qty_to_process - $stock->current_piece_stock;
                            $stock->current_piece_stock = 0;
                            $stock->current_box_stock = 0;
                            
                            $array_of_ids = [];
                            $array_of_ids[] = $r->select_sub_supply_id;
                            
                            while($qty_remaining > 0) {
                                $loop_search = PharmacySupplySubStock::whereNotIn('id', $array_of_ids)
                                ->where('subsupply_id', $d->id)
                                ->where('current_piece_stock', '>', 0)
                                ->orderBy('expiration_date', 'ASC')
                                ->first();

                                if($loop_search->current_piece_stock < $qty_remaining) {
                                    $loop_search->current_box_stock = 0;
                                    $loop_search->current_piece_stock = 0;
                                    
                                    $array_of_ids[] = $loop_search->id;
                                }
                                else {
                                    $loop_search->current_piece_stock = $loop_search->current_piece_stock - $qty_remaining;

                                    while(($loop_search->current_box_stock * $d->pharmacysupplymaster->config_piecePerBox) > $loop_search->current_piece_stock) {
                                        $loop_search->current_box_stock = $loop_search->current_box_stock - 1;
                                    }
                                }
            
                                $qty_remaining = ($qty_remaining - $loop_search->getOriginal('current_piece_stock'));
                                
                                if($loop_search->isDirty()) {
                                    $loop_search->save();
                                }
                            }
                        }

                        //PROCESS STOCK CARD
                        $create_sc = $r->user()->pharmacystockcard()->create([
                            'subsupply_id' => $d->id,
                            'type' => 'ISSUED',
                            'before_qty_box' => $d->getOriginal('master_box_stock'),
                            'before_qty_piece' => $d->getOriginal('master_piece_stock'),
                            'qty_to_process' => $r->qty_to_process,
                            'qty_type' => 'PIECE',
                            'after_qty_box' => $d->master_box_stock,
                            'after_qty_piece' => $d->master_piece_stock,
                            'total_cost' => $r->total_cost,
                            'drsi_number' => $r->drsi_number,

                            'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                            'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $r->receiving_patient_id : NULL,
                            'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,

                            'remarks' => $r->remarks,
                        ]);

                        if($d->isDirty()) {
                            $d->save();
                        }
    
                        if($stock->isDirty()) {
                            $stock->save();
                        }
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Stock was changed upon processing. Please try again.')
                        ->with('msgtype', 'warning');
                    }
                }
    
                $actiontxt = 'Issued';
            }
            else {
                //ISSUE BOTTLE TYPE
                $stock = PharmacySupplySubStock::findOrFail($r->select_sub_stock_id);
                $d->master_box_stock = $d->master_box_stock - $r->qty_to_process;

                if($stock->current_box_stock < $r->qty_to_process) {
                    $qty_remaining = $r->qty_to_process - $stock->current_box_stock;
                    $stock->current_box_stock = 0;
                    
                    $array_of_ids = [];
                    $array_of_ids[] = $r->select_sub_supply_id;
                    
                    while($qty_remaining > 0) {
                        $loop_search = PharmacySupplySubStock::whereNotIn('id', $array_of_ids)
                        ->where('subsupply_id', $d->id)
                        ->where('current_box_stock', '>', 0)
                        ->orderBy('expiration_date', 'ASC')
                        ->first();

                        if($loop_search->current_piece_stock < $qty_remaining) {
                            $loop_search->current_box_stock = 0;
                            
                            $array_of_ids[] = $loop_search->id;
                        }
                        else {
                            $loop_search->current_box_stock = $loop_search->current_box_stock - $qty_remaining;
                        }
    
                        $qty_remaining = ($qty_remaining - $loop_search->getOriginal('current_box_stock'));
                        
                        if($loop_search->isDirty()) {
                            $loop_search->save();
                        }
                    }
                }
                else {
                    $stock->current_box_stock = $stock->current_box_stock - $r->qty_to_process;
                }

                //PROCESS STOCK CARD
                $create_sc = $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $d->id,
                    'type' => 'ISSUED',
                    'before_qty_box' => $d->getOriginal('master_box_stock'),
                    'before_qty_piece' => NULL,
                    'qty_to_process' => $r->qty_to_process,
                    'qty_type' => 'BOX',
                    'after_qty_box' => $d->master_box_stock,
                    'after_qty_piece' => NULL,
                    'total_cost' => $r->total_cost,
                    'drsi_number' => $r->drsi_number,

                    'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                    'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $r->receiving_patient_id : NULL,
                    'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,

                    'remarks' => $r->remarks,
                ]);

                if($d->isDirty()) {
                    $d->save();
                }

                if($stock->isDirty()) {
                    $stock->save();
                }
            }
        }
        else {
            //RECEIVE
            if($d->pharmacysupplymaster->quantity_type == 'BOX') {
                if($r->qty_type == 'BOX') {
                    $d->master_box_stock += $r->qty_to_process;
                    $d->master_piece_stock += ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);

                    //check if sub stock with expiration date exists
                    $stock = PharmacySupplySubStock::where('subsupply_id', $d->id)
                    ->whereDate('expiration_date', $r->expiration_date)
                    ->first();

                    if($stock) {
                        $stock->update([
                            'current_box_stock' => $stock->current_box_stock + $r->qty_to_process,
                            'current_piece_stock' => $stock->current_piece_stock + ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox),
                        ]);
                    }
                    else {
                        $create_stock = $r->user()->pharmacysupplysubstock()->create([
                            'subsupply_id' => $d->id,
                            'expiration_date' => $r->expiration_date,
                            'current_box_stock' => $r->$r->qty_to_process,
                            'current_piece_stock' => $r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox,
                        ]);
                    }

                    //PROCESS STOCK CARD
                    $create_sc = $r->user()->pharmacystockcard()->create([
                        'subsupply_id' => $d->id,
                        'type' => 'ISSUED',
                        'before_qty_box' => $d->getOriginal('master_box_stock'),
                        'before_qty_piece' => $d->getOriginal('master_piece_stock'),
                        'qty_to_process' => $r->qty_to_process,
                        'qty_type' => 'BOX',
                        'after_qty_box' => $d->master_box_stock,
                        'after_qty_piece' => $d->master_piece_stock,
                        'total_cost' => $r->total_cost,
                        'drsi_number' => $r->drsi_number,

                        'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                        'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $r->receiving_patient_id : NULL,
                        'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,

                        'remarks' => $r->remarks,
                    ]);

                    if($d->isDirty()) {
                        $d->save();
                    }
    
                    if($stock->isDirty()) {
                        $stock->save();
                    }
                }
                else {
                    //PER PIECE, NOT ALLOWED
                }
            }
            else {
                $d->master_box_stock += $r->qty_to_process;
                
                //check if sub stock with expiration date exists
                $stock = PharmacySupplySubStock::where('subsupply_id', $d->id)
                ->whereDate('expiration_date', $r->expiration_date)
                ->first();

                if($stock) {
                    $stock->update([
                        'current_box_stock' => $stock->current_box_stock + $r->qty_to_process,
                    ]);
                }
                else {
                    $create_stock = $r->user()->pharmacysupplysubstock()->create([
                        'subsupply_id' => $d->id,
                        'expiration_date' => $r->expiration_date,
                        'current_box_stock' => $r->$r->qty_to_process,
                    ]);
                }

                //PROCESS STOCK CARD
                $create_sc = $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $d->id,
                    'type' => 'RECEIVED',
                    'before_qty_box' => $d->getOriginal('master_box_stock'),
                    'before_qty_piece' => $d->getOriginal('master_piece_stock'),
                    'qty_to_process' => $r->qty_to_process,
                    'qty_type' => 'BOX',
                    'after_qty_box' => $d->master_box_stock,
                    'after_qty_piece' => $d->master_piece_stock,
                    'total_cost' => $r->total_cost,
                    'drsi_number' => $r->drsi_number,

                    'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                    'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $r->receiving_patient_id : NULL,
                    'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,

                    'remarks' => $r->remarks,
                ]);

                if($d->isDirty()) {
                    $d->save();
                }

                if($stock->isDirty()) {
                    $stock->save();
                }
            }

            $actiontxt = 'Received';
        }
        
        /*
        if($r->type == 'ISSUED') {
            $stock = PharmacySupplySubStock::findOrFail($r->select_sub_stock_id);
            
            $d->master_box_stock = ($d->master_box_stock - $r->qty_to_process);
            $actiontxt = 'Issued';

            if($stock->current_box_stock < $r->qty_to_process) {
                $qty_remaining = $r->qty_to_process - $stock->current_box_stock;

                $array_of_ids = [];
                $array_of_ids[] = $r->select_sub_supply_id;

                while($qty_remaining > 0) {
                    $loop_search = PharmacySupplySubStock::whereNotIn('id', $array_of_ids)
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

                $stock->current_box_stock = 0;
            }
            else {
                $stock->current_box_stock = $d->master_box_stock;
            }
        }
        else {
            $d->master_box_stock = ($d->master_box_stock + $r->qty_to_process);
            $d->master_piece_stock = (!is_null($d->master_piece_stock)) ? $d->master_piece_stock + ($d->pharmacysupplymaster->config_piecePerBox * $r->qty_to_process) : NULL;
            $actiontxt = 'Received';

            //$sub_stock->current_box_stock = $search->master_box_stock;

            //add stock supply
            $substock_search = PharmacySupplySubStock::where('subsupply_id', $d->id)
            ->whereDate('expiration_date', $r->expiration_date)
            ->first();
            
            if($substock_search) {
                $substock_search->current_box_stock = $substock_search->current_box_stock + $r->qty_to_process;

                $substock_search->updated_by = auth()->user()->id;

                if($substock_search->isDirty()) {
                    $substock_search->save();
                }
            }
            else {
                $new_stock_create = $r->user()->pharmacysupplystock()->create([
                    'supply_id' => $search->id,
                    'expiration_date' => $r->expiration_date,
                    'current_box_stock' => $r->qty_to_process,
                ]);
            }
        }
        */

        /*
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

                //$sub_stock->current_box_stock = $search->master_box_stock;

                //add stock supply
                $stock_search = PharmacySupplyStock::where('supply_id', $search->id)
                ->whereDate('expiration_date', $r->expiration_date)
                ->first();
                
                if($stock_search) {
                    $stock_search->current_box_stock = $stock_search->current_box_stock + $r->qty_to_process;
                    $stock_search->updated_by = auth()->user()->id;

                    if($stock_search->isDirty()) {
                        $stock_search->save();
                    }
                }
                else {
                    $new_stock_create = $r->user()->pharmacysupplystock()->create([
                        'supply_id' => $search->id,
                        'expiration_date' => $r->expiration_date,
                        'current_box_stock' => $r->qty_to_process,
                    ]);
                }
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
        */
    }

    public function masterItemHome() {
        if(request()->input('q')) {
            $q = request()->input('q');
            
            $list = PharmacySupplyMaster::where('sku_code', $q)
            ->orWhere('name','LIKE', '%'.$q.'%')
            ->orWhere('sku_code_doh', $q)
            ->paginate(10);
        }
        else {
            $list = PharmacySupplyMaster::orderBy('name', 'ASC')
            ->paginate(10);
        }
        
        return view('pharmacy.itemlist_viewMasterList', [
            'list' => $list,
        ]);
    }

    public function viewMasterItem($id) {
        $d = PharmacySupplyMaster::findOrFail($id);
        
        return view('pharmacy.itemlist_viewMaster', [
            'd' => $d,
        ]);
    }

    public function updateMasterItem($id, Request $r) {
        //find existing first

        $d = PharmacySupplyMaster::where('id', '!=', $id)
        ->where(function ($q) use ($r) {
            $q->where('name', mb_strtoupper($r->name))
            ->orWhere('sku_code', mb_strtoupper($r->sku_code));
        })->first();

        if(!($d)) {
            $u = PharmacySupplyMaster::where('id', $id)
            ->update([
                'name' => mb_strtoupper($r->name),
                'sku_code' => mb_strtoupper($r->sku_code),
                'sku_code_doh' => mb_strtoupper($r->sku_code_doh),
                'category' => $r->category,
                'description' => $r->description,
                'quantity_type' => $r->quantity_type,
                'config_piecePerBox' => $r->config_piecePerBox,

                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('pharmacy_masteritem_list')
            ->with('msg', 'Master Item details were updated successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Another Master Item Name or SKU already exists in the server.')
            ->with('msgtype', 'warning');
        }
    }
    
    public function viewItemList() {
        if(request()->input('q')) {
            $q = request()->input('q');

            $list = PharmacySupplySub::whereHas('pharmacysupplymaster', function ($r) use ($q) {
                $r->where('sku_code', $q)
                ->orWhere('name','LIKE', '%'.$q.'%');
            })
            ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
            ->paginate(10);
        }
        else {
            $list = PharmacySupplySub::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
            ->whereHas('pharmacysupplymaster', function ($q) {
                $q->orderBy('name', 'ASC');
            })
            ->paginate(10);
        }

        return view('pharmacy.itemlist', [
            'list' => $list,
        ]);
    }

    public function viewItem($item_id) {
        $item = PharmacySupplySub::findOrFail($item_id);

        if($item->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {
            $sub_list = PharmacySupplySubStock::where('subsupply_id', $item->id)
            ->orderBy('expiration_date', 'ASC')
            ->get();

            $scard = PharmacyStockCard::where('subsupply_id', $item->id)
            ->orderBy('created_at', 'DESC')
            ->get();

            return view('pharmacy.itemlist_viewSub', [
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
        $item = PharmacySupply::findOrFail($item_id);

        if($item->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {

            $check = PharmacySupply::where('sku_code', mb_strtoupper($r->sku_code))
            ->where('id', '!=', $item->id)
            ->first();

            if($check) {
                return redirect()->back()
                ->with('msg', 'Error: SKU Code already exists in the system.')
                ->with('msgtype', 'warning');
            }
            else {
                $check2 = PharmacySupply::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
                ->where('id', '!=', $item->id)
                ->first();

                if($check2) {
                    return redirect()->back()
                    ->with('msg', 'Error: Item Name already exists in the system.')
                    ->with('msgtype', 'warning');
                }
                else {
                    $item->update([
                        'name' => mb_strtoupper($r->name),
                        'category' => ($r->filled('category')) ? mb_strtoupper($r->category) : NULL,
                        'quantity_type' => $r->quantity_type,

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
                    ]);

                    return redirect()->back()
                    ->with('msg', 'Item Details were updated successfully.')
                    ->with('msgtype', 'success');
                }
            }
        }
        else {
            return abort(401);
        }
    }

    public function exportStockCard($supply_id) {
        $item = PharmacyStockCard::where('supply_id', $supply_id)
        ->orderBy('created_at', 'ASC')
        ->get();
    }

    public function viewReport() {
        //get expiration within 3 months
        $expired_list = PharmacySupplySubStock::whereBetween('expiration_date', [date('Y-m-d'), date('Y-m-t', strtotime('+3 Months'))])
        ->where('current_box_stock', '>', 0)
        ->orderBy('expiration_date', 'ASC')
        ->get();

        return view('pharmacy.report', [
            'expired_list' => $expired_list,
        ]);
    }

    public function viewPatientList() {

    }

    public function viewPatient() {

    }
    
    public function updatePatient() {

    }

    public function modifyStockPatientView() {

    }

    public function modifyStockPatientProcess($id, Request $r) {
        
    }
}
