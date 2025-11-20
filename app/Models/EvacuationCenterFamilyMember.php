<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvacuationCenterFamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'familyhead_id',
        'relationship_tohead',
        'lname',
        'fname',
        'mname',
        'suffix',
        'nickname',
        'bdate',
        'sex',
        //'is_pregnant',
        //'is_lactating',
        'highest_education',
        'occupation',
        //'outcome',
        //'date_missing',
        //'date_returned',
        //'date_died',
        //'is_injured',
        //'is_pwd',
        'is_4ps',
        'is_indg',
        //'cswd_serialno',
        //'dswd_serialno',
        //'remarks',
        'created_by',
        'updated_by',
        'hash',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

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

    public function getAge() {
        return Carbon::parse($this->bdate)->age;
    }
}
