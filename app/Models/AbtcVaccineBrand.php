<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcVaccineBrand extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'brand_name',
        'generic_name',
        'est_maxdose_perbottle',
        'enabled',
    ];

    public function ifHasStock() {
        $s = AbtcVaccineStocks::where('vaccine_id', $this->id)
        ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->first();

        if($s) {
            if($s->enabled == 1) {
                if($s->current_stock <= 0) {
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}
