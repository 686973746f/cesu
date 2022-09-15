<?php

namespace App\Models;

use App\Models\Forms;
use App\Models\Records;
use App\Models\LinelistMasters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LinelistSubs extends Model
{
    use HasFactory;

    protected $fillable = [
        'linelist_masters_id',
        'specNo',
        'dateAndTimeCollected',
        'accessionNo',
        'records_id',
        'remarks',
        'oniSpecType',
        'oniReferringHospital'
    ];

    public function linelistmaster() {
        return $this->belongsTo(LinelistMasters::class, 'linelist_masters_id');
    }

    public function records() {
        return $this->belongsTo(Records::class);
    }

    public function forms() {
        return Forms::where('records_id', $this->records_id)->orderBy('created_at', 'DESC')->first();
    }

    public function ricon() {
        if($this->res_result == 'POSITIVE') {
            return '(+)';
        }
        else if($this->res_result == 'NEGATIVE') {
            return '(-)';
        }
        else {
            return '';
        }
    }
}
