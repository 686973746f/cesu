<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interviewers extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'desc',
        'brgy_id'
    ];

    public function getName() {
        return $this->lname.', '.$this->fname.' '.$this->mname;
    }

    public function getCifName() {
        //para mag-match sa dropdown ng create cif, di kailangan ng middle name
        return $this->lname.', '.$this->fname;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function brgy() {
        return $this->belongsTo(Brgy::class);
    }

    public function paswablink() {
        return $this->belongsTo(PaSwabLinks::class);
    }
}
