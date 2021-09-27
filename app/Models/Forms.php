<?php

namespace App\Models;

//use App\Models\Records;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forms extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function records() {
        return $this->belongsTo(Records::class);
    }

    public function getEditedBy() {
        if(!is_null($this->updated_by)) {
            $u = User::find($this->updated_by);
            return $u->name;
        }
        else {
            return NULL;
        }
    }

    public function getType() {
        if($this->pType == 'PROBABLE') {
            return 'SUSPECTED';
        }
        else if($this->pType == 'CLOSE CONTACT') {
            return 'CLOSE CONTACT';
        }
        else if($this->pType == 'CLOSE CONTACT') {
            return 'NON-COVID CASE';
        }
    }

    public function getReferralCode() {
        if(!is_null($this->majikCode)) {
            $check = PaSwabDetails::where('majikCode', $this->majikCode)->first();
            if($check) {
                return $check->linkCode;
            }
            else {
                return 'N/A';
            }
        }
        else {
            return 'N/A';
        }
    }

    public function getQuarantineStatus() {
        if($this->dispoType == 1) {
            return 'ADMITTED AT HOSPITAL';
        }
        else if($this->dispoType == 2) {
            return 'ADMITTED IN ISOLATION FACILITY';
        }
        else if($this->dispoType == 3) {
            return 'SELF-QUARANTINE';
        }
        else if($this->dispoType == 4) {
            return 'DISCHARGED TO HOME';
        }
        else {
            return 'OTHERS';
        }
    }
}
