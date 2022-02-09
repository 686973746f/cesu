<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecondaryTertiaryRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'morbidityMonth',
        'dateReported',
        'lname',
        'fname',
        'mname',
        'gender',
        'bdate',
        'email',
        'mobile',
        'address_houseno',
        'address_street',
        'address_brgy',
        'address_city',
        'address_cityjson',
        'address_province',
        'address_provincejson',
        'temperature',
        'is_primarycc',
        'is_secondarycc',
        'is_tertiarycc',
        'is_primarycc_date',
        'is_secondarycc_date',
        'is_tertiarycc_date',
        'is_primarycc_date_set',
        'is_secondarycc_date_set',
        'is_tertiarycc_date_set',
        'from_establishment',
        'remarks',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getName() {
        return $this->lname.', '.$this->fname.' '.$this->mname;
    }

    public function getAge() {
        if(!is_null($this->bdate)) {
            if(Carbon::parse($this->attributes['bdate'])->age > 0) {
                return Carbon::parse($this->attributes['bdate'])->age;
            }
            else {
                if (Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m') == 0) {
                    return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%d DAYS');
                }
                else {
                    return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
                }
            }
        }
        else {
            return 'N/A';
        }
    }
}
