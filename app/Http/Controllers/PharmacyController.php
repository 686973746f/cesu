<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\ExportJobs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PharmacyBranch;
use App\Models\PharmacySupply;
use App\Models\PharmacyCartSub;
use App\Models\PharmacyPatient;
use App\Models\PharmacyCartMain;
use App\Models\PharmacyStockLog;
use App\Models\PharmacyStockCard;
use App\Models\PharmacySupplySub;
use Illuminate\Support\Facades\DB;
use App\Models\PharmacySupplyStock;
use App\Models\PharmacyPrescription;
use App\Models\PharmacySupplyMaster;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\BarangayHealthStation;
use App\Models\PharmacyCartSubBranch;
use App\Models\PharmacyCartMainBranch;
use App\Models\PharmacySupplySubStock;
use App\Models\PharmacyQtyLimitPatient;
use PhpOffice\PhpSpreadsheet\IOFactory;
use OpenSpout\Common\Entity\Style\Style;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Jobs\CallPharmacyAnnualInOutReport;

/*
PERMISSION LIST
PHARMACY_ADMIN
PHARMACY_ENCODER

PHARMACY_BRGY_ADMIN
PHARMACY_BRGY_ENCODER
*/

class PharmacyController extends Controller
{
    public function home() {
        //perms and init check
        if(!(auth()->user()->canAccessPharmacy())) {
            return redirect()->route('home')
            ->with('msg', 'ERROR: You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }

        if(is_null(auth()->user()->pharmacy_branch_id)) {
            return redirect()->route('home')
            ->with('msg', 'ERROR: You are not linked to a Pharmacy Branch yet. Please contact the System Admin.')
            ->with('msgtype', 'warning');
        }

        //Expiration List
        $expired_list = PharmacySupplySubStock::whereHas('pharmacysub', function ($q) {
            $q->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id);
        })
        ->whereBetween('expiration_date', [date('Y-m-d'), date('Y-m-t', strtotime('+2 Months'))])
        ->orderBy('expiration_date', 'ASC')
        ->get();

        $es_collect = collect();

        foreach($expired_list as $es) {
            if($es->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
                if($es->current_box_stock > 0) {
                    $es_collect->push($es);
                }
            }
            else {
                if($es->current_piece_stock > 0) {
                    $es_collect->push($es);
                }
            }
        }

        return view('pharmacy.home', [
            'expired_list' => $es_collect,
        ]);
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
    
                    'master_box_stock' => ($r->quantity_type == 'BOX') ? $r->master_box_stock : NULL,
                    'master_piece_stock' => ($r->quantity_type == 'BOX') ? ($r->config_piecePerBox * $r->master_box_stock) : $r->master_box_stock, 
                ]);
    
                $e = $r->user()->pharmacysupplysubstock()->create([
                    'subsupply_id' => $d->id,
                    'expiration_date' => $r->expiration_date,
                    'current_box_stock' => ($r->quantity_type == 'BOX') ? $r->master_box_stock : NULL,
                    'current_piece_stock' => ($r->quantity_type == 'BOX') ? ($r->config_piecePerBox * $r->master_box_stock) : $r->master_box_stock,
                ]);
    
                $f = $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $d->id,
    
                    'type' => 'RECEIVED',
                    'before_qty_box' => ($r->quantity_type == 'BOX') ? 0 : NULL,
                    'before_qty_piece' => 0,
                    'qty_to_process' => $r->master_box_stock,
                    'qty_type' => $r->quantity_type,
                    'after_qty_box' => ($r->quantity_type == 'BOX') ? $r->master_box_stock : NULL,
                    'after_qty_piece' => ($r->quantity_type == 'BOX') ? ($r->config_piecePerBox * $r->master_box_stock) : $r->master_box_stock,
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

    public function modifyStockQr() {
        if(request()->input('code')) {
            $code = request()->input('code');

            if(Str::startsWith($code, 'PATIENT_')) {
                $newString = Str::replaceFirst("PATIENT_", "", $code);

                $d = PharmacyPatient::where('qr', $newString)->first();

                if($d) {
                    if($d->status == 'DISABLED') {
                        return redirect()-back()
                        ->with('msg', 'ERROR: Account of Patient '.$d->getName().' was DISABLED in the system. Issuance of Medicine/s was blocked. You may contact Pharmacist/Encoder if you think this was a mistake.')
                        ->with('msgtype', 'danger');
                    }

                    return redirect()->route('pharmacy_modify_patient_stock', [
                        'id' => $d->id,
                    ]);
                }
                else {
                    return redirect()->back()
                    ->with('msg', 'Error: Patient QR does not exist in the system.')
                    ->with('msgtype', 'warning');
                }
            }
            else if(Str::startsWith($code, 'ENTITY_')) {
                $newString = Str::replaceFirst("ENTITY_", "", $code);

                $d = PharmacyBranch::where('qr', $newString)->first();

                if($d) {
                    if($d->id == auth()->user()->pharmacy_branch_id) {
                        return redirect()->back()
                        ->with('msg', 'Error: You cannot do transaction/s againsts your own Branch.')
                        ->with('msgtype', 'warning');
                    }

                    if($d->enabled == 0) {
                        return redirect()-back()
                        ->with('msg', 'ERROR: The Branch '.$d->name.' was DISABLED in the system. Issuance of Medicine/s was blocked. You may contact Pharmacist/Encoder if you think this was a mistake.')
                        ->with('msgtype', 'danger');
                    }

                    return redirect()->route('pharmacy_viewBranchCart', $d->id);
                }
                else {
                    return redirect()->back()
                    ->with('msg', 'Error: Branch QR does not exist in the system.')
                    ->with('msgtype', 'warning');
                }
            }
            else if(Str::startsWith($code, 'SUBSTOCK_')) {
                $newString = Str::replaceFirst("SUBSTOCK_", "", $code);

                $d = PharmacySupplySubStock::where('id', $newString)->first();

                if($d) {
                    if($d->pharmacysub->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {
                        return redirect()->route('pharmacy_modify_view', [$d->pharmacysub->id, 'select_substock' => 1]);
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: You are not authorized to do that.')
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    return redirect()->back()
                    ->with('msg', 'Error: Item SubStock ID does not exist in the system.')
                    ->with('msgtype', 'warning');
                }
            }
            else {
                $d = PharmacySupplyMaster::where('sku_code', $code)->first();

                if($d) {
                    $e = PharmacySupplySub::whereHas('pharmacysupplymaster', function ($q) use ($code) {
                        $q->where('sku_code', $code);
                    })
                    ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
                    ->first();

                    if($e) {
                        $t = PharmacySupplySubStock::where('subsupply_id', $d->id)
                        ->orderBy('expiration_date', 'ASC')
                        ->first();
    
                        if($t) {
                            return redirect()->route('pharmacy_modify_view', $t->id);
                        }
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Item ['.$d->name.'] was not yet initialized in the Branch '.auth()->user()->pharmacybranch->name)
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    return redirect()->back()
                    ->with('msg', 'Error: SKU Code does not exist in the system.')
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
        ->whereDate('expiration_date', '>', date('Y-m-d'))
        ->get();

        $branch_list = PharmacyBranch::where('id', '!=', auth()->user()->pharmacy_branch_id)
        ->orderBy('name', 'ASC')
        ->get();

        if(request()->input('process_patient')) {
            $get_patient = PharmacyPatient::findOrFail(request()->input('process_patient'));
        }
        else {
            $get_patient = NULL;
        }

        if(request()->input('select_branch')) {
            $select_branch = PharmacyBranch::findOrFail(request()->input('select_branch'));
        }
        else {
            $select_branch = NULL;
        }

        return view('pharmacy.modify_stock', [
            'd' => $d,
            'sub_list' => $sub_list,
            'branch_list' => $branch_list,
            'get_patient' => $get_patient,
            'select_branch' => $select_branch,
        ]);
    }

    public function modifyStockPatientView($id) {
        $d = PharmacyPatient::findOrFail($id);

        //get latest prescription
        $prescription = $d->getLatestPrescription();

        if($prescription) {
            $search_cart = $d->getPendingCartMain();

            if(!($search_cart)) {
                $search_cart = request()->user()->pharmacycartmain()->create([
                    'patient_id' => $d->id,
                    'prescription_id' => $prescription->id,
                    'branch_id' => auth()->user()->pharmacy_branch_id,
                ]);
            }

            $load_subcart = PharmacyCartSub::where('main_cart_id', $search_cart->id)->get();

            $meds_list = PharmacySupplySub::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
            ->get();

            $scard = PharmacyStockCard::where('receiving_patient_id', $d->id)
            ->whereBetween('created_at', [date('Y-m-d', strtotime('-30 Days')), date('Y-m-d')])
            ->orderBy('created_at', 'DESC')
            ->get();

            return view('pharmacy.modify_stock_patientview', [
                'd' => $d,
                'meds_list' => $meds_list,
                'load_cart' => $search_cart,
                'load_subcart' => $load_subcart,
                'prescription' => $prescription,
                'scard' => $scard,
            ]);
        }
        else {
            return view('pharmacy.modify_stock_patientview', [
                'd' => $d,
                'getReasonList' => PharmacyPatient::getReasonList(),
                'prescription' => $prescription,
            ]);
        }
    }

    public function addCartItem($patient_id, Request $r) {
        $get_patient = PharmacyPatient::findOrFail($patient_id);

        if($r->submit == 'submit_changes') {
            /*
            $get_patient->concerns_list = implode(',', $r->concerns_list);

            if($get_patient->isDirty()) {
                $get_patient->updated_by = auth()->user()->id;

                $get_patient->save();
            }
            */

            //check if there was prescription issued today, and block it

            $pcheck = PharmacyPrescription::where('patient_id', $get_patient->id)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

            if($pcheck) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'ERROR: Patient has existing prescription that was created today and your request was blocked to avoid duplicate entries.')
                ->with('msgtype', 'warning');
            }

            if(is_null($get_patient->is_lgustaff)) {
                $get_patient->is_lgustaff = ($r->is_lgustaff == 'Y') ? 1 : 0;
                $get_patient->lgu_office_name = ($r->is_lgustaff == 'Y' && $r->filled('lgu_office_name')) ? mb_strtoupper($r->lgu_office_name) : NULL;

                if($get_patient->isDirty()) {
                    $get_patient->save();
                }
            }

            $r->user()->pharmacyprescription()->create([
                'patient_id' => $get_patient->id,
                'concerns_list' => implode(',', $r->concerns_list),
            ]);

            return redirect()->back()
            ->with('msg', 'Patient data was initialized successfully. You may now input medicine/s for issuing to the patient.')
            ->with('msgtype', 'success');
        }
        else if($r->submit == 'new_prescription') {
            $get_latest_prescription = PharmacyPrescription::where('finished', 0)
            ->where('patient_id', $get_patient->id)
            ->latest()
            ->first();

            if($get_latest_prescription) {
                $get_latest_prescription->update([
                    'finished' => 1,
                ]);
            }

            //block if prescription was just created yesterday or today
            /*
            $date1 = Carbon::parse($get_latest_prescription->created_at);
            $date2 = Carbon::parse(date('Y-m-d'));
            $date3 = Carbon::parse(date('Y-m-d', strtotime('-1 Day')));
            if ($date1->equalTo($date2) || $date1->equalTo($date3)) {
                return redirect()->back()
                ->with('msg', 'ERROR: Patient just had a recent prescription 1-2 Days ago.')
                ->with('msgtype', 'warning');
            }

            
            */

            //get main cart and delete
            $delete_main_cart = PharmacyCartMain::where('prescription_id', $get_latest_prescription->id)
            ->where('patient_id', $get_patient->id)
            ->where('branch_id', auth()->user()->pharmacy_branch_id)
            ->delete();

            return redirect()->back()
            ->with('msg', 'New prescription was successfully created. Please fill-out the details below before issuing Medicine/s to the Patient.')
            ->with('msgtype', 'success');
        }
        else if($r->submit == 'add_cart') {
            if($r->meds) {
                $sku_code = $r->meds;
            }
            else {
                $sku_code = $r->alt_meds_id;
            }
    
            $find_substock = PharmacySupplySub::whereHas('pharmacysupplymaster', function ($q) use ($sku_code) {
                $q->where('sku_code', $sku_code);
            })
            ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
            ->first();
    
            if($find_substock) {
                $get_master_id = $find_substock->pharmacysupplymaster->id;

                //search if substock exist in the subcart
                $subcart_search = PharmacyCartSub::where('main_cart_id', $get_patient->getPendingCartMain()->id)
                ->where('subsupply_id', $find_substock->id)
                ->first();
    
                if($subcart_search) {
                    return redirect()->back()
                    ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' already exists in the list.')
                    ->with('msgtype', 'warning');
                }
    
                if($find_substock->pharmacysupplymaster->quantity_type == 'PIECE' && $r->type_to_process == 'BOX') {
                    return redirect()->back()
                    ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' only allows issuance type by PIECE.')
                    ->with('msgtype', 'warning');
                }
    
                if($r->type_to_process == 'BOX') {
                    //Code added April 14, 2024 - Check value first in substock

                    if($find_substock->master_box_stock <= 0) {
                        return redirect()->back()
                        ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' ran OUT OF STOCK.')
                        ->with('msgtype', 'warning');
                    }
                    else if($r->qty_to_process > $find_substock->master_box_stock) {
                        return redirect()->back()
                        ->with('msg', 'Error: Item ['.$find_substock->pharmacysupplymaster->name.' - Current Stock: '.$find_substock->master_box_stock.' '.Str::plural('BOX', $find_substock->master_box_stock).'] does not have enough stock to process '.$r->qty_to_process.' '.Str::plural('BOX', $r->qty_to_process))
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    if($find_substock->master_piece_stock <= 0) {
                        return redirect()->back()
                        ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' ran OUT OF STOCK.')
                        ->with('msgtype', 'warning');
                    }
                    else if($r->qty_to_process > $find_substock->master_piece_stock) {
                        return redirect()->back()
                        ->with('msg', 'Error: Item ['.$find_substock->pharmacysupplymaster->name.' - Current Stock: '.$find_substock->master_piece_stock.' '.Str::plural('PC', $find_substock->master_piece_stock).'] does not have enough stock to process '.$r->qty_to_process.' '.Str::plural('PC', $r->qty_to_process))
                        ->with('msgtype', 'warning');
                    }
                }

                if(!($r->enable_override)) {
                    $get_latest_prescription = PharmacyPrescription::where('finished', 0)
                    ->where('patient_id', $get_patient->id)
                    ->latest()
                    ->first();

                    //Check if lagpas na qty limit
                    $search_qtylimit = PharmacyQtyLimitPatient::where('master_supply_id', $get_master_id)
                    ->where('prescription_id', $get_latest_prescription->id)
                    ->first();

                    if($search_qtylimit) {
                        //get sum of pieces in stock card and compare to limit
                        $curr_qty_obtained = PharmacyStockCard::whereHas('pharmacysub', function ($q) use ($get_master_id) {
                            $q->whereHas('pharmacysupplymaster', function ($r) use ($get_master_id) {
                                $r->where('id', $get_master_id);
                            });
                        })
                        ->whereDate('created_at', '>=', $search_qtylimit->date_started)
                        ->where('qty_type', 'PIECE')
                        ->sum('qty_to_process');

                        if($curr_qty_obtained >= $search_qtylimit->set_pieces_limit) {
                            return redirect()->back()
                            ->with('msg', 'Error: Patient already reached the Quantity Limit based on the Patient Prescription. (Used: '.$curr_qty_obtained.' '.Str::plural('PC', $curr_qty_obtained).' | Max Limit: '.$search_qtylimit->set_pieces_limit.' '.Str::plural('PC', $search_qtylimit->set_pieces_limit).'). Advise the Patient to re-consult to OPD for a new prescription.')
                            ->with('msgtype', 'warning');
                        }
                        else if(($r->qty_to_process + $curr_qty_obtained) > $search_qtylimit->set_pieces_limit) {
                            return redirect()->back()
                            ->with('msg', 'Error: Quantity to Issue Exceeds the Quantity Limit based on the Patient Prescription. (Used: '.$curr_qty_obtained.' '.Str::plural('PC', $curr_qty_obtained).' | Max Limit: '.$search_qtylimit->set_pieces_limit.' '.Str::plural('PC', $search_qtylimit->set_pieces_limit).') Adjust/Reduce the Quantity to Issue then try again.')
                            ->with('msgtype', 'warning');
                        }
                    }

                    //Check if sobra na ng kuha based on duration
                    if(!is_null($find_substock->pharmacysupplymaster->maxpiece_perduration) || !is_null($find_substock->self_maxpiece_perduration)) {
                        if($find_substock->self_maxpiece_perduration) {
                            $get_max_piece_allowed = $find_substock->self_maxpiece_perduration;
                        }
                        else {
                            $get_max_piece_allowed = $find_substock->pharmacysupplymaster->maxpiece_perduration;
                        }

                        if($find_substock->self_duration_days) {
                            $get_days_duration = $find_substock->self_duration_days;
                        }
                        else {
                            $get_days_duration = $find_substock->pharmacysupplymaster->duration_days;
                        }
                        
                        if($search_qtylimit) {
                            $curr_qty_obtained = PharmacyStockCard::whereHas('pharmacysub', function ($q) use ($get_master_id) {
                                $q->whereHas('pharmacysupplymaster', function ($r) use ($get_master_id) {
                                    $r->where('id', $get_master_id);
                                });
                            })
                            ->whereBetween('created_at', [$search_qtylimit->date_started, Carbon::parse($search_qtylimit->date_started)->addDays($get_days_duration)->format('Y-m-d')])
                            ->where('qty_type', 'PIECE')
                            ->sum('qty_to_process');

                            if($curr_qty_obtained >= $get_max_piece_allowed) {
                                return redirect()->back()
                                ->with('msg', 'Error: Patient reached the Issuing Quota for the System Duration. Patient should come back after '.Carbon::parse($search_qtylimit->date_started)->addDays($get_days_duration)->format('m/d/Y (D)'))
                                ->with('msgtype', 'warning');
                            }
                            else if(($r->qty_to_process + $curr_qty_obtained) > $get_max_piece_allowed) {
                                return redirect()->back()
                                ->with('msg', 'Error: Quantity to Issue Exceeds the Maximum Quantity Allowed per Duration set by the System. Adjust/Reduce the Quantity to Issue then try again.')
                                ->with('msgtype', 'warning');
                            }
                        }
                    }
                }
                
                $create_subcart = PharmacyCartSub::create([
                    'main_cart_id' => $r->selected_maincart_id,
                    'subsupply_id' => $find_substock->id,
                    'qty_to_process' => $r->qty_to_process,
                    'type_to_process' => $r->type_to_process,
                ]);
    
                return redirect()->back()
                ->with('msg', 'Medicine '.$find_substock->pharmacysupplymaster->name.' added to list successfully.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()->back()
                ->with('msg', 'Error: SKU Code does not exist in the server. Please double check and try again.')
                ->with('msgtype', 'warning');
            }
        }
    }

    public function processCartItem($patient_id, Request $r) {
        $d = PharmacyPatient::findOrFail($patient_id);

        if($d->status == 'DISABLED') {
            return redirect()-back()
            ->with('msg', 'ERROR: Account of Patient '.$d->getName().' was DISABLED in the system. Issuance of Medicine/s was blocked. You may contact Pharmacist/Encoder if you think this was a mistake.')
            ->with('msgtype', 'danger');
        }

        if($r->submit == 'clear') {
            $sc = PharmacyCartSub::where('main_cart_id', $r->selected_maincart_id)
            ->delete();

            return redirect()->back()
            ->with('msg', 'Cart was reset.')
            ->with('msgtype', 'success');
        }
        else if($r->delete) {
            $sc = PharmacyCartSub::where('main_cart_id', $r->selected_maincart_id)
            ->where('id', $r->delete)->delete();

            return redirect()->back()
            ->with('msg', 'Item removed from cart.')
            ->with('msgtype', 'success');
        }
        else if($r->submit == 'process') {
            /*
            if(!($d->getPendingCartMain())) {
                return redirect()->back()
                ->with('msg', 'ERROR: Cart of the Patient ID: '.$d->id.'cannot be found. Please try again or contact the system admin.')
                ->with('msgtype', 'danger');
            }
            */

            $get_maincart = PharmacyCartMain::findOrFail($r->selected_maincart_id);

            $subcart_list = PharmacyCartSub::where('main_cart_id', $get_maincart->id)->get();

            foreach($subcart_list as $ind => $sc) {
                //check if from opd first
                if($sc->qty_to_process == 0) {
                    $sc->qty_to_process = $r->set_dyn_qty[$ind];
                    if($sc->isDirty()) {
                        $sc->save();
                    }
                }

                //check if subsupply has enough stocks
                $subsupply = PharmacySupplySub::findOrFail($sc->subsupply_id);

                if($sc->type_to_process == 'BOX') {
                    if($sc->qty_to_process <= $subsupply->master_box_stock) {
                        $subsupply->master_box_stock -= $sc->qty_to_process;
                        $subsupply->master_piece_stock -= ($sc->qty_to_process * $sc->pharmacysub->pharmacysupplymaster->config_piecePerBox);

                        $qty_remaining = $sc->qty_to_process;

                        $substock_search = PharmacySupplySubStock::where('subsupply_id', $subsupply->id)
                        ->where('current_box_stock', '>', 0)
                        ->whereDate('expiration_date', '>', date('Y-m-d'))
                        ->orderBy('expiration_date', 'ASC')
                        ->get();

                        foreach($substock_search as $substock) {
                            if($qty_remaining > 0) {
                                if($qty_remaining <= $substock->current_box_stock) {
                                    $substock->current_box_stock -= $qty_remaining;
                                    $substock->current_piece_stock -= ($qty_remaining * $substock->pharmacysub->pharmacysupplymaster->config_piecePerBoxconfig_piecePerBox);

                                    $qty_remaining = 0;
                                }
                                else {
                                    $substock->current_box_stock = 0;
                                    $substock->current_piece_stock = 0;

                                    $qty_remaining -= ($qty_remaining - $substock->getOriginal('current_box_stock'));
                                }

                                if($substock->isDirty()) {
                                    $substock->save();
                                }
                            }
                        }
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Medicine '.$sc->pharmacysub->pharmacysupplymaster->name.' Box stocks were updated before processing. Please check the available stock and try again.')
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    if($sc->qty_to_process <= $subsupply->master_piece_stock) {
                        $subsupply->master_piece_stock -= $sc->qty_to_process;

                        if($subsupply->pharmacysupplymaster->quantity_type == 'BOX') {
                            while(($subsupply->master_box_stock * $subsupply->pharmacysupplymaster->config_piecePerBox) > $subsupply->master_piece_stock) {
                                $subsupply->master_box_stock--;
                            }
                        }

                        $qty_remaining = $sc->qty_to_process;

                        $substock_search = PharmacySupplySubStock::where('subsupply_id', $subsupply->id)
                        ->where('current_piece_stock', '>', 0)
                        ->whereDate('expiration_date', '>', date('Y-m-d'))
                        ->orderBy('expiration_date', 'ASC')
                        ->get();

                        foreach($substock_search as $substock) {
                            if($qty_remaining > 0) {
                                if($qty_remaining <= $substock->current_piece_stock) {
                                    $substock->current_piece_stock -= $qty_remaining;

                                    if($substock->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
                                        while(($substock->current_box_stock * $substock->pharmacysub->pharmacysupplymaster->config_piecePerBox) > $substock->current_piece_stock) {
                                            $substock->current_box_stock--;
                                        }
                                    }

                                    $qty_remaining = 0;
                                }
                                else {
                                    $substock->current_piece_stock = 0;

                                    if($substock->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
                                        $substock->current_box_stock = 0;
                                    }

                                    $qty_remaining -= ($qty_remaining - $substock->getOriginal('current_box_stock'));
                                }

                                if($substock->isDirty()) {
                                    $substock->save();
                                }
                            }
                        }

                        //Create QTY Limit if not existing
                        $search_qtylimit = PharmacyQtyLimitPatient::where('prescription_id', $get_maincart->prescription_id)
                        ->where('master_supply_id', $sc->pharmacysub->pharmacysupplymaster->id)
                        ->first();

                        if(!($search_qtylimit)) {
                            $create_qty_limit = PharmacyQtyLimitPatient::create([
                                'prescription_id' => $get_maincart->prescription_id,
                                'master_supply_id' => $sc->pharmacysub->pharmacysupplymaster->id,
                                'set_pieces_limit' => $r->set_pieces_limit[$ind],
                                'date_started' => date('Y-m-d'),
                            ]);
                        }
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Medicine '.$sc->pharmacysub->pharmacysupplymaster->name.' Quantity (in Pieces) were updated before processing. Please check the available stock and try again.')
                        ->with('msgtype', 'warning');
                    }
                }

                //make stock card
                $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $subsupply->id,
                    'type' => 'ISSUED',
                    'before_qty_box' => ($subsupply->pharmacysupplymaster->quantity_type == 'BOX') ? $subsupply->getOriginal('master_box_stock') : NULL,
                    'before_qty_piece' => $subsupply->getOriginal('master_piece_stock'),
                    'qty_to_process' => $sc->qty_to_process,
                    'qty_type' => $sc->type_to_process,
                    'after_qty_box' => ($subsupply->pharmacysupplymaster->quantity_type == 'BOX') ? $subsupply->master_box_stock : NULL,
                    'after_qty_piece' => $subsupply->master_piece_stock,

                    'receiving_patient_id' => $d->id,
                    'patient_age_years' => $d->getAgeInt(),
                    'patient_prescription_id' => $d->getLatestPrescription()->id,

                    'sentby_branch_id' => auth()->user()->pharmacy_branch_id,
                ]);

                if($subsupply->isDirty()) {
                    $subsupply->save();
                }
            }
            
            $get_maincart->status = 'COMPLETED';
            if($get_maincart->isDirty()) {
                $get_maincart->save();
            }

            return redirect()->route('pharmacy_home')
            ->with('msg', 'Issuance of Medicine/s was processed successfully. You may now return the Card and Prescription to the Patient.')
            ->with('msgtype', 'success');
        }
    }

    public function modifyStockBranchView($branch_id) {
        $d = PharmacyBranch::findOrFail($branch_id);

        if($d->id == auth()->user()->pharmacy_branch_id) {
            return redirect()->back()
            ->with('msg', 'Error: You cannot do transaction/s againsts your own Branch.')
            ->with('msgtype', 'warning');
        }

        $maincart = PharmacyCartMainBranch::where('branch_id', $d->id)
        ->where('processor_branch_id', auth()->user()->pharmacy_branch_id)
        ->where('status', 'PENDING')
        ->latest()
        ->first();

        if(!($maincart)) {
            $maincart = auth()->user()->pharmacycartmainbranch()->create([
                'status' => 'PENDING',
                'branch_id' => $d->id,
                'processor_branch_id' => auth()->user()->pharmacy_branch_id,
            ]);
        }

        $load_subcart = PharmacyCartSubBranch::where('main_cart_id', $maincart->id)->get();

        $meds_list = PharmacySupplySub::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)->get();

        return view('pharmacy.modify_stock_branchview', [
            'd' => $d,
            'maincart' => $maincart,
            'load_subcart' => $load_subcart,
            'meds_list' => $meds_list,
        ]);
    }

    public function addCartBranch($branch_id, Request $r) {
        $get_branch = PharmacyBranch::findOrFail($branch_id);

        if($r->meds) {
            $sku_code = $r->meds;

            //convert to sku_code
            /*
            else if(Str::startsWith($code, 'SUBSTOCK_')) {
                $newString = Str::replaceFirst("SUBSTOCK_", "", $code);
            */
        }
        else {
            $sku_code = $r->alt_meds_id;
        }

        $find_substock = PharmacySupplySub::whereHas('pharmacysupplymaster', function ($q) use ($sku_code) {
            $q->where('sku_code', $sku_code);
        })
        ->where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
        ->first();

        if($find_substock) {
            $get_master_id = $find_substock->pharmacysupplymaster->id;

            //search if substock exist in the subcart
            $subcart_search = PharmacyCartSubBranch::where('main_cart_id', $r->selected_maincart_id)
            ->where('subsupply_id', $find_substock->id)
            ->first();

            if($subcart_search) {
                return redirect()->back()
                ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' already exists in the list.')
                ->with('msgtype', 'warning');
            }

            if($find_substock->pharmacysupplymaster->quantity_type == 'PIECE' && $r->type_to_process == 'BOX') {
                return redirect()->back()
                ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' only allows issuance type by PIECE.')
                ->with('msgtype', 'warning');
            }

            if($r->type_to_process == 'BOX') {
                if($find_substock->master_box_stock <= 0) {
                    return redirect()->back()
                    ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' ran OUT OF STOCK.')
                    ->with('msgtype', 'warning');
                }
                else if($r->qty_to_process > $find_substock->master_box_stock) {
                    return redirect()->back()
                    ->with('msg', 'Error: Item ['.$find_substock->pharmacysupplymaster->name.' - Current Stock: '.$find_substock->master_box_stock.' '.Str::plural('BOX', $find_substock->master_box_stock).'] does not have enough stock to process '.$r->qty_to_process.' '.Str::plural('BOX', $r->qty_to_process))
                    ->with('msgtype', 'warning');
                }
            }
            else {
                if($find_substock->master_piece_stock <= 0) {
                    return redirect()->back()
                    ->with('msg', 'Error: Item '.$find_substock->pharmacysupplymaster->name.' ran OUT OF STOCK.')
                    ->with('msgtype', 'warning');
                }
                else if($r->qty_to_process > $find_substock->master_piece_stock) {
                    return redirect()->back()
                    ->with('msg', 'Error: Item ['.$find_substock->pharmacysupplymaster->name.' - Current Stock: '.$find_substock->master_piece_stock.' '.Str::plural('PC', $find_substock->master_piece_stock).'] does not have enough stock to process '.$r->qty_to_process.' '.Str::plural('PC', $r->qty_to_process))
                    ->with('msgtype', 'warning');
                }
            }

            /*

            NO OVERRIDE IN BRANCHES TRANSACTION

            if(!($r->enable_override)) {
                $get_latest_prescription = PharmacyPrescription::where('finished', 0)
                ->where('patient_id', $get_patient->id)
                ->latest()
                ->first();

                //Check if lagpas na qty limit
                $search_qtylimit = PharmacyQtyLimitPatient::where('master_supply_id', $get_master_id)
                ->where('prescription_id', $get_latest_prescription->id)
                ->first();

                if($search_qtylimit) {
                    //get sum of pieces in stock card and compare to limit
                    $curr_qty_obtained = PharmacyStockCard::whereHas('pharmacysub', function ($q) use ($get_master_id) {
                        $q->whereHas('pharmacysupplymaster', function ($r) use ($get_master_id) {
                            $r->where('id', $get_master_id);
                        });
                    })
                    ->whereDate('created_at', '>=', $search_qtylimit->date_started)
                    ->where('qty_type', 'PIECE')
                    ->sum('qty_to_process');

                    if($curr_qty_obtained >= $search_qtylimit->set_pieces_limit) {
                        return redirect()->back()
                        ->with('msg', 'Error: Patient already reached the Quantity Limit based on the Patient Prescription. (Used: '.$curr_qty_obtained.' '.Str::plural('PC', $curr_qty_obtained).' | Max Limit: '.$search_qtylimit->set_pieces_limit.' '.Str::plural('PC', $search_qtylimit->set_pieces_limit).'). Advise the Patient to re-consult to OPD for a new prescription.')
                        ->with('msgtype', 'warning');
                    }
                    else if(($r->qty_to_process + $curr_qty_obtained) > $search_qtylimit->set_pieces_limit) {
                        return redirect()->back()
                        ->with('msg', 'Error: Quantity to Issue Exceeds the Quantity Limit based on the Patient Prescription. (Used: '.$curr_qty_obtained.' '.Str::plural('PC', $curr_qty_obtained).' | Max Limit: '.$search_qtylimit->set_pieces_limit.' '.Str::plural('PC', $search_qtylimit->set_pieces_limit).') Adjust/Reduce the Quantity to Issue then try again.')
                        ->with('msgtype', 'warning');
                    }
                }

                //Check if sobra na ng kuha based on duration
                if(!is_null($find_substock->pharmacysupplymaster->maxpiece_perduration) || !is_null($find_substock->self_maxpiece_perduration)) {
                    if($find_substock->self_maxpiece_perduration) {
                        $get_max_piece_allowed = $find_substock->self_maxpiece_perduration;
                    }
                    else {
                        $get_max_piece_allowed = $find_substock->pharmacysupplymaster->maxpiece_perduration;
                    }

                    if($find_substock->self_duration_days) {
                        $get_days_duration = $find_substock->self_duration_days;
                    }
                    else {
                        $get_days_duration = $find_substock->pharmacysupplymaster->duration_days;
                    }
                    
                    if($search_qtylimit) {
                        $curr_qty_obtained = PharmacyStockCard::whereHas('pharmacysub', function ($q) use ($get_master_id) {
                            $q->whereHas('pharmacysupplymaster', function ($r) use ($get_master_id) {
                                $r->where('id', $get_master_id);
                            });
                        })
                        ->whereBetween('created_at', [$search_qtylimit->date_started, Carbon::parse($search_qtylimit->date_started)->addDays($get_days_duration)->format('Y-m-d')])
                        ->where('qty_type', 'PIECE')
                        ->sum('qty_to_process');

                        if($curr_qty_obtained >= $get_max_piece_allowed) {
                            return redirect()->back()
                            ->with('msg', 'Error: Patient reached the Issuing Quota for the System Duration. Patient should come back after '.Carbon::parse($search_qtylimit->date_started)->addDays($get_days_duration)->format('m/d/Y (D)'))
                            ->with('msgtype', 'warning');
                        }
                        else if(($r->qty_to_process + $curr_qty_obtained) > $get_max_piece_allowed) {
                            return redirect()->back()
                            ->with('msg', 'Error: Quantity to Issue Exceeds the Maximum Quantity Allowed per Duration set by the System. Adjust/Reduce the Quantity to Issue then try again.')
                            ->with('msgtype', 'warning');
                        }
                    }
                }
            }
            */
            
            $create_subcart = PharmacyCartSubBranch::create([
                'main_cart_id' => $r->selected_maincart_id,
                'subsupply_id' => $find_substock->id,
                'qty_to_process' => $r->qty_to_process,
                'type_to_process' => $r->type_to_process,
            ]);

            return redirect()->back()
            ->with('msg', 'Medicine '.$find_substock->pharmacysupplymaster->name.' added to list successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: SKU Code does not exist in the server. Please double check and try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function processCartBranch($branch_id, Request $r) {
        $d = PharmacyBranch::findOrFail($branch_id);

        if($d->enabled == 0) {
            return redirect()->back()
            ->with('msg', 'ERROR: Branch '.$d->name.' was DISABLED in the system. Issuance of Medicine/s was blocked. You may contact Pharmacist/Encoder if you think this was a mistake.')
            ->with('msgtype', 'danger');
        }

        if($r->submit == 'clear') {
            $sc = PharmacyCartSubBranch::where('main_cart_id', $r->selected_maincart_id)
            ->delete();

            return redirect()->back()
            ->with('msg', 'Cart was reset.')
            ->with('msgtype', 'success');
        }
        else if($r->delete) {
            $sc = PharmacyCartSubBranch::where('main_cart_id', $r->selected_maincart_id)
            ->where('id', $r->delete)->delete();

            return redirect()->back()
            ->with('msg', 'Item removed from cart.')
            ->with('msgtype', 'success');
        }
        else if($r->submit == 'process') {
            $get_maincart = PharmacyCartMainBranch::findOrFail($r->selected_maincart_id);

            $subcart_list = PharmacyCartSubBranch::where('main_cart_id', $get_maincart->id)->get();

            foreach($subcart_list as $ind => $sc) {
                //check if subsupply has enough stocks
                $subsupply = PharmacySupplySub::findOrFail($sc->subsupply_id);

                if($sc->type_to_process == 'BOX') {
                    if($sc->qty_to_process <= $subsupply->master_box_stock) {
                        $subsupply->master_box_stock -= $sc->qty_to_process;
                        $subsupply->master_piece_stock -= ($sc->qty_to_process * $sc->pharmacysub->pharmacysupplymaster->config_piecePerBox);

                        $qty_remaining = $sc->qty_to_process;

                        $substock_search = PharmacySupplySubStock::where('subsupply_id', $subsupply->id)
                        ->where('current_box_stock', '>', 0)
                        ->whereDate('expiration_date', '>', date('Y-m-d'))
                        ->orderBy('expiration_date', 'ASC')
                        ->get();

                        foreach($substock_search as $substock) {
                            if($qty_remaining != 0) {
                                if($qty_remaining <= $substock->current_box_stock) {
                                    $substock->current_box_stock -= $qty_remaining;
                                    $substock->current_piece_stock -= ($qty_remaining * $substock->pharmacysub->pharmacysupplymaster->config_piecePerBoxconfig_piecePerBox);

                                    $qty_remaining = 0;
                                }
                                else {
                                    $substock->current_box_stock = 0;
                                    $substock->current_piece_stock = 0;

                                    $qty_remaining -= ($qty_remaining - $substock->getOriginal('current_box_stock'));
                                }

                                if($substock->isDirty()) {
                                    $substock->save();
                                }
                            }
                        }
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Medicine '.$sc->pharmacysub->pharmacysupplymaster->name.' Box stocks were updated before processing. Please check the available stock and try again.')
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    if($sc->qty_to_process <= $subsupply->master_piece_stock) {
                        $subsupply->master_piece_stock -= $sc->qty_to_process;

                        if($subsupply->pharmacysupplymaster->quantity_type == 'BOX') {
                            while(($subsupply->master_box_stock * $subsupply->pharmacysupplymaster->config_piecePerBox) > $subsupply->master_piece_stock) {
                                $subsupply->master_box_stock--;
                            }
                        }

                        $qty_remaining = $sc->qty_to_process;

                        $substock_search = PharmacySupplySubStock::where('subsupply_id', $subsupply->id)
                        ->where('current_piece_stock', '>', 0)
                        ->whereDate('expiration_date', '>', date('Y-m-d'))
                        ->orderBy('expiration_date', 'ASC')
                        ->get();

                        foreach($substock_search as $substock) {
                            if($qty_remaining != 0) {
                                if($qty_remaining <= $substock->current_piece_stock) {
                                    $substock->current_piece_stock -= $qty_remaining;

                                    if($substock->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
                                        while(($substock->current_box_stock * $substock->pharmacysub->pharmacysupplymaster->config_piecePerBox) > $substock->current_piece_stock) {
                                            $substock->current_box_stock--;
                                        }
                                    }

                                    $qty_remaining = 0;
                                }
                                else {
                                    $substock->current_piece_stock = 0;

                                    if($substock->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
                                        $substock->current_box_stock = 0;
                                    }

                                    $qty_remaining -= ($qty_remaining - $substock->getOriginal('current_box_stock'));
                                }

                                if($substock->isDirty()) {
                                    $substock->save();
                                }
                            }
                        }

                        //Create QTY Limit if not existing
                        /*
                        $search_qtylimit = PharmacyQtyLimitPatient::where('prescription_id', $get_maincart->prescription_id)
                        ->where('master_supply_id', $sc->pharmacysub->pharmacysupplymaster->id)
                        ->first();
                        if(!($search_qtylimit)) {
                            $create_qty_limit = PharmacyQtyLimitPatient::create([
                                'prescription_id' => $get_maincart->prescription_id,
                                'master_supply_id' => $sc->pharmacysub->pharmacysupplymaster->id,
                                'set_pieces_limit' => $r->set_pieces_limit[$ind],
                                'date_started' => date('Y-m-d'),
                            ]);
                        }
                        */
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Medicine '.$sc->pharmacysub->pharmacysupplymaster->name.' Quantity (in Pieces) were updated before processing. Please check the available stock and try again.')
                        ->with('msgtype', 'warning');
                    }
                }

                //make stock card (issuance)
                $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $subsupply->id,
                    'type' => 'ISSUED',
                    'before_qty_box' => ($subsupply->pharmacysupplymaster->quantity_type == 'BOX') ? $subsupply->getOriginal('master_box_stock') : NULL,
                    'before_qty_piece' => $subsupply->getOriginal('master_piece_stock'),
                    'qty_to_process' => $sc->qty_to_process,
                    'qty_type' => $sc->type_to_process,
                    'after_qty_box' => ($subsupply->pharmacysupplymaster->quantity_type == 'BOX') ? $subsupply->master_box_stock : NULL,
                    'after_qty_piece' => $subsupply->master_piece_stock,

                    //'receiving_patient_id' => $d->id,
                    //'patient_age_years' => $d->getAgeInt(),
                    //'patient_prescription_id' => $d->getLatestPrescription()->id,

                    'receiving_branch_id' => $d->id,
                    'sentby_branch_id' => auth()->user()->pharmacy_branch_id,
                ]);

                //Search the Substock ID of the Branch and add the quantity received
                $search_branchSubStock = PharmacySupplySub::where('supply_master_id', $subsupply->pharmacysupplymaster->id)
                ->where('pharmacy_branch_id', $d->id)
                ->first();

                if($search_branchSubStock) {
                    //UPDATE AND ADD TO EXISTING STOCKS
                    if($sc->type_to_process == 'BOX') {
                        $search_branchSubStock->master_box_stock += $sc->qty_to_process;
                        $search_branchSubStock->master_piece_stock += ($sc->qty_to_process * $subsupply->pharmacysupplymaster->config_piecePerBox);
                    }
                    else {
                        if($subsupply->pharmacysupplymaster->quantity_type == 'BOX') { 
                            $search_branchSubStock->master_piece_stock += $sc->qty_to_process;
                            $search_branchSubStock->master_box_stock = floor($search_branchSubStock->master_piece_stock / $subsupply->pharmacysupplymaster->config_piecePerBox);
                        }
                        else {
                            $search_branchSubStock->master_piece_stock += $sc->qty_to_process;
                        }
                    }
                    
                    //FOR STOCK CARD PURPOSES
                    if($sc->type_to_process == 'BOX' || $subsupply->pharmacysupplymaster->quantity_type == 'BOX') {
                        $input_before_qty_box = $search_branchSubStock->getOriginal('master_box_stock');
                        $input_before_qty_piece = $search_branchSubStock->getOriginal('master_piece_stock');

                        $input_master_box_stock = $search_branchSubStock->master_box_stock;
                        $input_master_piece_stock = $search_branchSubStock->master_piece_stock;
                    }
                    else {
                        $input_before_qty_box = NULL;
                        $input_before_qty_piece = $search_branchSubStock->getOriginal('master_piece_stock');

                        $input_master_box_stock = NULL;
                        $input_master_piece_stock = $search_branchSubStock->master_piece_stock;
                    }

                    if($search_branchSubStock->isDirty()) {
                        $search_branchSubStock->save();
                    }
                }
                else {
                    if($sc->type_to_process == 'BOX') {
                        /*
                        If issuing type is BOX:
                        - master_box_stock = master_box_stock + RECEIVED QTY
                        - master_piece_stock = master_piece_stock + (RECEIVED QTY * MAXIMUM QTY PER BOX)
                        */

                        $input_master_box_stock = $sc->qty_to_process;
                        $input_master_piece_stock = ($sc->qty_to_process * $subsupply->pharmacysupplymaster->config_piecePerBox);
                    }
                    else {
                        /*
                        If the master item is a BOX:
                            - add base piece qty to received piece qty
                            - get total new base piece qty then divide to maximum qty per box to update the box qty
                        else:
                            - just add the base piece qty to received piece qty
                        */

                        if($subsupply->pharmacysupplymaster->quantity_type == 'BOX') {
                            $input_master_piece_stock = $sc->qty_to_process;
                            $input_master_box_stock = floor($sc->qty_to_process / $subsupply->pharmacysupplymaster->config_piecePerBox);
                        }
                        else {
                            $input_master_box_stock = NULL;
                            $input_master_piece_stock = $sc->qty_to_process;
                        }
                    }

                    //FOR STOCK CARD PURPOSES
                    if($sc->type_to_process == 'BOX' || $subsupply->pharmacysupplymaster->quantity_type == 'BOX') {
                        $input_before_qty_box = 0;
                        $input_before_qty_piece = 0;
                    }
                    else {
                        $input_before_qty_box = NULL;
                        $input_before_qty_piece = 0;
                    }

                    $search_branchSubStock = $r->user()->pharmacysupplysub()->create([
                        'supply_master_id' => $subsupply->pharmacysupplymaster->id,
                        'pharmacy_branch_id' => $d->id,
                        
                        'master_box_stock' => $input_master_box_stock,
                        'master_piece_stock' => $input_master_piece_stock,
                    ]);
                }

                //make stock card (receive)
                $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $search_branchSubStock->id,
                    'type' => 'RECEIVED',
                    'before_qty_box' => $input_before_qty_box,
                    'before_qty_piece' => $input_before_qty_piece,

                    'qty_to_process' => $sc->qty_to_process,
                    'qty_type' => $sc->type_to_process,
                    
                    'after_qty_box' => $input_master_box_stock,
                    'after_qty_piece' => $input_master_piece_stock,

                    //'receiving_patient_id' => $d->id,
                    //'patient_age_years' => $d->getAgeInt(),
                    //'patient_prescription_id' => $d->getLatestPrescription()->id,

                    //'receiving_branch_id' => $d->id,
                    'sentby_branch_id' => auth()->user()->pharmacy_branch_id,
                ]);

                if($subsupply->isDirty()) {
                    $subsupply->save();
                }
            }
            
            $get_maincart->status = 'COMPLETED';
            if($get_maincart->isDirty()) {
                $get_maincart->save();
            }

            return redirect()->route('pharmacy_home')
            ->with('msg', 'Issued Medicines to Branch: '.$d->name.' successfully. You may now return the Card to the Focal Person.')
            ->with('msgtype', 'success');
        }
    }

    public function modifyStockProcess($subsupply_id, Request $r) {
        $d = PharmacySupplySub::findOrFail($subsupply_id);

        if($r->type == 'ISSUED') {
            $substock = PharmacySupplySubStock::findOrFail($r->select_sub_stock_id);

            //Check if Authorized
            if($substock->subsupply_id != $d->id && auth()->user()->pharmacy_branch_id != $d->pharmacy_branch_id) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
            
            //IF PATIENT, GET PATIENT
            if($r->select_recipient == 'PATIENT') {
                if(Str::startsWith($r->receiving_patient_id, 'PATIENT_')) {
                    $newString = Str::replaceFirst("PATIENT_", "", $r->receiving_patient_id);

                    $search_patient = PharmacyPatient::where('qr', $newString)->first();
                }
                else {
                    $search_patient = PharmacyPatient::where('qr', $r->receiving_patient_id)
                    ->orWhere('id', $r->receiving_patient_id)
                    ->first();
                }

                if($search_patient) {
                    $get_patient_id = $search_patient->id;
                    $get_patient_age = $search_patient->getAgeInt();
                    $get_patient_prescription = $search_patient->getLatestPrescription()->id;
                }
                else {
                    return redirect()->back()
                    ->withInput()
                    ->with('msg', 'Patient record does not exist on the server. Please double check and try again.')
                    ->with('msgtype', 'warning');
                }
            }

            if($d->pharmacysupplymaster->quantity_type == 'BOX') {
                if($r->qty_type == 'BOX') {
                    //BOX ISSUANCE IN BOX

                    if($r->qty_to_process <= $substock->current_box_stock) {
                        //UPDATE SUBSUPPLY
                        $d->master_box_stock -= $r->qty_to_process;
                        $d->master_piece_stock -= $r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox;
                        
                        //UPDATE SUBSTOCK
                        $substock->current_box_stock -= $r->qty_to_process;
                        $substock->current_piece_stock -= $r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox;
                    }
                    else {
                        return redirect()->back()
                        ->withInput()
                        ->with('msg', 'Error: Quantity to Process is greater than the Current Batch Quantity. Or Current Batch Quantity was updated.')
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    //BOX ISSUANCE IN PIECE

                    if($r->qty_to_process <= $substock->current_piece_stock) {
                        //UPDATE SUBSUPPLY
                        $d->master_piece_stock -= $r->qty_to_process;
                        while(($d->master_box_stock * $d->pharmacysupplymaster->config_piecePerBox) > $d->master_piece_stock) {
                            $d->master_box_stock--;
                        }

                        //UPDATE SUBSTOCK
                        $substock->current_piece_stock -= $r->qty_to_process;
                        while(($substock->current_box_stock * $d->pharmacysupplymaster->config_piecePerBox) > $substock->current_piece_stock) {
                            $substock->current_box_stock--;
                        }
                    }
                    else {
                        return redirect()->back()
                        ->withInput()
                        ->with('msg', 'Error: Quantity to Process is greater than the Current Batch Quantity. Or Current Batch Quantity was updated.')
                        ->with('msgtype', 'warning');
                    }
                }
            }
            else {
                //PIECE ISSUANCE
                if($r->qty_to_process <= $substock->current_piece_stock) {
                    //UPDATE SUBSUPPLY
                    $d->master_piece_stock -= $r->qty_to_process;

                    //UPDATE SUBSTOCK
                    $substock->current_piece_stock -= $r->qty_to_process;
                }
                else {
                    return redirect()->back()
                    ->withInput()
                    ->with('msg', 'Error: Quantity to Process is greater than the Current Batch Quantity. Or Current Batch Quantity was updated.')
                    ->with('msgtype', 'warning');
                }
            }

            //CREATE STOCK CARD
            $new_stockcard = $r->user()->pharmacystockcard()->create([
                'subsupply_id' => $d->id,
                'type' => 'ISSUED',
                'before_qty_box' => ($d->pharmacysupplymaster->quantity_type == 'BOX') ? $d->getOriginal('master_box_stock') : NULL,
                'before_qty_piece' => $d->getOriginal('master_piece_stock'),
                'qty_to_process' => $r->qty_to_process,
                'qty_type' => $r->qty_type,
                'after_qty_box' => $d->master_box_stock,
                'after_qty_piece' => $d->master_piece_stock,
                'total_cost' => $r->total_cost,
                'drsi_number' => $r->drsi_number,

                'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $get_patient_id : NULL,
                'patient_age_years' => ($r->select_recipient == 'PATIENT') ? $get_patient_age : NULL,
                'patient_prescription_id' => ($r->select_recipient == 'PATIENT') ? $get_patient_prescription : NULL,
                'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,
                
                'remarks' => $r->remarks,
                'sentby_branch_id' => auth()->user()->pharmacy_branch_id,
            ]);

            if($d->isDirty()) {
                $d->save();
            }

            if($substock->isDirty()) {
                $substock->save();
            }

            //IF BRANCH, PROCESS STOCKCARD
            if($r->select_recipient == 'BRANCH') {
                //PROCESS BRANCH SUBSUPPLY

                $branch_subsupply = PharmacySupplySub::whereHas('pharmacysupplymaster', function ($q) use ($d) {
                    $q->where('sku_code', $d->pharmacysupplymaster->sku_code);
                })->where('pharmacy_branch_id', $r->receiving_branch_id)
                ->first();

                if($d->pharmacysupplymaster->quantity_type == 'BOX') {
                    if($r->qty_type == 'BOX') {
                        //BOX ISSUANCE IN BOX

                        if($branch_subsupply) {
                            //OK
                            $branch_subsupply->master_box_stock += $r->qty_to_process;
                            $branch_subsupply->master_piece_stock += ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);
                        
                            $sspid = $branch_subsupply->id;

                            $before_branch_qty_box = $branch_subsupply->getOriginal('master_box_stock');
                            $before_branch_qty_piece = $branch_subsupply->getOriginal('master_piece_stock');

                            $after_branch_qty_box = $branch_subsupply->master_box_stock;
                            $after_branch_qty_piece = $branch_subsupply->master_piece_stock;

                            if($branch_subsupply->isDirty()) {
                                $branch_subsupply->save();
                            }
                        }
                        else {
                            //OK
                            $new_branch_subsupply = $r->user()->pharmacysupplysub()->create([
                                'supply_master_id' => $d->pharmacysupplymaster->id,
                                'pharmacy_branch_id' => $r->receiving_branch_id,

                                'master_box_stock' => $r->qty_to_process,
                                'master_piece_stock' => ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox),
                            ]);

                            $sspid = $new_branch_subsupply->id;

                            $before_branch_qty_box = 0;
                            $before_branch_qty_piece = 0;

                            $after_branch_qty_box = $new_branch_subsupply->master_box_stock;
                            $after_branch_qty_piece = $new_branch_subsupply->master_piece_stock;
                        }
                    }
                    else {
                        //BOX ISSUANCE IN PIECE
                        if($branch_subsupply) {
                            //OK
                            $branch_subsupply->master_piece_stock += $r->$r->qty_to_process;
                            while(($branch_subsupply->master_box_stock * $d->pharmacysupplymaster->config_piecePerBox) < $branch_subsupply->master_piece_stock) {
                                $branch_subsupply->master_box_stock++;
                            }

                            $sspid = $branch_subsupply->id;

                            $before_branch_qty_box = $branch_subsupply->getOriginal('master_box_stock');
                            $before_branch_qty_piece = $branch_subsupply->getOriginal('master_piece_stock');

                            $after_branch_qty_box = $branch_subsupply->master_box_stock;
                            $after_branch_qty_piece = $branch_subsupply->master_piece_stock;

                            if($branch_subsupply->isDirty()) {
                                $branch_subsupply->save();
                            }
                        }
                        else {
                            //OK
                            $new_branch_subsupply = $r->user()->pharmacysupplysub()->create([
                                'supply_master_id' => $d->pharmacysupplymaster->id,
                                'pharmacy_branch_id' => $r->receiving_branch_id,

                                'master_box_stock' => ($r->qty_to_process % $d->pharmacysupplymaster->config_piecePerBox === 0) ? ($r->qty_to_process / $d->pharmacysupplymaster->config_piecePerBox) : 0,
                                'master_piece_stock' => $r->qty_to_process,
                            ]);

                            $sspid = $new_branch_subsupply->id;

                            $before_branch_qty_box = 0;
                            $before_branch_qty_piece = 0;

                            $after_branch_qty_box = $new_branch_subsupply->master_box_stock;
                            $after_branch_qty_piece = $new_branch_subsupply->master_piece_stock;
                        }
                    }
                }
                else {
                    //PIECE ISSUANCE
                    if($branch_subsupply) {
                        //OK
                        $branch_subsupply->master_piece_stock += $r->qty_to_process;

                        $sspid = $branch_subsupply->id;
                        
                        $before_branch_qty_box = NULL;
                        $before_branch_qty_piece = $branch_subsupply->getOriginal('master_piece_stock');

                        $after_branch_qty_box = NULL;
                        $after_branch_qty_piece = $branch_subsupply->master_piece_stock;

                        if($branch_subsupply->isDirty()) {
                            $branch_subsupply->save();
                        }
                    
                    }
                    else {
                        //OK
                        $new_branch_subsupply = $r->user()->pharmacysupplysub()->create([
                            'supply_master_id' => $d->pharmacysupplymaster->id,
                            'pharmacy_branch_id' => $r->receiving_branch_id,

                            //'master_box_stock' => ($r->qty_to_process % $d->pharmacysupplymaster->config_piecePerBox === 0) ? ($r->qty_to_process / $d->pharmacysupplymaster->config_piecePerBox) : 0,
                            'master_piece_stock' => $r->qty_to_process,
                        ]);

                        $before_branch_qty_box = NULL;
                        $before_branch_qty_piece = 0;

                        $after_branch_qty_box = NULL;
                        $after_branch_qty_piece = $new_branch_subsupply->master_piece_stock;

                        $sspid = $new_branch_subsupply->id;
                    }
                }

                //PROCESS BRANCH SUBSTOCK
                $branch_substock = PharmacySupplySubStock::where('subsupply_id', $sspid)
                ->whereDate('expiration_date', $substock->expiration_date)
                ->first();

                if($d->pharmacysupplymaster->quantity_type == 'BOX') {
                    if($r->qty_type == 'BOX') {
                        if($branch_substock) {
                            //OK
                            $branch_substock->current_box_stock += $r->qty_to_process;
                            $branch_substock->current_piece_stock += ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);
                        
                            if($branch_substock->isDirty()) {
                                $branch_substock->save();
                            }
                        }
                        else {
                            //OK
                            $new_branch_substock = $r->user()->pharmacysupplysubstock()->create([
                                'subsupply_id' => $sspid,
                                'expiration_date' => $substock->expiration_date,
                                'current_box_stock' => $r->qty_to_process,
                                'current_piece_stock' => ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox),
                            ]);
                        }
                    }
                    else {
                        if($branch_substock) {
                            //OK
                            $branch_substock->current_piece_stock += $r->qty_to_process;
                            while(($branch_substock->current_box_stock * $d->pharmacysupplymaster->config_piecePerBox) < $branch_substock->current_piece_stock) {
                                $branch_substock->current_box_stock++;
                            }

                            if($branch_substock->isDirty()) {
                                $branch_substock->save();
                            }
                        }
                        else {
                            //OK
                            $new_branch_substock = $r->user()->pharmacysupplysubstock()->create([
                                'subsupply_id' => $sspid,
                                'expiration_date' => $substock->expiration_date,
                                'current_box_stock' => ($r->qty_to_process % $d->pharmacysupplymaster->config_piecePerBox === 0) ? ($r->qty_to_process / $d->pharmacysupplymaster->config_piecePerBox) : 0,
                                'current_piece_stock' => $r->qty_to_process,
                            ]);
                        }
                    }
                }
                else {
                    if($branch_substock) {
                        $branch_substock->current_piece_stock += $r->qty_to_process;

                        if($branch_substock->isDirty()) {
                            $branch_substock->save();
                        }
                    }
                    else {
                        $new_branch_substock = $r->user()->pharmacysupplysubstock()->create([
                            'subsupply_id' => $sspid,
                            'expiration_date' => $substock->expiration_date,
                            //'current_box_stock' => ($r->qty_to_process % $d->pharmacysupplymaster->config_piecePerBox === 0) ? ($r->qty_to_process / $d->pharmacysupplymaster->config_piecePerBox) : 0,
                            'current_piece_stock' => $r->qty_to_process,
                        ]);
                    }
                }

                //CREATE STOCK CARD FOR BRANCH
                $new_stockcard_branch = $r->user()->pharmacystockcard()->create([
                    'subsupply_id' => $sspid,
                    'type' => 'RECEIVED',
                    'before_qty_box' => $before_branch_qty_box,
                    'before_qty_piece' => $before_branch_qty_piece,
                    'qty_to_process' => $r->qty_to_process,
                    'qty_type' => $r->qty_type,
                    'after_qty_box' => $after_branch_qty_box,
                    'after_qty_piece' => $after_branch_qty_piece,
                    //'total_cost' => $r->total_cost,
                    //'drsi_number' => $r->drsi_number,

                    //'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                    //'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $get_patient_id : NULL,
                    //'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,
                    
                    'remarks' => 'RECEIVED FROM '.$d->pharmacybranch->name,
                    'sentby_branch_id' => auth()->user()->pharmacy_branch_id,
                ]);
            }

            $txt = 'Processing of ISSUED Item was successful';
        }
        else if ($r->type == 'RECEIVED') {
            //RECEIVE, BOX MODE ONLY LOCKED TO MAIN BRANCH

            if($d->pharmacysupplymaster->quantity_type == 'BOX') {
                $d->master_box_stock += $r->qty_to_process;
                $d->master_piece_stock += ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);
            }
            else {
                $d->master_piece_stock += $r->qty_to_process;
                
            }

            //ADD TO SUBSTOCK
            if(PharmacySupplySubStock::where('subsupply_id', $d->id)->where('batch_number', mb_strtoupper($r->batch_number))->exists()) {
                return redirect()->back()
                ->with('msg', 'Error: Batch Name already exists. Kindly double check and try again.')
                ->with('msgtype', 'warning');
            }

            $substock = PharmacySupplySubStock::where('subsupply_id', $d->id)
            ->where('expiration_date', $r->expiration_date)
            ->first();

            $new_params = [
                'subsupply_id' => $d->id,
                'expiration_date' => $r->expiration_date,
                'batch_number' => mb_strtoupper($r->batch_number),
                'source' => $r->source,
            ];

            if($d->pharmacysupplymaster->quantity_type == 'BOX') {
                if($substock) {
                    $substock->current_box_stock =+ $r->qty_to_process;
                    $substock->current_piece_stock += ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox);

                    if($substock->isDirty()) {
                        $substock->save();
                    }
                }
                else {

                    $new_params = $new_params + [
                        'current_box_stock' => $r->qty_to_process,
                        'current_piece_stock' => ($r->qty_to_process * $d->pharmacysupplymaster->config_piecePerBox),
                    ];

                    $new_substock = $r->user()->pharmacysupplysubstock()->create($new_params);
                }
            }
            else {
                if($substock) {
                    $substock->current_piece_stock += $r->qty_to_process;

                    if($substock->isDirty()) {
                        $substock->save();
                    }
                }
                else {

                    $new_params = $new_params + [
                        'current_piece_stock' => $r->qty_to_process,
                    ];

                    $new_substock = $r->user()->pharmacysupplysubstock()->create($new_params);
                }
            }
            
            //CREATE STOCK CARD
            $new_stockcard_branch = $r->user()->pharmacystockcard()->create([
                'subsupply_id' => $d->id,
                'type' => 'RECEIVED',
                'before_qty_box' => ($d->pharmacysupplymaster->quantity_type == 'BOX') ? $d->getOriginal('master_box_stock') : NULL,
                'before_qty_piece' => $d->getOriginal('master_piece_stock'),
                'qty_to_process' => $r->qty_to_process,
                'qty_type' => $r->qty_type,
                'after_qty_box' => ($d->pharmacysupplymaster->quantity_type == 'BOX') ? $d->master_box_stock : NULL,
                'after_qty_piece' => $d->master_piece_stock,

                //'total_cost' => $r->total_cost,
                //'drsi_number' => $r->drsi_number,

                //'receiving_branch_id' => ($r->select_recipient == 'BRANCH') ? $r->receiving_branch_id : NULL,
                //'receiving_patient_id' => ($r->select_recipient == 'PATIENT') ? $get_patient_id : NULL,
                //'recipient' => ($r->select_recipient == 'OTHERS') ? $r->recipient : NULL,
                
                'remarks' => mb_strtoupper($r->remarks),
            ]);

            if($d->isDirty()) {
                $d->save();
            }

            $txt = 'Processing of RECEIVED Item was successful';
        }

        if($r->redirect_to_branch) {
            return redirect()->route('pharmacy_view_branch', $r->redirect_to_branch)
            ->with('msg', $txt)
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->route('pharmacy_home')
            ->with('msg', $txt)
            ->with('msgtype', 'success');
        }        
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

        //get latest sku_code and increment to 1
        $lsku = PharmacySupplyMaster::orderBy('sku_code', 'DESC')->first();

        $lsku = substr($lsku->sku_code, 1) + 1;
        
        return view('pharmacy.itemlist_viewMasterList', [
            'list' => $list,
            'lsku' => 'G'.$lsku,
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
                'enabled' => $r->enabled,
                'name' => mb_strtoupper($r->name),
                'sku_code' => mb_strtoupper($r->sku_code),
                'sku_code_doh' => mb_strtoupper($r->sku_code_doh),
                'category' => $r->category,
                'description' => $r->description,
                'quantity_type' => $r->quantity_type,
                'config_piecePerBox' => $r->config_piecePerBox,
                'usage_category' => $r->filled('usage_category') ? implode(',', $r->usage_category) : NULL,

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

    public function initItem(Request $r) {

    }

    public function viewItem($item_id) {
        $item = PharmacySupplySub::findOrFail($item_id);

        if($item->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {
            $sub_list = PharmacySupplySubStock::where('subsupply_id', $item->id)
            ->orderBy('expiration_date', 'ASC')
            ->get();

            $scard = PharmacyStockCard::where('subsupply_id', $item->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

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

    public function viewMoreTransactions($item_id) {
        $item = PharmacySupplySub::findOrFail($item_id);

        if($item->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {
            $list = PharmacyStockCard::where('subsupply_id', $item->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

            return view('pharmacy.itemlist_viewSub_moreTransactions', [
                'd' => $item,
                'list' => $list,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function updateItem($item_id, Request $r) {
        $d = PharmacySupplySub::findOrFail($item_id);

        if($d->ifAuthorizedToUpdate()) {
            $d->update([
                //'self_sku_code' => $r->self_sku_code,
                //'self_description' => $r->self_description,

                'po_contract_number' => mb_strtoupper($r->po_contract_number),
                'supplier' => mb_strtoupper($r->supplier),
                'dosage_form' => mb_strtoupper($r->dosage_form),
                'dosage_strength' => mb_strtoupper($r->dosage_strength),
                'unit_measure' => mb_strtoupper($r->unit_measure),
                'entity_name' => mb_strtoupper($r->entity_name),
                'source_of_funds' => mb_strtoupper($r->source_of_funds),
                'unit_cost' => mb_strtoupper($r->unit_cost),
                'mode_of_procurement' => mb_strtoupper($r->mode_of_procurement),
                'end_user' => mb_strtoupper($r->end_user),
            ]);

            return redirect()->route('pharmacy_itemlist_viewitem', $d->id)
            ->with('msg', 'Details of the Sub-Item was updated successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function viewItemMonthlyStock($item_id) {
        $item = PharmacySupplySub::findOrFail($item_id);

        $month_array = [];

        //month loop
        for($i=1;$i<=12;$i++) {
            $nomonth = Carbon::create()->month($i)->format('m');

            $issued_count = PharmacyStockCard::where('subsupply_id', $item->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $nomonth)
            ->where('status', 'approved')
            ->where('type', 'ISSUED')
            ->sum('qty_to_process');

            $received_count = PharmacyStockCard::where('subsupply_id', $item->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $nomonth)
            ->where('status', 'approved')
            ->where('type', 'RECEIVED')
            ->sum('qty_to_process');

            $month_array[] = [
                'month' => Carbon::create()->month($i)->format('F'),
                'issued_count' => $issued_count,
                'received_count' => $received_count,
            ];
        }

        return view('pharmacy.itemlist_viewSubMonthlyStock', [
            'd' => $item,
            'month_array' => $month_array,
        ]);
    }

    public function printQrItem ($item_id) {
        $d = PharmacySupplySub::findOrFail($item_id);

        $templateProcessor  = new TemplateProcessor(storage_path('PHARMACY_SUBQR.docx'));
        $templateProcessor->setValue('sku_code', $d->pharmacysupplymaster->sku_code);
        $templateProcessor->setValue('name', $d->pharmacysupplymaster->name);
        $templateProcessor->setValue('branch', $d->pharmacybranch->name);

        ob_clean();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=PHARMACY_SUBCARD_".$d->id.".docx");
        $templateProcessor->saveAs('php://output');
    }

    public function viewSubStock($id) {
        $d = PharmacySupplySubStock::findOrFail($id);

        if($d->ifUserAuthorized()) {
            return view('pharmacy.substock_view', [
                'd' => $d,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function printQrSubStock($id) {
        $d = PharmacySupplySubStock::findOrFail($id);
        
        $templateProcessor  = new TemplateProcessor(storage_path('PHARMACY_SUBSTOCKQR.docx'));
        $templateProcessor->setValue('id', $d->id);
        $templateProcessor->setValue('name', $d->pharmacysub->pharmacysupplymaster->name);
        $templateProcessor->setValue('sku_code', $d->pharmacysub->pharmacysupplymaster->sku_code);
        $templateProcessor->setValue('branch', $d->pharmacysub->pharmacybranch->name);

        ob_clean();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=PHARMACY_SUBSTOCK_".$d->id.".docx");
        $templateProcessor->saveAs('php://output');
    }

    public function updateSubStock($id, Request $r) {
        $d = PharmacySupplySubStock::findOrFail($id);

        if($d->ifUserAuthorized()) {
            $d->expiration_date = $r->expiration_date;
            $d->batch_number = $r->batch_number;
            $d->source = $r->source;
            $d->lot_number = $r->lot_number;
            $d->updated_by = Auth::id();

            if($d->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
                $d->current_box_stock = $r->change_qty_box;
                //$d->current_piece_stock = $r->change_qty_piece;
                $d->current_piece_stock = ($r->change_qty_box * $d->pharmacysub->pharmacysupplymaster->config_piecePerBox);
                /*
                if($d->isDirty('current_box_stock')) {
                    
                }
                */

                $d->save();

                $sb = PharmacySupplySub::findOrFail($d->pharmacysub->id);

                /*
                if($d->getOriginal('current_box_stock') > $r->change_qty_box) {
                    $sb->master_box_stock -= ($d->getOriginal('current_box_stock') - $r->change_qty_box);
                    $sb->master_piece_stock -= ($d->getOriginal('current_box_stock') - $r->change_qty_box) * $sb->pharmacysupplymaster->config_piecePerBox;
                }
                else {
                    $sb->master_box_stock += ($r->change_qty_box - $d->getOriginal('current_box_stock'));
                    $sb->master_piece_stock += ($d->getOriginal('current_box_stock') - $r->change_qty_box) * $sb->pharmacysupplymaster->config_piecePerBox;
                }

                if($sb->isDirty()) {
                    $sb->save();
                }
                */

                //Re-compute SubItem Box and Pieces
                $substock_list = PharmacySupplySubStock::where('subsupply_id', $sb->id)->where('is_expired', 'N')->get();
                
                $final_box_stock = 0;
                foreach($substock_list as $ss) {
                    $final_box_stock += $ss->current_box_stock;
                }

                $sb->master_box_stock = $final_box_stock;
                $sb->master_piece_stock = $final_box_stock * $d->pharmacysub->pharmacysupplymaster->config_piecePerBox;

                if($sb->isDirty()) {
                    $sb->save();
                }

                /*
                if($d->isDirty('current_piece_stock')) {
                    $sb = PharmacySupplySub::findOrFail($d->pharmacysub->id);

                    if($d->getOriginal('current_piece_stock') > $r->change_qty_piece) {
                        $piece_multiplier = ($d->getOriginal('current_piece_stock') - $r->change_qty_piece) * $d->pharmacysupplymaster->config_piecePerBox;
                        
                        $sb->master_piece_stock -= ($d->getOriginal('current_piece_stock') - $r->change_qty_piece);
                    }
                    else {
                        $sb->master_piece_stock += ($r->change_qty_piece - $d->getOriginal('current_piece_stock'));
                    }

                    if($sb->isDirty()) {
                        $sb->save();
                    }
                }
                */
            }
            else {
                $d->current_piece_stock = $r->change_qty_piece;

                /*
                if($d->isDirty('current_piece_stock')) {
                    
                }
                */

                $d->save();
                    
                $sb = PharmacySupplySub::findOrFail($d->pharmacysub->id);

                /*
                if($d->getOriginal('current_piece_stock') > $r->change_qty_piece) {
                    $sb->pharmacysub->master_piece_stock -= ($d->getOriginal('current_piece_stock') - $r->change_qty_piece);
                }
                else {
                    $sb->pharmacysub->master_piece_stock += ($r->change_qty_piece - $d->getOriginal('current_piece_stock'));
                }

                if($sb->isDirty()) {
                    $sb->save();
                }
                */

                //Re-compute SubItem Pieces
                $substock_list = PharmacySupplySubStock::where('subsupply_id', $sb->id)->where('is_expired', 'N')->get();
                
                $final_piece_stock = 0;
                foreach($substock_list as $ss) {
                    $final_piece_stock += $ss->current_piece_stock;
                }

                $sb->master_piece_stock = $final_piece_stock;

                if($sb->isDirty()) {
                    $sb->save();
                }
            }

            return redirect()->route('pharmacy_itemlist_viewitem', $d->pharmacysub->id)
            ->with('msg', 'Pharmacy Sub Stock (ID: #'.$d->id.') was updated successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }        
    }

    public function exportStockCard($supply_id, Request $r) {
        $d = PharmacySupplySub::findOrFail($supply_id);

        $spreadsheet = IOFactory::load(storage_path('PHARMA_STOCKCARD.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('G3', ($d->pharmacysupplymaster->sku_code_doh) ? $d->pharmacysupplymaster->sku_code_doh : 'N/A');
        $sheet->setCellValue('C8', ($d->po_contract_number) ? $d->po_contract_number : 'N/A');
        $sheet->setCellValue('C9', ($d->supplier) ? $d->supplier : 'N/A');
        $sheet->setCellValue('C10', $d->pharmacysupplymaster->name);
        $sheet->setCellValue('C11', ($d->dosage_form) ? $d->dosage_form : 'N/A');
        $sheet->setCellValue('C12', ($d->dosage_strength) ? $d->dosage_strength : 'N/A');
        $sheet->setCellValue('C13', ($d->unit_measure) ? $d->unit_measure : 'N/A');
        $sheet->setCellValue('G8', ($d->entity_name) ? $d->entity_name : 'N/A');
        $sheet->setCellValue('G9', ($d->source_of_funds) ? $d->source_of_funds : 'N/A');
        $sheet->setCellValue('G10', ($d->unit_cost) ? $d->unit_cost : 'N/A');
        $sheet->setCellValue('G11', ($d->mode_of_procurement) ? $d->mode_of_procurement : 'N/A');
        $sheet->setCellValue('G13', ($d->end_user) ? $d->end_user : 'N/A');

        $transaction = PharmacyStockCard::where('subsupply_id', $d->id)
        ->orderBy('created_at', 'ASC')
        ->get();

        $start_row = 16;

        foreach($transaction as $t) {
            $sheet->setCellValue('A'.$start_row, date('Y-m-d', strtotime($t->created_at)));
            $sheet->setCellValue('B'.$start_row, ($t->type == 'RECEIVED') ? $t->getQtyAndType() : '');
            $sheet->setCellValue('D'.$start_row, ($t->type == 'ISSUED') ? $t->getQtyAndType() : '');
            $sheet->setCellValue('E'.$start_row, $t->getBalance());
            $sheet->setCellValue('F'.$start_row, ($t->total_cost) ? $t->total_cost : 'N/A');
            $sheet->setCellValue('G'.$start_row, ($t->drsi_number) ? $t->drsi_number : 'N/A');
            $sheet->setCellValue('H'.$start_row, $t->getRecipientAndRemarks());
            $start_row++;
        }

        $fileName = 'STOCKCARD_'.$d->pharmacysupplymaster->name.'_'.date('mdY').'.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function viewReport() {
        ini_set('max_execution_time', 90000);

        if(request()->input('select_branch')) {
            $selected_branch = request()->input('select_branch');
        }
        else {
            $selected_branch = auth()->user()->pharmacy_branch_id;
        }

        if(request()->input('submit') == 'view_report') {
            $list_branch = PharmacyBranch::get();

            if(request()->input('type') && request()->input('year')) {
                $input_type = request()->input('type');
                $input_year = request()->input('year');

                //TOP FAST MOVING MEDS
                $fast_moving = PharmacyStockCard::where('type', 'ISSUED')
                ->get();

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
                    ->where('type', 'ISSUED')
                    ->whereYear('created_at', $input_year);

                    if($input_type == 'YEARLY') {
                        $check = $check->first();
                    }
                    else if($input_type == 'QUARTERLY') {
                        $selected_qtr = request()->input('quarter');
                        
                        if($selected_qtr == 1) {
                            $qtr_date1 = Carbon::parse($input_year.'-01-01')->format('Y-m-d');
                            $qtr_date2 = Carbon::parse($input_year.'-03-31')->format('Y-m-d');
                        }
                        else if($selected_qtr == 2) {
                            $qtr_date1 = Carbon::parse($input_year.'-04-01')->format('Y-m-d');
                            $qtr_date2 = Carbon::parse($input_year.'-06-30')->format('Y-m-d');
                        }
                        else if($selected_qtr == 3) {
                            $qtr_date1 = Carbon::parse($input_year.'-07-01')->format('Y-m-d');
                            $qtr_date2 = Carbon::parse($input_year.'-09-30')->format('Y-m-d');
                        }
                        else if($selected_qtr == 4) {
                            $qtr_date1 = Carbon::parse($input_year.'-10-01')->format('Y-m-d');
                            $qtr_date2 = Carbon::parse($input_year.'-12-31')->format('Y-m-d');
                        }

                        $check = $check->whereBetween('created_at', [$qtr_date1, $qtr_date2])->first();
                    }
                    else if($input_type == 'MONTHLY') {
                        $convert_month = Carbon::create()->month(request()->input('month'))->format('m');

                        $check = $check->whereMonth('created_at', $convert_month)->first();
                    }
                    else if($input_type == 'WEEKLY') {
                        $check = $check->whereRaw('WEEK(created_at) = ?', [request()->input('week')])->first();
                    }

                    if($check) {
                        $issue_box_qry = PharmacyStockCard::where('receiving_branch_id', $e->id)
                        ->where('status', 'approved')
                        ->where('type', 'ISSUED')
                        ->where('qty_type', 'BOX')
                        ->whereYear('created_at', $input_year);

                        $issue_piece_qry = PharmacyStockCard::where('receiving_branch_id', $e->id)
                        ->where('status', 'approved')
                        ->where('type', 'ISSUED')
                        ->where('qty_type', 'PIECE')
                        ->whereYear('created_at', $input_year);

                        if($input_type == 'YEARLY') {
                            $issued_box_count = ($issue_box_qry->sum('qty_to_process') * $check->pharmacysub->pharmacysupplymaster->config_piecePerBox);

                            $issued_box_piece_count = $issue_piece_qry->sum('qty_to_process');
                        }
                        else if($input_type == 'QUARTERLY') {
                            $selected_qtr = request()->input('quarter');

                            if($selected_qtr == 1) {
                                $qtr_date1 = Carbon::parse($input_year.'-01-01')->format('Y-m-d');
                                $qtr_date2 = Carbon::parse($input_year.'-03-31')->format('Y-m-d');
                            }
                            else if($selected_qtr == 2) {
                                $qtr_date1 = Carbon::parse($input_year.'-04-01')->format('Y-m-d');
                                $qtr_date2 = Carbon::parse($input_year.'-06-30')->format('Y-m-d');
                            }
                            else if($selected_qtr == 3) {
                                $qtr_date1 = Carbon::parse($input_year.'-07-01')->format('Y-m-d');
                                $qtr_date2 = Carbon::parse($input_year.'-09-30')->format('Y-m-d');
                            }
                            else if($selected_qtr == 4) {
                                $qtr_date1 = Carbon::parse($input_year.'-10-01')->format('Y-m-d');
                                $qtr_date2 = Carbon::parse($input_year.'-12-31')->format('Y-m-d');
                            }

                            $issued_box_count = $check->whereBetween('created_at', [$qtr_date1, $qtr_date2])->sum('qty_to_process');
                        }
                        else if($input_type == 'MONTHLY') {
                            $convert_month = Carbon::create()->month(request()->input('month'))->format('m');

                            $issued_box_count = $check->whereMonth('created_at', $convert_month)->sum('qty_to_process');
                        }
                        else if($input_type == 'WEEKLY') {
                            $issued_box_count = $check->whereRaw('WEEK(created_at) = ?', [request()->input('week')])->sum('qty_to_process');
                        }

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

                //STOCKS MASTERLIST
                $list_subitem = PharmacySupplySub::where('pharmacy_branch_id', $selected_branch)
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

                    for($i=1;$i<=12;$i++) {
                        $nomonth = Carbon::create()->month($i)->format('m');

                        if($item['unit'] == 'BOX') {
                            $issued_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                            ->whereYear('created_at', $input_year)
                            ->whereMonth('created_at', $nomonth)
                            ->where('status', 'approved')
                            ->where('type', 'ISSUED')
                            ->where('qty_type', 'BOX')
                            ->sum('qty_to_process');

                            $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                            ->whereYear('created_at', $input_year)
                            ->whereMonth('created_at', $nomonth)
                            ->where('status', 'approved')
                            ->where('type', 'RECEIVED')
                            ->where('qty_type', 'BOX')
                            ->sum('qty_to_process');

                            $issued_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                            ->whereYear('created_at', $input_year)
                            ->whereMonth('created_at', $nomonth)
                            ->where('status', 'approved')
                            ->where('type', 'ISSUED')
                            ->where('qty_type', 'PIECE')
                            ->sum('qty_to_process');

                            $received_count_piece = PharmacyStockCard::where('subsupply_id', $item['id'])
                            ->whereYear('created_at', $input_year)
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
                            ->whereYear('created_at', $input_year)
                            ->whereMonth('created_at', $nomonth)
                            ->where('status', 'approved')
                            ->where('type', 'ISSUED')
                            ->sum('qty_to_process');

                            $received_count = PharmacyStockCard::where('subsupply_id', $item['id'])
                            ->whereYear('created_at', $input_year)
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
                
                //NEAREST EXPIRATION DATES
                $expired_list = PharmacySupplySubStock::whereBetween('expiration_date', [date('Y-m-d'), date('Y-m-t', strtotime('+3 Months'))])
                ->where('current_box_stock', '>', 0)
                ->orderBy('expiration_date', 'ASC')
                ->get();

                //GET PATIENT AGE GROUPS / MALE,FEMALE
                //['< 10', '11-20', '21-30', '31-40', '41-50', '51-60', '> 61']

                /*
                MIGHT BE USEFUL IN THE FUTURE
                $age_group1_count = PharmacyStockCard::whereHas('getReceivingPatient', function ($q) {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) > 10');
                })
                ->groupBy('receiving_patient_id')
                ->select('receiving_patient_id', DB::raw('SUM(amount) as total_amount'))
                ->get();

                */

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

                    $age_group_set_male[] = $qry_male->whereYear('created_at', $input_year)
                    ->groupBy('receiving_patient_id')
                    ->pluck('receiving_patient_id')
                    ->count();

                    $age_group_set_female[] = $qry_female->whereYear('created_at', $input_year)
                    ->groupBy('receiving_patient_id')
                    ->pluck('receiving_patient_id')
                    ->count();
                }

                //GET PATIENT TOP REASON FOR MEDS
                $get_grouped_prescription = PharmacyStockCard::where('type', 'ISSUED')
                ->whereNotNull('receiving_patient_id')
                ->groupBy('patient_prescription_id')
                ->pluck('patient_prescription_id');

                //$reason_selection = PharmacyPatient::getReasonList();

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

                return view('pharmacy.report', [
                    'expired_list' => $expired_list,
                    'list_branch' => $list_branch,
                    'entities_arr' => $entities_arr,
                    'fm_array' => $fm_array,
                    'si_array' => $si_array,
                    'age_group_set_male' => $age_group_set_male,
                    'age_group_set_female' => $age_group_set_female,
                    'reason_array' => $reason_array,
                ]);
            }
            else {
                return view('pharmacy.report');
            }
        }
        else if(request()->input('submit') == 'generate_inoutreport') {
            if(!request()->input('year')) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }

            $year = request()->input('year');

            if($year == date('Y') || $year > date('Y')) {
                if(date('n') == 1) {
                    return redirect()->back()
                    ->with('msg', 'Error: Month of January is not over yet.')
                    ->with('msgtype', 'warning');
                }
            }

            //Call Export Job
            $c = ExportJobs::create([
                'name' => 'Pharmacy In/Out Report '.$year,
                'for_module' => 'Pharmacy',
                'type' => 'EXPORT',
                'status' => 'pending',
                //'date_finished'
                //'filename',
                'created_by' => auth()->user()->id,
                'facility_id' => auth()->user()->itr_facility_id,
            ]);

            CallPharmacyAnnualInOutReport::dispatch(Auth::id(), $c->id, $year, $selected_branch);

            return redirect()->route('export_index')
            ->with('msg', 'Your download request is now being requested. The server will now prepare the file. Please refresh this page after 5-10 minutes or more until the status turns to completed.')
            ->with('msgtype', 'success');
        }
    }

    public function viewPatientList() {
        if(request()->input('q')) {
            $search = request()->input('q');

            $list = PharmacyPatient::where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($search))."%")
            ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($search))."%")
            ->orWhere('id', $search)
            ->paginate(10);
        }
        else {
            $list = PharmacyPatient::orderBy('created_at', 'DESC')
            ->paginate(10);
        }

        return view('pharmacy.patient_list', [
            'list' => $list,
        ]);
    }

    public function newPatient() {
        if(request()->input('lname') && request()->input('fname') && request()->input('bdate')) {
            $getname = mb_strtoupper(request()->input('lname')).', '.mb_strtoupper(request()->input('fname'));

            $s = PharmacyPatient::ifDuplicateFound(request()->input('lname'), request()->input('fname'), request()->input('mname'), request()->input('suffix'), request()->input('bdate'));

            if($s) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'ERROR: Patient ['.$getname.'] already exists in the system.')
                ->with('ep', $s)
                ->with('msgtype', 'warning');
            }
            else {
                return view('pharmacy.patient_register');
            }
        }
        else {
            return abort(401);
        }
    }

    public function storePatient(Request $r) {
        $lname = $r->lname;
        $fname = $r->fname;
        $mname = $r->mname;
        $suffix = $r->suffix;
        $bdate = $r->bdate;

        if(!(PharmacyPatient::ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate))) {
            $foundunique = false;

            while(!$foundunique) {
                $qr = mb_strtoupper(Str::random(6));

                $search = PharmacyPatient::where('qr', $qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            $foundunique = false;

            while(!$foundunique) {
                $global_qr = Str::random(20);

                $search = PharmacyPatient::where('global_qr', $global_qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            //STORE PATIENT
            $c = $r->user()->pharmacypatient()->create([
                'lname' => mb_strtoupper($r->lname),
                'fname' => mb_strtoupper($r->fname),
                'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
                'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
                'bdate' => $r->bdate,
                'gender' => $r->gender,
                'email' => $r->email,
                'contact_number' => $r->contact_number,
                'contact_number2' => $r->contact_number2,
                'philhealth' => $r->philhealth,
        
                'address_region_code' => $r->address_region_code,
                'address_region_text' => $r->address_region_text,
                'address_province_code' => $r->address_province_code,
                'address_province_text' => $r->address_province_text,
                'address_muncity_code' => $r->address_muncity_code,
                'address_muncity_text' => $r->address_muncity_text,
                'address_brgy_code' => $r->address_brgy_text,
                'address_brgy_text' => $r->address_brgy_text,
                'address_street' => ($r->filled('address_street')) ? mb_strtoupper($r->address_street) : NULL,
                'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
                
                //'concerns_list' => implode(',', $r->concerns_list),
                'qr' => $qr,
                'global_qr' => $global_qr,
        
                'id_file' => NULL,
                'selfie_file' => NULL,
        
                'status' => 'ENABLED',
        
                'pharmacy_branch_id' => auth()->user()->pharmacy_branch_id,
            ]);

            //MAKE PRESCRIPTION DATA
            $prescription = $r->user()->pharmacyprescription()->create([
                'patient_id' => $c->id,
                'concerns_list' => implode(',', $r->concerns_list),
            ]);

            return redirect()->route('pharmacy_view_patient_list')
            ->with('msg', 'Patient record successfully created.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient was just encoded into the system.')
            ->with('msgtype', 'danger');
        }
    }

    public function viewPatient($id) {
        $d = PharmacyPatient::findOrFail($id);

        $scard = PharmacyStockCard::where('receiving_patient_id', $d->id)
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('pharmacy.patient_view', [
            'd' => $d,
            'scard' => $scard,
        ]);
    }

    public function printPatientCard($id) {
        /*
        TEMPLATE PROCESSOR

        $d = PharmacyPatient::findOrFail($id);

        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=PHARMACY_CARD_".$d->lname.".docx");

        $templateProcessor  = new TemplateProcessor(storage_path('PHARMACY_PATIENT_CARD.docx'));
        $templateProcessor->setValue('patient_id', $d->id);

        $templateProcessor->saveAs('php://output');
        */

        $d = PharmacyPatient::findOrFail($id);

        $templateProcessor  = new TemplateProcessor(storage_path('PHARMACY_PATIENT_CARD.docx'));

        $templateProcessor->setValue('patient_id', $d->id);
        $templateProcessor->setValue('dreg', date('m/d/Y', strtotime($d->created_at)));
        $templateProcessor->setValue('name', $d->getName());
        $templateProcessor->setValue('bdate', date('m/d/Y', strtotime($d->bdate)));
        $templateProcessor->setValue('sex', $d->gender);
        $templateProcessor->setValue('brgy', $d->address_brgy_text);
        $templateProcessor->setValue('patient_qr', 'PATIENT_'.$d->qr);
        //$templateProcessor->setValue('qr', $d->qr);
        $templateProcessor->setValue('branch', $d->pharmacybranch->name);

        ob_clean();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=PHARMACY_PATIENT_CARD_".$d->lname."_".date('mdY').".docx");
        $templateProcessor->saveAs('php://output');
    }

    public function printBranchCard($id) {
        $d = PharmacyBranch::findOrFail($id);

        $templateProcessor  = new TemplateProcessor(storage_path('PHARMACY_BRANCH_CARD.docx'));

        $templateProcessor->setValue('name', $d->name);
        $templateProcessor->setValue('id', $d->id);
        $templateProcessor->setValue('dreg', date('m/d/Y', strtotime($d->created_at)));
        $templateProcessor->setValue('qr', 'ENTITY_'.$d->qr);

        ob_clean();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=PHARMACY_BRANCH_CARD_".$d->qr.".docx");
        $templateProcessor->saveAs('php://output');
    }
    
    public function updatePatient($id, Request $r) {
        $d = PharmacyPatient::findOrFail($id);

        if(!(PharmacyPatient::ifDuplicateFoundOnUpdate($d->id, $r->lname, $r->fname, $r->mname, $r->suffix, $r->bdate))) {
            $d->update([
                'lname' => mb_strtoupper($r->lname),
                'fname' => mb_strtoupper($r->fname),
                'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
                'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
                'bdate' => $r->bdate,
                'gender' => $r->gender,
                'email' => $r->email,
                'contact_number' => $r->contact_number,
                'contact_number2' => $r->contact_number2,
                'philhealth' => $r->philhealth,
        
                'address_region_code' => $r->address_region_code,
                'address_region_text' => $r->address_region_text,
                'address_province_code' => $r->address_province_code,
                'address_province_text' => $r->address_province_text,
                'address_muncity_code' => $r->address_muncity_code,
                'address_muncity_text' => $r->address_muncity_text,
                'address_brgy_code' => $r->address_brgy_text,
                'address_brgy_text' => $r->address_brgy_text,
                'address_street' => ($r->filled('address_street')) ? mb_strtoupper($r->address_street) : NULL,
                'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
                
                //'concerns_list' => implode(',', $r->concerns_list),
    
                'status' => $r->status,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('pharmacy_view_patient_list')
            ->with('msg', 'Patient data was updated successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Patient data already exists in the server.')
            ->with('msgtype', 'warning');
        }
    }

    public function deletePatient($patient_id) {
        if(auth()->user()->isPharmacyMasterAdmin()) {
            $d = PharmacyPatient::where('id', $patient_id)->delete();
        }
        else if(auth()->user()->isPharmacyBranchAdmin()) {
            $d = PharmacyPatient::findOrFail($patient_id);

            if($d->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {
                $d->delete();
            }
            else {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }
        else {
            return abort(401);
        }

        return redirect()->route('pharmacy_view_patient_list')
        ->with('msg', 'Patient was deleted successfully.')
        ->with('msgtype', 'success');
    }

    public function listBranch() {
        if(request()->input('q')) {
            $search = request()->input('q');

            $list = PharmacyBranch::where('name', 'LIKE', '%'.$search.'%')
            ->paginate(10);
        }
        else {
            $list = PharmacyBranch::orderBy('name', 'ASC')
            ->paginate(10);
        }

        $list_brgy = BarangayHealthStation::get();
        
        return view('pharmacy.branches_list', [
            'list' => $list,
            'list_brgy' => $list_brgy,
        ]);
    }

    public function storeBranch(Request $r) {
        $s = PharmacyBranch::where('name', mb_strtoupper($r->name))->first();

        if(!($s)) {
            if($r->if_bhs) {
                $bs = PharmacyBranch::where('if_bhs_id', $r->if_bhs_id)->first();
                if($bs) {
                    return redirect()->back()
                    ->withInput()
                    ->with('msg', 'Error: Only 1 Pharmacy Branch/Entity Only per BHS.')
                    ->with('msgtype', 'warning');
                }
            }
            
            $foundunique = false;

            while(!$foundunique) {
                $qr = mb_strtoupper(Str::random(5));

                $search = PharmacyBranch::where('qr', $qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            $c = $r->user()->createpharmacybranch()->create([
                'name' => mb_strtoupper($r->name),
                'focal_person' => $r->filled('focal_person') ? mb_strtoupper($r->focal_person) : NULL,
                'contact_number' => $r->filled('contact_number') ? mb_strtoupper($r->contact_number) : NULL,
                'description' => $r->filled('description') ? mb_strtoupper($r->description) : NULL,
                'level' => $r->level,
                'qr' => $qr,
                'if_bhs_id' => ($r->if_bhs) ? $r->if_bhs_id : NULL,
            ]);

            //Initialize Inventory
            if($r->level == 1) {
                $list_master_gamot = PharmacySupplyMaster::where('enabled', 1)->get();
                foreach($list_master_gamot as $l) {
                    $check = PharmacySupplySub::where('supply_master_id', $l->id)
                    ->where('pharmacy_branch_id', $c->id)
                    ->first();

                    if(!$check) {
                        $sub_create = PharmacySupplySub::create([
                            'supply_master_id' => $l->id,
                            'pharmacy_branch_id' => $c->id,
                            'master_box_stock' => 0,
                            'master_piece_stock' => 0,
                            'created_by' => auth()->user()->id,
                        ]);
                    }
                }
            }
            
            return redirect()->back()
            ->with('msg', 'New Pharmacy Entity/Branch was added successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Branch Name already exists. Please try again by using a different name.')
            ->with('msgtype', 'warning');
        }
    }

    public function viewBranch($id) {
        $d = PharmacyBranch::findOrFail($id);

        $bhs_list = BarangayHealthStation::get();

        /*
        where(function ($q) use ($id) {
            $q->whereNotNull('receiving_branch_id')
            ->where('receiving_branch_id', $id);
        })
        */

        $get_transactions = PharmacyStockcard::WhereHas('user', function ($q) use ($id) {
            $q->where('pharmacy_branch_id', $id);
        })
        ->orWhereHas('pharmacysub', function ($q) use ($id) {
            $q->where('pharmacy_branch_id', $id);
        })
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        return view('pharmacy.branches_view', [
            'd' => $d,
            'bhs_list' => $bhs_list,
            'get_transactions' => $get_transactions,
        ]);
    }

    public function newBranchTransaction($branch_id, Request $r) {
        $b = PharmacyBranch::findOrFail($branch_id);

        $ss = PharmacySupplySub::findOrFail($r->select_medicine);

        return redirect()->route('pharmacy_modify_view', [$ss->id, 'select_branch' => $b->id]);
    }

    public function updateBranch($id, Request $r) {
        $d = PharmacyBranch::findOrFail($id);

        $search = PharmacyBranch::where('id', '!=', $d->id)
        ->where('name', mb_strtoupper($r->name))
        ->first();

        if(!($search)) {
            if($r->filled('if_bhs_id')) {
                $bhs_search = PharmacyBranch::where('id', '!=', $d->id)
                ->where('if_bhs_id', $r->if_bhs_id)
                ->first();

                if($bhs_search) {
                    return redirect()->back()
                    ->with('msg', 'Error: Only 1 Pharmacy Branch/Entity Only per BHS.')
                    ->with('msgtype', 'warning');
                }
            }

            $d->name = mb_strtoupper($r->name);
            $d->focal_person = ($r->filled('focal_person')) ? mb_strtoupper($r->focal_person) : NULL;
            $d->contact_number = ($r->filled('contact_number')) ? $r->contact_number : NULL;
            $d->description = ($r->filled('description')) ? mb_strtoupper($r->description) : NULL;
            $d->level = $r->level;
            $d->if_bhs_id = ($r->filled('if_bhs_id')) ? $r->if_bhs_id : NULL;
            $d->updated_by = auth()->user()->id;

            if($d->isDirty()) {
                $d->save();
            }

            return redirect()->route('pharmacy_list_branch')
            ->with('msg', 'Pharmacy Branch (ID: #'.$d->id.') was updated successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Another Branch with the updated name already exists in the server.')
            ->with('msgtype', 'warning');
        }
    }

    public function walkinpart1($branch_qr) {
        $branch = PharmacyBranch::where('qr', $branch_qr)->first();

        if($branch) {
            return view('pharmacy.walkin', [
                'branch' => $branch,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function walkinpart2($branch_qr) {
        $branch = PharmacyBranch::where('qr', $branch_qr)->first();

        if($branch) {
            if(request()->input('lname') && request()->input('fname') && request()->input('bdate')) {
                $lname = request()->input('lname');
                $fname = request()->input('fname');
                $mname = request()->input('mname');
                $suffix = request()->input('suffix');
                $bdate = request()->input('bdate');
                
                if(!(PharmacyPatient::ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate))) {
                    return view('pharmacy.walkin_part2', [
                        'branch' => $branch,
                    ]);
                }
                else {
                    return redirect()->back()
                    ->withInput()
                    ->with('msg', 'Error: You are already registered. Please proceed to CHO General Trias to get your card.')
                    ->with('msgtype', 'warning');
                }
            }
            else {
                return abort(401);
            }
        }
        else {
            return abort(401);
        }
    }

    public function walkinpart3($branch_qr, Request $r) {
        $branch = PharmacyBranch::where('qr', $branch_qr)->first();

        if($branch) {
            $lname = $r->lname;
            $fname = $r->fname;
            $mname = $r->mname;
            $suffix = $r->suffix;
            $bdate = $r->bdate;

            if(!(PharmacyPatient::ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate))) {
                $foundunique = false;

                while(!$foundunique) {
                    $qr = mb_strtoupper(Str::random(6));

                    $search = PharmacyPatient::where('qr', $qr)->first();
                    if(!$search) {
                        $foundunique = true;
                    }
                }

                $foundunique = false;

                while(!$foundunique) {
                    $global_qr = Str::random(20);

                    $search = PharmacyPatient::where('global_qr', $global_qr)->first();
                    if(!$search) {
                        $foundunique = true;
                    }
                }

                //STORE PATIENT
                $c = PharmacyPatient::create([
                    'lname' => mb_strtoupper($r->lname),
                    'fname' => mb_strtoupper($r->fname),
                    'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
                    'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
                    'bdate' => $r->bdate,
                    'gender' => $r->gender,
                    'email' => $r->email,
                    'contact_number' => $r->contact_number,
                    'contact_number2' => $r->contact_number2,
                    'philhealth' => $r->philhealth,
            
                    'address_region_code' => $r->address_region_code,
                    'address_region_text' => $r->address_region_text,
                    'address_province_code' => $r->address_province_code,
                    'address_province_text' => $r->address_province_text,
                    'address_muncity_code' => $r->address_muncity_code,
                    'address_muncity_text' => $r->address_muncity_text,
                    'address_brgy_code' => $r->address_brgy_text,
                    'address_brgy_text' => $r->address_brgy_text,
                    'address_street' => ($r->filled('address_street')) ? mb_strtoupper($r->address_street) : NULL,
                    'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
                    
                    //'concerns_list' => implode(',', $r->concerns_list),
                    'qr' => $qr,
                    'global_qr' => $global_qr,
            
                    'id_file' => NULL,
                    'selfie_file' => NULL,
            
                    'status' => 'ENABLED',
                    'from_outside' => 1,
                    'outside_name' => mb_strtoupper($r->outside_name),
            
                    'pharmacy_branch_id' => $branch->id,
                    'created_by' => 58, //TONETTE
                ]);

                //MAKE PRESCRIPTION DATA, PHARMACY SHOULD MAKE IT NOT THE PATIENT
                /*
                $prescription = PharmacyPrescription::create([
                    'patient_id' => $c->id,
                    'concerns_list' => implode(',', $r->concerns_list),
                ]);
                */

                return redirect()->route('pharmacy_getcard', ['q' => $c->global_qr])
                ->with('msg', 'Registration Complete. You may now save your Pharmacy Card and present it to the Pharmacy together with your Prescription and Valid ID.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: Patient was just encoded into the system.')
                ->with('msgtype', 'danger');
            }
        }
        else {
            return abort(401);
        }
    }

    public function searchcard(Request $r) {
        
    }

    public function globalcard() {
        if(request()->input('q')) {
            $d = PharmacyPatient::where('global_qr', request()->input('q'))->first();

            if($d) {
                return view('pharmacy.online_card', [
                    'd' => $d,
                ]);
            }
            else {
                return redirect()->route('pharmacy_walkin')
                ->with('msg', 'Error: QR Code does not exists. You may double check and try again.')
                ->with('msgtype', 'warning');
            }
        }
        else {
            return abort(401);
        }
    }

    public function viewPrescription($id) {
        $d = PharmacyPrescription::findOrFail($id);

        return view('pharmacy.prescription_view', ['d' => $d]);
    }

    public function updatePrescription($id, Request $r) {
        $d = PharmacyPrescription::findOrFail($id);

        $d->concerns_list = implode(',', $r->concerns_list);

        if($d->isDirty()) {
            $d->save();
        }
        
        return redirect()->route('pharmacy_modify_patient_stock', $d->pharmacypatient->id)
        ->with('msg', 'Prescription details were updated successfully.')
        ->with('msgtype', 'success');
    }

    public function generateMedicineDispensary(Request $r) {
        if($r->submit == 'generateV1') {
            if(Carbon::parse($r->start_date)->isSameDay(Carbon::parse($r->end_date))) {
                $list_query = PharmacyStockCard::whereDate('created_at', $r->start_date);
            }
            else {
                $list_query = PharmacyStockCard::whereBetween('created_at', [$r->start_date, $r->end_date]);
            }
    
            /*
            $list_query = $list_query->whereNotNull('receiving_patient_id')
            ->where('type', 'ISSUED');
            */
    
            $list_query = $list_query->where('type', 'ISSUED');
    
            if($r->select_branch != 'ALL') {
                $list_query = $list_query->whereHas('pharmacysub', function ($q) use ($r) {
                    $q->where('pharmacy_branch_id', $r->select_branch);
                });
            }
    
            $list_query = $list_query->orderBy('created_at', 'DESC');
    
            function queryGenerator($query) {
                foreach ($query->cursor() as $u) {
                    yield $u;
                }
            }
    
            $sheets = new SheetCollection([
                'MAIN' => queryGenerator($list_query),
            ]);
    
            $header_style = (new Style())->setFontBold();
            //$rows_style = (new Style())->setShouldWrapText();
    
            $file_name = 'PHARMACY_MEDICINE_DISPENSARY_'.date('mdY').'.xlsx';
    
            return (new FastExcel($sheets))
            ->headerStyle($header_style)
            ->download($file_name, function ($f) {
    
                if(!is_null($f->receiving_patient_id)) {
                    $name = $f->getReceivingPatient->lname.', '.$f->getReceivingPatient->fname;
                    $age = $f->getReceivingPatient->getAge();
                    $sex = substr($f->getReceivingPatient->gender,0,1);
                    $barangay = $f->getReceivingPatient->address_brgy_text;
                }
                else {
                    $name = $f->getReceivingBranch->name;
                    $age = 'N/A';
                    $sex = 'N/A';
                    $barangay = (!is_null($f->getReceivingBranch->if_bhs_id)) ? $f->getReceivingBranch->bhs->brgy->brgyName : "N/A";
                }
    
                return [
                    'DATE/TIME' => date('m/d/Y h:i A', strtotime($f->created_at)),
                    'NAME' => $name,
                    'AGE' => $age,
                    'SEX' => $sex,
                    'BARANGAY' => $barangay,
                    'MEDICINE GIVEN' => $f->pharmacysub->pharmacysupplymaster->name,
                    'QUANTITY' => $f->qty_to_process.' '.Str::plural($f->qty_type, $f->qty_to_process),
                    'ENCODER' => $f->user->name,
                ];
            });
        }
        else if($r->submit == 'generateV2') {
            if(Carbon::parse($r->start_date)->isSameDay(Carbon::parse($r->end_date))) {
                $list_query = PharmacyCartMain::whereDate('created_at', $r->start_date);
            }
            else {
                $list_query = PharmacyCartMain::whereBetween('created_at', [$r->start_date, $r->end_date]);
            }

            if($r->select_branch != 'ALL') {
                $list_query = $list_query->where('branch_id', $r->select_branch);
            }

            $list_query = $list_query->orderBy('created_at', 'DESC');

            function queryGenerator($query) {
                foreach ($query->cursor() as $u) {
                    yield $u;
                }
            }

            $sheets = new SheetCollection([
                'MAIN' => queryGenerator($list_query),
            ]);

            $header_style = (new Style())->setFontBold();

            $file_name = 'PHARMACY_MEDICINE_DISPENSARY_V2_'.date('mdY').'.xlsx';

            return (new FastExcel($sheets))
            ->headerStyle($header_style)
            ->download($file_name, function ($f) {
    
                /*
                if(!is_null($f->receiving_patient_id)) {
                    $name = $f->getReceivingPatient->lname.', '.$f->getReceivingPatient->fname;
                    $age = $f->getReceivingPatient->getAge();
                    $sex = substr($f->getReceivingPatient->gender,0,1);
                    $barangay = $f->getReceivingPatient->address_brgy_text;
                }
                else {
                    $name = $f->getReceivingBranch->name;
                    $age = 'N/A';
                    $sex = 'N/A';
                    $barangay = (!is_null($f->getReceivingBranch->if_bhs_id)) ? $f->getReceivingBranch->bhs->brgy->brgyName : "N/A";
                }
                */

                $list_medicines = PharmacyCartSub::where('main_cart_id', $f->id)->get();
                $final_med_list = [];

                foreach($list_medicines as $m) {
                    $final_med_list[] = $m->pharmacysub->pharmacysupplymaster->name.'('.$m->qty_to_process.' '.$m->type_to_process.')';
                }
    
                return [
                    'DATE/TIME' => date('m/d/Y h:i A', strtotime($f->created_at)),
                    'NAME' => $f->pharmacypatient->getName(),
                    'AGE' => $f->pharmacypatient->getAgeInt(),
                    'SEX' => substr($f->pharmacypatient->gender,0,1),
                    'BARANGAY' => $f->pharmacypatient->address_brgy_text,
                    'DIAGNOSIS' => $f->prescription->concerns_list,
                    'MEDICINE GIVEN' => implode(",", $final_med_list),
                    'ENCODER' => $f->user->name,
                ];
            });
        }
    }

    public function report2() {
        return view('pharmacy.report2');
    }

    public function viewMasterlist2() {
        $list = PharmacySupplySub::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
        ->get();

        return view('pharmacy.stock_masterlist', [
            'list' => $list,
        ]);
    }

    public function ajaxMedicineDispensary() {
        if(request()->input('sdate')) {
            $sdate = Carbon::parse(request()->input('sdate'))->format('Y-m-d');
        }
        else {
            $sdate = date('Y-m-d');
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
                'datetime' => date('m/d/Y H:i', strtotime($d->created_at)),
                'name' => $d->getReceivingPatient->getName(),
                'agesex' => $d->getReceivingPatient->getAge().'/'.$d->getReceivingPatient->sg(),
                'barangay' => $d->getReceivingPatient->address_brgy_text,
                'medicine_given' => implode(", ", $d->getMedicineIssuanceList()),
                'quantity' => implode(", ", $d->getQuantityIssuanceList()),
                'encoder' => $d->user->name,
            ];
        }

        return response()->json($final_array);
    }
}
