<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExposureHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_date',
        'form_id',
        'primarycc_id',
        'cif_linkid',
        'exposure_date',
        'user_id',
        'updated_by',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function form() {
        return $this->belongsTo(Forms::class);
    }

    public function getCifLinkRecords() {
        $data = Forms::findOrFail($this->cif_linkid);

        return $data;
    }

    public function getPrimaryCCList($form_id) {
        $data = ExposureHistory::where('form_id', $form_id)->get();

        $arr = collect();

        foreach($data as $item) {
            $q = Forms::where('id', $item->cif_linkid)->first();

            $arr->push($q);
        }
        
        return $arr;
    }
}
