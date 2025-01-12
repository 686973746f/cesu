<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlsMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'cho_employee',
        'employee_id',
        'lname',
        'fname',
        'mname',
        'suffix',
        'provider_type',
        'position',
        'institution',
        'employee_type',
        'bdate',
        'gender',
        'street_purok',
        'address_brgy_code',
        'email',
        'contact_number',
        'codename',
        'sfa_pretest',
        'sfa_posttest',
        'sfa_remedial',
        'sfa_ispassed',
        'sfa_notes',
        'bls_pretest',
        'bls_posttest',
        'bls_remedial',
        'bls_cognitive_ispassed',
        'bls_cpr_adult',
        'bls_cpr_infant',
        'bls_fbao_adult',
        'bls_fbao_infant',
        'bls_rb_adult',
        'bls_rb_infant',
        'bls_psychomotor_ispassed',
        'bls_affective',
        'bls_finalremarks',
        'bls_notes',
        'bls_id_number',
        'sfa_id_number',
        'bls_expiration_date',
        'picture',
        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function batch() {
        return $this->belongsTo(BlsMain::class, 'batch_id');
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
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
            return $this->age;
        }
    }

    public function getAgeInt() {
        return Carbon::parse($this->attributes['bdate'])->age;
    }

    public function sg() {
        return substr($this->gender, 0,1);
    }

    public function getAddress() {
        $final = $this->street_purok.', BRGY. '.$this->brgy->name.', '.$this->brgy->city->name.', '.$this->brgy->city->province->name;

        return $final;
    }

    public function getLastTrainingData() {
        $d = BlsBatchParticipant::where('member_id', $this->member_id)->latest()->first();

        return $d;
    }

    public function getLastTrainingYear() {
        if(!is_null($this->getLastTrainingData())) {
            $year = Carbon::parse($this->getLastTrainingData()->batch->training_date_start)->format('Y');
        }
        else {
            $year = NULL;
        }

        return $year;
    }

    public function ifForRefresher() {
        if(!is_null($this->getLastTrainingData())) {
            if(($this->getLastTrainingYear() + 2) >= date('Y')) {
                return 'Y';
            }
            else {
                return 'N';
            }
        }
        else {
            return 'N';
        }
    }
}
