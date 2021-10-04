<?php

namespace App\Models;

use App\Models\Records;
use App\Models\CifUploads;
use App\Models\PaSwabLinks;
use App\Models\LinelistMaster;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'isAdmin',
        'brgy_id',
        'company_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ifTopAdmin() {
        if($this->isAdmin == 1) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public function isCesuAccount() {
        if($this->isAdmin == 1 || $this->isAdmin == 2) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isBrgyAccount() {
        if(!is_null($this->brgy_id)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isCompanyAccount() {
        if(!is_null($this->company_id)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canUseLinelist() {
        if($this->canAccessLinelist == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    public function records() {
        return $this->hasMany(Records::class);
    }

    public function form() {
        return $this->hasMany(Forms::class);
    }

    public function brgy() {
        return $this->hasMany(Brgy::class);
    }

    public function brgyCode() {
        return $this->hasMany(BrgyCodes::class);
    }

    public function interviewer() {
        return $this->hasMany(Interviewers::class);
    }

    public function defaultInterviewer() {
        if(!is_null($this->interviewer_id)) {
            $i = Interviewers::find($this->interviewer_id);
            
            return $i->lname.", ".$i->fname;
        }
        else {
            return null;
        }
    }

    public function linelistmaster() {
        return $this->hasMany(LinelistMasters::class);
    }

    public function cifupload() {
        return $this->hasMany(CifUploads::class);
    }

    public function company() {
        return $this->hasOne(Companies::class);
    }

    public function referralCode() {
        return $this->hasMany(ReferralCodes::class);
    }

    public function paSwabLink() {
        return $this->hasMany(PaSwabLinks::class);
    }
}
