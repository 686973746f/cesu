<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacySupplySubStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'subsupply_id',
        'expiration_date',
        'batch_number',
        'lot_number',
        'current_box_stock',
        'current_piece_stock',

        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }
    }

    public function pharmacysub() {
        return $this->belongsTo(PharmacySupplySub::class, 'subsupply_id');
    }

    public function getMainQty() {
        if($this->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
            $get_qty = $this->current_box_stock;
        }
        else {
            $get_qty = $this->current_piece_stock;
        }
        
        return $get_qty;
    }

    public function displayQty() {
        if($this->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX') {
            return $this->current_box_stock.' '.Str::plural('BOX', $this->current_box_stock).' ('.($this->current_box_stock * $this->pharmacysub->pharmacysupplymaster->config_piecePerBox).' '.Str::plural('PC', ($this->current_box_stock * $this->pharmacysub->pharmacysupplymaster->config_piecePerBox)).')';
        }
        else {
            return $this->current_piece_stock.' '.Str::plural('PC', $this->current_piece_stock);
        }
    }

    public function ifUserAuthorized() {
        if(auth()->user()->isAdminPharmacy() || $this->pharmacysub->pharmacy_branch_id == auth()->user()->pharmacy_branch_id) {
            return true;
        }
        else {
            return false;
        }
    }
}
