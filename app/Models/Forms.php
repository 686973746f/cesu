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
}
