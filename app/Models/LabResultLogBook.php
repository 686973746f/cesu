<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabResultLogBook extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'group_id',
        'for_case_id',

        'lname',
        'fname',
        'mname',
        'suffix',
        'age',
        'gender',

        'specimen_type',
        'test_type',
        'date_collected',
        'collector_name',
        'lab_number',

        'date_released',
        'result',
        'result_updated_date',
        'result_updated_by',
        'interpretation',
        'remarks',
        'facility_id',
        'updated_by',
    ];

    public function getName() {
        $fullname = $this->lname.", ".$this->fname;

        if(!is_null($this->mname)) {
            $fullname = $fullname." ".$this->mname;
        }

        if(!is_null($this->suffix)) {
            $fullname = $fullname." ".$this->suffix;
        }

        return $fullname;
        //return $this->lname.", ".$this->fname.' '.$this->suffix." ".$this->mname;
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function group() {
        return $this->belongsTo(LabResultLogBook::class, 'group_id');
    }
}
